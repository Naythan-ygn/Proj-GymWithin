<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class EquipmentController extends Controller
{
    public function index()
    {
        // Simulating database data based on Image 1
        // In a real scenario, this would be: $equipment = Equipment::all();
        $equipment = [
            [
                'id' => 1,
                'name' => 'Raptors Stride',
                // Ensure these image names match files in public/Equipment/
                'image' => 'treadmill_v1.png', 
                'price' => null,
            ],
            [
                'id' => 2,
                'name' => 'Advanced Treadmill 5000',
                'image' => 'treadmill_v2.png',
                'price' => '1,899', // One item has a price badge in the image
            ],
            [
                'id' => 3,
                'name' => 'Ellex Elliptical',
                'image' => 'elliptical_v1.png',
                'price' => null,
            ],
            [
                'id' => 4,
                'name' => 'Compact Dumbbell Set',
                'image' => 'bench_v1.png',
                'price' => null,
            ],
            [
                'id' => 5,
                'name' => 'Raptors Air Rower',
                'image' => 'treadmill_v3.png',
                'price' => null,
            ],
            [
                'id' => 6,
                'name' => 'Compact Dumbbell Set',
                'image' => 'dumbbells_v1.png',
                'price' => null,
            ],
            [
                'id' => 7,
                'name' => 'Compact Dumbbell Set',
                'image' => 'bike_v1.png',
                'price' => null,
            ],
            [
                'id' => 8,
                'name' => 'Velocity Cycle',
                'image' => 'bike_v2.png',
                'price' => null,
            ],
        ];

        return view('equipment', compact('equipment'));
    }
}