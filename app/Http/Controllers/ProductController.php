<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('product.index');
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
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'stock' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        $product = Product::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan!',
            'data' => $product,
            ],201);
    }


   
    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product = Product::find($id);
        return $product;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'stock' => 'required|numeric',
            'price' => 'required|numeric',
        ]);

        $product->update($validatedData);
        return response()->json([
            'data' => $product,
            'success' => true,
            'message' => 'Produk berhasil diperbarui!'
            ],201);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'Product Deleted'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }
    }
    

    
    public function getProducts(){
        $product = Product::orderBy('created_at', 'desc')->get();
    
        return Datatables::of($product)
            ->addColumn('action', function($product){
                return'<a onclick="editForm('. $product->id .')" class="btn btn-warning "> Edit</a> ' .
                    '<a onclick="deleteData('. $product->id .')" class="btn btn-error ">Delete</a>';
            })
            ->rawColumns(['action'])->make(true);
    }
    
}
