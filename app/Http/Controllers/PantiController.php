<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PantiController extends Controller
{
    public function show($id)
    {
        // Ensure $panti is always defined
        $panti = [
            'id' => $id,
            'name' => 'Panti Sejahtera',
            'location' => 'Jakarta, Indonesia',
            'description' => 'A caring home for children in need.',
            'image' => '/panti-detail.jpg',
            'products' => [
                [
                    'id' => 1,
                    'name' => 'Handmade Craft 1',
                    'description' => 'Beautiful handmade craft',
                    'price' => 50000,
                    'image' => '/product1.jpg'
                ],
                [
                    'id' => 2,
                    'name' => 'Handmade Craft 2',
                    'description' => 'Another beautiful handmade craft',
                    'price' => 75000,
                    'image' => '/product2.jpg'
                ]
            ]
        ];

        // Double-check the variable is being passed
        return view('page.panti-detail', ['panti' => $panti]);
    }
}