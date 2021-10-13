<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{mix('assets/css/app.css')}}">
    <style>
        * {
            margin: 0;
            padding: 0
        }

        html {
            height: 100%
        }

        p {
            color: grey
        }

        #heading {
            text-transform: uppercase;
            color: #673AB7;
            font-weight: normal
        }

        #register {
            text-align: center;
            position: relative;
            margin-top: 20px
        }

        #register fieldset {
            background: white;
            border: 0 none;
            border-radius: 0.5rem;
            box-sizing: border-box;
            width: 100%;
            margin: 0;
            padding-bottom: 20px;
            position: relative
        }

        .form-card {
            text-align: left
        }

        #register fieldset:not(:first-of-type) {
            display: none
        }

        #register input,
        #register textarea {
            padding: 8px 15px 8px 15px;
            border: 1px solid #ccc;
            border-radius: 0px;
            margin-top: 2px;
            width: 100%;
            box-sizing: border-box;
            font-family: montserrat;
            color: #2C3E50;
            background-color: #ECEFF1;
            font-size: 16px;
            letter-spacing: 1px
        }

        #register input:focus,
        #register textarea:focus {
            -moz-box-shadow: none !important;
            -webkit-box-shadow: none !important;
            box-shadow: none !important;
            border: 1px solid #673AB7;
            outline-width: 0
        }

        #register .action-button {
            width: 100px;
            background: #673AB7;
            font-weight: bold;
            color: white;
            border: 0 none;
            border-radius: 0px;
            cursor: pointer;
            padding: 10px 5px;
            margin: 10px 0px 10px 5px;
            float: right
        }

        #register .action-button:hover,
        #register .action-button:focus {
            background-color: #311B92
        }

        #register .action-button-previous {
            width: 100px;
            background: #616161;
            font-weight: bold;
            color: white;
            border: 0 none;
            border-radius: 0px;
            cursor: pointer;
            padding: 10px 5px;
            margin: 10px 5px 10px 0px;
            float: right
        }

        #register .action-button-previous:hover,
        #register .action-button-previous:focus {
            background-color: #000000
        }

        .card {
            z-index: 0;
            border: none;
            position: relative
        }

        .fs-title {
            font-size: 25px;
            color: #673AB7;
            margin-bottom: 15px;
            font-weight: normal;
            text-align: left
        }

        .purple-text {
            color: #673AB7;
            font-weight: normal
        }

        .steps {
            font-size: 25px;
            color: gray;
            margin-bottom: 10px;
            font-weight: normal;
            text-align: right
        }

        .fieldlabels {
            color: gray;
            text-align: left
        }

        #progressbar {
            margin-bottom: 30px;
            overflow: hidden;
            color: lightgrey
        }

        #progressbar .active {
            color: #673AB7
        }

        #progressbar li {
            list-style-type: none;
            font-size: 15px;
            width: 33.33%;
            float: left;
            position: relative;
            font-weight: 400
        }

        #progressbar #account:before {
            font-family: FontAwesome;
            content: "\f13e"
        }

        #progressbar #personal:before {
            font-family: FontAwesome;
            content: "\f007"
        }

        #progressbar #payment:before {
            font-family: FontAwesome;
            content: "\f030"
        }

        #progressbar #confirm:before {
            font-family: FontAwesome;
            content: "\f00c"
        }

        #progressbar li:before {
            width: 50px;
            height: 50px;
            line-height: 45px;
            display: block;
            font-size: 20px;
            color: #ffffff;
            background: lightgray;
            border-radius: 50%;
            margin: 0 auto 10px auto;
            padding: 2px
        }

        #progressbar li:after {
            content: '';
            width: 100%;
            height: 2px;
            background: lightgray;
            position: absolute;
            left: 0;
            top: 25px;
            z-index: -1
        }

        #progressbar li.active:before,
        #progressbar li.active:after {
            background: #673AB7
        }

        .progress {
            height: 20px
        }

        .progress-bar {
            background-color: #673AB7
        }

        .fit-image {
            width: 100%;
            object-fit: cover
        }
    </style>

