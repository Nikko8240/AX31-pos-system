<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\SaleModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $productModel = new ProductModel();
        $saleModel = new SaleModel();

        $todayStart = date('Y-m-d 00:00:00');
        $tomorrowStart = date('Y-m-d 00:00:00', strtotime('+1 day'));

        $todayTotals = $saleModel
            ->selectSum('total_price')
            ->where('created_at >=', $todayStart)
            ->where('created_at <', $tomorrowStart)
            ->first();

        return view('dashboard', [
            'title' => 'Dashboard',
            'productCount' => $productModel->countAll(),
            'lowStockProducts' => $productModel
                ->where('stock_quantity <=', 5)
                ->orderBy('stock_quantity', 'ASC')
                ->findAll(),
            'todaySales' => $todayTotals['total_price'] ?? 0,
        ]);
    }
}