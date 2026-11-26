<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use App\Models\SaleModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class CustomerController extends BaseController
{
    private CustomerModel $customers;

    public function __construct()
    {
        $this->customers = new CustomerModel();
    }

    public function index()
    {
        return view('customers/index', [
            'title' => 'Customers',
            'customers' => $this->customers->orderBy('id', 'DESC')->findAll(),
        ]);
    }

    public function new()
    {
        return view('customers/form', [
            'title' => 'Add Customer',
            'customer' => null,
        ]);
    }

    public function create()
    {
        if (! $this->validateCustomer()) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->customers->insert([
            'full_name' => $this->request->getPost('full_name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone') ?: null,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/customers')->with('success', 'Customer created successfully.');
    }

    public function edit(int $id)
    {
        $customer = $this->customers->find($id);

        if (! $customer) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('customers/form', [
            'title' => 'Edit Customer',
            'customer' => $customer,
        ]);
    }

    public function update(int $id)
    {
        if (! $this->customers->find($id)) {
            throw PageNotFoundException::forPageNotFound();
        }

        if (! $this->validateCustomer()) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->customers->update($id, [
            'full_name' => $this->request->getPost('full_name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone') ?: null,
        ]);

        return redirect()->to('/customers')->with('success', 'Customer updated successfully.');
    }

    public function delete(int $id)
    {
        if (! $this->customers->find($id)) {
            throw PageNotFoundException::forPageNotFound();
        }

        $hasSales = (new SaleModel())
            ->where('customer_id', $id)
            ->countAllResults() > 0;

        if ($hasSales) {
            return redirect()->to('/customers')->with(
                'error',
                'This customer cannot be deleted because they have sales records.'
            );
        }

        $this->customers->delete($id);

        return redirect()->to('/customers')->with('success', 'Customer deleted successfully.');
    }

    private function validateCustomer(): bool
    {
        return $this->validate([
            'full_name' => 'required|max_length[100]',
            'email' => 'required|valid_email|max_length[100]',
            'phone' => 'permit_empty|max_length[20]',
        ]);
    }
}