</head>
<body>
  <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-11 col-sm-9 col-md-7 col-lg-6 col-xl-5 text-center p-0 mt-3 mb-2">
                <div class="card px-0 pt-4 pb-0 mt-3 mb-3">
                    <h2 id="heading">Sign Up Your User Account</h2>
                    <p>Fill all form field to go to next step</p>
                    <form id="register" method="POST" action="{{route('register')}}">
                        @csrf
                        <!-- progressbar -->
                        <ul id="progressbar">
                            <li class="active" id="account"><strong>Account</strong></li>
                            <li id="personal"><strong>Personal</strong></li>
                            <li id="payment"><strong>Image</strong></li>
{{--                            <li id="confirm"><strong>Finish</strong></li>--}}
                        </ul>
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <br> <!-- fieldsets -->
                        <fieldset id="step-1">
                            <div class="form-card">
                                <div class="row">
                                    <div class="col-7">
                                        <h2 class="fs-title">Account Information:</h2>
                                    </div>
                                    <div class="col-5">
                                        <h2 class="steps">Step 1 - 3</h2>
                                    </div>
                                </div>
                                <div class="col-12 mb-3 px-0">
                                    <label class="fieldlabels">Coop email: *</label>
                                    <input type="email" name="email_coop" class="form-control" />
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-12 mb-3 px-0">
                                    <label class="fieldlabels">Personal phone: *</label>
                                    <input type="text" name="phone" class="form-control" value="+994 "/>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-12 mb-3 px-0">
                                    <label class="fieldlabels">Password: *</label>
                                    <input type="password" name="password" class="form-control" />
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-12 mb-3 px-0">
                                    <label class="fieldlabels">Confirm Password: *</label>
                                    <input type="password" name="password_confirmation" class="form-control" />
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <input type="button" name="next" class="next action-button" value="Next" />
                        </fieldset>
                        <fieldset id="step-2">
                            @php
                                $departments = \App\Models\Department::all()->pluck('name', 'id')->toArray();
                                $companies   = \App\Models\Company::all()->pluck('name', 'id')->toArray();
                            @endphp
                            <div class="form-card">
                                <div class="row">
                                    <div class="col-7">
                                        <h2 class="fs-title">Personal Information:</h2>
                                    </div>
                                    <div class="col-5">
                                        <h2 class="steps">Step 2 - 3</h2>
                                    </div>
                                </div>
                                <div class="col-12 mb-3 px-0">
                                    <label class="fieldlabels">First Name: *</label>
                                    <input type="text" name="name" class="form-control"/>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-12 mb-3 px-0">
                                    <label class="fieldlabels">Last Name: *</label>
                                    <input type="text" name="surname" class="form-control"/>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-12 mb-3 px-0">
                                    <label class="fieldlabels">Department: *</label>
                                    <select name="department_id" class="form-control">
                                        <option value="null">Choose department</option>
                                        @foreach($departments as $id => $dep)
                                            <option value="{{$id}}">{{$dep}}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-12 mb-3 px-0">
                                    <label class="fieldlabels">Company: *</label>
                                    <select name="company_id" class="form-control">
                                        <option value="null">Choose company</option>
                                        @foreach($companies as $id => $company)
                                            <option value="{{$id}}">{{$company}}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-12 mb-3 px-0">
                                    <label class="fieldlabels">Default lang: *</label>
                                    <option value="null">Choose language</option>
                                    <select name="default_lang" class="form-control">
                                        @foreach(config('app.locales') as $id => $lang)
                                            <option value="{{$id}}">{{$lang}}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <input type="button" name="next" class="next action-button" value="Next" />
                            <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
                        </fieldset>
                        <fieldset id="step-3">
                            <div class="form-card">
                                <div class="row">
                                    <div class="col-7">
                                        <h2 class="fs-title">Image Upload:</h2>
                                    </div>
                                    <div class="col-5">
                                        <h2 class="steps">Step 3 - 3</h2>
                                    </div>
                                </div>
                                <label class="fieldlabels">Upload Your Photo:</label> <input type="file" name="avatar" accept="image/*">
                            </div>
                            <input type="submit" name="next" class="next action-button" value="Submit" />
                            <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
                        </fieldset>
{{--                        <fieldset>--}}
{{--                            <div class="form-card">--}}
{{--                                <div class="row">--}}
{{--                                    <div class="col-7">--}}
{{--                                        <h2 class="fs-title">Finish:</h2>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-5">--}}
{{--                                        <h2 class="steps">Step 4 - 4</h2>--}}
{{--                                    </div>--}}
{{--                                </div> <br><br>--}}
{{--                                <h2 class="purple-text text-center"><strong>SUCCESS !</strong></h2> <br>--}}
{{--                                <div class="row justify-content-center">--}}
{{--                                    <div class="col-3"> <img src="https://i.imgur.com/GwStPmg.png" class="fit-image"> </div>--}}
{{--                                </div> <br><br>--}}
{{--                                <div class="row justify-content-center">--}}
{{--                                    <div class="col-7 text-center">--}}
{{--                                        <h5 class="purple-text text-center">You Have Successfully Signed Up</h5>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </fieldset>--}}
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="{{mix('assets/js/app.js')}}"></script>
    <script>
        $(document).ready(function(){
            let current_fs, next_fs, previous_fs;
            let opacity;
            let current = 1;
            let steps = $("fieldset").length;

            setProgressBar(current);

            $(".next").click(function(){

                current_fs = $(this).parent();
                next_fs = $(this).parent().next();

                const data = {};
                $('#' + current_fs.attr('id') + ' input').each(function (index, input){
                    data[$(input).attr('name')] = $(input).val();
                    $(input).removeClass('is-invalid').next().text('');
                });
                data['_token'] = '{{ csrf_token() }}';

                $.ajax({
                    url: '{{route('new-form')}}',
                    method: 'POST',
                    data: data,
                    dataType: 'json',
                    success: function (res){
                        if(res.success){
                            $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
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
                        }else{
                            for (const err in res.errors) {
                                $(`#${current_fs.attr('id')} input[name="${err}"]`).addClass('is-invalid').next().text(res.errors[err]);
                            }
                        }
                    },
                    error: function (err){
                        console.log(err)
                    }
                });
            });

            $(".previous").click(function(){

                current_fs = $(this).parent();
                previous_fs = $(this).parent().prev();

                $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

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
</body>
</html>