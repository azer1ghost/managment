<div class="py-3">
    <div class="row m-0 p-3 border border-secondary">
        <div class="col-12">
            <h3><i class="fa fa-folder"></i> Related Files</h3>
            <hr/>
        </div>
        @php($supportedTypes = \App\Models\Document::supportedTypeIcons())
        <ul>
            @foreach($documents as $document)
                @php($type = $supportedTypes[$document->type])
                <li class="col-12 py-2">
                    <a href="{{route('documents.show', $document)}}" target="_blank" class="text-dark d-flex" style="word-break: break-word">
                        <i class="mb-2 fa fa-file-{{$type['icon']}} fa-2x mr-2 text-{{$type['color']}}"></i>
                        <span>{{$document->name}}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>