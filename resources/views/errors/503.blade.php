@extends('errors.error')
@section('error', '503')
@section('content', 'Hal-hazırda Sistemdə Profilaktik İşlər Gedir. Anlayışınız Üçün Təşəkkürlər.')
@section('downtime')
    @php($down_time = \Carbon\Carbon::parse('31.12.2021 23:59'))
    {{$down_time->locale('az')->diffForHumans(['parts' => 2, 'short' => false])}} yenidən yoxlayın

    <script>
        setTimeout(function (){
            window.location.reload();
        }, 2000);
    </script>
@endsection