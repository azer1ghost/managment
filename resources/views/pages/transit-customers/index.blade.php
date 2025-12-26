@extends('layouts.main')

@section('title', 'Transit Müştəriləri')

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            Transit Müştəriləri
        </x-bread-crumb-link>
    </x-bread-crumb>
    
    <form action="{{route('transit-customers.index')}}">
        <div class="row d-flex justify-content-between mb-2">
            <div class="col-8 col-md-6 mb-3">
                <div class="input-group mb-3">
                    <input type="search" name="search" value="{{request()->get('search')}}" 
                           class="form-control" placeholder="Axtarış..." 
                           aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit">
                            <i class="fal fa-search"></i>
                        </button>
                        <a class="btn btn-outline-danger d-flex align-items-center" href="{{route('transit-customers.index')}}">
                            <i class="fal fa-times"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-4 col-md-2 mb-3">
                <select class="form-control" name="type">
                    <option value="">Hamısı</option>
                    <option value="legal" @if(request()->get('type') == 'legal') selected @endif>Hüquqi</option>
                    <option value="people" @if(request()->get('type') == 'people') selected @endif>Fiziki</option>
                </select>
            </div>
            <div class="col-4 col-md-2 mb-3">
                <a class="btn btn-outline-success float-right" href="{{route('transit-customers.create')}}">
                    <i class="fal fa-plus"></i> Yeni Müştəri
                </a>
            </div>
            <div class="col-8 pt-2 d-flex align-items-center">
                <p class="mb-0">Cəmi: {{$customers->total()}} müştəri</p>
            </div>
        </div>
        
        <div class="table-responsive" style="overflow-x: auto;">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Ad</th>
                        <th scope="col">Email</th>
                        <th scope="col">Telefon</th>
                        <th scope="col">Tip</th>
                        <th scope="col">VOEN</th>
                        <th scope="col">Ölkə</th>
                        <th scope="col">Balans</th>
                        <th scope="col">Qeydiyyat Tarixi</th>
                        <th scope="col">Əməliyyatlar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                        <tr>
                            <th>{{$customer->id}}</th>
                            <td>
                                <strong>{{$customer->name}}</strong>
                            </td>
                            <td>{{$customer->email}}</td>
                            <td>{{$customer->phone}}</td>
                            <td>
                                <span class="badge badge-{{$customer->type == 'legal' ? 'info' : 'secondary'}}">
                                    {{$customer->type == 'legal' ? 'Hüquqi' : 'Fiziki'}}
                                </span>
                            </td>
                            <td>{{$customer->voen ?: '-'}}</td>
                            <td>{{$customer->country ?: '-'}}</td>
                            <td>
                                <strong class="text-success">{{number_format($customer->balance, 2)}} AZN</strong>
                            </td>
                            <td>
                                <small>{{$customer->created_at->format('d.m.Y H:i')}}</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{route('transit-customers.show', $customer)}}" 
                                       class="btn btn-sm btn-info" 
                                       title="Bax">
                                        <i class="fal fa-eye"></i>
                                    </a>
                                    <a href="{{route('transit-customers.edit', $customer)}}" 
                                       class="btn btn-sm btn-primary" 
                                       title="Redaktə et">
                                        <i class="fal fa-edit"></i>
                                    </a>
                                    <a href="{{route('transit-customers.destroy', $customer)}}" 
                                       class="btn btn-sm btn-danger" 
                                       delete
                                       data-name="{{$customer->name}}"
                                       data-status="Bu müştərini silmək istədiyinizə əminsiniz?"
                                       title="Sil">
                                        <i class="fal fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">Müştəri tapılmadı</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($customers->hasPages())
            <div class="mt-4">
                {{$customers->appends(request()->input())->links()}}
            </div>
        @endif
    </form>
@endsection

