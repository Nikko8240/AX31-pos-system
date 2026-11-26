<?php

namespace App\Models;

use CodeIgniter\Model;

class SaleModel extends Model
{
    protected $table = 'sales';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useAutoIncrement = true;
    protected $protectFields = true;
    protected $allowedFields = [
        'product_id',
        'customer_id',
        'sold_by',
        'quantity',
        'total_price',
        'created_at',
    ];

    protected $useTimestamps = false;

    protected $validationRules = [
        'product_id' => 'required|integer',
        'customer_id' => 'permit_empty|integer',
        'sold_by' => 'required|integer',
        'quantity' => 'required|integer|greater_than[0]',
        'total_price' => 'required|decimal',
    ];
}