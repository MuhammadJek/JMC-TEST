<?php

namespace App\Service;

use App\Models\Category;
use Yajra\DataTables\DataTables;

class CategoryService
{
    public function index()
    {
        $category = Category::get();
        if (request()->ajax()) {
            return DataTables::of($category)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<div class="">
                    <button class="btn btn-sm btn-transparent" onclick="showEditModal(this)" data-id="' . $row->id . '"><i class="mdi mdi-pencil"></i></button>
                    <button class="btn btn-sm btn-transparent" onclick="deleteModal(this)" data-id="' . $row->id . '"><i class="mdi mdi-delete text-danger"></i></button>
                </div>';
                })->make(true);
        }
        return view('category.index');
    }
    public function store(array $data)
    {
        return Category::create([
            'name' => $data['name'],
            'code_category' => $data['code_category']
        ]);
    }
    public function show($id)
    {
        return Category::find($id);
    }
    public function update(array $data, $id)
    {
        return Category::find($id)->update([
            'name' => $data['name'],
            'code_category' => $data['code_category']
        ]);
    }
    public function delete($id)
    {
        return Category::find($id)->delete();
    }
}
