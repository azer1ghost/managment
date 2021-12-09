<div class="modal fade" tabindex="-1" id="notify-modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Updates!</h5>
            </div>
            <div class="modal-body">
                <p>
                    Lorem ipsum dolor sit amet,
                    consectetur adipisicing elit.
                    Atque ea illum ipsam libero
                    omnis possimus quaerat suscipit
                    veritatis voluptas voluptate!
                </p>
            </div>
            <div class="modal-footer">
                <a href="{{route('closeNotify', 1)}}" class="btn btn-secondary" data-dismiss="modal">Oxudum</a>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        $('#notify-modal').modal('show');
    </script>
@endpush