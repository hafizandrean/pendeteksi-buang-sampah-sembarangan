<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function index()
    {
        $events = DB::table('events')->orderBy('id', 'desc')->get();

        return view('dashboard', compact('events'));
    }
}