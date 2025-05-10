<?php

namespace App\Service;

use App\Models\Barang;
use App\Models\Category;
use App\Models\InformasiBarang;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spipu\Html2Pdf\Html2Pdf;
use Yajra\DataTables\DataTables;

class BarangService
{
    public function index($request)
    {
        $Ibarang = InformasiBarang::with(['barang', 'users'])
            ->when($request->category_id, function ($query) use ($request) {
                $query->where('category_id', $request->category_id);
            })->when($request->sub_category_id, function ($query) use ($request) {
                $query->whereHas('subCategory', function ($q) use ($request) {
                    $q->where('sub_category_id', $request->sub_category_id);
                });
            })->get();
        // $Ibarang2 = InformasiBarang::with(['barang', 'users'])->get();

        $category = Category::all();
        $subcategory = SubCategory::with('category')->get()->groupBy('category_id');

        // $role = RoleEnum::cases();
        if (request()->ajax()) {
            return DataTables::of($Ibarang)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a class="btn btn-sm btn-transparent" href=' . route('barang.edit', $row->id) . '><i class="mdi mdi-pencil"></i></a>';
                    $btn = $btn . '<button class="btn btn-sm btn-transparent" onclick="deleteModal(this)" data-id="' . $row->id . '"><i class="mdi mdi-delete text-danger"></i></button>';
                    $btn = $btn . '<a class="btn btn-sm btn-transparent lock-toggle" href=' . route('barang.cetak', $row->id) . '><i class="mdi mdi-file-document text-dark"></i></a>';
                    // if ($row->lock == "unlocked") {
                    //     $btn = $btn . '<button class="btn btn-sm btn-transparent lock-toggle" onclick="lockToggle(this)" data-id="' . $row->id . '"><i class="mdi mdi-lock-outline text-danger"></i></button>';
                    // } else {
                    // }

                    return $btn;
                })->addColumn('harga', function ($row) {
                    $html = '';
                    foreach ($row->barang as $item) {
                        $html .= "<tr class='p-3 m-auto'>
                        <td class='p-3'>" . "Rp " . number_format($item->harga, 2, ',', '.') . "</td>
                    </tr>";
                    }
                    return "<table class='table table-striped'>$html</table>";
                })
                ->addColumn('kode', function ($row) {
                    $html = '';
                    foreach ($row->barang as $item) {
                        $html .= "<tr class='p-3 m-auto'>
                        <td class='p-3'>" . $row->category->code_category . "</td>
                    </tr>";
                    }
                    return "<table class='table table-striped'>$html</table>";
                })->addColumn('nama', function ($row) {
                    $html = '';
                    foreach ($row->barang as $item) {
                        $html .= "<tr class='p-3 m-auto'>
                        <td class='p-3'>" . $item->name . "</td>
                    </tr>";
                    }
                    return "<table class='table table-striped'>$html</table>";
                })->addColumn('jumlah_barang', function ($row) {
                    $html = '';
                    foreach ($row->barang as $item) {
                        $html .= "<tr class='p-3 m-auto'>
                        <td class='p-3'>" . $item->jumlah_barang . " " . $item->satuan . "</td>
                    </tr>";
                    }
                    return "<table class='table table-striped'>$html</table>";
                })->addColumn('total_barang', function ($row) {
                    $html = '';
                    foreach ($row->barang as $item) {
                        $html .= "<tr class='p-3 m-auto'>
                        <td class='p-3'>" . "Rp " . number_format($item->total_barang, 2, ',', '.') . "</td>
                    </tr>";
                    }
                    return "<table class='table table-striped'>$html</table>";
                })->addColumn('status', function ($row) {
                    $html = '';
                    foreach ($row->barang as $item) {
                        if ($item->status == 'verifikasi') {
                            $html .= "<tr class='p-2 m-auto'>
                        <td class='p-1'>
                            <button class='btn btn-icon' onclick='lockToggle(this)' data-id=" . $item->id . "><i class='mdi mdi-check-circle-outline text-success'></i></button>
                        </td>
                    </tr>";
                        } else {
                            $html .= "<tr class='p-2 m-auto'>
                            <td class='p-1'>
                                <button class='btn btn-icon' onclick='lockToggle(this)' data-id=" . $item->id . "><i class='mdi mdi-minus-circle-outline text-warning'></i></button>
                            </td>
                        </tr>";
                        }
                    }
                    return "<table class='table table-striped'>$html</table>";
                })->addColumn('unit', 'gudang utama')->rawColumns(['harga', 'action', 'kode', 'nama', 'jumlah_barang', 'total_barang', 'status'])->make(true);
        }
        return view('barang.index', compact('category', 'subcategory'));
    }
    public function create()
    {
        $operator = User::where('role', 'operator')->get();
        $categoryGrouped = SubCategory::with('category')->get()->groupBy('category_id');
        $categoryFind = SubCategory::with('category')->first()->groupBy('category_id');
        $kategori = Category::all();
        return view('barang.create', compact('categoryGrouped', 'categoryFind', 'operator', 'kategori'));
    }
    public function edit($id)
    {
        $informasiBarang = InformasiBarang::find($id);
        $barangs = Barang::where('informasi_barang_id', $id)->get();
        $operator = User::where('role', 'operator')->get();
        $categoryGrouped = SubCategory::with('category')->get()->groupBy('category_id');
        $categoryFind = SubCategory::with('category')->first()->groupBy('category_id');
        $kategori = Category::all();
        return view('barang.edit', compact('categoryGrouped', 'categoryFind', 'operator', 'kategori', 'informasiBarang', 'barangs'));
    }
    public function update($request, $id)
    {
        $barang = Barang::where('id', $id)->pluck('harga');
        $totalbarang = Barang::where('id', $id)->pluck('total_barang');
        $jumlahbarang = Barang::where('id', $id)->pluck('jumlah_barang');


        if (Auth::user()->role == 'admin') {
            $dataValidateInformasi = $request->validate([
                'operator_id' => 'required|exists:users,id',
                'category_id' => 'required|exists:categories,id',
                'sub_category_id' => 'required|exists:sub_categories,id',
                'max_price' => 'required',
                'asal_barang' => 'nullable',
                'file' => 'nullable'
            ]);
        } else {
            $dataValidateInformasi = $request->validate([
                'category_id' => 'required|exists:categories,id',
                'sub_category_id' => 'required|exists:sub_categories,id',
                'max_price' => 'required',
                'asal_barang' => 'nullable',
                'file' => 'nullable'
            ]);
        }
        $name = $request->input('name');
        $harga = $request->input('harga');
        $jumlah_barang = $request->input('jumlah_barang');
        $satuan = $request->input('satuan');
        $total_barang = $request->input('total_barang');
        $date = $request->input('expired');
        $nosurat = $request->input('no_surat');
        // dd($name);
        if (!empty($name) && is_array($name)) {
            $dataValidateBarang = $request->validate([
                'name.*' => 'required',
                'harga.*' => 'required',
                'jumlah_barang.*' => 'required|integer',
                'satuan.*' => 'required',
                'total_barang.*' => 'required',
                'date.*' => 'required|date',
            ]);
        } else {
            // $dataValidateBarang = $request->validate([
            //     'name.*' => 'nullable',
            //     'harga.*' => 'nullable',
            //     'jumlah_barang.*' => 'nullable|integer',
            //     'satuan.*' => 'nullable',
            //     'total_barang.*' => 'nullable',
            //     'date.*' => 'nullable|date',
            // ]);
        }

        // dd($dataValidateInformasi['max_price']);
        try {
            // dd($barang);
            $replaceharga = str_replace('.', '', $barang);
            $replacetotal = str_replace('.', '', $totalbarang);
            // dd($replaceharga);
            foreach ($barang as $index => $replacehargas) {
                $hasil[] = $replacehargas * $jumlahbarang[$index];
            }
            $count = (array_sum($hasil));
            $replacemax_price = str_replace('.', '', $dataValidateInformasi['max_price']);
            if ($replacemax_price >= $count) {
                if ($request->file) {
                    $imageName = time() . '.' . $request->file->extension();
                    $request->file->move(public_path('storage/image'), $imageName);
                    if (Auth::user()->role == 'admin') {
                        $informasiBarang = InformasiBarang::find($id)->update([
                            'operator_id' => $dataValidateInformasi['operator_id'],
                            'category_id' => $dataValidateInformasi['category_id'],
                            'sub_category_id' => $dataValidateInformasi['sub_category_id'],
                            'max_price' => $replacemax_price,
                            'asal_barang' => $dataValidateInformasi['asal_barang'],
                            'no_surat' => $request->no_surat,
                            'file' => $imageName,
                        ]);
                    } else {
                        $informasiBarang = InformasiBarang::find($id)->update([
                            'operator_id' => Auth::user()->id,
                            'category_id' => $dataValidateInformasi['category_id'],
                            'sub_category_id' => $dataValidateInformasi['sub_category_id'],
                            'max_price' => $replacemax_price,
                            'asal_barang' => $dataValidateInformasi['asal_barang'],
                            'no_surat' => $request->no_surat,
                            'file' => $imageName,
                        ]);
                    }
                } else {
                    if (Auth::user()->role == 'admin') {
                        $informasiBarang =  InformasiBarang::find($id)->update([
                            'operator_id' => $dataValidateInformasi['operator_id'],
                            'category_id' => $dataValidateInformasi['category_id'],
                            'sub_category_id' => $dataValidateInformasi['sub_category_id'],
                            'max_price' => $replacemax_price,
                            'no_surat' => $request->no_surat,
                            'asal_barang' => $dataValidateInformasi['asal_barang'],
                        ]);
                    } else {
                        $informasiBarang =  InformasiBarang::find($id)->update([
                            'operator_id' => Auth::user()->id,
                            'category_id' => $dataValidateInformasi['category_id'],
                            'sub_category_id' => $dataValidateInformasi['sub_category_id'],
                            'max_price' => $replacemax_price,
                            'no_surat' => $request->no_surat,
                            'asal_barang' => $dataValidateInformasi['asal_barang'],
                        ]);
                    }
                }

                if (!empty($name) && is_array($name)) {
                    foreach ($name as $key => $value) {
                        Barang::create([
                            'informasi_barang_id' => $id,
                            'name' => $name[$key],
                            'harga' => $replaceharga[$key],
                            'jumlah_barang' => $jumlah_barang[$key],
                            'satuan' => $satuan[$key],
                            'total_barang' => $replacetotal[$key],
                            'expired' => $date[$key],
                        ]);
                    }
                    return redirect()->route('barang.index')->with('success', 'Berhasil Membuat Data');
                } else {
                }
            } else {
                return redirect()->back()->with('error', 'Total Semua Barang And Melebihi Batas Maximal');
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function store($request)
    {
        if (Auth::user()->role == 'admin') {
            $dataValidateInformasi = $request->validate([
                'operator_id' => 'required|exists:users,id',
                'category_id' => 'required|exists:categories,id',
                'sub_category_id' => 'required|exists:sub_categories,id',
                'max_price' => 'required',
                'asal_barang' => 'nullable',
                'file' => 'nullable'
            ]);
        } else {
            $dataValidateInformasi = $request->validate([
                'category_id' => 'required|exists:categories,id',
                'sub_category_id' => 'required|exists:sub_categories,id',
                'max_price' => 'required',
                'asal_barang' => 'nullable',
                'file' => 'nullable'
            ]);
        }
        $dataValidateBarang = $request->validate([
            'name.*' => 'required',
            'harga.*' => 'required',
            'jumlah_barang.*' => 'required|integer',
            'satuan.*' => 'required',
            'total_barang.*' => 'required',
            'date.*' => 'required|date',
        ]);

        // dd($dataValidateInformasi['max_price']);
        try {
            $name = request()->input('name');
            $harga = request()->input('harga');
            $jumlah_barang = request()->input('jumlah_barang');
            $satuan = request()->input('satuan');
            $total_barang = request()->input('total_barang');
            $date = request()->input('expired');
            $nosurat = request()->input('no_surat');



            $replaceharga = str_replace('.', '', $harga);
            $replacetotal = str_replace('.', '', $total_barang);

            foreach ($replaceharga as $index => $replacehargas) {
                $hasil[] = $replacehargas * $dataValidateBarang['jumlah_barang'][$index];
            }
            $count = (array_sum($hasil));
            $replacemax_price = str_replace('.', '', $dataValidateInformasi['max_price']);
            if ($replacemax_price >= $count) {
                if ($request->file) {
                    $imageName = time() . '.' . $request->file->extension();
                    $request->file->move(public_path('storage/image'), $imageName);
                    if (Auth::user()->role == 'admin') {
                        $informasiBarang = InformasiBarang::create([
                            'operator_id' => $dataValidateInformasi['operator_id'],
                            'category_id' => $dataValidateInformasi['category_id'],
                            'sub_category_id' => $dataValidateInformasi['sub_category_id'],
                            'max_price' => $replacemax_price,
                            'asal_barang' => $dataValidateInformasi['asal_barang'],
                            'no_surat' => $request->no_surat,
                            'file' => $imageName,
                        ]);
                    } else {
                        $informasiBarang = InformasiBarang::create([
                            'operator_id' => Auth::user()->id,
                            'category_id' => $dataValidateInformasi['category_id'],
                            'sub_category_id' => $dataValidateInformasi['sub_category_id'],
                            'max_price' => $replacemax_price,
                            'asal_barang' => $dataValidateInformasi['asal_barang'],
                            'no_surat' => $request->no_surat,
                            'file' => $imageName,
                        ]);
                    }
                } else {
                    if (Auth::user()->role == 'admin') {
                        $informasiBarang =  InformasiBarang::create([
                            'operator_id' => $dataValidateInformasi['operator_id'],
                            'category_id' => $dataValidateInformasi['category_id'],
                            'sub_category_id' => $dataValidateInformasi['sub_category_id'],
                            'max_price' => $replacemax_price,
                            'no_surat' => $request->no_surat,
                            'asal_barang' => $dataValidateInformasi['asal_barang'],
                        ]);
                    } else {
                        $informasiBarang =  InformasiBarang::create([
                            'operator_id' => Auth::user()->id,
                            'category_id' => $dataValidateInformasi['category_id'],
                            'sub_category_id' => $dataValidateInformasi['sub_category_id'],
                            'max_price' => $replacemax_price,
                            'no_surat' => $request->no_surat,
                            'asal_barang' => $dataValidateInformasi['asal_barang'],
                        ]);
                    }
                }


                foreach ($name as $key => $value) {
                    Barang::create([
                        'informasi_barang_id' => $informasiBarang->id,
                        'name' => $name[$key],
                        'harga' => $replaceharga[$key],
                        'jumlah_barang' => $jumlah_barang[$key],
                        'satuan' => $satuan[$key],
                        'total_barang' => $replacetotal[$key],
                        'expired' => $date[$key],
                    ]);
                }

                return redirect()->route('barang.index')->with('success', 'Berhasil Membuat Data');
            } else {
                return redirect()->back()->with('error', 'Total Semua Barang And Melebihi Batas Maximal');
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function updateActivation($request, $id)
    {
        $barang = Barang::find($id);

        try {
            // dd($barang->lock);
            if ($barang->status == 'verifikasi') {
                $barang->update([
                    'status' => 'unverifikasi'
                ]);
                return response()->json(['message' => 'Tidak Terverifikasi']);
            } else {
                $barang->update([
                    'status' => 'verifikasi'
                ]);
                return response()->json(['message' => 'Terverifikasi']);
            }
        } catch (\Exception $error) {
            return response()->json(['message' => $error->getMessage()]);
        }
    }
    public function cetakBarang($id)
    {
        $barang = Barang::where('informasi_barang_id', $id)->get();
        $informasiUmum = InformasiBarang::find($id);
        $html2pdf = new Html2Pdf('P', 'A4', 'de', false, 'UTF-8');
        $doc = view('laporan.laporan-barang', compact('barang', 'informasiUmum'));

        $html2pdf->pdf->setTitle('Cetak Provinsi | PDF');
        $html2pdf->setDefaultFont('Times');
        $html2pdf->writeHTML($doc, false);
        $html2pdf->Output('CETAK_BARANG_ROW.pdf');
    }
    public function delete($id)
    {
        return   InformasiBarang::find($id)->delete();
    }
    public function deleteBarang($id)
    {
        return  Barang::find($id)->delete();
    }
}
