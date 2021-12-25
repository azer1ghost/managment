@extends('errors.error')
@section('error', '503')
@section('content', 'Hal-hazırda Sistemdə Profilaktik İşlər Gedir.Anlayışınız Üçün Təşəkkürlər')
@section('downtime')
    {{\Carbon\Carbon::parse(config('app.downtime'))->locale('az')->diffForHumans()}}
@endsection
