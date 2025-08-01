<form action="{{$action}}" method="POST" enctype="multipart/form-data" id="work-form">

    @if(!is_null($data) && $method != 'PUT')
        @can('update', $data)
            <div class="col-12 my-4 pl-0">
                <a class="btn btn-outline-success" href="{{route('works.edit', $data)}}">Edit</a>
            </div>
        @endcan
    @endif
    @method($method) @csrf
    <div wire:loading.delay class="col-12 mr-2" style="position: absolute;right:20px">
        <div style="position: absolute;right: 0;top: -25px">
            <div class="spinner-border text-primary" role="status"></div>
        </div>
    </div>
    <div class=" row my-5">
        <div class="form-group col-12">
            <div class="row m-0">
                    @if(auth()->user()->hasPermission('viewPrice-work'))
                        <div class="form-group col-12 col-md-3">
                            <label for="e-invoice">E-qaimə</label>
                            <input value="{{optional($data)->getAttribute('code')}}" type="text" name="code" id="e-invoice" class="form-control" placeholder="E-qaimə nömrəsi daxil edin">
                        </div>
                    @endif
                        @php($service_id = \App\Models\Service::find($selected['service_id'])->id)
                    @if(in_array($service_id, [1,2,14,16,17,18,19,20,21,22,23,26,27,29,30,15,24]))
                        <div class="form-group col-12 col-md-3">
                            <label for="declaration_no">Sorğu nömrəsi</label>
                            <input value="{{optional($data)->getAttribute('declaration_no')}}"
                                   type="text"
                                   name="declaration_no"
                                   id="declaration_no"
                                   class="form-control"
                                   placeholder="Sorğu nömrəsi daxil edin"
                                   @if(!auth()->user()->hasPermission('canRedirect-work') && optional($data)->getAttribute('status') > 3) minlength="14" required @endif
                            >
                        </div>
                    @endif
                        <div class="form-group col-12 col-md-3">
                            <label for="transport_no">Nəqliyyat nömrəsi</label>
                            <input value="{{optional($data)->getAttribute('transport_no')}}"
                                   type="text"
                                   name="transport_no"
                                   id="transport_no"
                                   class="form-control"
                                   placeholder="Nəqliyyat nömrəsi daxil edin">
                        </div>
                        @if(in_array($service_id, [2]))
                            <div class="form-group col-12 col-md-3">
                            <label for="mark">@lang('translates.fields.mark')</label>
                            <input value="{{optional($data)->getAttribute('mark')}}"
                                   type="text"
                                   name="mark"
                                   id="mark"
                                   class="form-control"
                                   placeholder="Qeydiyyat nişanı daxil edin"
                            >
                        </div> @endif

                    <div class="form-group col-12 col-md-6" wire:ignore>
                    <label for="data-client-type">{{trans('translates.fields.clientName')}}</label><br/>
                    <div class="d-flex align-items-center">
                        <select name="client_id" id="data-client-type" data-url="{{route('clients.search')}}" class="custom-select2" style="width: 100% !important;" required>
                            @if(is_numeric(optional($data)->getAttribute('client_id')))
                                <option class="@if(optional($data)->getRelationValue('client')->getAttribute('active') == 1) text-danger @endif"  value="{{optional($data)->getAttribute('client_id')}}">{{optional($data)->getRelationValue('client')->getAttribute('fullname_with_voen')}}</option>
                            @endif
                        </select>
                        @if(is_numeric(optional($data)->getAttribute('client_id')))
                            @can('update', \App\Models\Client::find(optional($data)->getAttribute('client_id')))
                                <a target="_blank" href="{{route('clients.edit', optional($data)->getAttribute('client_id'))}}" class="btn btn-outline-primary ml-3">
                                    <i class="fa fa-pen"></i>
                                </a>
                            @endcan
                        @endif
                        @if(optional($data)->getAttribute('status') != \App\Models\Work::DONE)
                            <a target="_blank" href="{{route('clients.create', ['type' => \App\Models\Client::PHYSICAL])}}" class="btn btn-outline-success ml-1">
                                <i class="fa fa-plus"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <div class="form-group col-12 col-md-3 custom_client">
                    <label for="custom_client">Digər Müştəri</label>
                    <input value="{{optional($data)->getAttribute('custom_client')}}" type="text" name="custom_client" id="custom_client" class="form-control" placeholder="Digər Müştəri qeydiyatını daxil edin">
                </div>

                <div class="form-group col-12 col-md-6" wire:ignore>
                    <label for="data-department_id">@lang('translates.fields.department')</label>
                    <input wire:ignore readonly type="text" class="form-control" id="data-department_id" value="{{\App\Models\Department::find($selected['department_id'])->name}}">
                    <input wire:ignore type="hidden" wire:model="selected.department_id" name="department_id">
                </div>

                <div class="form-group col-12 col-md-6" wire:ignore>
                    <label for="data-service_id">@lang('translates.general.work_service')</label>
                    <select @if($this->subServices->isEmpty()) name="service_id" @endif id="data-service_id" class="form-control">
                        @foreach($this->services as $service)
                            <option @if(\App\Models\Service::find($selected['service_id'])->id === $service->id ) selected @endif
                            value="{{ $service->getAttribute('id') }}">
                                {{ $service->getAttribute('name') }}
                            </option>
                        @endforeach
                    </select>
{{--                    <input wire:ignore disabled type="text" class="form-control" id="data-service_id" value="{{\App\Models\Service::find($selected['service_id'])->name}}">--}}
{{--                    <input type="hidden" @if($this->subServices->isEmpty()) name="service_id"  @endif wire:model="selected.service_id">--}}
                </div>

                @if(!$this->subServices->isEmpty())
                    <div class="form-group col-12 col-md-6" wire:ignore>
                        <label for="data-service_id">@lang('translates.general.work_service_type')</label>
                        <select name="service_id" id="data-service_id" class="form-control">
                            @foreach($this->subServices as $service)
                                <option @if(optional($data)->getAttribute('service_id') === $service->id ) selected @endif
                                value="{{ $service->getAttribute('id') }}">
                                    {{ $service->getAttribute('name') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                @if($selected['department_id'])
                    <div class="form-group col-12 col-md-6" wire:key="department-user" wire:ignore>
                        <label for="data-user_id">@lang('translates.general.user_select')</label>
                        <select name="user_id" id="data-user_id" class="form-control"
                                @if(!is_null(optional($data)->getAttribute('user_id'))) wire:model="selected.user_id" @endif
                                @if(!auth()->user()->hasPermission('canRedirect-work')) disabled @endif
                        >
                            <option value="" selected>@lang('translates.general.user_select')</option>
                            <option value="{{ auth()->id() }}">{{  auth()->user()->name . ' ' . auth()->user()->surname }}</option>
                            @foreach($users ?? [] as $user)
                                @php($position = $user['position']['name'][app()->getLocale()] ?? ($user['position']['name']['en'] ?? ''))
                                <option value="{{ $user['id'] }}">{{ $user['name'] . ' ' . $user['surname'] . "($position)"}}</option>
                            @endforeach
                        </select>
                        @if(!auth()->user()->hasPermission('canRedirect-work'))
                            <input type="hidden" wire:model="selected.user_id" name="user_id">
                        @endif
                    </div>
                @endif
                        {{-- Sorter --}}
                        <div class="form-group">
                            <label for="sorter_id">Sorter</label>
                            <select name="sorter_id" class="form-control">
                                <option value="">-- Seçin --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user['id'] }}" {{ ($data->sorter_id ?? '') == $user['id'] ? 'selected' : '' }}>
                                        {{ $user['name'] }} {{ $user['surname'] }}
                                    </option>

                                @endforeach
                            </select>
                        </div>

                        {{-- Operator --}}
{{--                        <div class="form-group">--}}
{{--                            <label for="operator_id">Operator</label>--}}
{{--                            <select name="operator_id" class="form-control">--}}
{{--                                <option value="">-- Seçin --</option>--}}
{{--                                @foreach($users as $user)--}}
{{--                                    <option value="{{ $user['id'] }}" {{ ($data->operator_id ?? '') == $user['id'] ? 'selected' : '' }}>--}}
{{--                                        {{ $user['name'] }} {{ $user['surname'] }}--}}
{{--                                    </option>--}}

