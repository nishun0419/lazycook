<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TodoController extends Controller
{
    //
    public function getTodo(){

    	$todos = DB::table('Todos')->get();

    	return $todos;

    }
}
