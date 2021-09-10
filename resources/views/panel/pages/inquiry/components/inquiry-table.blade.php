@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>
        .custom-dropdown    {
            min-width: max-content !important;
            /*padding: 10px !important;*/
            /*top: 45px !important;*/
            /*left: -10px !important;*/
        }
    </style>
@endsection
<div>
    <form id="inquiryFilterForm" class="row">
        <div wire:ignore class="form-group col-12 col-md-3 mb-3 mb-md-0" >
            <label for="daterange">{{__('translates.filters.date')}}</label>
            <input type="text" placeholder="{{__('translates.placeholders.range')}}" id="daterange" name="daterange" wire:model.defer="daterange" class="form-control">
        </div>

        <div class="form-group col-12 col-md-3 mb-3 mb-md-0">
            <label for="codeFilter">{{__('translates.filters.code')}}</label>
            <input type="search" id="codeFilter" placeholder="{{__('translates.placeholders.code')}}" class="form-control" wire:model.defer="filters.code">
        </div>

        <div class="form-group col-12 col-md-4 mb-3 mb-md-0" wire:ignore>
            <label class="d-block" for="subjectFilter">{{__('translates.filters.subject')}}</label>
            <select id="subjectFilter" multiple class="filterSelector form-control" data-width="fit" wire:model.defer="parameterFilters.subjects" title="{{__('translates.filters.select')}}" >
                @foreach($subjects as $subject)
                    <option value="{{$subject->getAttribute('id')}}">{{ucfirst($subject->getAttribute('text'))}}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group col-12 col-md-1 mb-3 mb-md-0 d-flex flex-column align-items-end pr-0">
            <label for="">{{__('translates.buttons.filter')}}</label>
            <button type="submit" class="btn btn-outline-primary"><i class="fas fa-filter"></i></button>
        </div>
        <div class="form-group col-12 col-md-1 mb-3 mb-md-0 d-flex flex-column align-items-end pl-0">
            <label for="">{{__('translates.filters.clear')}}</label>
            <a href="{{route('inquiry.index')}}">
                <button type="button" class="btn btn-outline-danger text-center"><i class="fal fa-times-circle"></i></button>
            </a>
        </div>

        <div class="col-12 m-2 p-0"></div>

        <div class="form-group col-12 col-md-3 mb-3 mb-md-0"  wire:ignore>
            <label class="d-block" for="companyFilter">{{__('translates.filters.company')}}</label>
            <select id="companyFilter" multiple class="filterSelector" data-width="fit" wire:model.defer="filters.company_id" title="{{__('translates.filters.select')}}" >
                @foreach($companies as $company)
                    <option value="{{$company->getAttribute('id')}}">{{ucfirst($company->getAttribute('name'))}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-12 col-md-3 mb-3 mb-md-0" wire:ignore>
            <label class="d-block" for="statusFilter">Status</label>
            <select id="statusFilter" multiple class="filterSelector form-control" data-width="fit" wire:model.defer="parameterFilters.status" title="{{__('translates.filters.select')}}" >
                @foreach($statuses as $status)
                    <option value="{{$status->getAttribute('id')}}">{{ucfirst($status->getAttribute('text'))}}</option>
                @endforeach
            </select>
        </div>
        @if(\App\Models\Inquiry::userCanViewAll())
            <div class="form-group col-12 col-md-3 mb-3 mb-md-0"  wire:ignore>
                <label class="d-block" for="writtenByFilter">{{__('translates.filters.written_by')}}</label>
                <select id="writtenByFilter" class="filterSelector" data-width="fit" wire:model.defer="filters.user_id" title="{{__('translates.filters.written_by')}}" >
                    @foreach($users as $user)
                        <option value="{{$user->getAttribute('id')}}">{{$user->getAttribute('name')}} {{$user->getAttribute('surname')}}</option>
                    @endforeach
                </select>
            </div>
        @endif
        <div class="col-12"></div>
        <div class="form-group col-12 col-md-3 mt-2 mb-3 mb-md-0">
            <label for="clientNamePhoneFilter">@lang('translates.filters.or', ['first' => __('translates.fields.client'), 'second' => __('translates.fields.phone'), 'third' => __('translates.fields.mail')])</label>
            <input type="search" id="clientNamePhoneFilter" placeholder="@lang('translates.placeholders.or', ['first' => __('translates.fields.client'), 'second' => __('translates.fields.phone'), 'third' =>  __('translates.fields.mail')])" class="form-control" wire:model.defer="parameterFilters.search_client">
        </div>
        <div class="form-group col-12 col-md-3 mt-2 mb-3 mb-md-0">
            <label for="noteFilter">@lang('translates.fields.note')</label>
            <textarea id="noteFilter" cols="5" rows="2" placeholder="@lang('translates.placeholders.note')" class="form-control" wire:model.defer="filters.note"></textarea>
        </div>
    </form>

    <div class="col-12">
        <hr>
        <div class="float-right">
            @can('create', \App\Models\Inquiry::class)
                <a href="{{route('inquiry.create')}}" class="btn btn-outline-success">
                    <i class="fal fa-plus"></i>
                </a>
            @endcan
            <a href="{{ !request()->has('trash-box') ? route('inquiry.index', ['trash-box' => true]) : route('inquiry.index') }}" class="btn btn-outline-secondary">
                <i class="far {{ !request()->has('trash-box') ? 'fa-recycle' : 'fa-phone' }}"></i>
            </a>
        </div>
        <div>
            <p> @lang('translates.total_items', ['count' => $inquiries->count(), 'total' => $inquiries->total()])</p>
        </div>
    </div>
    <div class="col-md-12 overflow-auto">
        <table class="table table-responsive-sm table-hover table-striped" style="min-height: 500px">
            <thead>
                <tr>
                    <th>{{__('translates.fields.mgCode')}}</th>
                    <th>{{__('translates.fields.date')}}</th>
                    <th>{{__('translates.fields.time')}}</th>
                    <th>{{__('translates.fields.company')}}</th>
                    <th>{{__('translates.fields.clientName')}}</th>
                    <th>{{__('translates.fields.writtenBy')}}</th>
                    <th>{{__('translates.fields.subject')}}</th>
                    <th class="text-center">Status</th>
                    <th>{{__('translates.fields.actions')}}</th>
                </tr>
            </thead>
            <tbody>
            @foreach($inquiries as $inquiry)
                <tr>
                    <td>{{$inquiry->getAttribute('code')}}</td>
                    <td>{{$inquiry->getAttribute('datetime')->format('d-m-Y')}}</td>
                    <td>{{$inquiry->getAttribute('datetime')->format('H:i')}}</td>
                    <td>{{$inquiry->getRelationValue('company')->getAttribute('name')}}</td>
                    <td>{{optional($inquiry->getParameter('fullname'))->getAttribute('value')}}</td>
                    <td>{{$inquiry->getRelationValue('user')->getAttribute('fullname')}}</td>
                    <td>{{optional($inquiry->getParameter('subject'))->getAttribute('text')}}</td>
                    <td class="text-center">
                        @if (optional($inquiry->getParameter('status'))->getAttribute('id') == 22 || !auth()->user()->can('view', $inquiry) )
                            <i class="fa fa-check text-success" style="font-size: 18px"></i>
                        @else
                            @if($trashBox)
                                {{optional($inquiry->getParameter('status'))->getAttribute('text') ?? __('translates.filters.select')}}
                            @else
                                <select class="form-control" style="width:auto;" onfocus="this.oldValue = this.value" id="inquiry-{{$inquiry->getAttribute('id')}}" onchange="inquiryStatusHandler(this, {{$inquiry->getAttribute('id')}}, '{{$inquiry->getAttribute('code')}}', this.oldValue, this.value)">
                                    <option value="null" @if (!optional($inquiry->getParameter('status'))->getAttribute('id')) selected @else  @endif>@lang('translates.filters.select')</option>
                                    @foreach ($statuses as $status)
                                        <option
                                                @if ($status->getAttribute('id') == optional($inquiry->getParameter('status'))->getAttribute('id')) selected @endif
                                        value="{{$status->getAttribute('id')}}"
                                        >
                                            {{$status->getAttribute('text')}}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                        @endif
                    </td>
                    <td>
                        <div class="btn-sm-group d-flex align-items-center justify-content-center">
                            @if(!$trashBox)
                                @can('view', $inquiry)
                                    <a href="{{route('inquiry.show', $inquiry)}}" class="btn btn-sm btn-outline-primary mr-2">
                                        <i class="fal fa-eye"></i>
                                    </a>
                                @endcan
                            @endif
                            <div class="dropdown">
                                <button class="btn" type="button" id="inquiry_actions-{{$loop->iteration}}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fal fa-ellipsis-v-alt"></i>
                                </button>
                                <div class="dropdown-menu custom-dropdown">
                                    @if($trashBox)
                                        @can('restore', $inquiry)
                                            <a href="{{route('inquiry.restore', $inquiry)}}" class="dropdown-item-text text-decoration-none">
                                                <i class="fal fa-repeat pr-2 text-info"></i>Restore
                                            </a>
                                        @endcan
                                        @can('forceDelete', $inquiry)
                                            <a href="javascript:void(0)" onclick="deleteAction('{{route('inquiry.forceDelete', $inquiry)}}', '{{$inquiry->code}}')" class="dropdown-item-text text-decoration-none">
                                                <i class="fa fa-times pr-2 text-danger"></i>Permanent delete
                                            </a>
                                        @endcan
                                    @else
                                        @can('update', $inquiry)
                                            <a href="{{route('inquiry.edit', $inquiry)}}" class="dropdown-item-text text-decoration-none">
                                                <i class="fal fa-pen pr-2 text-success"></i>Edit
                                            </a>
                                        @endcan
                                        @can('delete', $inquiry)
                                            <a href="javascript:void(0)" onclick="deleteAction('{{route('inquiry.destroy', $inquiry)}}', '{{$inquiry->code}}')" class="dropdown-item-text text-decoration-none">
                                                <i class="fal fa-trash-alt pr-2 text-danger"></i>Delete
                                            </a>
                                        @endcan
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
            <div wire:loading.delay>
                <div
                        class="d-flex justify-content-center align-items-center"
                        style="position: absolute;
                               top: 0;left: 0;
                               width: 100%;height: 100%;
                               background: rgba(0, 0, 0, 0.3);z-index: 999;"
                >
                    <h3 class="text-white">@lang('translates.loading')...</h3>
                </div>
            </div>
        </table>
    </div>
    <div class="col-12">
        <div class="float-right">
            {{ $inquiries->links() }}
        </div>
    </div>
</div>
@section('scripts')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

    <script>
        function alertHandler(event){
            $.alert({
                type:    event?.detail?.type,
                title:   event?.detail?.title,
                content: event?.detail?.message,
                theme: 'modern',
                typeAnimated: true
            });
        }
        addEventListener('alert', alertHandler);

        function inquiryStatusHandler(element, inquiryId, mgCode, oldVal, val){
            $.confirm({
                title: `${mgCode} update`,
                content: `Are you sure to change status from ${$("#" + $(element).attr('id') + ` option[value=${oldVal}]`).text()} to ${$("#" + $(element).attr('id') + ` option[value=${val}]`).text()}?`,
                autoClose: 'confirm|8000',
                icon: 'fa fa-question',
                type: 'red',
                theme: 'modern',
                typeAnimated: true,
                buttons: {
                    confirm: function () {
                        Livewire.emit('statusChanged', +inquiryId, +oldVal, +val)
                    },
                    cancel: function () {
                        $(element).val(oldVal);
                    },
                }
            });
        }
    </script>

    <script>
        $('.filterSelector').selectpicker()

        $(function() {
            $('input[name="daterange"]').daterangepicker({
                opens: 'left',
                locale: {
                    format: "DD/MM/YYYY",
                },
                maxDate: new Date(),
            }, function(start, end, label) {}
            );
        });

        $('#inquiryFilterForm').submit(function (e){
            e.preventDefault();
            @this.set('daterange', $('#daterange').val())
            Livewire.emit('updateFilter')
        });

        function deleteAction(url, name){
            $.confirm({
                title: 'Confirm delete action',
                content: `Are you sure delete <b>${name}</b> ?`,
                autoClose: 'confirm|8000',
                icon: 'fa fa-question',
                type: 'red',
                theme: 'modern',
                typeAnimated: true,
                buttons: {
                    confirm: function () {
                        $.ajax({
                            url:   url,
                            type: 'DELETE',
                            success: function (responseObject, textStatus, xhr)
                            {
                                $.confirm({
                                    title: 'Delete successful',
                                    icon: 'fa fa-check',
                                    content: '<b>:name</b>'.replace(':name',  name),
                                    type: 'blue',
                                    typeAnimated: true,
                                    autoClose: 'reload|3000',
                                    theme: 'modern',
                                    buttons: {
                                        reload: {
                                            text: 'Ok',
                                            btnClass: 'btn-blue',
                                            keys: ['enter'],
                                            action: function(){
                                                window.location.reload()
                                            }
                                        }
                                    }
                                });
                            },
                            error: function ()
                            {
                                $.confirm({
                                    title: 'Confirm!',
                                    content: 'Ops something went wrong! Please reload page and try again.',
                                    type: 'red',
                                    typeAnimated: true,
                                    buttons: {
                                        cancel: function () {

                                        },
                                        reload: {
                                            text: 'Reload page',
                                            btnClass: 'btn-blue',
                                            keys: ['enter'],
                                            action: function(){
                                                window.location.reload()
                                            }
                                        }
                                    }
                                });
                            }
                        });
                    },
                    cancel: function () {

                    },
                }
            });
        }

    </script>
@endsection

{{--    function edit(dataID){--}}
{{--        window.location.href = "{{route('inquiry.edit', '%id%')}}".replace('%id%', dataID);--}}
{{--    }--}}

{{--    @if($errors->any())--}}
{{--        $('#createModal').modal('show')--}}
{{--    @endif--}}

{{--    window.addEventListener('keypress', function (e) {--}}
{{--        if (e.key === '+') {--}}
{{--            $('#createModal').modal('show')--}}
{{--        }--}}
{{--    }, false);--}}



