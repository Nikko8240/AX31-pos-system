<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\SaleModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class ProductController extends BaseController
{
    private ProductModel $products;

    public function __construct()
    {
        $this->products = new ProductModel();
    }

    public function index()
    {
        return view('products/index', [
            'title' => 'Products',
            'products' => $this->products->orderBy('id', 'DESC')->findAll(),
        ]);
    }

    public function new()
    {
        return view('products/form', [
            'title' => 'Add Product',
            'product' => null,
        ]);
    }

    public function create()
    {
        if (! $this->validateProduct()) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $imageName = $this->storeImage();

        $this->products->insert([
            'name' => $this->request->getPost('name'),
            'price' => $this->request->getPost('price'),
            'stock_quantity' => $this->request->getPost('stock_quantity'),
            'image' => $imageName,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/products')->with('success', 'Product created successfully.');
    }

    public function edit(int $id)
    {
        $product = $this->products->find($id);

        if (! $product) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('products/form', [
            'title' => 'Edit Product',
            'product' => $product,
        ]);
    }

    public function update(int $id)
    {
        $product = $this->products->find($id);

        if (! $product) {
            throw PageNotFoundException::forPageNotFound();
        }

        if (! $this->validateProduct()) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $imageName = $product['image'];
        $newImage = $this->storeImage();

        if ($newImage !== null) {
            if ($imageName && is_file(FCPATH . 'uploads/products/' . $imageName)) {
                unlink(FCPATH . 'uploads/products/' . $imageName);
            }

            $imageName = $newImage;
        }

        $this->products->update($id, [
            'name' => $this->request->getPost('name'),
            'price' => $this->request->getPost('price'),
            'stock_quantity' => $this->request->getPost('stock_quantity'),
            'image' => $imageName,
        ]);

        return redirect()->to('/products')->with('success', 'Product updated successfully.');
    }

    public function delete(int $id)
    {
        $product = $this->products->find($id);

        if (! $product) {
            throw PageNotFoundException::forPageNotFound();
        }

        $hasSales = (new SaleModel())
            ->where('product_id', $id)
            ->countAllResults() > 0;

        if ($hasSales) {
            return redirect()->to('/products')->with(
                'error',
                'This product cannot be deleted because it has sales records.'
            );
        }

        if ($product['image'] && is_file(FCPATH . 'uploads/products/' . $product['image'])) {
            unlink(FCPATH . 'uploads/products/' . $product['image']);
        }

        $this->products->delete($id);

        return redirect()->to('/products')->with('success', 'Product deleted successfully.');
    }

    private function validateProduct(): bool
    {
        $rules = [
            'name' => 'required|max_length[100]',
            'price' => 'required|decimal|greater_than[0]',
            'stock_quantity' => 'required|integer|greater_than_equal_to[0]',
        ];

        $image = $this->request->getFile('image');

        if ($image && $image->getError() !== UPLOAD_ERR_NO_FILE) {
            $rules['image'] = 'uploaded[image]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]|max_size[image,2048]';
        }

        return $this->validate($rules);
    }

    private function storeImage(): ?string
    {
        $image = $this->request->getFile('image');

        if (! $image || $image->getError() === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $fileName = $image->getRandomName();

        service('image')
            ->withFile($image->getTempName())
            ->fit(600, 600, 'center')
            ->save(FCPATH . 'uploads/products/' . $fileName);

        return $fileName;
    }
}
