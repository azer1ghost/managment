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
                            <li class="nav-item">
                                <a class="nav-link text-black" id="pills-order-tab" data-toggle="pill" href="#pills-order" role="tab" aria-controls="pills-order" aria-selected="false">Orders</a>
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
                                        <p><strong>@lang('translates.columns.phone'):</strong> {{ $client->phone2 }}</p>
                                        <p><strong>Email:</strong> {{ $client->email1 }}</p>
                                        <p><strong>Email:</strong> {{ $client->email2 }}</p>
                                        <p><strong>Joined:</strong> {{ $client->created_at->format('F j, Y') }}</p>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                                            <i class="fas fa-tools text-white"></i>
                                            Edit Profile
                                        </button>
                                        <a href="{{ route('client-logout') }}" class="text-white btn btn-danger" onclick="event.preventDefault();
                                            document.getElementById('client-logout-form').submit();">
                                            <i class="fas fa-house-leave text-white"></i>
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
                                                    <td colspan="8" class="alert alert-primary text-center">Sizin işiniz yoxdur</td>
                                                </tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                        {{$works->appends(request()->input())->links()}}
                                    </div>
                                    <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                                        <div class="my-5">
                                            <x-documents :documents="$client->documents" title="Müqavilə və Document" />
                                            <div>
                                                <form id="document-form" class="form-row" action="{{route('doc.store', $client->id)}}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="input-group col-12 col-md-6 @error('file') is-invalid @enderror">
                                                        <div class="custom-file" style="width: 350px !important;max-width: 100%">
                                                            <input type="file" name="file" id="document-file" class="custom-file-input" required>
                                                            <label class="custom-file-label" for="document-file">@lang('translates.placeholders.choose_file')</label>
                                                        </div>
                                                        <div class="input-group-append">
                                                            <button type="submit" id="document-form-submit" class="btn btn-outline-primary mr-3">@lang('translates.buttons.upload_file')</button>
                                                            <div class="spinner-border text-primary d-none" id="document-form-btn"></div>
                                                        </div>
                                                        <input type="hidden" name="model" value="Client">
                                                    </div>
                                                    @error('file')
                                                    <div class="invalid-feedback p-2">
                                                        {{$message}}
                                                    </div>
                                                    @enderror
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="pills-order" role="tabpanel" aria-labelledby="pills-order-tab">
                                        <div class="col-12 p-lg-5 py-4">
                                            <div class="card position-sticky top-0">
                                                <ul class="nav nav-pills nav-justified mb-3" id="ex1" role="tablist">
                                                    <li class="nav-item col-sm-6" role="presentation">
                                                        <a class="nav-link btn btn-primary m-3" id="tab-transit" data-toggle="tab" href="#pills-transit"
                                                           role="tab"
                                                           aria-controls="pills-transit" aria-selected="true">Online Transit</a>
                                                    </li>
                                                    <li class="nav-item col-sm-6" role="presentation">
                                                        <a class="nav-link btn btn-primary m-3" id="tab-declaration" data-toggle="tab"
                                                           href="#pills-declaration" role="tab"
                                                           aria-controls="pills-declaration" aria-selected="false">Short Import Declaration</a>
                                                    </li>
                                                </ul>
                                                <div class="tab-content col-12">
                                                    <div class="tab-pane fade show active" id="pills-transit" role="tabpanel"
                                                         aria-labelledby="tab-transit">
                                                        <form action="{{ route('order.store') }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="text-center mb-3">
                                                                <h2>WEB TRANSIT</h2>
                                                            </div>

                                                            <div class="transit">
                                                                <div id="row">
                                                                    <div class="form-outline mb-4">
                                                                        <label class="form-label" for="transitCmr">CMR</label>
                                                                        <div class="input-group mb-4">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text" id="cmr">Upload</span>
                                                                            </div>
                                                                            <div class="custom-file">
                                                                                <input type="file" name="cmr[]" class="custom-file-input" id=cmr" aria-describedby="inputGroupFileAddon01">
                                                                                <label class="custom-file-label" for="cmr">Upload Files</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-outline mb-4">
                                                                        <label class="form-label" for="transitInv">İNVOYS</label>
                                                                        <div class="input-group mb-4">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text" id="invoice">Upload</span>
                                                                            </div>
                                                                            <div class="custom-file">
                                                                                <input type="file" name="invoice[]" class="custom-file-input" id="invoice"
                                                                                       aria-describedby="inputGroupFileAddon01">
                                                                                <label class="custom-file-label" for="invoice">Upload Files</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div id="div"></div>
                                                            <a class="btn btn-group btn-primary addButton">Add</a>
                                                            <div class="row mb-4">
                                                                <div class="col-md-6 d-flex">
                                                                    <div class="form-check mb-3 ml-4 mb-md-0">
                                                                        <input class="form-check-input" type="checkbox" value="" id="transitCheck" checked/>
                                                                        <label class="form-check-label" for="transitCheck">I have read <a href="#" class="text-black">terms</a></label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-outline mb-4">
                                                                <button type="submit" class="btn btn-warning col-12">Pay</button>
                                                            </div>
                                                            <div class="text-center">
                                                                <p>Hər hansısa sualınız var? <a href="tel:+994513339090" class="text-black">Contact Us</a></p>
                                                            </div>
                                                        </form>
                                                        <div class="col-12 text-center">
                                                            <a type="button" href="https://www.facebook.com/mobilbroker.az" class="btn btn-link btn-floating mx-1">
                                                                <i class="fab fa-facebook-f"></i>
                                                            </a>
                                                            <a type="button" href="https://www.instagram.com/mobilbroker.az/" class="btn btn-link btn-floating mx-1">
                                                                <i class="fab fa-instagram"></i>
                                                            </a>
                                                            <a type="button" href="https://www.linkedin.com/in/mobil-broker-and-logistics-2a1336203/" class="btn btn-link btn-floating mx-1">
                                                                <i class="fab fa-linkedin"></i>
                                                            </a>

                                                            <a type="button" href="https://www.youtube.com/channel/UCpbkZXCIy4LBkXI0RuF6G8A" class="btn btn-link btn-floating mx-1">
                                                                <i class="fab fa-youtube"></i>
                                                            </a>
                                                        </div>
                                                    </div>

                                                    <div class="tab-pane fade show " id="pills-declaration" role="tabpanel"
                                                         aria-labelledby="tab-declaration">
                                                        <form>
                                                            <div class="text-center mb-3">
                                                                <h2>Short Import Declaration</h2>
                                                            </div>
                                                            <div class="alert alert-success text-center">Coming Soon</div>
                                                        </form>
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
        </div>
    </div>
    <div class="modal fade " id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Profil Edit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('client-account.update', $client)}}" method="POST">
                    @csrf @method('PUT')

                    <div class="modal-body">
                        <input type="hidden" name="voen" class="form-control" value="{{$client->getAttribute('voen')}}">

                        <div class="form-group">
                            <label for="fullname">@lang('translates.columns.full_name')</label>
                            <input type="text" name="fullname" class="form-control" value="{{$client->getAttribute('fullname')}}" id="fullname" placeholder="@lang('translates.placeholders.fullname')">
                        </div>
                        <div class="form-group">
                            <label for="email1">@lang('translates.columns.email')</label>
                            <input type="text" name="email1" class="form-control" value="{{$client->getAttribute('email1')}}" id="email1" placeholder="@lang('translates.placeholders.mail')">
                            <small class="form-text text-muted">direktor emaili üçün nəzərdə tutulub</small>
                        </div>
                        <div class="form-group">
                            <label for="email2">@lang('translates.columns.email')</label>
                            <input type="text" name="email2" class="form-control" value="{{$client->getAttribute('email2')}}" id="email2" placeholder="@lang('translates.placeholders.mail')">
                            <small class="form-text text-muted">nümayəndə emaili üçün nəzərdə tutulub</small>
                        </div>
                        <div class="form-group">
                            <label for="phone1">@lang('translates.columns.phone')</label>
                            <input type="text" name="phone1" class="form-control" value="{{$client->getAttribute('phone1')}}" id="phone1" placeholder="@lang('translates.placeholders.phone')">
                            <small class="form-text text-muted">direktor nömrəsi üçün nəzərdə tutulub</small>
                        </div>
                        <div class="form-group">
                            <label for="phone2">@lang('translates.columns.phone')</label>
                            <input type="text" name="phone2" class="form-control" value="{{$client->getAttribute('phone2')}}" id="phone2" placeholder="@lang('translates.placeholders.phone')">
                            <small class="form-text text-muted">direktor nömrəsi üçün nəzərdə tutulub</small>
                        </div>
                        <div class="custom-control custom-switch mb-5">
                            <input type="checkbox" name="send_sms" class="custom-control-input" id="send_sms" @if($client->getAttribute('send_sms')) checked @endif>
                            <label class="custom-control-label" for="send_sms">@lang('translates.buttons.send_sms')</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $('#document-form').submit(function (){
            $('#document-form-btn').removeClass('d-none');
            $('#document-form-submit').prop('disabled', true);
            $('#document-file').prop('readonly', true);
        });

        // Add the following code if you want the name of the file appear on select
        $("#document-file").on("change", function() {
            const fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
    </script>
@endpush