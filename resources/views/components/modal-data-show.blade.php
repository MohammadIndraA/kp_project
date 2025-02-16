    <!-- Modal -->
    <div class="modal fade" id="modal-form-show" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="modal-title-show" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title-show" id="modal-title-show">Show Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div> <!-- end modal header -->
                <form action="javascript:void(0);" method="post" class="form-horizontal" enctype="multipart/form-data"
                    id="myFormShow">
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
    <div>
        <!-- Well begun is half done. - Aristotle -->
    </div>
