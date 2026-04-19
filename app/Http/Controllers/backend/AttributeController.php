<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Attribute;
use App\Models\AttributeValue;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attributes = Attribute::with('values')->get();
        return view('backend.pages.attributes', compact('attributes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $request->validate([
            'name' => 'required|string|unique:attributes,name',
            'type' => 'required|in:select,color,button',
        ]);

        Attribute::create($request->only('name', 'type'));

        return back()->with('success', 'Attribute তৈরি হয়েছে।');
    }

    /**
     * Display the specified resource.
     */
    public function show(Attribute $attribute)
    {
        $values = $attribute->values()->orderBy('order')->get();
        return view('backend.pages.attributeValueShow', compact('attribute', 'values'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    //attribute value store
    public function storeValue(Request $request, Attribute $attribute)
    {
        $request->validate([
            'value'      => 'required|string',
            'color_code' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        $attribute->values()->create([
            'value'      => $request->value,
            'color_code' => $request->color_code,
            'order'      => $attribute->values()->max('order') + 1,
        ]);

        return back()->with('success', 'Value যোগ হয়েছে।');
    }

    //attribute value delete
    public function destroyValue(AttributeValue $value)
    {
        $value->delete();
        return back()->with('success', 'মুছে ফেলা হয়েছে।');
    }
}


