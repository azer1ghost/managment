@extends('pages.clients.auth.layout')
@section('title', 'Login')
@section('content')
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active text-black" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Profile</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-black" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Works</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-black" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">Documents</a>
                            </li>
                        </ul>

                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="tab-content" id="pills-tabContent">
                                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">

                                        <p><strong>Username:</strong> {{ $client->fullname }}</p>
                                        <p><strong>Voen:</strong> {{ $client->voen }}</p>
                                        <p><strong>@lang('translates.columns.phone'):</strong> {{ $client->phone1 }}</p>
                                        <p><strong>@lang('translates.columns.phone'):</strong> {{ $client->phone1 }}</p>
                                        <p><strong>Email:</strong> {{ $client->email1 }}</p>
                                        <p><strong>Email:</strong> {{ $client->email2 }}</p>
                                        <p><strong>Joined:</strong> {{ $client->created_at->format('F j, Y') }}</p>
                                        <a href="{{ route('client-logout') }}" onclick="event.preventDefault();
                                            document.getElementById('client-logout-form').submit();">
                                            <i class="fas fa-house-leave text-primary"></i>
                                            Logout
                                        </a>
                                        <form id="client-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                        <table class="table table-responsive-md">
                                            <thead>
                                            <tr>
                                                <th>Xidmət</th>
                                                <th>Department</th>
                                                <th>Tarix</th>
                                                <th>Status</th>
                                                <th>Sənəd</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @forelse($works as $work)
                                                <tr>
                                                    <td>{{$work->getRelationValue('service')->getAttribute('name')}}</td>
                                                    <td>{{$work->getRelationValue('department')->getAttribute('name')}}</td>
                                                    <td>{{$work->getAttribute('created_at')}}</td>
                                                    <td>{{trans('translates.work_status.' . $work->getAttribute('status'))}}</td>
                                                    <td>
                                                        @php $supportedTypes = \App\Models\Document::supportedTypeIcons() @endphp
                                                        @foreach($work->documents as $document)
                                                            @php $type = $supportedTypes[$document->type] @endphp
                                                            @php $route = $document->type == 'application/pdf' ? route('document.temporaryUrl', $document) : route('document.temporaryViewerUrl', $document) @endphp
                                                            <a href="{{$route}}" data-toggle="tooltip" title="{{$document->file}}" target="_blank" class="text-dark d-flex align-items-center mr-2" style=" word-break: break-word">
                                                                <i class="fa fa-file-{{$type['icon']}} fa-2x m-1 text-{{$type['color']}}"></i>
                                                                <span>{{substr($document->name, 0, 10) . '...'}} </span>
                                                            </a>
                                                        @endforeach
                                                    </td>

                                                    @empty
                                                        <td colspan="8" class="alert alert-primary text-center">Sizin işiniz
                                                            yoxdur
                                                        </td>
                                                </tr>

                                            @endforelse
                                            </tbody>
                                        </table>
                                        {{$works->appends(request()->input())->links()}}
                                    </div>
                                    <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                                        <div class="my-5">
                                            <x-documents :documents="$client->documents" title="Müqavilə və Document" />
                                            <x-document-upload :id="$client->id" model="Client"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection