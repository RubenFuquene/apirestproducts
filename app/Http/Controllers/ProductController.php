<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends Controller
{
     // Constructor para aplicar middleware de autenticación
     public function __construct()
     {
         $this->middleware('auth:sanctum');
     }
 
     // Obtener el listado de productos
     public function index()
     {
         try {
             $products = Product::with('category')->get();
             return response()->json($products);
         } catch (\Exception $e) {
             return response()->json(['message' => 'Error retrieving products'], 500);
         }
     }
 
     // Agregar un nuevo producto
     public function store(Request $request)
     {
         $validatedData = $request->validate([
             'name' => 'required|string|max:255',
             'description' => 'nullable|string',
             'price' => 'required|numeric|min:0',
             'category_id' => 'required|exists:categories,id',
         ]);
 
         try {
             $product = Product::create($validatedData);
             return response()->json($product, 201);
         } catch (ValidationException $e) {
             return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
         } catch (\Exception $e) {
             return response()->json(['message' => 'Error creating product'], 500);
         }
     }
 
     // Modificar un producto existente
     public function update(Request $request, $id)
     {
         $validatedData = $request->validate([
             'name' => 'sometimes|required|string|max:255',
             'description' => 'sometimes|nullable|string',
             'price' => 'sometimes|required|numeric|min:0',
             'category_id' => 'sometimes|required|exists:categories,id',
         ]);
 
         try {
             $product = Product::findOrFail($id);
             $product->update($validatedData);
             return response()->json($product);
         } catch (ValidationException $e) {
             return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
         } catch (ModelNotFoundException $e) {
             return response()->json(['message' => 'Product not found'], 404);
         } catch (\Exception $e) {
             return response()->json(['message' => 'Error updating product'], 500);
         }
     }
 
     // Eliminar un producto
     public function destroy($id)
     {
         try {
             $product = Product::findOrFail($id);
             $product->delete();
             return response()->json(['message' => 'Product deleted successfully']);
         } catch (ModelNotFoundException $e) {
             return response()->json(['message' => 'Product not found'], 404);
         } catch (\Exception $e) {
             return response()->json(['message' => 'Error deleting product'], 500);
         }
     }
}
