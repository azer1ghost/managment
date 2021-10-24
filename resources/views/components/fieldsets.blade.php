<ul id="progressbar">
    <li class="active" id="language"><strong>@lang('translates.register.progress.language')</strong></li>
    <li id="account"><strong>@lang('translates.register.progress.account')</strong></li>
    <li id="personal"><strong>@lang('translates.register.progress.personal')</strong></li>
    <li id="image"><strong>@lang('translates.register.progress.avatar')</strong></li>
</ul>
<div class="progress">
    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
</div>
<br>
<fieldset id="step-1">
    <div class="form-card">
        <div class="row align-items-center w-100 m-0">
            <div class="col-6">
                <h2 class="fs-title">@lang('translates.register.progress.language'):</h2>
            </div>
            <div class="col-6 pr-0">
                <h4 class="steps">@lang('translates.register.steps', ['step' => 1])</h4>
            </div>
        </div>
        <div class="row py-5">
            @foreach(config('app.locales') as $lang => $language)
                <div class="col-12 col-md-6 text-center lang">
                    <a href="@if (app()->getLocale() != $lang) {{route('locale', $lang)}} @else # @endif" onclick="loadLanguage()">
                        <div class="@if (app()->getLocale() == $lang) active @endif p-3" style="cursor: pointer" onclick="">
                            <span class="flag-icon flag-icon-{{$lang == 'en' ? 'gb' : $lang}}" style="font-size: 100px"></span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
    <div class="row justify-content-end align-items-center w-100 m-0">
        <div class="spinner-border text-primary mr-3 d-none" id="load-language" role="status"></div>
        <input type="button" name="next" class="next action-button" value="@lang('translates.buttons.next')" id="default_lang_next"/>
    </div>
    <input type="hidden" name="default_lang" value="{{app()->getLocale()}}">
    <script>
        function loadLanguage(){
            document.getElementById('load-language').classList.remove('d-none');
            document.getElementById('default_lang_next').disabled = true;
        }
    </script>
</fieldset>
<x-fieldset header="account" step="2">
    @if (!$isOutsource)
        <div class="col-12 mb-3 px-0">
            <label class="fieldlabels">@lang('translates.register.mail_coop'): *</label>
            <input type="email" name="email_coop" class="form-control" />
            <div class="invalid-feedback"></div>
        </div>
    @else
        <div class="col-12 mb-3 px-0">
            <label class="fieldlabels">@lang('translates.register.mail'): *</label>
            <input type="email" name="email" class="form-control" />
            <div class="invalid-feedback"></div>
        </div>
    @endif
    <div class="col-12 mb-3 px-0">
        <label class="fieldlabels">@lang('translates.register.phone'): *</label>
        <input type="text" name="phone" class="form-control" value="+994"/>
        <div class="invalid-feedback"></div>
    </div>
    <div class="col-12 mb-3 px-0">
        <label class="fieldlabels">@lang('translates.register.password'): *</label>
        <input type="password" name="password" class="form-control" />
        <div class="invalid-feedback"></div>
    </div>
    <div class="col-12 mb-3 px-0">
        <label class="fieldlabels">@lang('translates.register.password_confirm'): *</label>
        <input type="password" name="password_confirmation" class="form-control" />
        <div class="invalid-feedback"></div>
    </div>
