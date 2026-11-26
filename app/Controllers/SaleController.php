<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use App\Models\ProductModel;

class SaleController extends BaseController
{
    public function index()
    {
        $sales = db_connect()
            ->table('sales')
            ->select('sales.*, products.name AS product_name, customers.full_name AS customer_name, users.full_name AS staff_name')
            ->join('products', 'products.id = sales.product_id')
            ->join('customers', 'customers.id = sales.customer_id', 'left')
            ->join('users', 'users.id = sales.sold_by')
            ->orderBy('sales.created_at', 'DESC')
            ->get()
            ->getResultArray();

        return view('sales/index', [
            'title' => 'Sales History',
            'sales' => $sales,
        ]);
    }

    public function new()
    {
        return view('sales/form', [
            'title' => 'Record Sale',
            'products' => (new ProductModel())
                ->where('stock_quantity >', 0)
                ->orderBy('name', 'ASC')
                ->findAll(),
            'customers' => (new CustomerModel())
                ->orderBy('full_name', 'ASC')
                ->findAll(),
        ]);
    }

    public function create()
    {
        if (! $this->validate([
            'product_id' => 'required|integer',
            'customer_id' => 'permit_empty|integer',
            'quantity' => 'required|integer|greater_than[0]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $productId = (int) $this->request->getPost('product_id');
        $customerId = $this->request->getPost('customer_id')
            ? (int) $this->request->getPost('customer_id')
            : null;
        $quantity = (int) $this->request->getPost('quantity');

        if ($customerId !== null && ! (new CustomerModel())->find($customerId)) {
            return redirect()->back()->withInput()->with('error', 'The selected customer does not exist.');
        }

        $db = db_connect();
        $db->transBegin();

        try {
            // Lock the selected product row until the sale and stock update both finish.
            $product = $db->query(
                'SELECT id, price, stock_quantity FROM products WHERE id = ? FOR UPDATE',
                [$productId]
            )->getRowArray();

            if (! $product) {
                $db->transRollback();

                return redirect()->back()->withInput()->with('error', 'The selected product does not exist.');
            }

            if ((int) $product['stock_quantity'] < $quantity) {
                $db->transRollback();

                return redirect()->back()->withInput()->with(
                    'error',
                    'Sale rejected: only ' . $product['stock_quantity'] . ' unit(s) are available.'
                );
            }

            $totalPrice = round((float) $product['price'] * $quantity, 2);

            $db->table('sales')->insert([
                'product_id' => $productId,
                'customer_id' => $customerId,
                'sold_by' => session()->get('user_id'),
                'quantity' => $quantity,
                'total_price' => $totalPrice,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            $db->table('products')
                ->where('id', $productId)
                ->set('stock_quantity', 'stock_quantity - ' . $quantity, false)
                ->update();

            if ($db->transStatus() === false) {
                $db->transRollback();

                return redirect()->back()->withInput()->with(
                    'error',
                    'The sale could not be recorded. Please try again.'
                );
            }

            $db->transCommit();

            return redirect()->to('/sales')->with('success', 'Sale recorded and stock updated.');
        } catch (\Throwable $exception) {
            $db->transRollback();
            log_message('error', $exception->getMessage());

            return redirect()->back()->withInput()->with(
                'error',
                'The sale could not be recorded. Please try again.'
            );
        }
    }
}