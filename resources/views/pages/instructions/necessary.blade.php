@extends('layouts.main')

@section('title', __('translates.navbar.necessary'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.necessary')
        </x-bread-crumb-link>
    </x-bread-crumb>

    <style>
        .necessary li{
            font-size: 20px;
        }
    </style>
    <div class="row mb-2">
        <div class="col-12 justify-content-center">
            <div class="col-12">
                <table class="table table-responsive-sm table-hover">

                    <h1>Gömrük rəsmiləşdirilməsi üçün lazım olan sənədlər</h1>
                    <ol class="necessary">
                        <li>İdxalçının anbar (obyekt) kodu</li>
                        <li>İdxalçının VÖEN-ə bağlı hesabı</li>
                        <li>Alqı - satqı müqaviləsi</li>
                        <ul>
                            <li>Malın dəyəri</li>
                            <li>Çatdırılma şərti</li>
                            <li>Ödəniş şərti</li>
                            <li>Və digər məlumatlar</li>
                        </ul>
                        <li>Müqavilənin pin kodu</li>
                        <li>Müqaviləyə əlavə</li>
                        <li>Müqaviləyə əlavənin pin kodu </li>
                        <li>İnvoys</li>
                        <li>Mənşə sertifikatı (CT-1 sertifikatı)</li>
                        <li>Packing list</li>
                        <li>CMR / Aviaqaimə / Poçt qaiməsi</li>
                        <li>Bank rekvizitləri</li>
                        <li>Reestrdən çıxarış</li>
                        <li>VÖEN</li>
                        <li>Broker xidmət müqaviləsi </li>
                        <li>Fiziki şəxs etibarnamə - HNBGİ</li>
                        <li>Fiziki şəxs etibarnamə - AMBGİ</li>
                        <li>Fiziki şəxs etibarnamə - BBGİ</li>
                        <li>Hüquqi şəxs Gömrük etibarnamə - HNBGİ</li>
                        <li>Hüquqi şəxs ASG etibarnamə - HNBGİ</li>
                        <li>Hüquqi şəxs Gömrük etibarnamə - BBGİ</li>
                        <li>Hüquqi şəxs Gömrük etibarnamə - AMBGİ</li>
                    </ol>

                    <h2>Xüsusi icazə sənədləri lazımdırsa</h2>
                    <ol class="necessary">
                        <li>Gigiyenik sertifikat</li>
                        <li>Baytarlıq sertifikatı</li>
                        <li>AQTA icazə</li>
                        <li>Səhiyyə Analitik Ekspertiza İdarəsi</li>
                    </ol>
                </table>
            </div>
            </div>
    </div>

@endsection