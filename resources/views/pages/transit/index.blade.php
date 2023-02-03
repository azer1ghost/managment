@extends('pages.transit.layout')

@section('title', __('translates.navbar.transit'))

<nav class="navbar bg-gradient-dark">
    <div class="container-fluid">
        <ul class="nav navbar-nav navbar-right">
            <li><a href="{{route('profile.index')}}"><span class="user"></span>Account</a></li>
            <li><a href="#"><span class="glyphicon glyphicon-log-in"></span>Hesabdan çıx</a></li>
        </ul>
    </div>
</nav>
@section('content')
        <ul class="nav nav-pills nav-justified mb-3" id="ex1" role="tablist">
            <li class="nav-item col-sm-6" role="presentation">
                <a class="nav-link btn btn-primary m-3" id="tab-transit" data-toggle="tab" href="#pills-transit"
                   role="tab"
                   aria-controls="pills-transit" aria-selected="true">Online Tranzit</a>
            </li>
            <li class="nav-item col-sm-6" role="presentation">
                <a class="nav-link btn btn-primary m-3" id="tab-declaration" data-toggle="tab"
                   href="#pills-declaration" role="tab"
                   aria-controls="pills-declaration" aria-selected="false">Qısa İdxal Bəyannaməsi</a>
            </li>
        </ul>

        <!-- Pills navs -->

        <!-- Pills content -->
        <div class="tab-content col-12">
            <div class="tab-pane fade show active" id="pills-transit" role="tabpanel"
                 aria-labelledby="tab-transit">
                <form>
                    <div class="text-center mb-3">
                        <p>Web Transit</p>

                    </div>

                    <div class="transit">
                        <div id="row">
                            <div class="form-outline mb-4">
                                <label class="form-label" for="transitCmr">CMR</label>
                                <div class="input-group mb-4">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="cmr">Yüklə</span>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id=cmr"
                                               aria-describedby="inputGroupFileAddon01">
                                        <label class="custom-file-label" for="cmr">Sənədləri yüklə</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Password input -->
                            <div class="form-outline mb-4">
                                <label class="form-label" for="transitInv">İNVOYS</label>
                                <div class="input-group mb-4">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="invoys">Yüklə</span>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="invoys"
                                               aria-describedby="inputGroupFileAddon01">
                                        <label class="custom-file-label" for="invoys">Sənədləri yüklə</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="div"></div>
                    <a class="btn btn-group btn-primary addButton">Add</a>

                    <!-- 2 column grid layout -->
                    <div class="row mb-4">
                        <div class="col-md-6 d-flex">
                            <!-- Checkbox -->
                            <div class="form-check mb-3 mb-md-0">
                                <input class="form-check-input" type="checkbox" value="" id="transitCheck"
                                       checked/>
                                <label class="form-check-label" for="transitCheck">Şərtlərlə tanış oldum</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-outline mb-4">
                        <button type="button" class="btn btn-warning col-12">Kart ilə ödə</button>
                    </div>

                    <div class="form-outline mb-4">
                        <button type="button" class="btn btn-warning col-12">Avans</button>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block mb-3"><a href="{{route('service')}}">Ana
                            Səhifəyə qayıt</a>
                    </button>

                    <!-- Submit button -->

                <!-- declaration buttons -->
                    <div class="text-center">
                        <p>Hər hansısa sualınız var? <a href="#!">Əlaqə saxlayın</a></p>
                    </div>
                </form>
                <button type="button" class="btn btn-link btn-floating mx-1">
                    <i class="fab fa-facebook-f"></i>
                </button>
                <button type="button" class="btn btn-link btn-floating mx-1">
                    <i class="fab fa-google"></i>
                </button>

                <button type="button" class="btn btn-link btn-floating mx-1">
                    <i class="fab fa-twitter"></i>
                </button>

                <button type="button" class="btn btn-link btn-floating mx-1">
                    <i class="fab fa-github"></i>
                </button>
            </div>


            <div class="tab-pane fade show " id="pills-declaration" role="tabpanel"
                 aria-labelledby="tab-declaration">
                <form>
                    <div class="text-center mb-3">
                        <p>Qısa İdxal Bəyannaməsi</p>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 d-flex ">
                            <!-- Checkbox -->
                            <div class="form-check mb-3 mb-md-0">
                                <input class="form-check-input" type="checkbox" value="" id="declarationCheck"
                                       checked/>
                                <label class="form-check-label" for="declarationCheck">Şərtlərlə tanış
                                    oldum</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-outline mb-4">
                        <button type="button" class="btn btn-warning col-12">Kart ilə ödə</button>
                    </div>

                    <div class="form-outline mb-4">
                        <button type="button" class="btn btn-warning col-12">Avans</button>
                    </div>


                    <!-- Submit button -->
                    <button type="submit" class="btn btn-primary btn-block mb-4"><a href="{{route('payment')}}">Ödəniş
                            edin!</a></button>

                    <!-- declaration buttons -->
                    <div class="text-center">
                        <p>Hər hansısa sualınız var? <a href="#!">Əlaqə saxlayın</a></p>
                    </div>
                </form>
            </div>
        </div>

{{--</div>--}}
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
        $(document).ready(function () {

            $(".addButton").click(function () {
                $(".transit").append("<div> <div id='row''> <div class='form-outline mb-4'> <label class='form-label' for='transitCmr'>CMR</label> <div class='input-group mb-4'> <div class='input-group-prepend'> <span class='input-group-text' id='cmr'>Yüklə</span> </div> <div class='custom-file'> <input type='file' class='custom-file-input' id=cmr'aria-describedby='inputGroupFileAddon01'><label class='custom-file-label' for='cmr'>Sənədləri yüklə</label> </div> </div> </div> <div class='form-outline mb-4'> <label class='form-label' for='transitInv'>İNVOYS</label> <div class='input-group mb-4'> <div class='input-group-prepend'> <span class='input-group-text' id='invoys'>Yüklə</span> </div> <div class='custom-file'> <input type='file' class='custom-file-input' id='invoys'aria-describedby='inputGroupFileAddon01'> <label class='custom-file-label' for='invoys'>Sənədləri yüklə</label> </div> </div> <button class='btn btn-danger'id='DeleteRow' type='button'> <i class='bi bi-trash'></i>Delete </button></div> </div>");
            });
        });
        $("body").on("click", "#DeleteRow", function () {
            $(this).parents("#row").remove();
        })


    </script>
@endsection