{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                        </div>--}}

                        {{-- Analyst --}}
                        <div class="form-group">
                            <label for="analyst_id">Analyst</label>
                            <select name="analyst_id" class="form-control">
                                <option value="">-- Seçin --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user['id'] }}" {{ ($data->analyst_id ?? '') == $user['id'] ? 'selected' : '' }}>
                                        {{ $user['name'] }} {{ $user['surname'] }}
                                    </option>
                                @endforeach>
                            </select>
                        </div>



                    @if($this->service->getAttribute('has_asan_imza') && $method != 'POST')
                    <div class="form-group col-12 col-md-6" wire:key="asan-imza" wire:ignore>
                        <label for="data-asan_imza_id">Asan imza və Təmsilçilik şirkət</label>
                        <select name="asan_imza_id" id="data-asan_imza_id" data-url="{{route('asanImza.user.search')}}" class="custom-select2 form-control">
                            <option value="" selected>Asan imza select</option>
                            @foreach(\App\Models\AsanImza::get() as $asanUser)
                                <option value="{{ $asanUser->getAttribute('id') }}"
                                        @if(optional($data)->getAttribute('asan_imza_id') == $asanUser->getAttribute('id')) selected @endif>
                                    {{ $asanUser->getRelationValue('user')->getAttribute('fullname') }}
                                    ({{ $asanUser->getRelationValue('company')->getAttribute('name') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-12 col-md-3 custom_asan">
                        <label for="custom_asan">Digər Şirkət Asan İmza</label>
                        <input value="{{optional($data)->getAttribute('custom_asan')}}" type="text" name="custom_asan" id="custom_asan" class="form-control" placeholder="Digər şirkətin Asan imzasını daxil edin">
                    </div>
                @endif

                @if($method != 'POST')
                    <div class="form-group col-12 col-md-3" wire:ignore>
                        <label for="data-status">@lang('translates.general.status_choose')</label>
                        <select name="status" id="data-status" class="form-control">
                            <option disabled >@lang('translates.general.status_choose')</option>
                            @foreach($statuses as $key => $status)
                                <option
                                        @if(optional($data)->getAttribute('status') === $status ) selected
                                        @endif value="{{$status}}"
                                        @if($status == \App\Models\Work::REJECTED || (optional($data)->hasAsanImza() && $status == \App\Models\Work::DONE && is_null(optional($data)->getAttribute('asan_imza_id'))))
                                        @endif
                                >
                                    @lang('translates.work_status.' . $key)
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-12 col-md-3" wire:ignore>
                        <label for="data-destination">@lang('translates.general.destination_choose')</label>
                        <select name="destination" id="data-status" class="form-control">
                            <option disabled>@lang('translates.placeholders.choose')</option>
                            @foreach($destinations as $key => $destination)
                                <option
                                        @if(optional($data)->getAttribute('destination') == $destination) selected @endif
                                value="{{$destination}}"
                                >
                                    @lang('translates.work_destination.' . $destination)
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                @if(!is_null($data) && !is_null(optional($data)->getAttribute('datetime')))
                    <x-input::text wire:ignore name="datetime"  :label="__('translates.fields.date')" value="{{$data->getAttribute('datetime')->format('Y-m-d H:i')}}" width="3" class="pr-3" />
                @endif

                    @foreach($parameters as $parameter)
                        @if(in_array('hideOnPost', explode(' ', $parameter->attributes)) && $method == 'POST')
                            @continue
                        @endif

                        @switch($parameter->type)
                            @case('text')
                                <div class="form-group col-12 col-md-3" @if(!auth()->user()->hasPermission('editPrice-work') && in_array('finance', explode(' ', $parameter->attributes))) style="display: none" @endif   wire:ignore>
                                    <label for="data-parameter-{{$parameter->id}}">{{$parameter->label}}</label>
                                    <input type="text" data-label="{{$parameter->getTranslation('label', 'az')}}" name="parameters[{{$parameter->id}}]" {{$parameter->attributes}} id="data-parameter-{{$parameter->id}}" class="form-control parameters parameters[{{$parameter->id}}]" placeholder="{{$parameter->placeholder}}" wire:model="workParameters.{{$parameter->name}}">
                                </div>
                                @break
                            @case('number')
                                <div class="form-group col-12 col-md-3" @if(!auth()->user()->hasPermission('editPrice-work') && in_array('finance', explode(' ', $parameter->attributes))) style="display: none" @endif wire:ignore>
                                    <label for="data-parameter-{{$parameter->id}}">{{$parameter->label}}</label>
                                    <input type="number" data-label="{{$parameter->getTranslation('label', 'az')}}" name="parameters[{{$parameter->id}}]" {{$parameter->attributes}} id="data-parameter-{{$parameter->id}}" class="form-control parameters" placeholder="{{$parameter->placeholder}}" wire:model="workParameters.{{$parameter->name}}">
                                </div>
                            @break
                            @case('select')
                                <div class="form-group col-12 col-md-3" @if(!auth()->user()->hasPermission('editPrice-work') && in_array('finance', explode(' ', $parameter->attributes))) style="display: none" @endif wire:ignore>
                                    <label for="data-parameter-{{$parameter->id}}">{{$parameter->label}}</label>
                                    <select data-label="{{$parameter->getTranslation('label', 'az')}}" name="parameters[{{$parameter->id}}]" {{$parameter->attributes}} id="data-parameter-{{$parameter->id}}" class="form-control parameters" wire:model="workParameters.{{$parameter->name}}">
                                        <option value="" selected>{{$parameter->placeholder}}</option>
                                        @foreach($parameter->getRelationValue('options') as $option)
                                            <option value="{{$option->id}}" data-value="{{$option->getTranslation('text', 'az')}}">{{$option->text}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @break
                        @endswitch
                    @endforeach
                        @if($method != 'POST')
                <div class="form-group col-12 col-md-3" wire:ignore>
                    <label for="data-payment_method">@lang('translates.general.payment_method')</label>
                    <select name="payment_method" id="data-payment_method" class="form-control">
                        <option disabled>@lang('translates.general.payment_method')</option>
                        @php($clientPaymentMethod = optional($data)->getRelationValue('client')->getAttribute('payment_method'))
                        @foreach($payment_methods as $key => $payment_method)
                            <option
                                @if(!is_null($clientPaymentMethod) && $clientPaymentMethod == $key)
                                    selected
                                @elseif(optional($data)->getAttribute('payment_method') === $payment_method || $data === null && $payment_method == 2)
                                    selected
                                @endif
                                value="{{$payment_method}}">
                                @lang('translates.payment_methods.' . $key)
                            </option>
                        @endforeach
                    </select>
                </div>
                        @endif

                <div class="form-group col-12 col-md-3 bank_charge" wire:ignore>
                    <label for="bank_charge">Bank Xərci</label>
                    <input class="form-control" type="text" name="bank_charge" value="{{optional($data)->getAttribute('bank_charge')}}">
                </div>

                @if(!is_null($data))
                    <x-input::text wire:ignore name="paid_at" readonly :label="__('translates.fields.paid_at')" value="{{optional($data->getAttribute('paid_at'))->format('Y-m-d H:i')}}" width="3" class="pr-3" />
                @endif
                @if(!is_null($data) )
                    <x-input::text wire:ignore name="vat_date" readonly :label="__('translates.fields.vat_paid_at')" value="{{optional($data->getAttribute('vat_date'))->format('Y-m-d H:i')}}" width="3" class="pr-3" />
                @endif
                @if(!is_null($data))
                    <x-input::text wire:ignore name="invoiced_date" readonly :label="__('translates.fields.invoiced_date')" value="{{optional($data->getAttribute('invoiced_date'))->format('Y-m-d H:i')}}" width="3" class="pr-3" />
                @endif
                @if(auth()->user()->hasPermission('canVerify-work') && $method != 'POST' && optional($data)->getAttribute('status') == \App\Models\Work::DONE)
                    <div class="col-12" wire:ignore>
                        <input type="checkbox" id="data-verified" name="verified" @if(!is_null(optional($data)->getAttribute('verified_at'))) checked @endif>
                        <label class="form-check-label" for="data-verified">@lang('translates.columns.verified')</label>
                    </div>
                    <div class="col-12" wire:ignore>
                        <input type="checkbox" id="data-rejected" name="rejected" @if(optional($data)->getAttribute('status') == \App\Models\Work::REJECTED) checked @endif>
                        <label class="form-check-label" for="data-rejected">@lang('translates.columns.rejected')</label>
                    </div>
                    <div class="col-12" wire:ignore>
                        <input type="checkbox" id="data-paid-check" name="paid_check" @if(!is_null(optional($data)->getAttribute('paid_at'))) checked @endif>
                        <label class="form-check-label" for="data-paid-check">@lang('translates.columns.paid')</label>
                    </div>
                    <div class=" form-group col-12" wire:ignore>
                        <input type="checkbox" id="data-vat-paid-check" name="vat_paid_check" @if(!is_null(optional($data)->getAttribute('vat_date'))) checked @endif>
                        <label class="form-check-label" for="data-vat-paid-check">@lang('translates.columns.vat_paid')</label>
                    </div>
                @endif


                <div class="form-group col-12" wire:ignore>
                    <label for="data-detail">@lang('translates.general.work_detail')</label>
                    <textarea wire:ignore name="detail" id="data-detail" class="form-control" style="height: 300px"
                    >{{optional($data)->getAttribute('detail')}}</textarea>
                </div>
            </div>

            @if(optional(optional($data)->user())->exists() && \App\Models\Work::DONE == optional($data)->getAttribute('status'))
                <div class="col-12">
                    <button type="button" class="btn btn-outline-success copy">@lang('translates.buttons.copy')</button>
                </div>
            @endif

        </div>
    </div>
            <!-- Modal -->

    @if($action)
        <x-input::submit :value="__('translates.buttons.save')"/>
    @endif
</form>
{{--<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">--}}
{{--    <div class="modal-dialog">--}}
{{--        <div class="modal-content">--}}
{{--            <div class="modal-header">--}}
{{--                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>--}}
{{--                <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                    <span aria-hidden="true">&times;</span>--}}
{{--                </button>--}}
{{--            </div>--}}
{{--            <form action="" method="POST">--}}
{{--                @csrf--}}
{{--                <div class="modal-body">--}}
{{--                    <div class="form-group">--}}
{{--                        <input type="hidden" class="form-control" id="work_id" name="work_id" value="{{$data->getAttribute('id')}}">--}}
{{--                    </div>--}}

{{--                    <div class="form-group">--}}
{{--                        <label for="return_reason">Qayıtma Səbəbi</label>--}}
{{--                        <input type="text" class="form-control" id="return_reason" name="return_reason" placeholder="Qayıtma Səbəbini daxil edin">--}}
{{--                    </div>--}}

{{--                    <div class="form-group">--}}
{{--                        <label for="main_reason">Əsas Səbəb</label>--}}
{{--                        <input type="text" class="form-control" id="main_reason" name="main_reason" placeholder="Əsas Səbəbi daxil edin">--}}
{{--                    </div>--}}

{{--                    <div class="form-group">--}}
{{--                        <label for="name">İnspektorun Adı</label>--}}
{{--                        <input type="text" class="form-control" id="name" name="name" placeholder="İnspektorun Adını daxil edin">--}}
{{--                    </div>--}}

{{--                    <div class="form-group">--}}
{{--                        <label for="phone">Əlaqə nömrəsi</label>--}}
{{--                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Əlaqə Nömrəsini daxil edin">--}}
{{--                    </div>--}}

{{--                    <div class="form-group">--}}
{{--                        <label for="note">Not</label>--}}
{{--                        <textarea class="form-control" id="note" name="note" placeholder="Əlavə notunuz varsa daxil edin"></textarea>--}}
{{--                    </div>--}}

{{--            </div>--}}
{{--            <div class="modal-footer">--}}
{{--                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('translates.buttons.close')</button>--}}
{{--                <button class="btn btn-primary" id="submit-form">Yadda Saxla</button>--}}
{{--            </div>--}}
{{--            </form>--}}

{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

@if(!is_null($data))
    <div class="col-12">
        <x-documents :documents="$data->documents"/>
        <x-document-upload :id="$data->id" model="Work"/>
    </div>
@endif

@push('scripts')
{{--    <script>--}}
{{--            $('#submit-form').click(function() {--}}
{{--                // Form verilerini AJAX ile gönderme--}}
{{--                e.preventDefault(); // Sayfanın yeniden yüklenmesini engeller--}}

{{--                $.ajax({--}}
{{--                    url: {{ route('AjaxStore') }},--}}
{{--                    method: 'POST',--}}
{{--                    data: $('#return-work-form').serialize(),--}}
{{--                    success: function(response) {--}}
{{--                        // İşleminiz başarılı olduğunda burada bir şeyler yapabilirsiniz.--}}
{{--                        // Örneğin, bir mesaj gösterebilir veya sayfayı yenileyebilirsiniz.--}}
{{--                        alert(response.message);--}}

{{--                    },--}}
{{--                    error: function(error) {--}}
{{--                        // Hata durumunda burada işlemler yapabilirsiniz.--}}
{{--                    }--}}
{{--                });--}}
{{--            });--}}
{{--    </script>--}}
    <script>
            $('#data-status').on('change', function() {
                let selectedValue = $(this).val();
                let noteValue =  $('#data-detail').val()
                if (selectedValue == 5) {
                    $('#data-detail').val(noteValue + '\n \n' + 'Əsas Səbəb:\n \nGeri Qayıtma Səbəbi:\n \nİnspektorun adı:\n  \nƏlaqə nömrəsi:\n \nSəbəbkar:\n \nÖdənişi ödəyən və ödəniş məbləği: ');
                    // $('#exampleModal').modal('show');

                }
            });
    </script>
    @php($isShow = is_null($action))
    @php($hasNotPermission = !auth()->user()->can('update', $data))
    @php($isDone = optional($data)->getAttribute('status') == \App\Models\Work::DONE)
    @php($isVerified = !is_null(optional($data)->getAttribute('verified_at')))

    @if(($isShow || ($hasNotPermission && $method != 'POST')) || ($hasNotPermission && $isVerified) )
        <script>
            $('#work-form :input').attr('disabled', true)
        </script>
    @endif

    @if(optional($data)->getAttribute('status') == \App\Models\Work::DONE && auth()->user()->hasPermission('canVerify-work') && $method == 'PUT')
        <script>
            $('input[name="verified"]').attr('disabled', false);
            $('input[name="rejected"]').attr('disabled', false);
            $('input[name="_method"]').attr('disabled', false);
            $('input[name="_token"]').attr('disabled', false);
            $('button[type="submit"]').attr('disabled', false);
        </script>
    @endif

    <script>
        @if($method !== 'POST')
            $('#work-form .copy').attr('disabled', false)
        @endif

        @if(optional(optional($data)->user())->exists() && optional($data)->getAttribute('status') == \App\Models\Work::DONE)
            function html(html, type = 'string')
            {
                let tmp = document.createElement("div");
                tmp.innerHTML = html;

                if(type === 'string'){
                    return tmp.innerText || tmp.textContent || "";
                }

                return tmp;
            }

            function copyToClipboard(element) {
                const $temp = $("<textarea>");
                const brRegex = /<br\s*[\/]?>/gi;
                $("body").append($temp);
                $temp.val($(element).html().replace(brRegex, "\r\n")).select();
                document.execCommand("copy");
                $temp.remove();
            }

            $('.copy').click(function (){
                const service = '{{$data->getRelationValue('service')->getTranslation('name', 'az')}}';
                const user = '{{$data->getRelationValue('user')->getAttribute('fullname')}}';
                const client = '{{$data->getRelationValue('client')->getAttribute('fullname_with_voen')}}';
                const asanImza = '{{$data->getRelationValue('asanImza')->getAttribute('user_with_company')}}';
                const date = '{{$data->getAttribute('datetime')}}';
                const detail = $(html('{{$data->getAttribute('detail')}}')).text();
                const gb = '{{$data->getParameter($data::GB)}}';
                const code = '{{$data->getParameter($data::CODE)}}';
                let data = `Xidmət: ${service}<br/>Müştəri: ${client}<br/>İcra edən: ${user}<br/>Asan imza: ${asanImza}<br/>Tarix: ${date}<br/>GB Sayı: ${gb}<br/>Kod Sayı: ${code}<br/>`;
                data += `${detail ? `Detal: ${detail}` : ''}`;

                copyToClipboard(html(data, 'html'));
            });
        @endif

        const asanImzaSelect2 = $('select[name="asan_imza_id"]');

        asanImzaSelect2.change(function (){
            const DONE = {{\App\Models\Work::DONE}};
            if($(this).val()){
                $(`#data-status option:eq(${DONE})`).prop('disabled', false);
            }else{
                $(`#data-status option:eq(${DONE})`).prop('disabled', true);
            }
        });
    </script>
    <script>
        const AsanId = $('#data-asan_imza_id');

        if (AsanId.val() === '22') $('.custom_asan').show();
        else $('.custom_asan').hide();

        AsanId.change(function () {
            if ($(this).val() === '22') $('.custom_asan').show();
            else $('.custom_asan').hide();
        });

        const PaymentMethod = $('#data-payment_method');

        if (PaymentMethod.val() === '3') $('.bank_charge').show();
        else $('.bank_charge').hide();

        PaymentMethod.change(function () {
            if ($(this).val() === '3') $('.bank_charge').show();
            else $('.bank_charge').hide();
        });

        const clientId = $('#data-client-type');

        if (clientId.val() === '1865') $('.custom_client').show();
        else $('.custom_client').hide();

        clientId.change(function () {
            if ($(this).val() === '1865') $('.custom_client').show();
            else $('.custom_client').hide();
        });
    </script>
@endpush