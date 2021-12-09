<div class="modal fade" tabindex="-1" id="notify-modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{$announcement->getAttribute('title')}}</h5>
            </div>
            <div class="modal-body">
                {!! $announcement->getAttribute('detail') !!}
            </div>
            <div class="modal-footer">
                <a href="{{route('closeNotify', $announcement)}}" class="btn btn-secondary">Oxudum</a>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        $('#notify-modal').modal('show');
    </script>
@endpush