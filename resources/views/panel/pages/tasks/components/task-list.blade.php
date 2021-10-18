<div>
    @if($task->canManageLists())
        <div class="add-items d-flex flex-column flex-md-row align-items-center">
            @if($task->getAttribute('status') != 'done')
                <input type="text" class="mb-3 mb-md-0 todo-list-input" wire:model.lazy="todo" placeholder="{{__('translates.tasks.list.placeholder')}}">
                <button wire:loading.attr="disabled"
                        class="d-inline-block btn btn-primary font-weight-bold"
                        wire:click="addToList"
                >
                    @lang('translates.buttons.add')
                </button>
            @endif
        </div>
    @endif
    <div class="list-wrapper">
        <ul class="d-flex flex-column-reverse todo-list">
            @foreach($taskList as $list)
                <li class="@if($list->is_checked) completed @endif" wire:key="task-list-{{$list->id}}">
                    <div class="form-check" wire:change="toggleState({{$list->id}})">
                        <label class="form-check-label" data-toggle="tooltip" title="Created: <strong>{{$list->created_at}}</strong> </br> @if ($list->is_checked)  Checked: <strong>{{$list->updated_at}}</strong> @endif">
                            @if($task->canManageLists() && $task->getAttribute('status') != 'done')
                                <input wire:loading.attr="disabled" class="checkbox" type="checkbox" @if($list->is_checked) checked @endif>
                            @endif
                                {{$list->name}}
                            <i class="input-helper"></i>
                        </label>
                    </div>
                    @if($list->canManage() && $task->getAttribute('status') != 'done')
                        <button wire:loading.attr="disabled">
                            <i class="remove fa fa-times" wire:click="removeFromList({{$list->id}})"></i>
                        </button>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</div>
<script>
    window.addEventListener('contentChanged', event => {
        $(function () {
            $('[data-toggle="tooltip"]').tooltip({
                content: function(){
                    return $(this).attr('title');
                }
            })
        });
    });
</script>
