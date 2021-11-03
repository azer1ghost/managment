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
                    <div class="card-body p-0">
                        <ul>
                            @foreach($documents as $document)
                                @php($type = $supportedTypes[$document->type])
                                <li class="col-12 py-2 d-flex align-items-center">
                                    <a href="{{route('documents.show', $document)}}" target="_blank" class="text-dark d-flex align-items-center mr-2" style="word-break: break-word">
                                        <i class="fa fa-file-{{$type['icon']}} fa-2x mr-2 text-{{$type['color']}}"></i>
                                        <span>{{$document->name}}</span>
                                    </a>
                                    @can('update', $document)
                                        <a href="{{route('documents.edit', $document)}}" class="mr-2">
                                            <i class="fal fa-pen text-success"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $document)
                                        <a href="{{route('documents.destroy', $document)}}" delete data-name="{{$document->getAttribute('name')}}">
                                            <i class="fal fa-trash text-danger"></i>
                                        </a>
                                    @endcan
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
</div>