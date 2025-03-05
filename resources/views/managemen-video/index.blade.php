@extends('layouts.main')
@section('style')
    <style>
        .container {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100vh;
            /* Penuh satu layar */
            max-width: 100%;
            background: #fff;
        }

        .header {
            background-color: #ffcc00;
            /* Warna kuning */
            color: #fff;
            text-align: center;
            padding: 10px;
            font-size: 24px;
            border-bottom: 4px solid #fff;
            box-sizing: border-box;
        }

        .content {
            flex: 1;
            display: flex;
            flex-direction: row;
            overflow: hidden;
            /* Hindari overflow */
        }

        .media {
            flex: 2;
            /* Media lebih besar dari deskripsi */
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #000;
            /* Warna latar hitam untuk media */
        }

        .carousel {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: start;
            overflow: hidden;
        }

        .carousel img,
        .carousel video {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            /* Pastikan media tetap rapi */
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
            position: absolute;
        }

        .carousel img.active,
        .carousel video.active {
            opacity: 1;
            position: static;
        }

        .description {
            flex: 1;
            padding: 20px;
            background-color: #32cd32;
            /* Warna hijau */
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow-y: auto;
        }

        .description h2 {
            font-size: 24px;
            margin-bottom: 10px;
            border-bottom: 2px solid #fff;
        }

        .description p {
            font-size: 18px;
            line-height: 1.6;
            text-align: justify;
        }
    </style>
