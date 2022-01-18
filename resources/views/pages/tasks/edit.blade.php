@extends('layouts.main')

@section('title', __('translates.navbar.task'))
@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('tasks.index')">
            @lang('translates.navbar.task')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if (!is_null($data))
                {{optional($data)->getAttribute('name')}}
            @else
                @lang('translates.buttons.create')
            @endif
        </x-bread-crumb-link>
    </x-bread-crumb>

    <livewire:task-form :action="$action" :method="$method" :task="$data" :inquiry="$inquiry"/>

    @if($method != 'POST')
        <x-documents :documents="$data->documents"/>
        <x-document-upload :id="$data->id" model="Task"/>

        @switch(optional($data)->getRelationValue('taskable')->getTable())
            @case('users')
                @if(auth()->id() == optional($data)->getRelationValue('taskable')->id)
                    <x-resultable  :id="$data->id" model="Task" :result="optional($data)->result" status="enable"/>
                @else
                    <x-resultable  :id="$data->id" model="Task" :result="optional($data)->result" status="disable"/>
                @endif

                @break

            @case('departments')
                @php
                    $departmentUsers = optional($data)->getRelationValue('taskable')->users()->isActive()->pluck('id')->toArray();
                @endphp
                @if(in_array(auth()->id(), $departmentUsers))
                    <x-resultable  :id="$data->id" model="Task" :result="optional($data)->result" status="enable"/>
                @else
                    <x-resultable  :id="$data->id" model="Task" :result="optional($data)->result" status="disable"/>
                @endif

                @break

            @default
        @endswitch
    @endif

    @if($method != 'POST')
        <div class="my-3 card p-3 my-5">
            <h3 id="task-lists-header">@lang('translates.tasks.list.to_do')</h3>
            @if($data->canManageLists())
                <form action="{{route('task-lists.store')}}" method="POST">
                    <div class="add-items d-flex flex-column flex-md-row align-items-center">
                        @if($data->getAttribute('status') != 'done')
                                @csrf
                                <input type="text" required name="name" class="mb-3 mb-md-0 todo-list-input" placeholder="{{__('translates.tasks.list.placeholder')}}">
                                <input type="hidden" name="task_id" value="{{$data->id}}">
                                <input type="hidden" value="{{request()->url()}}" name="url">
                                <button type="submit" class="d-inline-block btn btn-primary font-weight-bold">
                                    @lang('translates.buttons.add')
                                </button>
                        @endif
                    </div>
                </form>
            @endif
            <div class="list-wrapper">
                <ul class="d-flex flex-column todo-list">
                    @foreach($data->taskLists as $list)
                        <li>
                            <div class="form-check pl-1">
                                @php
                                    $user = $list->getRelationValue('user');
                                    $checkedBy = $list->getRelationValue('checkedBy');
                                @endphp
                                <form action="{{route('task-lists.update', $list)}}" class="edit-form" method="POST">
                                    @method('PUT') @csrf
                                    <div class="form-check">
                                        <input type="hidden" value="{{request()->url()}}" name="url">
                                        <input type="hidden" value="{{$list->name}}" name="name">
                                        @if($data->canManageLists() && $data->getAttribute('status') != 'done' && !$list->parentTask()->exists())
                                            <input class="form-check-input" type="checkbox"
                                                   id="list-check-{{$list->id}}"
                                                   @if($list->is_checked) checked @endif
                                            >
                                        @endif
                                        <label class="form-check-label @if($list->is_checked) completed @endif" for="list-check-{{$list->id}}"
                                               data-toggle="tooltip"
                                               title="
                                                Created:    <strong>{{$list->created_at}}</strong> </br>
                                                Created by: <strong>{{$user->fullname}} (#{{$user->id}})</strong> </br>
                                                @if ($list->is_checked)
                                                                   Checked: <strong>{{$list->updated_at}}</strong> </br>
                                                    Checked by: <strong>{{$checkedBy->fullname}} (#{{$checkedBy->id}})</strong>
                                                @endif"
                                        >{{$list->name}}</label>
                                    </div>
                                </form>
                            </div>
                            @if($list->canManage() && $data->getAttribute('status') != 'done')
                                <div class="actions d-flex align-items-center pl-5">
                                    @if(!$list->parentTask()->exists())
                                        <i class="fa fa-edit edit mr-2"></i>
                                    @endif
                                    <i class="fa fa-check submit mr-2 text-success d-none"></i>
                                    <a href="{{route('task-lists.destroy', $list)}}" delete data-name="{{$list->getAttribute('name')}}" style="height: 18px">
                                        <i class="fal fa-times remove"></i>
                                    </a>
                                    @if($list->parentTask()->exists())
                                        <a href="{{route('tasks.show', $list->parentTask)}}" class="ml-2" style="height: 20px">
                                            <i class="fas fa-thumbtack text-info"></i>
                                        </a>
                                    @else
                                        @if(auth()->user()->hasPermission('department-chief'))
                                            <a href="{{route('tasks.create', [
                                                'name' => $list->getAttribute('name'),
                                                'department' => auth()->user()->getRelationValue('department')->getAttribute('id'),
                                                'list_id' => $list->getAttribute('id')
                                            ])}}" class="ml-2"
                                            >
                                                <i class="fal fa-share text-info"></i>
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
    @if(is_numeric(optional($data)->getAttribute('inquiry_id')))
        <div class="card-header">
            Inquiry
        </div>
        <div class="card-body inquiry">
            <livewire:inquiry-form :inquiry="$inquiry" />
        </div>
    @endif

{{--    @if($method != "POST")--}}
{{--        <livewire:commentable :commentable="$data" :url="str_replace('/edit', '', url()->current())"/>--}}
{{--    @endif--}}
@endsection
@section('scripts')
    <script>
        // task lists js
        $('.edit').click(function () {
            $(this).hide();
            $(this).next().removeClass('d-none');

            let form = $(this).parent().parent().find('form');

            form.find('label').attr('contenteditable', true).focus()

            const range = document.createRange()
            const sel = window.getSelection()
            range.setStart(form.find('label')[0], 1)
            range.collapse(true)
            sel.removeAllRanges()
            sel.addRange(range)

            form.find('input[type="checkbox"]').attr('disabled', true)
        });

        $(".submit").click(function () {
            $(this).parent().parent().find('.edit-form').submit();
        });

        const label = $('.form-check-label');

        label.on('input', function (){
            $(this).prev().prev().val($(this).text())
        });

        $('.form-check-input').change(function (){
            let checked;
            if ($(this).is(':checked')) {
                checked = 1;
                $(this).next().addClass('completed');
            }else{
                checked = 0;
                $(this).next().removeClass('completed');
            }
            $.ajax({
                url: $(this).form().attr('action'),
                method: 'PUT',
                data: $(this).form().serialize() + "&is_checked=" + checked,
                success: function (){},
                error: function (){}
            });
            Livewire.emit('taskListChecked')
        });
    </script>
@endsection