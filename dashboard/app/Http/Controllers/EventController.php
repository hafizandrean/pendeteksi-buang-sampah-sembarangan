<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;

class EventController extends Controller
{
    public function index()
    {
        return View::make('events.index');
    }

    public function create()
    {
        return View::make('events.create');
    }

    public function store(Request $request)
    {
        return Redirect::back()->with('success', 'Data event berhasil disimpan.');
    }

    public function show($id)
    {
        return View::make('events.show', compact('id'));
    }
}