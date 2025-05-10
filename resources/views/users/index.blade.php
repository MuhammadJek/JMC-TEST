@extends('layout.app')
@section('content')
    <div class="col-lg-12 grid-margin ">
        <div class="gap-2 mb-2 d-flex align-items-center">
            <a href="#" class="text-decoration-none text-dark" style="font-size: 10pt;">HOME</a>
            <span> / </span>
            <a href="#" class="text-decoration-none text-dark" style="font-size: 10pt;">MANAJEMENT USER</a>

        </div>
        <h3 class="fw-bold">Manajement User</h3>
        <div class="card">
            <div class="card-body">
                <div class="justify-content-between d-flex align-items-center">
                    <button class="btn btn-primary btn-sm" href="javascript:void(0)" onclick="showCreateModal()">+ Tambah
                        Data
                    </button>

                    <div class="col-md-3 d-flex">

                        <select name="" id="" class="form-select filter-select" data-column="5">
                            <option value="">--Filter Role--</option>
                            @foreach ($role as $item)
                                {{-- {{ $item->value }} --}}
                                <option value="{{ $item->value }}">{{ $item->description() }}</option>
                            @endforeach
                        </select>

                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="datatable">


                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>action</th>
                                <th>Username</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('users.modal-form')
    @include('users.modal-edit-form')

    @push('scripts')
        <!-- Javascript Validation -->
        <script type="text/javascript" src="{{ asset('/vendor/jsvalidation/js/jsvalidation.js') }}"></script>
        {!! JsValidator::formRequest('App\Http\Requests\UserRequest', '#usersForm') !!}
        {!! JsValidator::formRequest('App\Http\Requests\UserUpdateRequest', '#userEditForm') !!}

        <script>
            let save_method;
            $(document).ready(function(e) {
                categoryTable();

            });

            function categoryTable() {
                var table = $('#datatable').DataTable({
                    columnDefs: [{
                        targets: 4, // Kolom ke-3 (index 2)
                        render: $.fn.dataTable.render.number('.', ',', 2, '')
                    }],
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: "{{ route('users.index') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            className: 'text-left',
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                        },
                        {
                            data: 'username',
                            name: 'username',
                        },
                        {
                            data: 'name',
                            name: 'name',
                        },
                        {
                            data: 'email',
                            name: 'email',
                        },
                        {
                            data: 'role',
                            name: 'role',
                        },

                    ],

                });
                $('.filter-select').change(function() {
                    table.column($(this).data('column')).search($(this).val()).draw();
                });

            }

            function resetValidation() {
                $('.is-invalid').removeClass('is-invalid');
                $('.is-valid').removeClass('is-valid');
                $('span.invalid-feedback').remove();
            }

            function showCreateModal() {
                $('#usersForm')[0].reset();
                save_method = 'create';
                resetValidation();
                $('#modalForm').modal('show');
                $('.modal-title').text('Tambah Sub Kategori');
                $('.btnSubmit').text('Create');
            }

            function showEditModal(e) {

                let id = e.getAttribute('data-id');

                save_method = 'update';
                resetValidation();

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "GET",
                    url: "users/" + id,
                    success: function(response) {
                        let result = response.data;
                        $('#username').val(result.username);
                        $('#name').val(result.name);
                        $('#email').val((result.email));
                        $('#role').val((result.role));
                        $('#id').val(result.id);

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert(jqXHR.responseText);
                    }
                });
                // $('#pembelianForm')[0].reset();
                $('#modalFormEdit').modal('show');
                $('.modal-title').text('Edit Pembelian');
                $('.btnSubmit').text('Update');
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
                        url: "users/" + id,
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
                    url: "user-lock/" + id,
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

            // //Store dan Update data
            $('#usersForm').on('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                let url, method;
                url = "users";
                method = "POST";

                if (save_method == 'update') {
                    url = "users/" + $('#id').val();
                    formData.append('_method', 'PUT');
                }

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: method,
                    url: url,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#modalForm').modal('hide');
                        $('#datatable').DataTable().ajax.reload();
                        console.log(response.message);

                        Swal.fire({
                            title: "Success",
                            text: response.message,
                            icon: "success"
                        });
                    },
                    error: function(jqXHR, textStatus, error) {
                        var response = JSON.parse(jqXHR.responseText);
                        console.log(response.message);
                    }
                })
            });
            $('#userEditForm').on('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                let url, method;
                url = "users";
                method = "POST";

                if (save_method == 'update') {
                    url = "users/" + $('#id').val();
                    formData.append('_method', 'PUT');
                }

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: method,
                    url: url,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#modalFormEdit').modal('hide');
                        $('#datatable').DataTable().ajax.reload();
                        console.log(response.message);

                        Swal.fire({
                            title: "Success",
                            text: response.message,
                            icon: "success"
                        });
                    },
                    error: function(jqXHR, textStatus, error) {
                        var response = JSON.parse(jqXHR.responseText);
                        console.log(response.message);
                    }
                })
            });
        </script>
    @endpush
@endsection
