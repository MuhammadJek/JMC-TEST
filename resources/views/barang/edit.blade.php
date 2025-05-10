@extends('layout.app')
@section('content')
    <div class="col-12 grid-margin ">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form class="card" action="{{ route('barang.update', $informasiBarang->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-header" style="border-radius: 20px 20px 0px 0px ">
                    <h4 class="">INFORMASI UMUM</h4>
                </div>
                <div class="card-body">
                    @if (Auth::check() && Auth::user()->role == 'admin')
                        <div class="form-group col-lg-3">
                            <label for="operator_id">Operator</label>
                            <select
                                class="form-select @error('operator_id')
                        is-invalid
                    @enderror"
                                name="operator_id" id="operator_id">
                                <option value="">-- Pilih operator --</option>
                                @foreach ($operator as $item)
                                    <option value="{{ $item->id }}"
                                        {{ $informasiBarang->operator_id == $item->id ? 'selected' : '' }}>
                                        {{ $item->username }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="form-group col-lg-3">
                        <label for="category_id">Kategori</label>
                        <select
                            class="form-select @error('category_id')
                            is-invalid
                        @enderror"
                            name="category_id" id="category_id">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($kategori as $item)
                                <option value="{{ $item->id }}"
                                    {{ $informasiBarang->category_id == $item->id ? 'selected' : '' }}>
                                    {{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="form-group col-lg-3">
                            <label for="sub_category_id">Sub Kategori</label>
                            <select
                                class="form-select @error('sub_category_id')
                                is-invalid
                            @enderror"
                                name="sub_category_id" id="sub_category_id">

                            </select>
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="max_price">Batas Harga</label>
                            <input type="text" name="max_price" id="max_price"
                                class="form-control rupiah @error('max_price')
                                is-invalid
                            @enderror"
                                value="{{ old('max_price') }}" readonly>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="form-group col-lg-6">
                            <label for="asal_barang">Asal barang</label>
                            <input type="text"
                                class="form-control @error('asal_barang')
                                is-invalid
                            @enderror"
                                id="asal_barang" name="asal_barang"
                                value="{{ old('asal_barang', $informasiBarang->asal_barang) }}" placeholder="">
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="form-group col-lg-3">
                            <label for="exampleInputPassword4">Nomor Surat</label>
                            <input type="text"
                                class="form-control @error('no_surat')
                                is-invalid
                            @enderror"
                                id="no_surat" name="no_surat" value="{{ old('no_surat', $informasiBarang->no_surat) }}"
                                placeholder="">
                        </div>
                        <div class="form-group col-lg-3">
                            <label>File upload</label>
                            <input type="file" name="file" class="file-upload-default">
                            <div class="input-group col-xs-12">
                                <input type="text"
                                    class="form-control @error('file')
                                    is-invalid
                                @enderror file-upload-info"
                                    disabled placeholder="Upload Image">
                                <span class="input-group-append">
                                    <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" style="border-radius: 0px 0px 0px 0px ">
                    <h4 class="">INFORMASI BARANG YG SUDAH ADA</h4>
                </div>
                <div class="gap-2 card-body d-flex">
                    <table class="table table-bordered table-responsive" id="datatable">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th>Harga Barang</th>
                                <th>Jumlah Barang</th>
                                <th>Satuan Barang</th>
                                <th>Total Barang</th>
                                <th>Expired</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($barangs as $items)
                                <tr class="">
                                    <td>
                                        {{ $items->name }}
                                    </td>
                                    <td>
                                        {{ $items->harga }}
                                    </td>
                                    <td>
                                        {{ $items->jumlah_barang }}
                                    </td>
                                    <td>
                                        {{ $items->satuan }}
                                    </td>
                                    <td>
                                        {{ $items->total_barang }}
                                    </td>
                                    <td>
                                        {{ $items->expired }}
                                    </td>
                                    <td>
                                        <button class="border btn-rounded btn btn-icon" type="button"
                                            onclick="deleteModal(this)" data-id="{{ $items->id }}"><i
                                                class="mdi mdi-delete text-danger"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
            <div class="card">
                <div class="card-header" style="border-radius: 0px 0px 0px 0px ">
                    <h4 class="">INFORMASI UMUM</h4>
                </div>
                <div class="gap-2 card-body d-flex">
                    <table id="table">
                        <tr class="set">
                            <td>
                                <div class="form-group">
                                    <label for="name">Nama Barang</label>
                                    <input
                                        class="form-control @error('name[0]')
                                        is-invalid
                                    @enderror"
                                        name="name[0]" id="name" value="{{ old('name[0]') }}" type="text">

                                </div>
                            </td>
                            <td>
                                <div class="form-group ">
                                    <label for="harga">Harga (Rp)</label>
                                    <input
                                        class="form-control rupiah @error('harga[0]')
                                        is-invalid
                                    @enderror angka"
                                        name="harga[0]" id="harga" type="text" value="{{ old('harga[0]') }}"
                                        placeholder="Rp">
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <label for="sub_category_id">Jumlah</label>
                                    <input
                                        class="form-control @error('jumlah_barang[0]')
                                        is-invalid
                                    @enderror angka"
                                        name="jumlah_barang[0]" id="jumlah_barang" value="{{ old('jumlah_barang[0]') }}"
                                        type="number">
                                </div>
                            </td>
                            <td>
                                <div class="form-group ">
                                    <label for="max_price">Satuan</label>
                                    <input
                                        class="form-control @error('satuan[0]')
                                        is-invalid
                                    @enderror"
                                        name="satuan[0]" id="satuan" type="text" value="{{ old('satuan[0]') }}">

                                </div>
                            </td>
                            <td>
                                <div class="form-group ">
                                    <label for="total_barang">Total</label>
                                    <input type="text"
                                        class="form-control rupiah @error('total_barang[0]')
                                        
                                    @enderror hasil"
                                        id="total_barang" name="total_barang[0]" placeholder="Rp"
                                        value="{{ old('total_barang[0]') }}" readonly>
                                </div>
                            </td>
                            <td>
                                <div class="form-group ">
                                    <label>Expired</label>
                                    <input type="date" name="expired[0]"
                                        class="form-control @error('date[0]')
                                        is-invalid
                                    @enderror"
                                        value="{{ old('date[0]') }}">
                                </div>
                            </td>
                            <td>
                                <button class="border btn-rounded btn btn-icon" type="button" id="add"><i
                                        class="mdi mdi-plus"></i></button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="card-footer">

                <button type="submit" class="mr-2 btn btn-primary">Submit</button>
                <button class="btn btn-light">Cancel</button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const categorySelect = document.getElementById('category_id');
                const subCategorySelect = document.getElementById('sub_category_id');
                const maxPrice = document.getElementById('max_price');

                const semesterGrouped = @json($categoryGrouped);
                const selectedSubCategoryId = '{{ old('sub_category_id', $informasiBarang->sub_category_id ?? '') }}';

                function populateSubCategories(categoryId) {
                    const categoryOption = semesterGrouped[categoryId] || [];

                    if (categoryOption.length > 0) {
                        subCategorySelect.innerHTML = '<option value="">--Pilih Category--</option>';
                    } else {
                        subCategorySelect.innerHTML =
                            '<option value="">Data Angkatan Kosong : Silahkan Pilih Kategori terlebih dahulu !</option>';
                        return;
                    }

                    categoryOption.forEach(semesterr => {
                        const option = document.createElement('option');
                        option.value = semesterr.id;
                        option.textContent = semesterr.name;
                        option.setAttribute('data-price', semesterr.max_price ? semesterr.max_price
                            .toLocaleString('id-ID') : '');
                        if (semesterr.id == selectedSubCategoryId) {
                            option.selected = true;
                            maxPrice.value = option.getAttribute('data-price') || '';
                        }
                        subCategorySelect.appendChild(option);
                    });
                }

                categorySelect.addEventListener('change', () => {
                    const categoryId = categorySelect.value;
                    populateSubCategories(categoryId);
                });

                subCategorySelect.addEventListener('change', () => {
                    const selectedOption = subCategorySelect.options[subCategorySelect.selectedIndex];
                    const price = selectedOption.getAttribute('data-price');
                    maxPrice.value = selectedOption.value ? price : '';
                });

                // ===> Jalankan saat halaman pertama kali dimuat (EDIT MODE)
                if (categorySelect.value) {
                    populateSubCategories(categorySelect.value);
                }
            });
        </script>
        <script>
            function updateRowIndexes() {
                $('.set').each(function(index) {
                    const rowIndex = index;

                    // Ubah atribut name dan id sesuai index baru
                    $(this).find('input[name^="name"]').attr('name', `name[${rowIndex}]`);
                    $(this).find('input[id^="name_"]').attr('id', `name_${rowIndex}`);
                    $(this).find('label[for^="name_"]').attr('for', `name_${rowIndex}`);
                });
            }
            var i = 0;
            $('#add').click(function() {
                // var ad = ++i;
                const ad = $('.set').length;
                // console.log(ad);
                $('#table').append(
                    `<tr class="set">
                           <td>
                                <div class="form-group ">
                                    <label for="name">Nama Barang</label>
                                    <input class="form-control @error('name[${ad}]')
                                        is-invalid
                                    @enderror" name="name[${ad}]" id="name" type="text">

                                </div>
                            </td>
                            <td>
                                <div class="form-group ">
                                    <label for="harga">Harga (Rp)</label>
                                    <input class="form-control rupiah @error('harga[${ad}]')
                                        is-invalid
                                    @enderror angka" name="harga[${ad}]" id="harga" type="text" placeholder="Rp">
                                </div>
                            </td>
                            <td>
                                <div class="form-group ">
                                    <label for="sub_category_id">Jumlah</label>
                                    <input class="form-control @error('jumlah_barang[${ad}]')
                                        is-invalid
                                    @enderror angka" name="jumlah_barang[${ad}]" id="jumlah_barang" type="number">
                                </div>
                            </td>
                            <td>
                                <div class="form-group ">
                                    <label for="max_price">Satuan</label>
                                    <input class="form-control @error('satuan[${ad}]')
                                        is-invalid
                                    @enderror" name="satuan[${ad}]" id="satuan" type="text">

                                </div>
                            </td>
                            <td>
                                <div class="form-group ">
                                    <label for="total_barang">Total</label>
                                    <input type="text" class="form-control rupiah @error('total_barang[${ad}]')
                                        is-invalid
                                    @enderror hasil" id="total_barang" name="total_barang[${ad}]"
                                        placeholder="Rp" readonly>
                                </div>
                            </td>
                            <td>
                                <div class="form-group ">
                                    <label>Expired</label>
                                    <input type="date" name="expired[${ad}]" class="form-control @error('date[${ad}]')
                                        is-invalid
                                    @enderror">
                                </div>
                            </td>
                            <td>
                                <button class="border btn-rounded btn btn-icon remove-table-row" type="button" id="add"><i
                                        class="mdi mdi-close text-danger"></i></button>
                            </td>

                        </tr>`
                );

                $('.rupiah').mask("#.##0", {
                    reverse: true
                });
                document.querySelectorAll('.set').forEach(set => {
                    const inputs = set.querySelectorAll('.angka');
                    const hasil = set.querySelector('.hasil');

                    // Tambahkan listener ke semua input dalam set
                    inputs.forEach(input => {
                        input.addEventListener('input', () => {
                            let total = 1;
                            let hasInput = false;

                            inputs.forEach(i => {
                                const val = i.value.replaceAll('.', '');
                                if (!isNaN(val)) {
                                    total *= val;
                                    hasInput = true;
                                }
                            });

                            hasil.value = hasInput ? total.toLocaleString('id-ID') : '';
                        });
                    });
                });
            });
            $(document).on('click', '.remove-table-row', function() {
                $(this).parents('tr').remove();
                updateRowIndexes();
            });

            @if (Session::has('error'))
                Swal.fire({
                    position: "top-end",
                    icon: "error",
                    title: "{{ Session::get('error') }}",
                    showConfirmButton: false,
                    timer: 1500
                });
            @endif
            document.querySelectorAll('.set').forEach(set => {
                const inputs = set.querySelectorAll('.angka');
                const hasil = set.querySelector('.hasil');

                // Tambahkan listener ke semua input dalam set
                inputs.forEach(input => {
                    input.addEventListener('input', () => {
                        let total = 1;
                        let hasInput = false;

                        inputs.forEach(i => {
                            const val = i.value.replaceAll('.', '');
                            if (!isNaN(val)) {
                                total *= val;
                                hasInput = true;
                            }
                        });

                        hasil.value = hasInput ? total.toLocaleString('id-ID') : '';
                    });
                });
            });
            $('.rupiah').mask("#.##0", {
                reverse: true
            });

            function deleteModal(e) {

                let id = e.getAttribute('data-id');

                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "DELETE",
                        url: "{{ route('barang.deletes', ':id') }}".replace(':id', id),
                        dataType: "json",
                        success: function(response) {
                            // $('#modalForm').modal('hide');
                            location.reload();

                            if (result.isConfirmed) {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: response.message,
                                    icon: "success"
                                });
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(errorThrown);
                        }
                    })
                });
            }
        </script>
    @endpush
@endsection
