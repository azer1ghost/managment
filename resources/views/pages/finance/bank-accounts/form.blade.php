@extends('layouts.main')

@section('title', $account ? 'Bank Hesabı Redaktə' : 'Yeni Bank Hesabı')

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">@lang('translates.navbar.dashboard')</x-bread-crumb-link>
        <x-bread-crumb-link :link="route('bank-accounts.index')">Bank Hesabları</x-bread-crumb-link>
        <x-bread-crumb-link>{{ $account ? $account->label : 'Yeni' }}</x-bread-crumb-link>
    </x-bread-crumb>

    <form action="{{ $action }}" method="POST" class="mt-3">
        @csrf
        @method($method)

        <div class="row">
            {{-- Əsas məlumatlar --}}
            <div class="col-12">
                <h5 class="mb-3 text-muted">Əsas Məlumatlar</h5>
            </div>

            <div class="col-12 col-md-6 mb-3">
                <label class="form-label">Şirkət <span class="text-danger">*</span></label>
                <select name="company_id" class="form-control @error('company_id') is-invalid @enderror" required>
                    <option value="">— Seçin —</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}"
                            {{ old('company_id', $account?->company_id) == $company->id ? 'selected' : '' }}>
                            {{ $company->name }}
                        </option>
                    @endforeach
                </select>
                @error('company_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12 col-md-6 mb-3">
                <label class="form-label">Ad (Label) <span class="text-danger">*</span></label>
                <input type="text" name="label" class="form-control @error('label') is-invalid @enderror"
                       value="{{ old('label', $account?->label) }}" required placeholder="Mobil Broker - Kapital Bank">
                @error('label')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12 col-md-6 mb-3">
                <label class="form-label">Slug <small class="text-muted">(boş buraxsanız avtomatik yaranacaq)</small></label>
                <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror"
                       value="{{ old('slug', $account?->slug) }}" placeholder="mbrokerKapital">
                @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12 col-md-6 mb-3">
                <label class="form-label">Qaimədə görünən ad <small class="text-muted">(boş buraxsanız şirkət adından götürülür)</small></label>
                <input type="text" name="company_display_name" class="form-control"
                       value="{{ old('company_display_name', $account?->company_display_name) }}"
                       placeholder='"Mobil Broker" MMC'>
            </div>

            <div class="col-12 col-md-6 mb-3">
                <label class="form-label">Şirkət VÖEN</label>
                <input type="text" name="voen" class="form-control"
                       value="{{ old('voen', $account?->voen) }}" placeholder="1804705371">
            </div>

            {{-- Bank məlumatları --}}
            <div class="col-12 mt-2">
                <h5 class="mb-3 text-muted">Bank Məlumatları</h5>
            </div>

            <div class="col-12 col-md-8 mb-3">
                <label class="form-label">Bank Adı</label>
                <input type="text" name="bank_name" class="form-control"
                       value="{{ old('bank_name', $account?->bank_name) }}"
                       placeholder="KAPITAL BANK ASC KOB mərkəz filialı">
            </div>

            <div class="col-12 col-md-4 mb-3">
                <label class="form-label">Bank Kodu</label>
                <input type="text" name="bank_kod" class="form-control"
                       value="{{ old('bank_kod', $account?->bank_kod) }}" placeholder="201412">
            </div>

            <div class="col-12 col-md-6 mb-3">
                <label class="form-label">Bank VÖEN</label>
                <input type="text" name="bank_voen" class="form-control"
                       value="{{ old('bank_voen', $account?->bank_voen) }}" placeholder="9900003611">
            </div>

            <div class="col-12 col-md-6 mb-3">
                <label class="form-label">SWIFT</label>
                <input type="text" name="swift" class="form-control"
                       value="{{ old('swift', $account?->swift) }}" placeholder="AIIBAZ2XXXX">
            </div>

            <div class="col-12 mb-3">
                <label class="form-label">H/H (Hesablaşma Hesabı)</label>
                <input type="text" name="hh" class="form-control"
                       value="{{ old('hh', $account?->hh) }}" placeholder="AZ78AIIB400500D9447193478229">
            </div>

            <div class="col-12 mb-3">
                <label class="form-label">M/H (Müxbir Hesab)</label>
                <input type="text" name="mh" class="form-control"
                       value="{{ old('mh', $account?->mh) }}" placeholder="AZ37NABZ01350100000000001944">
            </div>

            {{-- İmza məlumatları --}}
            <div class="col-12 mt-2">
                <h5 class="mb-3 text-muted">İmza Məlumatları</h5>
            </div>

            <div class="col-12 col-md-6 mb-3">
                <label class="form-label">İmzalayan (tam ad)</label>
                <input type="text" name="who" class="form-control"
                       value="{{ old('who', $account?->who) }}" placeholder="Vüsal Xəlilov İbrahim oğlu">
            </div>

            <div class="col-12 col-md-6 mb-3">
                <label class="form-label">İmzalayan (qısa)</label>
                <input type="text" name="who_footer" class="form-control"
                       value="{{ old('who_footer', $account?->who_footer) }}" placeholder="V.İ.Xəlilov">
            </div>

            <div class="col-12 col-md-6 mb-3">
                <label class="form-label">Vəzifə (Representer)</label>
                <input type="text" name="representer" class="form-control"
                       value="{{ old('representer', $account?->representer ?? 'Gömrük Təmsilçisi') }}"
                       placeholder="Gömrük Təmsilçisi">
            </div>

            <div class="col-12 col-md-6 mb-3">
                <label class="form-label">Möhür (stamp yolu)</label>
                <input type="text" name="stamp" class="form-control"
                       value="{{ old('stamp', $account?->stamp) }}"
                       placeholder="assets/images/finance/mbroker1.jpeg">
                @if($account?->stamp)
                    <small class="text-muted">
                        <img src="{{ asset($account->stamp) }}" height="40" class="mt-1"> {{ $account->stamp }}
                    </small>
                @endif
            </div>

            <div class="col-12 mt-2">
                <button type="submit" class="btn btn-success">
                    <i class="fal fa-save"></i> Yadda Saxla
                </button>
                <a href="{{ route('bank-accounts.index') }}" class="btn btn-outline-secondary ml-2">Geri</a>
            </div>
        </div>
    </form>
@endsection
