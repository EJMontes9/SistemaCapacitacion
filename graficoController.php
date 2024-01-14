<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class graficoController extends Controller
{
    public function showChart()
    {
        $categories = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $seriesData = [
            [
                'name' => 'Reggane',
                'data' => [16.0, 18.2, 23.1, 27.9, 32.2, 36.4, 39.8, 38.4, 35.5, 29.2, 22.0, 17.8]
            ],
            [
                'name' => 'Tallinn',
                'data' => [-2.9, -3.6, -0.6, 4.8, 10.2, 14.5, 17.6, 16.5, 12.0, 6.5, 2.0, -0.9]
            ],
        ];

        return view('chart', compact('categories', 'seriesData'));
    }
}