</x-fieldset>
<x-fieldset header="personal" step="3">
    @php
        $departments = \App\Models\Department::all()->pluck('name', 'id')->toArray();
        $companies   = \App\Models\Company::all()->pluck('name', 'id')->toArray();
    @endphp
    <div class="row m-0">
        <div class="col-12 col-md-6 mb-3 px-0 pr-3">
            <label class="fieldlabels">@lang('translates.register.name'): *</label>
            <input type="text" name="name" class="form-control"/>
            <div class="invalid-feedback"></div>
        </div>
        <div class="col-12 col-md-6 mb-3 px-0">
            <label class="fieldlabels">@lang('translates.register.surname'): *</label>
            <input type="text" name="surname" class="form-control"/>
            <div class="invalid-feedback"></div>
        </div>
    </div>
    @if (!$isOutsource)
        <div class="row m-0">
            <div class="col-12 col-md-6 mb-3 px-0 pr-3">
                <label class="fieldlabels">@lang('translates.fields.phone_coop'): *</label>
                <input type="text" name="phone_coop" class="form-control"/>
                <div class="invalid-feedback"></div>
            </div>
            <div class="col-12 col-md-6 mb-3 px-0">
                <label class="fieldlabels">@lang('translates.fields.email_private'): *</label>
                <input type="text" name="email" class="form-control"/>
                <div class="invalid-feedback"></div>
            </div>
        </div>
        <div class="col-12 mb-3 px-0">
            <label class="fieldlabels">@lang('translates.register.department'): *</label>
            <select name="department_id" class="form-control">
                <option value="">@lang('translates.fields.department') @lang('translates.placeholders.choose')</option>
                @foreach($departments as $id => $dep)
                    <option value="{{$id}}">{{$dep}}</option>
                @endforeach
            </select>
            <div class="invalid-feedback"></div>
        </div>
        <div class="col-12 mb-3 px-0">
            <label class="fieldlabels">@lang('translates.register.company'): *</label>
            <select name="company_id" class="form-control">
                <option value="">@lang('translates.fields.company') @lang('translates.placeholders.choose')</option>
                @foreach($companies as $id => $company)
                    <option value="{{$id}}">{{$company}}</option>
                @endforeach
            </select>
            <div class="invalid-feedback"></div>
        </div>
    @endif
    <div class="col-12 mb-3 px-0">
        <div class="row m-0">
            <div class="col-12 col-md-8 p-0 pr-md-3">
                <div class="row m-0 mb-3">
                    <div class="col-4 p-0">
                        <label for="" class="fieldlabels">@lang('translates.fields.serial'): *</label>
                        <select name="serial_pattern" class="form-control p-2">
                            @foreach(['AA', 'AZE'] as $serial)
                                <option value="{{$serial}}">{{$serial}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-8 p-0">
                        <label for=""> </label>
                        <input type="text" name="serial" class="form-control" placeholder="@lang('translates.placeholders.serial_pattern')"/>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 p-0">
                <label for="" class="fieldlabels">@lang('translates.placeholders.fin'): *</label>
                <input type="text" name="fin" class="form-control"/>
                <div class="invalid-feedback"></div>
            </div>
        </div>
    </div>
    @if($isOutsource)
        <input type="hidden" name="department_id" value="15">
        <input type="hidden" name="company_id" value="1">
        <input type="hidden" name="is_partner" value="1">
    @endif
</x-fieldset>
<x-fieldset header="avatar" step="4">
    <div class ='col-4 px-0'>
        <label for="data-avatar">
            <style>
                .card-hover{
                    position: relative;
                }
                .card-hover::after{
                    transition: all 0.4s ease !important;
                    font-family: 'Font Awesome 5 Pro', sans-serif;
                    content: '\f030';
                    display: grid;
                    place-items: center;
                    font-size: 60px;
                    color: white;
                    position:absolute;
                    top: 0;
                    right: 0;
                    background-color: black;
                    opacity: 0.7;
                    visibility: hidden;
                    width: 100%;
                    height: 100%;
                    z-index: 10;
                    border-radius: 50%;
                    cursor: pointer;

                }
                .card-hover:hover::after{
                    transition: all 4s ease !important;
                    visibility: visible;
                }
            </style>
            <div class="card-hover" style="max-width: 100%">
                <img class="img-fluid rounded-circle" style="height: 213px !important;" id="input-avatar" src="{{image('no_image')}}" alt="avatar">
            </div>
        </label>
    </div>
    <div class="col-12">
        <input
                type="file"
                accept="image/*"
                class="form-control d-none"
                name="avatar"
                id="data-avatar"
                onchange="previewFile()"
        >
        <div class="invalid-feedback"></div>
    </div>
    <script>
        function previewFile() {
            const preview = document.querySelector('#input-avatar');
            const file = document.querySelector('input[type=file]').files[0];
            if (hasExtension('data-avatar', ['.jpg', '.jpeg', '.gif', '.png'])) {
                preview.src = URL.createObjectURL(file);
                preview.onload = function() {
                    URL.revokeObjectURL(preview.src) // free memory
                }
            }
        }
        function hasExtension(inputID, exts) {
            const fileName = document.getElementById(inputID).value;
            return (new RegExp('(' + exts.join('|').replace(/\./g, '\\.') + ')$')).test(fileName);
        }
    </script>
</x-fieldset>

@push('scripts')
    <script>
        $(document).ready(function(){
            let current_fs, next_fs, previous_fs;
            let opacity;
            let current = 1;
            let fieldsets = $("fieldset");
            let steps = fieldsets.length;

            setProgressBar(current);

            function nextFieldSet(){
                $("#progressbar li").eq(fieldsets.index(next_fs)).addClass("active");
                next_fs.show();
                current_fs.animate({opacity: 0}, {
                    step: function(now) {
                        opacity = 1 - now;

                        current_fs.css({
                            'display': 'none',
                            'position': 'relative'
                        });
                        next_fs.css({'opacity': opacity});
                    },
                    duration: 500
                });
                setProgressBar(++current);
            }

            $(".next").click(function(){

                current_fs = $(this).parent().parent();
                next_fs = $(this).parent().parent().next();

                let btn = $(this);

                if(current_fs.attr('id') === 'step-1'){
                    nextFieldSet();
                    return;
                }

                const data = new FormData();
                $('#' + current_fs.attr('id') + ' :input').each(function (index, input){
                    if($(input).attr('name') === 'avatar'){
                        if($(input).prop('files').length !== 0){
                            data.append($(input).attr('name'), $(input).prop('files')[0]);
                        }
                    }else{
                        data.append($(input).attr('name'), $(input).val());
                    }
                    $(input).removeClass('is-invalid').next().text('');
                });
                data.append('_token', '{{ csrf_token() }}');
                $.ajax({
                    url: '{{route('validate-register')}}',
                    method: 'POST',
                    data: data,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    beforeSend: function(){
                        btn.attr('disabled', true);
                        btn.parent().find('div').removeClass('d-none');
                    },
                    success: function (res){
                        if(res.success){
                            if(current_fs.attr('id') === 'step-4'){
                                $('#register-form').submit();
                                return;
                            }
                            $("#progressbar li").eq(fieldsets.index(next_fs)).addClass("active");
                            nextFieldSet();
                        }else{
                            for (const err in res.errors) {
                                res.errors[err].forEach(function (error){
                                    $(`#${current_fs.attr('id')} :input[name="${err}"]`).addClass('is-invalid').next().append(`${error}</br>`);
                                });
                            }
                        }
                        btn.parent().find('div').addClass('d-none');
                        btn.attr('disabled', false);
                    },
                    error: function (err){
                        btn.parent().find('div').addClass('d-none');
                        console.log(err)
                    }
                });
            });

            $(".previous").click(function(){

                current_fs = $(this).parent().parent();
                previous_fs = $(this).parent().parent().prev();

                $("#progressbar li").eq(fieldsets.index(current_fs)).removeClass("active");

                previous_fs.show();

                current_fs.animate({opacity: 0}, {
                    step: function(now) {
                        opacity = 1 - now;

                        current_fs.css({
                            'display': 'none',
                            'position': 'relative'
                        });
                        previous_fs.css({'opacity': opacity});
                    },
                    duration: 500
                });
                setProgressBar(--current);
            });

            function setProgressBar(curStep){
                let percent = parseFloat(100 / steps) * curStep;
                percent = percent.toFixed();
                $(".progress-bar")
                    .css("width",percent+"%")
            }
        });
    </script>
@endpush