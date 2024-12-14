<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($id)
    {
        // Ensure $product is always defined
        $product = [
            'id' => $id,
            'name' => 'Handmade Craft',
            'description' => 'Beautiful handmade craft made by children at the orphanage.',
            'price' => 50000,
            'image' => '/product-detail.jpg',
            'panti' => [
                'id' => 1,
                'name' => 'Panti Sejahtera',
                'location' => 'Jakarta, Indonesia',
                'description' => 'A caring home for children in need.',
                'image' => '/panti-detail.jpg'
            ]
        ];

        // Explicitly pass the $product variable
        return view('page.product-detail', ['product' => $product]);
    }
}