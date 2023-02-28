@extends('pages.transit.layout')

@section('title', 'Online Transit | Log In')

@section('content')

    <div class="col-12 p-lg-5 py-4">
        <div class="card position-sticky top-0">
        <ul class="nav nav-pills nav-justified m-3" id="ex1" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link show active text-black" id="tab-login" data-toggle="tab" href="#pills-login" role="tab"
                   aria-controls="pills-login" aria-selected="true">Login</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link text-black" id="tab-register" data-toggle="tab" href="#pills-register" role="tab"
                   aria-controls="pills-register" aria-selected="false">Register</a>
            </li>
        </ul>
        <!-- Pills navs -->

        <!-- Pills content -->
        <div class="tab-content">
            <div class="tab-pane fade show active" id="pills-login" role="tabpanel" aria-labelledby="tab-login">
                @if($errors->any())
                    {!! implode('', $errors->all('<div class="text-danger">:message</div>')) !!}
                @endif
                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    <!-- Email input -->
                    <div class="form-outline mb-4">
                        <label class="form-label" for="loginName">Email or username</label>
                        <input type="email" name="login" id="loginName" class="form-control"/>
                    </div>

                    <div class="form-outline">
                        <label class="form-label" for="loginPassword">Password</label>
                        <input type="password" name="password" id="loginPassword" class="form-control"/>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-check ml-4">
                                <input class="form-check-input" type="checkbox" name="remember" id="loginCheck" checked/>
                                <label class="form-check-label" for="loginCheck"> Remember me </label>
                            </div>
                        </div>

                        <div class="col-md-6 d-flex justify-content-center">
                            <a href="#">Forgot password?</a>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block mb-4"><a href="{{route('service')}}">Sign in</a></button>

                    <div class="text-center">
                        <p>Not a member? <a class="text-black" id="tab-register" data-toggle="tab" href="#pills-register" role="tab"
                                             aria-controls="pills-register" aria-selected="false">Register</a></p>
                    </div>
                </form>
            </div>

            <div class="tab-pane fade show" id="pills-register" role="tabpanel" aria-labelledby="tab-register">

                <form action="{{ route('transitRegister') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="role_id" value="4">
                    <div class="form-group ">
                        <select class="form-control" id="type">
                            <option selected>Müştəri növünü seçin</option>
                            <option value="legal">Hüquqi</option>
                            <option value="people">Fiziki</option>
                        </select>
                    </div>

                    <div class="form-group mb-4" id="default">
                        <select class="form-control  mb-4" name="country" id="country">
                            <option selected>Ölkə seçin</option>
                            <option value="Azerbaijan">Azerbaijan</option>
                            <option value="Turkey">Turkey</option>
                            <option value="Georgia">Georgia</option>
                            <option value="Russia">Russia</option>
                            <option value="Iran">Iran</option>
                        </select>

                        <div class="input-group mb-4" id="rekvizit">
                            <label class="custom-file-label" for="registerName">Rekvizit</label>
                            <input type="file" name="rekvisit" id="registerName" class="custom-file-input"/>
                        </div>

                        <div class="form-outline mb-4" >
                            <label class="form-label" for="registerName">VOEN</label>
                            <input type="text" name="voen" id="registerName" class="form-control"/>
                        </div>

                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="registerName">Name</label>
                        <input type="text" name="name" id="registerName" class="form-control"/>
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="registerEmail">Email</label>
                        <input type="email" name="email" id="registerEmail" class="form-control"/>
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="registerPhone">Phone</label>
                        <input type="text" name="phone" id="registerPhone" class="form-control"/>
                    </div>

                    <!-- Password input -->
                    <div class="form-outline mb-4">
                        <label class="form-label" for="registerPassword">Password</label>
                        <input type="password" name="password" id="registerPassword" class="form-control"/>
                    </div>

                    <!-- Repeat Password input -->
                    <div class="form-outline mb-4">
                        <label class="form-label" for="registerRepeatPassword">Repeat password</label>
                        <input type="password" name="password_confirmation" id="registerRepeatPassword" class="form-control"/>
                    </div>

                    <!-- Checkbox -->
                    <div class="form-check d-flex justify-content-center mb-4">
                        <input class="form-check mr-2" type="checkbox" value="" id="registerCheck" checked
                               aria-describedby="registerCheckHelpText"/>
                        <label class="form-check" for="registerCheck">
                            I have read and agree to the <a href="#" class="text-black"> terms</a>
                        </label>
                    </div>

                    <!-- Submit button -->
                    <button type="submit" class="btn btn-primary btn-block mb-3">Register</button>
                </form>

            </div>
        </div>
    </div>
</div>


@endsection
@section('scripts')
    <script>
        $(function () {
            $('#default').hide();
            $('#type').change(function () {
                if ($('#type').val() == 'legal') {
                    $('#default').show();
                } else {
                    $('#default').hide();
                }
            });
        });
        $(function () {
            $('#rekvizit').hide();
            $('#country').change(function () {
                if ($('#country').val() == 'Azerbaijan') {
                    $('#rekvizit').show();
                } else {
                    $('#rekvizit').hide();
                }
            });
        });
    </script>
@endsection