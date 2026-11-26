<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerModel extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useAutoIncrement = true;
    protected $protectFields = true;
    protected $allowedFields = [
        'full_name',
        'email',
        'phone',
        'created_at',
    ];

    protected $useTimestamps = false;

    protected $validationRules = [
        'full_name' => 'required|max_length[100]',
        'email' => 'required|valid_email|max_length[100]',
        'phone' => 'permit_empty|max_length[20]',
    ];
}