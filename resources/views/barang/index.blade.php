@extends('layout.app')
@section('content')
    <div class="col-lg-12 grid-margin">
        <div class="gap-2 mb-2 d-flex align-items-center">
            <a href="#" class="text-decoration-none text-dark" style="font-size: 10pt;">HOME</a>
            <span> / </span>
            <a href="#" class="text-decoration-none text-dark" style="font-size: 10pt;">BARANG MASUK</a>

        </div>

        <h3 class="fw-bold">Barang Masuk</h3>
        <div class="card">
            <div class="card-body">
                <div class="justify-content-between d-flex align-items-center">
                    <a class="btn btn-primary btn-sm" href="{{ route('barang.create') }}">+ Tambah
                        Data
                    </a>

                    <div class="gap-2 col-lg-8 d-flex">

                        <select name="category_id" id="category_id" class="form-select filter">
                            <option value="">--Semua Category--</option>
                            @foreach ($category as $item)
                                {{-- {{ $item->value }} --}}
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        <select name="sub_category_id" id="sub_category_id" class="form-select">
                            <option value="">--Semua Subcategory--</option>

                        </select>
                        <select name="tahun" id="tahun" class="form-select filter-select" data-column="2">
                            <option value="">--Semua Tahun--</option>
                            <option value="2024">2024</option>
                            <option value="2025">2025</option>
                            <option value="2026">2026</option>
                        </select>

                    </div>
                </div>
                <div class="table table-responsive ">
                    <table class="table table-bordered" id="datatable">
                        <thead>
                            <tr class="text-capitalize">
                                <th>ID</th>
                                <th class="js-not-exportable">action</th>
                                <th>Tanggal</th>
                                <th>Asal Barang</th>
                                <th>Penerima</th>
                                <th>Unit</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Harga (Rp)</th>
                                <th>Jumlah</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const categorySelect = document.getElementById('category_id');
            const subCategorySelect = document.getElementById('sub_category_id');

            const semesterGrouped = @json($subcategory);


            categorySelect.addEventListener('change', () => {
                const angkatanId = categorySelect.value;
                const semesterOption = semesterGrouped[angkatanId] || [];
                if (semesterGrouped[angkatanId]) {
                    subCategorySelect.innerHTML = '<option value="">--Pilih SubCategory--</option>';
                } else {
                    subCategorySelect.innerHTML =
                        '<option value="">Data Subcategory kosong : Silahkan Pilih Kategori  terlebih dahulu !</option>';
                }

                semesterOption.forEach(semesterr => {
                    const option = document.createElement('option');
                    option.value = semesterr.id;
                    option.textContent = semesterr.name;
                    subCategorySelect.appendChild(option);
                });


            });
        </script>
        <!-- Javascript Validation -->
        <script>
            @if (Session::has('success'))
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "{{ Session::get('success') }}",
                    showConfirmButton: false,
                    timer: 1500
                });
            @endif
        </script>
        <script>
            let save_method;
            $(document).ready(function(e) {
                categoryTable();

            });

            function categoryTable() {
                var table = $('#datatable').DataTable({
                    layout: {
                        topStart: {
                            buttons: [{
                                extend: 'pdf',
                                text: 'Export PDF',
                                className: 'btn btn-danger',
                                exportOptions: {
                                    columns: ':visible :not(.js-not-exportable)'
                                }
                            }, {
                                extend: 'csv',
                                text: 'Export Excel',
                                className: 'btn btn-success',
                                exportOptions: {
                                    columns: ':visible :not(.js-not-exportable)'
                                }
                            }, ],

                        }
                    },
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: {
                        url: "{{ route('barang.index') }}",
                        data: function(d) {
                            d.category_id = $("#category_id").val();
                            d.sub_category_id = $("#sub_category_id").val();
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            className: 'text-left',
                        },
                        {
                            data: 'action',
                            name: 'action',
                            exportable: false,
                            orderable: false,
                            searchable: false,
                            buttons: false,
                        },
                        {
                            data: 'created_at',
                            name: 'created_at',
                            render: function(data) {
                                return moment(data).format('DD/MM/YYYY');
                            }
                        },
                        {
                            data: 'asal_barang',
                            name: 'asal_barang',
                        },
                        {
                            data: 'users.username',
                            name: 'users.username',
                        },

                        {
                            data: 'unit',
                            name: 'unit',

                        },
                        {
                            data: 'kode',
                            name: 'kode',

                        },
                        {
                            data: 'nama',
                            name: 'nama',

                        },
                        {
                            data: 'harga',
                            name: 'harga',


                        },
                        {
                            data: 'jumlah_barang',
                            name: 'jumlah_barang',

                        },
                        {
                            data: 'total_barang',
                            name: 'total_barang',

                        },
                        {
                            data: 'status',
                            name: 'status',

                        },
                    ],

                });
                $('#category_id, #sub_category_id').change(function() {
                    table.draw();
                });
                $('.filter-select').change(function() {
                    table.column($(this).data('column')).search($(this).val()).draw();
                });

            }

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
                        url: "barang/" + id,
                        dataType: "json",
                        success: function(response) {
                            // $('#modalForm').modal('hide');
                            $('#datatable').DataTable().ajax.reload();

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

            function lockToggle(e) {

                let id = e.getAttribute('data-id');

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "GET",
                    url: "barang-activation/" + id,
                    dataType: "json",
                    success: function(response) {
                        // $('#modalForm').modal('hide');
                        $('#datatable').DataTable().ajax.reload();

                        Swal.fire({
                            title: "Mengubah Kunci",
                            text: response.message,
                            icon: "success"
                        });

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(errorThrown);
                    }
                })

            }
        </script>
    @endpush
@endsection
