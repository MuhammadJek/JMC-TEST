<?php

namespace App\Service;

use App\Models\Category;
use App\Models\SubCategory;
use Yajra\DataTables\DataTables;

class SubCategoryService
{
    public function index()
    {
        $subcategory = SubCategory::with('category')->get();
        $category = Category::latest()->get();
        if (request()->ajax()) {
            return DataTables::of($subcategory)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<div class="">
                    <button class="btn btn-sm btn-transparent" onclick="showEditModal(this)" data-id="' . $row->id . '"><i class="mdi mdi-pencil"></i></button>
                    <button class="btn btn-sm btn-transparent" onclick="deleteModal(this)" data-id="' . $row->id . '"><i class="mdi mdi-delete text-danger"></i></button>
                </div>';
                })->make(true);
        }
        return view('subcategory.index', compact('category'));
    }
    public function store($data)
    {
        $replace_amount = str_replace('.', '', $data['max_price']);
        return SubCategory::create([
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'max_price' => $replace_amount,
        ]);
    }
    public function show($id)
    {
        return $data = SubCategory::find($id);
    }
    public function update(array $data, $id)
    {
        $replace_amount = str_replace('.', '', $data['max_price']);
        return SubCategory::find($id)->update([
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'max_price' => $replace_amount,
        ]);
    }

    public function delete($id)
    {
        return SubCategory::find($id)->delete();
    }
}
