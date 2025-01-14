    {{-- <!-- Modal -->
    @section('style')
        <!-- Quill css -->
        <link href="{{ asset('design-sistem/assets/css/vendor/quill.core.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('design-sistem/assets/css/vendor/quill.snow.css') }}" rel="stylesheet" type="text/css" />
    @endsection --}}
    <div class="modal fade" id="modal-form-standar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div> <!-- end modal header -->
                <form action="javascript:void(0);" method="post" class="form-horizontal" enctype="multipart/form-data"
                    id="myFormStandar">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id">
                        <div class="row mb-1">
                            <label for="tahun_periode_id" class="col-3 col-form-label">Tahun <sop class="text-danger">*
                                </sop>
                            </label>
                            <div class="col-9">
                                <select class="form-select form-select-sm mb-1" id="tahun_periode_id"
                                    name="tahun_periode_id">
                                    @foreach ($tahunPeriodes as $item)
                                        <option value="{{ $item->id }}">{{ $item->tahun_periode }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <label for="lembaga_akreditasi_id" class="col-3 col-form-label">Standar Akreditasi <sop
                                    class="text-danger">
                                    *
                                </sop>
                            </label>
                            <div class="col-9">
                                <select class="form-select form-select-sm mb-1" id="lembaga_akreditasi_id"
                                    name="lembaga_akreditasi_id">
                                    @foreach ($lembagaAkreditasis as $item)
                                        <option value="{{ $item->id }}">{{ $item->lembaga_akreditasi }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <label for="nama_standar_mutu" class="col-3 col-form-label">Nama Standar Mutu <sop
                                    class="text-danger">*
                                </sop>
                            </label>
                            <div class="col-9">
                                <div id="snow-editor" name="nama_standar_mutu"
                                    data-nama_standar_mutu="nama_standar_mutu" value="{{ old('nama_standar_mutu') }}"
                                    style="height: 150px;"></div>
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
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="btnClose"
                            data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="btnSave" class="btn btn-primary">Simpan</button>
                    </div> <!-- end modal footer -->
                </form>
            </div> <!-- end modal content-->
        </div> <!-- end modal dialog-->
    </div> <!-- end modal-->
    {{-- @section('script')
        <!-- quill js -->
        <script src="{{ asset('design-sistem/assets/js/vendor/quill.min.js') }}"></script>
        <!-- quill Init js-->
        <script src="{{ asset('design-sistem/assets/js/pages/demo.quilljs.js') }}"></script>
    @endsection --}}
