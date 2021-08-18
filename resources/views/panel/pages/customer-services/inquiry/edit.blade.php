@extends('layouts.main')

@section('style')

@endsection

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
                        {{__('translates.buttons.back')}}
                    </a>
                    {{optional($data)->getAttribute('code')}}
                    @if($data)
                    <form id="restoreForm" action="{{route('inquiry.versionRestore', $data)}}" class="float-right">
                        <div class="input-group">
                            <select class="custom-select" id="select" aria-label="Example select with button addon">
                                <option value="null" selected disabled>Old versions</option>
                                @forelse($data->backups()->select('id','created_at')->latest()->get() as $backup)
                                <option value="{{$backup->id}}">Backup {{$backup->created_at->diffForHumans(null, false, true)}}</option>
                                @empty
                                <option value="null" selected disabled>No any version available</option>
                                @endforelse
                            </select>
                            <div class="input-group-append">
                                <button disabled must-confirm="Restore old version|Are you sure restore <b>:name</b> version?|Restore" class="btn btn-outline-secondary" type="submit">
                                    <i class="fa fa-redo-alt"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    @endif
                </div>
                <div class="card-body">
                    @livewire('inquiry-form', ['action' => $action, 'method' => $method, 'inquiry' => $data])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('scripts')

    <script>
        $('#restoreForm select').change(function() {
            $('#restoreForm button').removeAttr('disabled');
        });

        $('*[must-confirm]').click(function (e){
            e.preventDefault();
            let [title, message, button] = $(this).attr('must-confirm').split('|');
            let formAction = $(this).parents('form').attr('action');
            let select = $('#select option:selected')
            let value = select.val();
            let Message = message.replace(':name', select.text());
            $.confirm({
                title: title ?? 'Do any action',
                content: Message ?? 'Are you sure do this action?',
                icon: "fa fa-redo-alt",
                theme: 'modern',
                type: 'blue',
                typeAnimated: true,
                buttons: {
                    restore: {
                        text: button ?? 'Do Action',
                        btnClass: 'btn-blue',
                        action: function(){
                                $.ajax({
                                    url: formAction,
                                    type: 'POST',
                                    data:{
                                        'backup_id': value
                                    },
                                    success: function (responseObject, textStatus, xhr) {
                                        $.alert({
                                            title: "Restored",
                                            icon: 'fa fa-check',
                                            theme: 'modern',
                                            type: 'blue',
                                            typeAnimated: true,
                                            buttons: {
                                                ok: function () {
                                                    //Livewire.emit('refreshInquiryForm')
                                                    window.location.reload()
                                                },
                                            }
                                        });
                                    },
                                })
                            }
                        },
                        close: function () {}
                    },
                });
            });

    </script>
@endsection
