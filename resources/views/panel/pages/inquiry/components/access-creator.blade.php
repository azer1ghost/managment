<div class="row">
    @forelse($editableUsers as $index => $editableUser)
        <div class="col-12 row m-2">
            <div class="col-4">
                <select class="form-control" name="user[{{$index}}][id]">
                    @foreach($users as $user)
                        <option @if($user['id'] == $editableUser['id']) selected @endif value="{{$user['id']}}">{{$user['name']}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6">
                <input class="form-control" type="text" name="user[{{$index}}][editable_ended_at]" value="{{$editableUser['pivot']['editable_ended_at']}}">
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
    <div class="col-12">
        <button wire:click.prevent="addUser" class="btn btn-xs form-control btn-outline-success">
            <i class="fal fa-plus"></i>
        </button>
    </div>
</div>



