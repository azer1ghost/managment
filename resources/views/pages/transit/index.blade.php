@extends('pages.transit.layout')

@section('title', 'Online Transit | Services')

<nav class="navbar bg-gradient-dark">
    <div class="container-fluid">
        <ul class="nav navbar-nav navbar-right">
            @auth()
                <li><a href="{{route('profile.index')}}"><span class="user"></span>Account</a></li>
            @endauth
        </ul>
    </div>
</nav>
@section('content')
    <div class="col-12 p-lg-5 py-4">
        <div class="card position-sticky top-0">
        <ul class="nav nav-pills nav-justified mb-3" id="ex1" role="tablist">
            <li class="nav-item col-sm-6" role="presentation">
                <a class="nav-link btn btn-primary m-3" id="tab-transit" data-toggle="tab" href="#pills-transit"
                   role="tab"
                   aria-controls="pills-transit" aria-selected="true">Online Transit</a>
            </li>
            <li class="nav-item col-sm-6" role="presentation">
                <a class="nav-link btn btn-primary m-3" id="tab-declaration" data-toggle="tab"
                   href="#pills-declaration" role="tab"
                   aria-controls="pills-declaration" aria-selected="false">Short Import Declaration</a>
            </li>
        </ul>

        <div class="tab-content col-12">
            <div class="tab-pane fade show active" id="pills-transit" role="tabpanel"
                 aria-labelledby="tab-transit">
                <form action="{{ route('order.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="text-center mb-3">
                        <h2>WEB TRANSIT</h2>
                    </div>

                    <div class="transit">
                        <div id="row">
                            <div class="form-outline mb-4">
                                <label class="form-label" for="transitCmr">CMR</label>
                                <div class="input-group mb-4">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="cmr">Upload</span>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" name="cmr[]" class="custom-file-input" id=cmr" aria-describedby="inputGroupFileAddon01">
                                        <label class="custom-file-label" for="cmr">Upload Files</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-outline mb-4">
                                <label class="form-label" for="transitInv">İNVOYS</label>
                                <div class="input-group mb-4">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="invoice">Upload</span>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" name="invoice[]" class="custom-file-input" id="invoice"
                                               aria-describedby="inputGroupFileAddon01">
                                        <label class="custom-file-label" for="invoice">Upload Files</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="div"></div>
                    <a class="btn btn-group btn-primary addButton">Add</a>
                    <div class="row mb-4">
                        <div class="col-md-6 d-flex">
                            <div class="form-check mb-3 ml-4 mb-md-0">
                                <input class="form-check-input" type="checkbox" value="" id="transitCheck" checked/>
                                <label class="form-check-label" for="transitCheck">I have read <a href="#" class="text-black">terms</a></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-outline mb-4">
                        <button type="submit" class="btn btn-warning col-12">Pay</button>
                    </div>
                    <div class="text-center">
                        <p>Hər hansısa sualınız var? <a href="tel:+994513339090" class="text-black">Contact Us</a></p>
                    </div>
                </form>
              <div class="col-12 text-center">
                  <a type="button" href="https://www.facebook.com/mobilbroker.az" class="btn btn-link btn-floating mx-1">
                      <i class="fab fa-facebook-f"></i>
                  </a>
                  <a type="button" href="https://www.instagram.com/mobilbroker.az/" class="btn btn-link btn-floating mx-1">
                      <i class="fab fa-instagram"></i>
                  </a>
                  <a type="button" href="https://www.linkedin.com/in/mobil-broker-and-logistics-2a1336203/" class="btn btn-link btn-floating mx-1">
                      <i class="fab fa-linkedin"></i>
                  </a>

                  <a type="button" href="https://www.youtube.com/channel/UCpbkZXCIy4LBkXI0RuF6G8A" class="btn btn-link btn-floating mx-1">
                      <i class="fab fa-youtube"></i>
                  </a>
              </div>
            </div>

            <div class="tab-pane fade show " id="pills-declaration" role="tabpanel"
                 aria-labelledby="tab-declaration">
                <form>
                    <div class="text-center mb-3">
                        <h2>Short Import Declaration</h2>
                    </div>
                    <div class="alert alert-success text-center">Coming Soon</div>
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
        $(document).ready(function () {

            $(".addButton").click(function () {
                $(".transit").append("<div> <div id='row''> <div class='form-outline mb-4'> <label class='form-label' for='transitCmr'>CMR</label> <div class='input-group mb-4'> <div class='input-group-prepend'> <span class='input-group-text' id='cmr'>Yüklə</span> </div> <div class='custom-file'> <input type='file' name='cmr[]' class='custom-file-input' id=cmr'aria-describedby='inputGroupFileAddon01'><label class='custom-file-label' for='cmr'>Upload Files</label> </div> </div> </div> <div class='form-outline mb-4'> <label class='form-label' for='transitInv'>İNVOYS</label> <div class='input-group mb-4'> <div class='input-group-prepend'> <span class='input-group-text' id='invoys'>Yüklə</span> </div> <div class='custom-file'> <input type='file' name='invoice[]' class='custom-file-input' id='invoys'aria-describedby='inputGroupFileAddon01'> <label class='custom-file-label' for='invoys'>Upload Files</label> </div> </div> <button class='btn btn-danger'id='DeleteRow' type='button'> <i class='bi bi-trash'></i>Delete </button></div> </div>");
            });
        });
        $("body").on("click", "#DeleteRow", function () {
            $(this).parents("#row").remove();
        })
    </script>
@endsection