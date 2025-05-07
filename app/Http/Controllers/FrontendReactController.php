<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontendReactController extends Controller
{
    public function view(Request $request)

    {

        return view('appreact.app-tecnicos');
    }
}
