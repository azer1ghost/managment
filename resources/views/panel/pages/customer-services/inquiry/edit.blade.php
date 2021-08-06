@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-2">
            <x-sidebar></x-sidebar>
        </div>
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <a href="{{route('inquiry.index')}}" class="btn btn-sm btn-outline-primary mr-4">
                        <i class="fa fa-arrow-left"></i>
                        Back
                    </a>
                    Edit Request
                    @if($data)
                    <form id="restoreForm" class="float-right">
                        <div class="input-group">
                            <select class="custom-select" id="inputGroupSelect04" aria-label="Example select with button addon">
                                <option value="null" selected disabled>Old versions</option>
                                @forelse($data->backups()->select('id','created_at')->latest()->get() as $backup)
                                <option value="{{$backup->id}}">Backup {{$backup->created_at->diffForHumans(null, false, true)}}</option>
                                @empty
                                <option value="null" selected disabled>No any version available</option>
                                @endforelse
                            </select>
                            <div class="input-group-append">
                                <button disabled class="btn btn-outline-secondary" type="submit">
                                    <i class="fa fa-redo-alt"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    @endif
                </div>
                <div class="card-body">
                    @livewire('inquiry-form', ['action' => $action, 'method' => $method, 'data' => $data])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('scripts')
    <script>

        // route('inquiry.versionRestore', 'ID')

        $('#restoreForm select').change(function() {
            $('#restoreForm button').removeAttr('disabled');
        });

        $('#restoreForm').submit(function (e){
            e.preventDefault()

            $.confirm({
                title: 'Do any action',
                content: 'Are you sure do this action?',
                type: 'blue',
                typeAnimated: true,
                buttons: {
                    restore: {
                        text: 'Restore',
                        btnClass: 'btn-blue',
                        action: function(){
                        }
                    },
                    close: function () {
                    }
                }
            });

        })
    </script>
@endsection
