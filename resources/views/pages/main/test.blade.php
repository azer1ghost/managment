@extends('layouts.main')

@section('title', trans('translates.navbar.dashboard'))

@section('style')
{{--    <style>--}}
{{--        .popup {--}}
{{--            position: fixed;--}}
{{--            top: 50%;--}}
{{--            left: 50%;--}}
{{--            transform: translate(-50%, -50%);--}}
{{--            background-color: #fff;--}}
{{--            padding: 20px;--}}
{{--            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);--}}
{{--        }--}}

{{--    </style>--}}
@endsection

@section('content')
{{--    <button onclick="showPopup()">Pop-up'ı Göster</button>--}}

@endsection

@section('scripts')
{{--    <script>--}}
{{--        function showPopup() {--}}
{{--            // Pop-up içeriğini oluşturun--}}
{{--            var popupContent = "<div>Bu bir pop-up içeriğidir!</div>";--}}

{{--            // Pop-up'ı sayfaya ekleyin--}}
{{--            var popupContainer = document.createElement('div');--}}
{{--            popupContainer.className = 'popup';--}}
{{--            popupContainer.innerHTML = popupContent;--}}
{{--            document.body.appendChild(popupContainer);--}}
{{--        }--}}
{{--    </script>--}}
@endsection