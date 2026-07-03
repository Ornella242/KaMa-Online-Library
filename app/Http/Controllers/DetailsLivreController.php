<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DetailsLivreController extends Controller
{
      public function index()
    {
        return view('catalogue.detaillivre');
    }
}
