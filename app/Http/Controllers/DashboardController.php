<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Echo_;

class DashboardController extends Controller
{
    public function index()
    {
       return view('dashboard.index');
    }
}
