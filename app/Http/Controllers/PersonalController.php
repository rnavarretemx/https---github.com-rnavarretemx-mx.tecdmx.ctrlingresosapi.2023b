<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Personal;

class PersonalController extends Controller
{
    public function __construct()
    {
        //$this->middleware('jwt');
    }

    public function readall()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'All records obteined successfully',
            'personal' => Personal::all(),
        ]);
    }
}
