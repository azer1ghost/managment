<div>
    @if($task->canManageLists())
        <div class="add-items d-flex">
            <input type="text" class="form-control1 todo-list-input" wire:model.lazy="todo" placeholder="What should be done ?">
            <button wire:loading.attr="disabled" class="add btn btn-primary font-weight-bold todo-list-add-btn" wire:click="addToList">Add</button>
        </div>
    @endif
    <div class="list-wrapper">
        <ul class="d-flex flex-column-reverse todo-list">
            @foreach($taskList as $list)
                <li class="@if($list->is_checked) completed @endif">
                    <div class="form-check" wire:change="toggleState({{$list->id}})">
                        <label class="form-check-label">
                            @if($list->canManage())
                                <input wire:loading.attr="disabled" class="checkbox" type="checkbox" @if($list->is_checked) checked @endif>
                            @endif
                                {{$list->name}}
                            <i class="input-helper"></i>
                        </label>
                    </div>
                    @if($list->canManage())
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

</script>
