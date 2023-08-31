@extends('layouts.main')

@section('title', trans('translates.navbar.instruction'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.instruction')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <style>
        h2 {
            background: #07625c;
            border: 1px solid;
            text-align: center;
            padding: 5px;
            color: whitesmoke;
            cursor: pointer;
        }
    </style>
    <div class="row mb-2">
        <div class="col-12 justify-content-center">

            <h2 data-toggle="collapse" href="#management">Mobil Management</h2>
            <div class="col-12 collapse" id="management">
                <h3 class="text-center">MB-TL-M001  Mobil Managementə giriş</h3>
                <video class="mr-5" width="100%" controls><source src="{{asset('videos/MB-TL-M001.mp4')}}" type="video/mp4"></video>
                <h3 class="text-center">MB-TL-M002  Tapşırıqlar bölməsinin funksiyasına dair təlimat</h3>
                <video class="mr-5" width="100%" controls><source src="{{asset('videos/task.mp4')}}" type="video/mp4"></video>
                <h3 class="text-center">MB-TL-M004  Təqvim bölməsinin funksiyasına dair təlimat</h3>
                <video class="mr-5" width="100%" controls><source src="{{asset('videos/teqvim.mp4')}}" type="video/mp4"></video>
                <h3 class="text-center">MB-TL-M006  Əməkdaşların bonusları </h3>
                <video class="mr-5" width="100%" controls><source src="{{asset('videos/bonuses.mp4')}}" type="video/mp4"></video>
            </div>

            @if(auth()->user()->isDirector() || auth()->user()->hasPermission('update-user'))
                <h2 data-toggle="collapse" href="#hr">HR</h2>
                <div class="collapse" id="hr">
                    <h3 class="text-center">MB-TL-H001  İşdən azad olma təlimatı</h3>
                    <video class="mr-5" width="100%" controls><source src="{{asset('videos/hr.mp4')}}" type="video/mp4"></video>
                    <h3 class="text-center">MB-TL-H002  Əmək haqqı və vəzifə dəyişikliyi təlimatı</h3>
                    <video class="mr-5" width="100%" controls><source src="{{asset('videos/salary.mp4')}}" type="video/mp4"></video>
                    <h3 class="text-center">MB-TL-M003  HR bölməsinin funksiyasına dair təlimat</h3>
                    <video class="mr-5" width="100%" controls><source src="{{asset('videos/hr.mp4')}}" type="video/mp4"></video>
                    <h3 class="text-center">MB-TL-M008  İşçi məmnuniyyətinə dair təlimat</h3>
                    <video class="mr-5" width="100%" controls><source src="{{asset('videos/IMG_6135.mp4')}}" type="video/mp4"></video>
                    <h3 class="text-center">MB-TL-M010  Dəyişikliklərin qeydiyyatı təlimatı</h3>
                    <video class="mr-5" width="100%" controls><source src="{{asset('videos/salary.mp4')}}" type="video/mp4"></video>
                    <h3 class="text-center">MB-TL-M011  Sənədlərin idarə olunması təlimatı</h3>
                    <video class="mr-5" width="100%" controls><source src="{{asset('videos/MB-TL-M011.mp4')}}" type="video/mp4"></video>
                </div>
            @endif

            <h2 data-toggle="collapse" href="#work">İşlər</h2>
            <div class="collapse" id="work">
                <h3 class="text-center">MB-TL-M005  İşlər bölməsinin funksiyalarına dair təlimat</h3>
                <video class="mr-5" width="100%" controls><source src="{{asset('videos/isler.mp4')}}" type="video/mp4"></video>
            </div>

            <h2 data-toggle="collapse" href="#inquiry">@lang('translates.navbar.sales')</h2>
            <div class="collapse" id="inquiry">
                <h3 class="text-center">MB-TL-M007  Satış bölməsinin fuksiyalarına dair təlim</h3>
                <video class="mr-5" width="100%" controls><source src="{{asset('videos/sales.mp4')}}" type="video/mp4"></video>
                <h3 class="text-center">MB-TL-M009  Müştəri məmnuniyyətinə dair təlimat</h3>
                <video class="mr-5" width="100%" controls><source src="{{asset('videos/IMG_6136.mp4')}}" type="video/mp4"></video>
            </div>


            @if(auth()->user()->isDirector() || auth()->user()->hasPermission('update-work'))
                <h2 data-toggle="collapse" href="#declaration">Bəyannamə</h2>
                <div class="collapse" id="declaration">
                    <h3 class="text-center">MB-TL-B001  BBGİ üzrə qısa idxal bəyannaməsi yazılması təlimatı</h3>
                    <video class="mr-5" width="100%" controls><source src="{{asset('videos/declaration.mp4')}}" type="video/mp4"></video>
                </div>
                <div class="collapse" id="declaration">
                    <h3 class="text-center">MB-TL-B002  BBGİ üzrə sərbəst dövriyyə üçün buraxılış yazılması təlimatı</h3>
                    <video class="mr-5" width="100%" controls><source src="{{asset('videos/idxal.mp4')}}" type="video/mp4"></video>
                </div>
                <div class="collapse" id="declaration">
                    <h3 class="text-center">MB-TL-B003  HNBGİ üzrə sərbəst dövriyyə üçün buraxılış yazılması təlimatı</h3>
                    <video class="mr-5" width="100%" controls><source src="{{asset('videos/MB_TL_B003_HNBGİ_üzrə_sərbəst_dövriyyə_üçün_buraxılış_yazılması.mp4')}}" type="video/mp4"></video>
                </div>
            @endif
        </div>
    </div>


@endsection