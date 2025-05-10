<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Service\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
// use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(private CategoryService $categoryService)
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {

        return $this->categoryService->index();
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request): JsonResponse
    {
        $data = $request->validated();

        try {
            //code...
            $this->categoryService->store($data);
            return response()->json(['message' => 'Category Berhasil dibuat']);
        } catch (\Exception $error) {
            return response()->json(['message' => $error->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    // public function show(string $id)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function show($id)
    {

        return response()->json(['data' => $this->categoryService->show($id)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, string $id)
    {
        $data = $request->validated();

        try {
            //code...
            $this->categoryService->update($data, $id);
            return response()->json(['message' => 'Category Berhasil diedit']);
        } catch (\Exception $error) {
            return response()->json(['message' => $error->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->categoryService->delete($id);
        return response()->json(['message' => 'Data Pembelian berhasil di hapus']);
    }
}
