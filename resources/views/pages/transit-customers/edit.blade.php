@extends('layouts.main')

@section('title', 'Transit Müştəri - ' . ($data ? 'Redaktə' : 'Yeni'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('transit-customers.index')">
            Transit Müştəriləri
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if ($method != 'POST')
                {{$data->name}}
            @else
                Yeni Müştəri
            @endif
        </x-bread-crumb-link>
    </x-bread-crumb>

    <form action="{{$action}}" method="POST" enctype="multipart/form-data" class="mt-4">
        @csrf
        @method($method)
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name" class="form-label">Ad <span class="text-danger">*</span></label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           class="form-control" 
                           value="{{$data ? $data->name : old('name')}}" 
                           required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" 
                           name="email" 
                           id="email" 
                           class="form-control" 
                           value="{{$data ? $data->email : old('email')}}" 
                           required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="phone" class="form-label">Telefon <span class="text-danger">*</span></label>
                    <input type="text" 
                           name="phone" 
                           id="phone" 
                           class="form-control" 
                           value="{{$data ? $data->phone : old('phone')}}" 
                           required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="type" class="form-label">Tip</label>
                    <select name="type" id="type" class="form-control">
                        <option value="">Seçin</option>
                        <option value="legal" {{($data && $data->type == 'legal') || old('type') == 'legal' ? 'selected' : ''}}>Hüquqi</option>
                        <option value="people" {{($data && $data->type == 'people') || old('type') == 'people' ? 'selected' : ''}}>Fiziki</option>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="voen" class="form-label">VOEN</label>
                    <input type="text" 
                           name="voen" 
                           id="voen" 
                           class="form-control" 
                           value="{{$data ? $data->voen : old('voen')}}">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="country" class="form-label">Ölkə</label>
                    <input type="text" 
                           name="country" 
                           id="country" 
                           class="form-control" 
                           value="{{$data ? $data->country : old('country')}}">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="balance" class="form-label">Balans (AZN)</label>
                    <input type="number" 
                           name="balance" 
                           id="balance" 
                           class="form-control" 
                           step="0.01" 
                           min="0"
                           value="{{$data ? $data->balance : (old('balance') ?? 0)}}">
                </div>
            </div>

            @if($method == 'POST')
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password" class="form-label">Şifrə <span class="text-danger">*</span></label>
                        <input type="password" 
                               name="password" 
                               id="password" 
                               class="form-control" 
                               required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Şifrə Təsdiqi <span class="text-danger">*</span></label>
                        <input type="password" 
                               name="password_confirmation" 
                               id="password_confirmation" 
                               class="form-control" 
                               required>
                    </div>
                </div>
            @else
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password" class="form-label">Yeni Şifrə (Boş buraxa bilərsiniz)</label>
                        <input type="password" 
                               name="password" 
                               id="password" 
                               class="form-control">
                        <small class="form-text text-muted">Yalnız şifrəni dəyişdirmək istəyirsinizsə doldurun</small>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Yeni Şifrə Təsdiqi</label>
                        <input type="password" 
                               name="password_confirmation" 
                               id="password_confirmation" 
                               class="form-control">
                    </div>
                </div>
            @endif

            <div class="col-md-6">
                <div class="form-group">
                    <label for="rekvisit" class="form-label">Rekvizit (PDF, JPG, PNG)</label>
                    <input type="file" 
                           name="rekvisit" 
                           id="rekvisit" 
                           class="form-control" 
                           accept=".pdf,.jpg,.jpeg,.png">
                    @if($data && $data->rekvisit)
                        <small class="form-text text-muted">
                            <a href="{{asset('storage/' . $data->rekvisit)}}" target="_blank">
                                <i class="fas fa-file"></i> Cari faylı görüntülə
                            </a>
                        </small>
                    @endif
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Saxla
                </button>
                <a href="{{route('transit-customers.index')}}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Ləğv et
                </a>
            </div>
        </div>
    </form>
@endsection

