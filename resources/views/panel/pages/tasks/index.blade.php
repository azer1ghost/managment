@extends('layouts.main')

@section('title', __('translates.navbar.task'))

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-2">
                <x-sidebar/>
            </div>
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        @lang('translates.navbar.task')
                    </div>
                    <form action="{{route('tasks.index')}}">
                        <div class="card-body">
                            <div class="row d-flex justify-content-between mb-2">
                                <div class="col-6">
                                    <div class="input-group mb-3">
                                        <input type="search" name="search" value="{{request()->get('search')}}" class="form-control" placeholder="Search" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                                            <a class="btn btn-outline-danger" href="{{route('tasks.index')}}"><i class="fal fa-times"></i></a>
                                        </div>
                                    </div>
                                </div>
                                @can('create', App\Models\Task::class)
                                    <div class="col-2">
                                        <a class="btn btn-outline-success float-right" href="{{route('tasks.create')}}">@lang('translates.buttons.create')</a>
                                    </div>
                                @endcan
                                <div class="col-12">
                                    <table class="table table-responsive-sm table-hover">
                                        <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Priority</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Department</th>
                                            <th scope="col">User</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($tasks as $task)
                                            <tr>
                                                <th scope="row">{{$loop->iteration}}</th>
                                                <td>{{$task->getAttribute('name')}}</td>
                                                <td>{{ucfirst($task->getAttribute('priority'))}}</td>
                                                <td>{{str_title($task->getAttribute('status'))}}</td>
                                                <td>{{$task->taskable->getClassShortName() == 'department' ? $task->taskable->getAttribute('name') : $task->taskable->getRelationValue('department')->getAttribute('name')}}</td>
                                                <td>{{$task->taskable->getClassShortName() == 'user' ? $task->taskable->getAttribute('fullname') : 'Ümumi' }}</td>
                                                <td>
                                                    <div class="btn-sm-group">
                                                        @can('view', $task)
                                                            <a href="{{route('tasks.show', $task)}}" class="btn btn-sm btn-outline-primary">
                                                                <i class="fal fa-eye"></i>
                                                            </a>
                                                        @endcan
                                                        @can('update', $task)
                                                            <a href="{{route('tasks.edit', $task)}}" class="btn btn-sm btn-outline-success">
                                                                <i class="fal fa-pen"></i>
                                                            </a>
                                                        @endcan
                                                        @can('delete', $task)
                                                            <a href="{{route('tasks.destroy', $task)}}" delete data-name="{{$task->getAttribute('name')}}" class="btn btn-sm btn-outline-danger" >
                                                                <i class="fal fa-trash"></i>
                                                            </a>
                                                        @endcan
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <th colspan="7">
                                                    <div class="row justify-content-center m-3">
                                                        <div class="col-7 alert alert-danger text-center" task="alert">Empty for now</div>
                                                    </div>
                                                </th>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-6">
                                    <div class="float-right">
                                        {{$tasks->links()}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $('select').change(function (){
            this.form.submit();
        });
    </script>
@endsection