<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DetectionController extends Controller
{
    public function index()
    {
        return "Dashboard Page";
    }

    public function create()
    {
        return "Upload Page";
    }

    public function store(Request $request)
    {
        return "Store Data";
    }

    public function show($id)
    {
        return "Detail Page";
    }

    public function updateValidation(Request $request, $id)
    {
        return "Update Validation";
    }

    public function exportCsv()
    {
        return "Export CSV";
    }
}