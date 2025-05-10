<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Category;
use App\Models\InformasiBarang;
use App\Models\SubCategory;
use App\Models\User;
use App\Service\BarangService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spipu\Html2Pdf\Html2Pdf;
use Yajra\DataTables\DataTables;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(private BarangService $barangService)
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        return $this->barangService->index($request);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return $this->barangService->create();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $this->barangService->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return $this->barangService->edit($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return $this->barangService->update($request, $id);
    }
    public function updateActivation(Request $request, $id)
    {
        return $this->barangService->updateActivation($request, $id);
    }
    public function cetakBarang($id)
    {
        // dd($id);
        // $request->validate(['provinsi_id' => 'required|exists:provinsis,id']);
        return $this->barangService->cetakBarang($id);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->barangService->delete($id);

        return response()->json(['message' => 'Data berhasil di hapus']);
    }
    public function deleteBarang($id)
    {
        $this->barangService->deleteBarang($id);
        return response()->json(['message' => 'Data berhasil di hapus']);
    }
}
