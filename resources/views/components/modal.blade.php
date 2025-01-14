    <!-- Modal -->
    <div class="modal fade" id="modal-form" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog {{ isset($size) ? 'modal-lg' : '' }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div> <!-- end modal header -->
                <form action="javascript:void(0);" method="post" class="form-horizontal" enctype="multipart/form-data"
                    id="myForm">
                    {{ $slot }}
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="btnClose"
                            data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="btnSave" class="btn btn-primary">Simpan</button>
                    </div> <!-- end modal footer -->
                </form>
            </div> <!-- end modal content-->
        </div> <!-- end modal dialog-->
    </div> <!-- end modal-->
