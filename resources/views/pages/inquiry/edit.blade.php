@extends('layouts.main')

@section('title', __('translates.navbar.inquiry'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="$backUrl">
            @if(auth()->user()->getAttribute('department_id') == \App\Models\Department::SALES))
                @lang('translates.navbar.inquiry_sales')
            @else
                @lang('translates.navbar.inquiry')
            @endif
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if ($method != 'POST')
                {{optional($data)->getAttribute('code')}}
            @else
                @lang('translates.buttons.create')
            @endif
        </x-bread-crumb-link>
    </x-bread-crumb>

    @if($data->backups()->exists())
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
    <div class="mt-3 pt-3" style="clear: both">
        <livewire:inquiry-form :action="$action"  :method="$method" :inquiry="$data" :backUrl="$backUrl"/>
    </div>
    @if(!is_null($data) && $data->getAttribute('id') != null)
        <x-documents :documents="$data->documents"/>
        <x-document-upload :id="$data->id" model="Inquiry"/>
    @endif

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
