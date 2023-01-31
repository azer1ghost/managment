@extends('pages.transit.layout')

@section('title', __('translates.navbar.transit'))
2
@section('content')
    <div class="col-12 p-5">
        <ul class="nav nav-pills nav-justified mb-3" id="ex1" role="tablist">
            <li class="nav-item " role="presentation">
                <a class="nav-link btn btn-primary m-3" id="tab-transit" data-toggle="tab" href="#pills-transit"
                   role="tab"
                   aria-controls="pills-transit" aria-selected="true">Online Tranzit</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link btn btn-primary m-3" id="tab-declaration" data-toggle="tab"
                   href="#pills-declaration" role="tab"
                   aria-controls="pills-declaration" aria-selected="false">Qısa İdxal Bəyannaməsi</a>
            </li>
        </ul>
        <!-- Pills navs -->

        <!-- Pills content -->
        <div class="tab-content">
            <div class="tab-pane fade show active" id="pills-transit" role="tabpanel"
                 aria-labelledby="tab-transit">
                <form>
                    <div class="text-center mb-3">
                        <p>Web Transit</p>

                    </div>

                    <div class="transit">
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
                    <div id="div"></div>
                    <i class="add">Add</i>

                    <!-- 2 column grid layout -->
                    <div class="row mb-4">
                        <div class="col-md-6 d-flex justify-content-center">
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
                {{--                    <button type="submit" class="btn btn-primary btn-block mb-4"><a href="{{route('payment')}}">Ödəniş--}}
                {{--                            edin!</a></button>--}}

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
                        <div class="col-md-6 d-flex justify-content-center">
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
        $(document).ready(function () {
            $(".add").click(function () {
                $(".transit").insertAfter(".div");
            });
        });

    </script>
@endsection