@endsection
@section('title', 'Manajemen Video')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Page</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Table</a></li>
                        <li class="breadcrumb-item active"> Manajemen Video</li>
                    </ol>
                </div>
                <h4 class="page-title"> Manajemen Video</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-4">
                        @can('create-video')
                            <div class="col-sm-4 col-md-3 h-50 pt-2">
                                <a href="#" class="btn btn-primary mb-2"
                                    onClick="addUser('{{ route('managemen-video.store') }}')"><i
                                        class="mdi mdi-plus-circle me-2"></i>Tambah Manajemen Video</a>
                            </div>
                        @endcan
                    </div>
                    <div class="table-responsive">
                        <table class="table table-borderless" id="data-table">
                            <thead class="">
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Title</th>
                                    <th>Deskripsi</th>
                                    <th>Status</th>
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
            <x-slot name="size">
                modal-lg
            </x-slot>
            <input type="hidden" name="id" id="id">
            <div class="row mb-1">
                <label for="judul" class="col-3 col-form-label">Judul <sop class="text-danger">*</sop>
                </label>
                <div class="col-9">
                    <input type="text" class="form-control" name="judul" id="judul" value="{{ old('judul') }}"
                        placeholder="Pembangunan Masyarakat">
                </div>
            </div>
            <div class="row mb-1">
                <label for="deskripsi" class="col-3 col-form-label">Deskripsi
                </label>
                <div class="col-9">
                    <textarea class="form-control" id="deskripsi" name="deskripsi" value="{{ old('deskripsi') }}" placeholder="-"
                        rows="4"></textarea>
                </div>
            </div>
            <div class="row mb-1">
                <label for="status" class="col-3 col-form-label">Status <sop class="text-danger">*</sop>
                </label>
                <div class="col-9">
                    <div class="form-check form-check-inline">
                        <input type="radio" id="aktif" name="status" value="1" class="form-check-input">
                        <label class="form-check-label" for="aktif">Aktif</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" id="nonaktif" name="status" value="0" class="form-check-input">
                        <label class="form-check-label" for="nonaktif">Nonaktif</label>
                    </div>
                </div>
            </div>
            <div class="row mb-1">
                <label for="path" class="col-3 col-form-label">Image / Video <sop class="text-danger">*</sop>
                </label>
                <div class="col-9">
                    <input type="file" class="form-control" name="path[]" id="path" multiple
                        value="{{ old('path') }}" placeholder="Pembangunan Masyarakat">
                </div>
            </div>
        </div>
    </x-modal>

    <x-modal-data-show>
        <div class="container">
            <div class="header">
                <h1 id="title"></h1>
            </div>
            <div class="content">
                <div class="media">
                    <div class="carousel" id="carousel"></div>
                </div>
                <div class="description">
                    <h2>Deskripsi Informasi</h2>
                    <p id="description"></p>
                </div>
            </div>
        </div>
    </x-modal-data-show>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('.select2').select2();
            // ajax setup for csrf token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('managemen-video.index') }}",
                },
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'judul',
                        name: 'judul',
                    },
                    {
                        data: 'deskripsi',
                        name: 'deskripsi',
                    },
                    {
                        data: 'status',
                        name: 'status',
                    },
                    {
                        data: 'action',
                        searchable: false,
                        sortable: false
                    }

                ]
            });

            // Event Handlers
            $('#filter_periode').on('change', function() {
                var table = $('#data-table').DataTable();
                table.ajax.reload();
            });

            $('#filter_lembaga').on('change', function() {
                var table = $('#data-table').DataTable();
                table.ajax.reload();
            });
        });


        // trigger add modal
        function addUser(url) {
            $('#myForm').attr('action', url);
            $('#myForm').data('type', 'add');
            $('#myForm').trigger("reset");
            $('#myForm').find('.is-invalid').removeClass('is-invalid'); // Remove validation errors
            $('#myForm').find('.invalid-feedback').text(''); // Clear validation error messages
            $('#modal-title').text("Tambah Data Manajemen Video");
            $('#modal-form').modal('show');
            $('#id').val('');
        }

        // trigger edit modal
        function editFunc(id) {
            $('#myForm').trigger("reset");
            $('#myForm').find('.is-invalid').removeClass('is-invalid'); // Remove validation errors
            $('#myForm').find('.invalid-feedback').text(''); // Clear validation error messages
            $('#modal-title').text("Edit Data Manajemen Video");
            $('#modal-form').modal('show');
            // url action to update
            let url = `{{ route('managemen-video.update', 'uid') }}`
            $('#myForm').attr('action', url.replace('uid', id));
            $('#myForm').data('type', 'edit');

            $.ajax({
                type: "GET",
                url: "{{ route('managemen-video.edit') }}",
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(res) {
                    $('#modal-title').html("Edit Data Manajemen Video");
                    $('#modal-form').modal('show');
                    $('#id').val(res.data.id);
                    $('#judul').val(res.data.judul);
                    $('#deskripsi').val(res.data.deskripsi);
                    if (res.data.status == 1) {
                        $('#aktif').prop('checked', true);
                    } else {
                        $('#nonaktif').prop('checked', true);
                    }
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
                    url: "{{ route('managemen-video.delete') }}",
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

        function loadMedia(data) {
            const currentData = data;

            // Bersihkan konten carousel sebelumnya
            const carouselContainer = document.getElementById('carousel');
            carouselContainer.innerHTML = '';
            currentData.multimedias.forEach(media => {
                let mediaElement;

                if (media.path.endsWith('.mp4')) {
                    mediaElement = document.createElement('video');
                    mediaElement.controls = true;
                    mediaElement.autoplay = false;
                    mediaElement.muted = true;

                    const sourceElement = document.createElement('source');
                    sourceElement.src = `/storage/${media.path}`;
                    sourceElement.type = 'video/mp4';
                    mediaElement.appendChild(sourceElement);
                } else {
                    mediaElement = document.createElement('img');
                    mediaElement.src = `/storage/${media.path}`;
                    mediaElement.alt = currentData.judul;
                }

                // Tambahkan class 'carousel-item'
                mediaElement.classList.add('carousel-item');
                carouselContainer.appendChild(mediaElement);
            });

            startCarousel();
        }

        function startCarousel() {
            const items = document.querySelectorAll('.carousel-item');
            let carouselIndex = 0;
            let isVideoPlaying = false;

            function showNextItem() {
                if (isVideoPlaying) return;

                items[carouselIndex].classList.remove('active');

                if (items[carouselIndex].tagName === 'VIDEO') {
                    items[carouselIndex].pause();
                    items[carouselIndex].currentTime = 0;
                }

                carouselIndex = (carouselIndex + 1) % items.length;
                items[carouselIndex].classList.add('active');

                if (items[carouselIndex].tagName === 'VIDEO') {
                    items[carouselIndex].play();
                    isVideoPlaying = true;

                    items[carouselIndex].addEventListener('ended', () => {
                        isVideoPlaying = false;
                        showNextItem();
                    }, {
                        once: true
                    });
                }
            }

            setInterval(() => {
                if (!isVideoPlaying) {
                    showNextItem();
                }
            }, 4000); // Ganti setiap 4 detik

            // Tampilkan item pertama
            items[carouselIndex].classList.add('active');
            if (items[carouselIndex].tagName === 'VIDEO') {
                items[carouselIndex].play();
                isVideoPlaying = true;
                items[carouselIndex].addEventListener('ended', () => {
                    isVideoPlaying = false;
                    showNextItem();
                }, {
                    once: true
                });
            }
        }

        // show data
        function showFunc(id) {
            $('#myFormShow').trigger("reset");
            $('#modal-title-show').text("Detail Data Manajemen Video");
            $('#modal-form-show').modal('show');
            $.ajax({
                type: "GET",
                url: "{{ route('managemen-video.edit') }}",
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(res) {
                    $('#modal-title-show').html("Detail Data Manajemen Video");
                    $('#id').val(res.data.id);
                    $('#title').html(res.data.judul);
                    $('#description').html(res.data.deskripsi);
                    loadMedia(res.data);
                },
                error: function(data) {
                    console.log(data.errors);

                    alertNotify('error', data.responseJSON.message);
                }
            });
        }

        document.getElementById('status').addEventListener('change', function() {
            // Jika checkbox dicentang, ubah value menjadi 1
            if (this.checked) {
                this.value = 1;
            } else { // Jika tidak dicentang, ubah value menjadi 0
                this.value = 0;
            }
        });
    </script>
@endsection
