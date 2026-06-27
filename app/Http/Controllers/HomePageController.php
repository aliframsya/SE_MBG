<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomePageController extends Controller
{
    public function index()
    {

        $portals = [
            [
                'role' => 'Dashboard',
                'description' => 'Silahkan Masuk Admin dan Karyawan',
                'icon' => 'fa-gauge-high',
                'color_theme' => 'blue',
                'url' => route('login'),
                'btn_text' => 'Masuk Dashboard'
            ]
        ];
        return view('homepage.index', compact('portals'));
    }
}
