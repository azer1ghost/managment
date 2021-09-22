
<div class="row">
    @forelse($editableUsers as $index => $editableUser)
        <div class="col-12 row m-2" wire:key="editable-user-{{$index}}">
            <div class="col-4">
                <select class="form-control" name="users[{{$index}}][user_id]" required>
                    <option value="" disabled selected>Choose user</option>
                    @foreach($users as $user)
                        <option @if($user['id'] == $editableUser['id']) selected @endif value="{{$user['id']}}">{{$user['name']}} {{$user['surname']}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6" wire:ignore>
                <input class="form-control editable-ended-at" type="text" name="users[{{$index}}][editable_ended_at]" value="{{$editableUser['pivot']['editable_ended_at']}}">
            </div>
            <div class="col-2">
                <button type="button" wire:click.prevent="removeUser({{$index}})" class="btn btn-outline-danger">
                    <i class='fal fa-times'></i>
                </button>
            </div>
        </div>
    @empty
        <div class="col-12">
            <p class="text-muted">There is no users can edit this inquiry</p>
        </div>
    @endforelse
    <div class="col-3 col-md-1 ml-3 pl-0">
        <button wire:click.prevent="addUser" class="btn btn-xs form-control btn-outline-success">
            <i class="fal fa-plus"></i>
        </button>
    </div>
    <div class="col-12 d-flex mt-3 justify-content-end">
        <button class="btn btn-outline-primary" type="submit">@lang('translates.buttons.save')</button>
    </div>
</div>