@extends('pages.transit.layout')

@section('title', __('translates.navbar.transit'))

@section('content')


    <div class="col-12 p-5">
        <ul class="nav nav-pills nav-justified mb-3" id="ex1" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link show active" id="tab-login" data-toggle="tab" href="#pills-login" role="tab"
                   aria-controls="pills-login" aria-selected="true">Login</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="tab-register" data-toggle="tab" href="#pills-register" role="tab"
                   aria-controls="pills-register" aria-selected="false">Register</a>
            </li>
        </ul>
        <!-- Pills navs -->

        <!-- Pills content -->
        <div class="tab-content">
            <div class="tab-pane fade show active" id="pills-login" role="tabpanel" aria-labelledby="tab-login">

                <form>

                    <!-- Email input -->
                    <div class="form-outline mb-4">
                        <label class="form-label" for="loginName">Email or username</label> 
                        <input type="email" id="loginName" class="form-control"/>
                    </div>

                    <!-- Password input -->
                    <div class="form-outline mb-4">
                        <label class="form-label" for="loginPassword">Password</label>
                        <input type="password" id="loginPassword" class="form-control"/>
                    </div>

                    <!-- 2 column grid layout -->
                    <div class="row mb-4">
                        <div class="col-md-6 d-flex justify-content-center">
                            <!-- Checkbox -->
                            <div class="form-check mb-3 mb-md-0">
                                <input class="form-check-input" type="checkbox" value="" id="loginCheck"
                                       checked/>
                                <label class="form-check-label" for="loginCheck"> Remember me </label>
                            </div>
                        </div>

                        <div class="col-md-6 d-flex justify-content-center">
                            <!-- Simple link -->
                            <a href="#!">Forgot password?</a>
                        </div>
                    </div>

                    <!-- Submit button -->
                    <button type="submit" class="btn btn-primary btn-block mb-4" ><a href="{{route('service')}}">Sign in</a></button>

                    <!-- Register buttons -->
                    <div class="text-center">
                        <p>Not a member? <a href="#!">Register</a></p>
                    </div>
                </form>
            </div>

            <div class="tab-pane fade show " id="pills-register" role="tabpanel" aria-labelledby="tab-register">

                <form>
                    <div class="form-group col-12">
                        <select class="form-control" id="type">
                            <option selected>Müştəri növünü seçin</option>
                            <option name="legal" value="legal">Hüquqi</option>
                            <option name="people" value="people">Fiziki</option>
                        </select>
                    </div>

                    <div class="form-group" id="default">
                        <select class="form-control" id="country">
                            <option selected>Ölkə seçin</option>
                            <option value="AZ">AZ</option>
                            <option value="">EN</option>
                            <option value="">TR</option>
                            <option value="">RU</option>
                        </select>


                        <div class="form-outline mb-4" >
                            <label class="form-label" for="registerName">VOEN</label>
                            <input type="text" id="registerName" class="form-control"/>
                        </div>
                        <div class="form-outline mb-4" id="rekvizit">
                            <label class="form-label" for="registerName">REKVİZİT</label>
                            <input type="text" id="registerName" class="form-control"/>
                        </div>
                    </div>

                    <div class="form-outline mb-4">
                        <label class="form-label" for="registerName">Name</label>
                        <input type="text" id="registerName" class="form-control"/>
                    </div>


                    <!-- Email input -->
                    <div class="form-outline mb-4">
                        <label class="form-label" for="registerEmail">Email</label>
                        <input type="email" id="registerEmail" class="form-control"/>
                    </div>

                    <!-- Password input -->
                    <div class="form-outline mb-4">
                        <label class="form-label" for="registerPassword">Password</label>
                        <input type="password" id="registerPassword" class="form-control"/>
                    </div>

                    <!-- Repeat Password input -->
                    <div class="form-outline mb-4">
                        <label class="form-label" for="registerRepeatPassword">Repeat password</label>
                        <input type="password" id="registerRepeatPassword" class="form-control"/>
                    </div>

                    <!-- Checkbox -->
                    <div class="form-check d-flex justify-content-center mb-4">
                        <input class="form-check-input me-2" type="checkbox" value="" id="registerCheck" checked
                               aria-describedby="registerCheckHelpText"/>
                        <label class="form-check-label mr-5" for="registerCheck">
                            I have read and agree to the terms
                        </label>
                    </div>

                    <!-- Submit button -->
                    <button type="submit" class="btn btn-primary btn-block mb-3">Sign in</button>
                </form>

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
                if ($('#country').val() == 'AZ') {
                    $('#rekvizit').show();
                } else {
                    $('#rekvizit').hide();
                }
            });
        });
    </script>
@endsection