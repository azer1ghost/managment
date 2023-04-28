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
        <div class="card text-center col-3 mr-3">
            <div class="card-header">
                40. SƏRBƏST DÖVRİYYƏ ÜÇÜN LAZIM OLAN SƏNƏDLƏR
            </div>
            <div class="card-body">
                <h5 class="card-title"></h5>
                <ol>
                    <li class="card-text">1.CMR
                    </li>
                    <li class="card-text">2.İNVOİCE
                    </li>
                    <li class="card-text">3.PACKİNG LİST
                    </li>
                    <li class="card-text">4.MƏNSƏ SERTİFİKATİ
                    </li>
                    <li class="card-text">5.EXPORT
                    </li>
                    <li class="card-text">6.PROFORMA İNVOİCE
                    </li>
                    <li class="card-text">7.YOL PULU (ÇATDIRILMA ŞƏRTİNI UYĞUN OLARAQ)
                    </li>
                    <li class="card-text">8.XARİCİ MUQAVİLƏ VƏ PİN KOD
                    </li>
                    <li class="card-text">9.MÜQAVİLƏYƏ ƏLAVƏ VARSA ONU VƏ PİN KODU
                    </li>
                    <li class="card-text">10.TƏMSİLÇİLİK MÜQAVİLƏSİ
                    </li>
                    <li class="card-text">11.ÖDƏNİŞ OLUNUBSA SWİFT
                    </li>
                    <li class="card-text">12.YÜKLƏMƏ BOŞALTMA YERİ
                    </li>
                    <li class="card-text">13.ƏGƏR YENİ MÜŞTƏRİDİRSƏ BANK REKVİZİTLƏRİ, ÇİXARIŞ, ANBAR ÜNVANİNİN DƏQİQLEŞDİRİLMƏSİ İLƏ BAĞLİ ANBAR KODU SƏNƏDİ
                    </li>
                    <li class="card-text">14.YÜKÜ ÇİXARAN ŞƏXS ÜÇÜN ETİBARNAMƏ
                    </li>
                    <li class="card-text">15.YÜKDEN ASLI OLARAQ DİGƏR SƏNƏDLƏR (AQTA SƏNƏDİ, GİGİYENİK SERTİFİKAT)
                    </li>
                </ol>

            </div>
        </div>
        <div class="card text-center col-3 mr-3">
            <div class="card-header">
                Featured
            </div>
            <div class="card-body">
                <h5 class="card-title">31. MÜVƏQQƏTİ İDXAL ÜÇÜN LAZIM OLAN SƏNƏDLƏR</h5>
                <ol>
                    <li class="card-text">1.CMR
                    </li>
                    <li class="card-text">2.İNVOİCE
                    </li>
                    <li class="card-text">3.PROFORMA İNVOİCE
                    </li>
                    <li class="card-text">4.XARİCİ MUQAVİLƏ VƏ PİN KOD
                    </li>
                    <li class="card-text">5. YOL PULU (ÇATDIRILMA ŞƏRTİNI UYĞUN OLARAQ)
                    </li>
                    <li class="card-text">6.MÜQAVİLƏYƏ ƏLAVƏ VARSA ONU VƏ PİN KODU
                    </li>
                    <li class="card-text">7.TƏMSİLÇİLİK MÜQAVİLƏSİ
                    </li>
                    <li class="card-text">8.YÜKLƏMƏ BOŞALTMA YERİ
                    </li>
                    <li class="card-text">9.ƏGƏR YENİ MÜŞTƏRİDİRSƏ BANK REKVİZİTLƏRİ, REYSTERDƏN ÇİXARIŞ, ANBAR ÜNVANİNİN DƏQİQLEŞDİRİLMƏSİ İLƏ BAĞLİ ANBAR KODU SƏNƏDİ
                    </li>
                    <li class="card-text">10.YÜKÜ ÇİXARAN ŞƏXS ÜÇÜN ETİBARNAMƏ
                    </li>
                    <li class="card-text">11.MALLARIN MÜVƏQQƏTİ İDXAL GÖMRÜK REJİMİ ALTINDA YERLƏŞDİRİLMƏSİNƏ DAİR ƏRİZƏ
                    </li>
                </ol>

            </div>
        </div>
        <div class="card text-center col-3 mr-3">
            <div class="card-header">
                Featured
            </div>
            <div class="card-body">
                <h5 class="card-title">Qısa İdxal Bəyannaməsi</h5>
                <ol>
                    <li class="card-text">With supporting text below as a natural lead-in to additional
                        content.
                    </li>
                    <li class="card-text">With supporting text below as a natural lead-in to additional
                        content.
                    </li>
                    <li class="card-text">With supporting text below as a natural lead-in to additional
                        content.
                    </li>
                    <li class="card-text">With supporting text below as a natural lead-in to additional
                        content.
                    </li>
                    <li class="card-text">With supporting text below as a natural lead-in to additional
                        content.
                    </li>
                    <li class="card-text">With supporting text below as a natural lead-in to additional
                        content.
                    </li>
                    <li class="card-text">With supporting text below as a natural lead-in to additional
                        content.
                    </li>
                    <li class="card-text">With supporting text below as a natural lead-in to additional
                        content.
                    </li>
                    <li class="card-text">With supporting text below as a natural lead-in to additional
                        content.
                    </li>
                    <li class="card-text">With supporting text below as a natural lead-in to additional
                        content.
                    </li>
                    <li class="card-text">With supporting text below as a natural lead-in to additional
                        content.
                    </li>
                    <li class="card-text">With supporting text below as a natural lead-in to additional
                        content.
                    </li>
                    <li class="card-text">With supporting text below as a natural lead-in to additional
                        content.
                    </li>
                </ol>

            </div>
        </div>
        <div class="card text-center col-3 mr-3">
            <div class="card-header">
                Featured
            </div>
            <div class="card-body">
                <h5 class="card-title">Qısa İdxal Bəyannaməsi</h5>
                <ol>
                    <li class="card-text">With supporting text below as a natural lead-in to additional
                        content.
                    </li>
                    <li class="card-text">With supporting text below as a natural lead-in to additional
                        content.
                    </li>
                    <li class="card-text">With supporting text below as a natural lead-in to additional
                        content.
                    </li>
                    <li class="card-text">With supporting text below as a natural lead-in to additional
                        content.
                    </li>
                    <li class="card-text">With supporting text below as a natural lead-in to additional
                        content.
                    </li>
                    <li class="card-text">With supporting text below as a natural lead-in to additional
                        content.
                    </li>
                    <li class="card-text">With supporting text below as a natural lead-in to additional
                        content.
                    </li>
                    <li class="card-text">With supporting text below as a natural lead-in to additional
                        content.
                    </li>
                    <li class="card-text">With supporting text below as a natural lead-in to additional
                        content.
                    </li>
                    <li class="card-text">With supporting text below as a natural lead-in to additional
                        content.
                    </li>
                    <li class="card-text">With supporting text below as a natural lead-in to additional
                        content.
                    </li>
                    <li class="card-text">With supporting text below as a natural lead-in to additional
                        content.
                    </li>
                    <li class="card-text">With supporting text below as a natural lead-in to additional
                        content.
                    </li>
                </ol>
            </div>
        </div>
    </div>
@endsection