<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }

        return view('auth/login');
    }

    public function attempt()
    {
        $rules = [
            'username' => 'required|max_length[50]',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with(
                'errors',
                $this->validator->getErrors()
            );
        }

        $user = (new UserModel())
            ->where('username', $this->request->getPost('username'))
            ->first();

        if (! $user || ! password_verify($this->request->getPost('password'), $user['password'])) {
            return redirect()->back()->withInput()->with(
                'error',
                'Invalid username or password.'
            );
        }

        session()->regenerate(true);
        session()->set([
            'user_id' => $user['id'],
            'username' => $user['username'],
            'full_name' => $user['full_name'],
            'avatar' => $user['avatar'],
            'isLoggedIn' => true,
        ]);

        return redirect()->to('/')->with('success', 'Welcome back, ' . $user['full_name'] . '.');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login')->with('success', 'You have been logged out.');
    }
}