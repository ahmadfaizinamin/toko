<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\CategoryStoreRequest;
use App\Http\Requests\Category\CategoryUpdateRequest;
use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = $this->categoryService->getAllCategory();

            return response()->json([
                'status' => 'success',
                'data' => CategoryResource::collection($data)
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'gagal ambil category, ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryStoreRequest $request)
    {
        try {
            $validasi = $request->validated();

            $data = $this->categoryService->createCategory($validasi);

            return response()->json([
                'status' => 'success',
                'data' => new CategoryResource($data)
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'gagal buat category, ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $data = $this->categoryService->getByIdCategory($id);

            return response()->json([
                'status' => 'success',
                'data' => new CategoryResource($data)
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'category tidak ditemukan'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'gagal ambil category, ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryUpdateRequest $request, string $id)
    {
        try {
            $validasi = $request->validated();

            $data = $this->categoryService->updateCategory($id, $validasi);

            return response()->json([
                'status' => 'success',
                'data' => new CategoryResource($data)
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'category tidak ditemukan'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'gagal ambil category, ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $data = $this->categoryService->getByIdCategory($id);
            $this->categoryService->deleteCategory($id);

            return response()->json([
                'status' => 'success',
                'message' => "category '{$data['name']}' telah dihapus"
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'category tidak ditemukan'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'gagal ambil category, ' . $e->getMessage()
            ], 500);
        }
    }
}
