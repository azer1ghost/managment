<div class="py-3">
    <div class="accordion m-0" id="documents-accordion">
            <div class="card">
                <div class="card-header">
                    @php($count = $documents->count())
                    <p class="mb-0" type="button" data-toggle="collapse" data-target="#document-files" style="font-size: 16px">
                        <i class="fa fa-folder"></i>
                        {{$title}}
                        <span class="badge badge-secondary">{{$count}}</span>
                    </p>
                </div>
                @php($supportedTypes = \App\Models\Document::supportedTypeIcons())
                <div id="document-files" class="collapse @if($count > 0) show @endif" data-parent="#documents-accordion">
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @foreach($documents as $document)
                                @php($type = $supportedTypes[$document->type])
                                <li class="col-12 py-2 d-flex align-items-center list-group-item">
                                    @php($route = $document->type == 'application/pdf' ? route('document.temporaryUrl', $document) : route('document.temporaryViewerUrl', $document))
                                    <a href="{{$route}}" data-toggle="tooltip" title="{{$document->file}}" target="_blank" class="text-dark d-flex align-items-center mr-2" style="font-size: 20px; word-break: break-word">
                                        <i style="font-size: 70px" class="fa fa-file-{{$type['icon']}} fa-3x mr-2 text-{{$type['color']}}"></i>
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