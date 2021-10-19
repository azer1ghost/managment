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

    <livewire:task-form :action="$action"  :method="$method" :task="$data" />
    @if($data)
        <div class="my-3 card p-3 my-5">
            <h3>@lang('translates.tasks.list.to_do')</h3>
            @if($data->canManageLists())
                <form action="{{route('task-lists.store')}}" method="POST">
                    <div class="add-items d-flex flex-column flex-md-row align-items-center">
                        @if($data->getAttribute('status') != 'done')
                                @csrf
                                <input type="text" name="name" class="mb-3 mb-md-0 todo-list-input" placeholder="{{__('translates.tasks.list.placeholder')}}">
                                <input type="hidden" name="task_id" value="{{$data->id}}">
                                <button type="submit" class="d-inline-block btn btn-primary font-weight-bold">
                                    @lang('translates.buttons.add')
                                </button>
                        @endif
                    </div>
                </form>
            @endif
            <div class="list-wrapper">
                <ul class="d-flex flex-column-reverse todo-list">
                    @foreach($data->taskLists as $list)
                        <li class="@if($list->is_checked) completed @endif">
                            <div class="form-check">
                                @php
                                    $user = $list->getRelationValue('user');
                                    $checkedBy = $list->getRelationValue('checkedBy');
                                @endphp
                                <label class="form-check-label" data-toggle="tooltip"
                                       title="
                                    Created:    <strong>{{$list->created_at}}</strong> </br>
                                    Created by: <strong>{{$user->fullname}} (#{{$user->id}})</strong> </br>
                                    @if ($list->is_checked)
                                               Checked: <strong>{{$list->updated_at}}</strong> </br>
                                        Checked by: <strong>{{$checkedBy->fullname}} (#{{$checkedBy->id}})</strong>
                                    @endif
                                               ">
                                    @if($data->canManageLists() && $data->getAttribute('status') != 'done')
                                        <input class="checkbox" type="checkbox"
                                               @if($list->is_checked) checked @endif>
                                    @endif
                                    <form action="{{route('task-lists.update', $list)}}" class="edit-form">
                                        @csrf
                                        <input type="text" value="{{$list->name}}" class="list" name="name" readonly data-checked="@if($list->is_checked) 1 @else 0 @endif" data-id="{{$list->id}}">
                                    </form>
                                    <i class="input-helper"></i>
                                </label>
                            </div>
                            @if($list->canManage() && $data->getAttribute('status') != 'done')
                                <div class="actions d-flex align-items-center">
                                    <i class="fa fa-pen-alt edit mr-2" style="position: relative; top: -2px;cursor: pointer"></i>
                                    <form action="{{route('task-lists.destroy', $list)}}" method="POST">
                                        @method('DELETE') @csrf
                                        <button type="submit">
                                            <i class="remove fa fa-times"></i>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
    @if($inquiry ?? optional($data)->inquiry_id)
        <div class="card-header">
            Inquiry
        </div>
        <div class="card-body inquiry">
            <livewire:inquiry-form :inquiry="$inquiry ?? $data->getRelationValue('inquiry')" />
        </div>
    @endif

    @if($method != "POST")
        <livewire:commentable :commentable="$data" :url="str_replace('/edit', '', url()->current())"/>
    @endif
@endsection
@section('scripts')
    <script>
        $('.edit').click(function () {
            $(this).parent().parent().find('form').find('input').attr('readonly', false)
        });

        $('.list').click(function () {
            $.ajax({
                url: $(this).form().attr('action'),
                method: 'PUT',
                data: {
                    'is_checked': $(this).data('checked'),
                    '_token': '{{csrf_token()}}'
                },
                success: function (){
                }
            });
        });

        $(".edit-form").submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: $(this).prop('action'),
                method: 'PUT',
                data: $(this).serialize(),
                success: function (){
                    $(".edit-form").find('input').attr('readonly', true)
                }
            });
        });
    </script>
@endsection