<?php

namespace App\Controllers;

use App\Models\SaleModel;
use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class UserController extends BaseController
{
    private UserModel $users;

    public function __construct()
    {
        $this->users = new UserModel();
    }

    public function index()
    {
        return view('users/index', [
            'title' => 'Staff',
            'users' => $this->users->orderBy('id', 'DESC')->findAll(),
        ]);
    }

    public function new()
    {
        return view('users/form', [
            'title' => 'Add Staff Member',
            'user' => null,
        ]);
    }

    public function create()
    {
        if (! $this->validateUser()) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        if ($this->usernameExists($this->request->getPost('username'))) {
            return redirect()->back()->withInput()->with('error', 'That username is already in use.');
        }

        $this->users->insert([
            'username' => $this->request->getPost('username'),
            'full_name' => $this->request->getPost('full_name'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'avatar' => $this->storeAvatar(),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/users')->with('success', 'Staff member created successfully.');
    }

    public function edit(int $id)
    {
        $user = $this->users->find($id);

        if (! $user) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('users/form', [
            'title' => 'Edit Staff Member',
            'user' => $user,
        ]);
    }

    public function update(int $id)
    {
        $user = $this->users->find($id);

        if (! $user) {
            throw PageNotFoundException::forPageNotFound();
        }

        if (! $this->validateUser(true)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        if ($this->usernameExists($this->request->getPost('username'), $id)) {
            return redirect()->back()->withInput()->with('error', 'That username is already in use.');
        }

        $avatar = $user['avatar'];
        $newAvatar = $this->storeAvatar();

        if ($newAvatar !== null) {
            if ($avatar && is_file(FCPATH . 'uploads/avatars/' . $avatar)) {
                unlink(FCPATH . 'uploads/avatars/' . $avatar);
            }

            $avatar = $newAvatar;
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'full_name' => $this->request->getPost('full_name'),
            'avatar' => $avatar,
        ];

        if ($this->request->getPost('password') !== '') {
            $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        $this->users->update($id, $data);

        if ((int) session()->get('user_id') === $id) {
            session()->set([
                'username' => $data['username'],
                'full_name' => $data['full_name'],
                'avatar' => $data['avatar'],
            ]);
        }

        return redirect()->to('/users')->with('success', 'Staff member updated successfully.');
    }

    public function delete(int $id)
    {
        $user = $this->users->find($id);

        if (! $user) {
            throw PageNotFoundException::forPageNotFound();
        }

        if ((int) session()->get('user_id') === $id) {
            return redirect()->to('/users')->with('error', 'You cannot delete your own account.');
        }

        $hasSales = (new SaleModel())
            ->where('sold_by', $id)
            ->countAllResults() > 0;

        if ($hasSales) {
            return redirect()->to('/users')->with(
                'error',
                'This staff member cannot be deleted because they have sales records.'
            );
        }

        if ($user['avatar'] && is_file(FCPATH . 'uploads/avatars/' . $user['avatar'])) {
            unlink(FCPATH . 'uploads/avatars/' . $user['avatar']);
        }

        $this->users->delete($id);

        return redirect()->to('/users')->with('success', 'Staff member deleted successfully.');
    }

    private function validateUser(bool $isUpdate = false): bool
    {
        $rules = [
            'username' => 'required|max_length[50]',
            'full_name' => 'required|max_length[100]',
            'password' => $isUpdate ? 'permit_empty|min_length[8]' : 'required|min_length[8]',
        ];

        $avatar = $this->request->getFile('avatar');

        if ($avatar && $avatar->getError() !== UPLOAD_ERR_NO_FILE) {
            $rules['avatar'] = 'uploaded[avatar]|is_image[avatar]|mime_in[avatar,image/jpg,image/jpeg,image/png,image/webp]|max_size[avatar,2048]';
        }

        return $this->validate($rules);
    }

    private function usernameExists(string $username, ?int $ignoreId = null): bool
    {
        $query = $this->users->where('username', $username);

        if ($ignoreId !== null) {
            $query->where('id !=', $ignoreId);
        }

        return $query->first() !== null;
    }

    private function storeAvatar(): ?string
    {
        $avatar = $this->request->getFile('avatar');

        if (! $avatar || $avatar->getError() === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $fileName = $avatar->getRandomName();

        service('image')
            ->withFile($avatar->getTempName())
            ->fit(300, 300, 'center')
            ->save(FCPATH . 'uploads/avatars/' . $fileName);

        return $fileName;
    }
}
