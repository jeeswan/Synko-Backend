<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;

class LabelController extends Controller
{
    public function index()
    {
        return response()->json(Label::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:labels|max:255',
        ]);

        $label = Label::create([
            'name' => $request->name,
        ]);

        return response()->json($label, 201);
    }
}
