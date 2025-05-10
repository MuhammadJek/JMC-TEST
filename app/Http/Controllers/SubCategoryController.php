<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubCategoryRequest;
use App\Models\Category;
use App\Models\SubCategory;
use App\Service\SubCategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(private SubCategoryService $subCategoryService)
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return $this->subCategoryService->index();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SubCategoryRequest $request): JsonResponse
    {
        $data = $request->validated();

        try {
            $this->subCategoryService->store($data);
            return response()->json(['message' => 'Sub Category Berhasil dibuat']);
        } catch (\Exception $error) {
            return response()->json(['message' => $error->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {

        return response()->json(['data' => $this->subCategoryService->show($id)]);
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
    public function update(SubCategoryRequest $request, $id)
    {
        $data = $request->validated();

        try {
            $this->subCategoryService->update($data, $id);
            return response()->json(['message' => 'Sub Category Berhasil diedit']);
        } catch (\Exception $error) {
            return response()->json(['message' => $error->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->subCategoryService->delete($id);
        return response()->json(['message' => 'Data Subcategory berhasil di hapus']);
    }
}
