@extends('layouts.main')
@section('title', 'Manajemen Video')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Page</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Table</a></li>
                        <li class="breadcrumb-item active">User</li>
                    </ol>
                </div>
                <h4 class="page-title">User</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        @can('create-user')
                            <div class="col-sm-4">
                                <a href="#" class="btn btn-primary mb-2" onClick="addUser('{{ route('user.store') }}')"><i
                                        class="mdi mdi-plus-circle me-2"></i>
                                    Add User</a>
                            </div>
                        @endcan
                    </div>

                    <div class="table-responsive">
                        <table class="table table-borderless dt-responsive nowrap" id="data-table">
                            <thead class="">
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Tanggal Dibuat</th>
                                    <th style="width: 20px" class="text-center"><i class="dripicons-gear"></i></th>
                                    {{-- <th style="width: 85px;">Action</th> --}}
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>

    <x-modal>
        <div class="modal-body">
            <div class="mb-2">
                <label for="name" class="form-label">Name</label>
                <input type="hidden" name="id" id="id">
                <input type="text" class="form-control" id="name" name="name" aria-describedby="emailHelp"
                    placeholder="Enter email" value="{{ old('name') }}">
            </div>

            <div class="mb-2">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp"
                    placeholder="Enter email" value="{{ old('email') }}">
            </div>

            <!-- Single Select -->
            <div class="mb-2">
                <label for="role" class="form-label">Role</label>
                <select class="form-select" id="roles" name="roles">
                    <option disabled selected>Open this select menu</option>
                    @foreach ($roles as $item)
                        <option value="{{ $item }}">{{ $item }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" id="exampleInputPassword1"
                    placeholder="Password">
            </div>
        </div>
    </x-modal>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            // ajax setup for csrf token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('user.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'roles',
                        name: 'roles',
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                    },
                    {
                        data: 'action',
                        searchable: false,
                        sortable: false
                    }

                ]
            });
        });


        // trigger add modal
        function addUser(url) {
            $('#myForm').attr('action', url);
            $('#myForm').data('type', 'add');
            $('#myForm').trigger("reset");
            $('#myForm').find('.is-invalid').removeClass('is-invalid'); // Remove validation errors
            $('#myForm').find('.invalid-feedback').text(''); // Clear validation error messages
            $('#modal-title').text("Tambah Data User");
            $('#modal-form').modal('show');
            $('#id').val('');
        }

        // trigger edit modal
        function editFunc(id) {
            $('#myForm').trigger("reset");
            $('#myForm').find('.is-invalid').removeClass('is-invalid'); // Remove validation errors
            $('#myForm').find('.invalid-feedback').text(''); // Clear validation error messages
            $('#modal-title').text("Tambah Data User");
            $('#modal-form').modal('show');
            // url action to update
            let url = `{{ route('user.update', 'id') }}`
            $('#myForm').attr('action', url.replace('id', id));
            $('#myForm').data('type', 'edit');

            $.ajax({
                type: "GET",
                url: "{{ route('user.edit') }}",
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(res) {
                    $('#modal-title').html("Edit Data Satuan");
                    $('#modal-form').modal('show');
                    $('#id').val(res.data.id);
                    $('#name').val(res.data.name);
                    $('#email').val(res.data.email);
                    $('#roles').val(res.role[0]);
                },
                error: function(data) {
                    console.log(data.errors);

                    alertNotify('error', data.responseJSON.message);
                }
            });
        }

        // trigger delete
        function deleteFunc(id) {
            if (confirm("Delete Record?") == true) {
                var id = id;

                // ajax
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('user.delete') }}",
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(data) {
                        alertNotify('success', data.message);
                        var oTable = $('#data-table').dataTable();
                        oTable.fnDraw(false);
                    },
                    error: function(data) {
                        alertNotify('error', data.responseJSON.message);
                    }
                });
            }
        }

        // submit form with ajax
        $('#myForm').submit(function(e) {
            $("#btnSave").html(`
            <div class="spinner-border spinner-border-sm" role="status">
                    <span class="visually-hidden">Loading...</span>
                     </div> Loading...
            `);
            $("#btnSave").attr("disabled", true);
            e.preventDefault();
            var formData = new FormData(this);

            if ($('#myForm').data('type') == 'edit') {
                formData.append('_method', 'PUT')
            }
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: (data) => {
                    $("#modal-form").modal('hide');
                    var oTable = $('#data-table').dataTable();
                    oTable.fnDraw(false);
                    $("#btnSave").html("Simpan");
                    $("#btnSave").attr("disabled", false);
                    alertNotify('success', data.message);
                },
                error: function(data) {
                    $("#btnSave").html("Simpan");
                    $("#btnSave").attr("disabled", false);
                    loopErrors(data.responseJSON.errors);
                    alertNotify('danger', data.responseJSON.message);
                }
            });
        });
    </script>
@endsection
