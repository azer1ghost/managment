<div class="py-3">
    <div class="accordion m-0" id="documents-accordion">
            <div class="card">
                <div class="card-header">
                    @php($count = $documents->count())
                    <p class="mb-0" type="button" data-toggle="collapse" data-target="#document-files" style="font-size: 16px">
                        <i class="fa fa-folder"></i>
                        Related Files
                        <span class="badge badge-secondary">{{$count}}</span>
                    </p>
                </div>
                @php($supportedTypes = \App\Models\Document::supportedTypeIcons())
                <div id="document-files" class="collapse @if($count > 0) show @endif" data-parent="#documents-accordion">
                    <div class="card-body">
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
            </div>
        </div>
</div>