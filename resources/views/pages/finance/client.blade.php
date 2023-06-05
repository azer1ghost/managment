@extends('layouts.main')

@section('title', trans('translates.navbar.dashboard'))

@section('style')

@endsection

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('financeClients')">
            @lang('translates.navbar.client')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
                {{optional($data)->getAttribute('name')}}
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{route('updateFinanceClient', $data->id)}}" method="POST" enctype="multipart/form-data">
        @method('PUT') @csrf
        <div class="row mt-4">
            <div class="form-group col-12">
                <div class="row">
                    <x-form-group  class="pr-3 col-12 col-lg-6"  >
                        <x-form-input  name="name" label="Müştəri Adı" placeholder="Müştəri Adı daxil edin" value="{{$data->getAttribute('name')}}"/>
                    </x-form-group>

                    <x-form-group  class="pr-3 col-12 col-lg-6"  >
                        <x-form-input  name="voen" label="Müştəri voen" placeholder="Müştəri voen daxil edin" value="{{$data->getAttribute('voen')}}"/>
                    </x-form-group>

                    <x-form-group  class="pr-3 col-12 col-lg-6"  >
                        <x-form-input  name="hn" label="Müştəri hesablaşma hesabı" placeholder="Müştəri hesablaşma hesabı daxil edin" value="{{$data->getAttribute('hn')}}"/>
                    </x-form-group>

                    <x-form-group  class="pr-3 col-12 col-lg-6"  >
                        <x-form-input  name="mh" label="Müştəri məxvi hesab" placeholder="Müştəri məxvi hesab daxil edin" value="{{$data->getAttribute('mh')}}"/>
                    </x-form-group>

                    <x-form-group  class="pr-3 col-12 col-lg-6"  >
                        <x-form-input  name="code" label="Müştəri Bank Kodu" placeholder="Müştəri bank kodu daxil edin" value="{{$data->getAttribute('code')}}"/>
                    </x-form-group>

                    <x-form-group  class="pr-3 col-12 col-lg-6"  >
                        <x-form-input  name="bank" label="Müştəri Bank Adı" placeholder="Müştəri bank adı daxil edin" value="{{$data->getAttribute('bank')}}"/>
                    </x-form-group>

                    <x-form-group  class="pr-3 col-12 col-lg-6"  >
                        <x-form-input  name="bvoen" label="Müştəri Bank Voen" placeholder="Müştəri bank voeni daxil edin" value="{{$data->getAttribute('bvoen')}}"/>
                    </x-form-group>

                    <x-form-group  class="pr-3 col-12 col-lg-6"  >
                        <x-form-input  name="swift" label="Müştəri Bank S.W.I.F.T" placeholder="Müştəri bank swift kodu daxil edin" value="{{$data->getAttribute('swift')}}"/>
                    </x-form-group>

                    <x-form-group  class="pr-3 col-12 col-lg-6"  >
                        <x-form-input  name="orderer" label="Vəzifə:Ad Soyad" placeholder="Vəzifə:Ad Soyad daxil edin" value="{{$data->getAttribute('orderer')}}"/>
                    </x-form-group>


                </div>
            </div>
        </div>

            <x-input::submit :value="__('translates.buttons.save')"/>

    </form>
@endsection
