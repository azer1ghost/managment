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

    <div class="row col-12">
        <div class="card text-center col-md-3 mr-3">
            <div class="card-header">
                40. SƏRBƏST DÖVRİYYƏ ÜÇÜN LAZIM OLAN SƏNƏDLƏR
            </div>
            <div class="card-body">
                <h5 class="card-title"></h5>
                <ol>
                    <li class="card-text">CMR
                    </li>
                    <li class="card-text">İNVOİCE
                    </li>
                    <li class="card-text">PACKİNG LİST
                    </li>
                    <li class="card-text">MƏNSƏ SERTİFİKATİ
                    </li>
                    <li class="card-text">EXPORT
                    </li>
                    <li class="card-text">PROFORMA İNVOİCE
                    </li>
                    <li class="card-text">YOL PULU (ÇATDIRILMA ŞƏRTİNI UYĞUN OLARAQ)
                    </li>
                    <li class="card-text">XARİCİ MUQAVİLƏ VƏ PİN KOD
                    </li>
                    <li class="card-text">MÜQAVİLƏYƏ ƏLAVƏ VARSA ONU VƏ PİN KODU
                    </li>
                    <li class="card-text">TƏMSİLÇİLİK MÜQAVİLƏSİ
                    </li>
                    <li class="card-text">ÖDƏNİŞ OLUNUBSA SWİFT
                    </li>
                    <li class="card-text">YÜKLƏMƏ BOŞALTMA YERİ
                    </li>
                    <li class="card-text">ƏGƏR YENİ MÜŞTƏRİDİRSƏ BANK REKVİZİTLƏRİ, ÇİXARIŞ, ANBAR ÜNVANİNİN
                        DƏQİQLEŞDİRİLMƏSİ İLƏ BAĞLİ ANBAR KODU SƏNƏDİ
                    </li>
                    <li class="card-text">YÜKÜ ÇİXARAN ŞƏXS ÜÇÜN ETİBARNAMƏ
                    </li>
                    <li class="card-text">YÜKDEN ASLI OLARAQ DİGƏR SƏNƏDLƏR (AQTA SƏNƏDİ, GİGİYENİK SERTİFİKAT)
                    </li>
                </ol>

            </div>
        </div>
        <div class="card text-center col-md-3 mr-3">
            <div class="card-header">
                31. MÜVƏQQƏTİ İDXAL ÜÇÜN LAZIM OLAN SƏNƏDLƏR
            </div>
            <div class="card-body">
                <h5 class="card-title"></h5>
                <ol>
                    <li class="card-text">CMR
                    </li>
                    <li class="card-text">İNVOİCE
                    </li>
                    <li class="card-text">PROFORMA İNVOİCE
                    </li>
                    <li class="card-text">XARİCİ MUQAVİLƏ VƏ PİN KOD
                    </li>
                    <li class="card-text">YOL PULU (ÇATDIRILMA ŞƏRTİNI UYĞUN OLARAQ)
                    </li>
                    <li class="card-text">MÜQAVİLƏYƏ ƏLAVƏ VARSA ONU VƏ PİN KODU
                    </li>
                    <li class="card-text">TƏMSİLÇİLİK MÜQAVİLƏSİ
                    </li>
                    <li class="card-text">YÜKLƏMƏ BOŞALTMA YERİ
                    </li>
                    <li class="card-text">ƏGƏR YENİ MÜŞTƏRİDİRSƏ BANK REKVİZİTLƏRİ, REYSTERDƏN ÇİXARIŞ, ANBAR
                        ÜNVANİNİN DƏQİQLEŞDİRİLMƏSİ İLƏ BAĞLİ ANBAR KODU SƏNƏDİ
                    </li>
                    <li class="card-text">YÜKÜ ÇİXARAN ŞƏXS ÜÇÜN ETİBARNAMƏ
                    </li>
                    <li class="card-text">MALLARIN MÜVƏQQƏTİ İDXAL GÖMRÜK REJİMİ ALTINDA YERLƏŞDİRİLMƏSİNƏ DAİR ƏRİZƏ
                    </li>
                </ol>
            </div>
        </div>
        <div class="card text-center col-md-3 mr-3">
            <div class="card-header">
                Qısa idxal üçün lazım olan sənədlər
            </div>
            <div class="card-body">
                <h5 class="card-title"></h5>
                <ol>
                    <li class="card-text">CMR
                    </li>
                    <li class="card-text">İNVOİCE
                    </li>
                    <li class="card-text">PACKİNG LİST
                    </li>
                    <li class="card-text">MƏNŞƏ SERTİFİKATİ
                    </li>
                    <li class="card-text">EXPORT
                    </li>
                    <li class="card-text">SƏRHƏD KEÇİD MƏNTƏQƏSİ
                    </li>
                    <li class="card-text">TƏYİNAT YERİ
                    </li>
                    <li class="card-text">MAŞININ TEXPASPORTU
                    </li>
                    <li class="card-text">SÜRÜCÜNÜN XARİCİ PASPORTU
                    </li>
                </ol>
            </div>
        </div>
    </div>
@endsection