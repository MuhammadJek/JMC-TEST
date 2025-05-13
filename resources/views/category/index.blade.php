@extends('layout.app')
@section('content')
    <div class="col-lg-12 grid-margin ">
        <div class="gap-2 mb-2 d-flex align-items-center">
            <a href="#" class="text-decoration-none text-dark" style="font-size: 10pt;">HOME</a>
            <span> / </span>
            <a href="#" class="text-decoration-none text-dark" style="font-size: 10pt;">KATEGORI</a>

        </div>
        <h3 class="fw-bold">Kategory</h3>
        <div class="card">
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-bordered" id="datatable">
                        <button class="btn btn-primary" href="javascript:void(0)" onclick="showCreateModal()">+ Tambah Data
                        </button>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>action</th>
                                <th>name</th>
                                <th>Code category</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('category.modal-form')
    @push('scripts')
        <!-- Laravel Javascript Validation -->
        <script type="text/javascript" src="{{ asset('/vendor/jsvalidation/js/jsvalidation.js') }}"></script>
        {!! JsValidator::formRequest('App\Http\Requests\CategoryRequest', '#categoryForm') !!}
        <script>
            let save_method;
            $(document).ready(function() {
                categoryTable();
            });

            function categoryTable() {
                var table = $('#datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: "{{ route('category.index') }}",
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
                            data: 'name',
                            name: 'name',
                        },
                        {
                            data: 'code_category',
                            name: 'code_category',
                        },


                    ],

                });
                // $('.filter-select').change(function() {
                //     table.column($(this).data('column')).search($(this).val()).draw();
                // });

            }

            function resetValidation() {
                $('.is-invalid').removeClass('is-invalid');
                $('.is-valid').removeClass('is-valid');
                $('span.invalid-feedback').remove();
            }

            function showCreateModal() {
                $('#categoryForm')[0].reset();
                save_method = 'create';
                resetValidation();
                $('#modalForm').modal('show');
                $('.modal-title').text('Create Kategori');
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
                    url: "category/" + id,
                    success: function(response) {
                        let result = response.data;
                        $('#code_category').val(result.code_category);
                        $('#name').val(result.name);
                        $('#id').val(result.id);

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert(jqXHR.responseText);
                    }
                });
                // $('#pembelianForm')[0].reset();
                $('#modalForm').modal('show');
                $('.modal-title').text('Edit Kategori');
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
                        url: "category/" + id,
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
            // //Store dan Update data
            $('#categoryForm').on('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                let url, method;
                url = "category";
                method = "POST";

                if (save_method == 'update') {
                    url = "category/" + $('#id').val();
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
                        if (response.message == "The code category has already been taken.") {
                            Swal.fire({
                                title: "Gagal",
                                text: response.message,
                                icon: "danger"
                            });
                        }
                        // console.log(response.message);
                    }
                })
            });
        </script>
    @endpush
@endsection
