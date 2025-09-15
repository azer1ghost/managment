<div class="{{$widget->class_attribute}} grid-margin stretch-card col-md-6" style="">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <p class="card-title">{{$widget->details}}</p>
                <h3 >GB</h3>
                <h3 >QIB</h3>
            </div>
            <p class="font-weight-500"></p>
            <div></div>
            <div class="col-md-12 col-xl-12">
                <div class="row">
                    <div class="col-md-12 border-right" style="overflow-y: scroll; height: 300px">
                        <div class="table-responsive mb-3 mb-md-0 mt-3">
                            <table class="table table-borderless report-table">
                                @if(in_array(auth()->user()->company_id , [1,2,3,4,6,10,11,12,14,15,16]) )
                                @foreach($works as $work)
                                    <tr>
                                        <td class="text-dark bold">{{$work->name . ' ' .$work->surname}}</td>
                                        <td class="w-75 px-0">
                                            <div class="progress progress-md mx-4">
                                                <div class="progress-bar"
                                                     style="width: {{$work->total_value / 725 * 100}}%;
                                                     background-color: {{rand_color()}}!important;"
                                                     role="progressbar">
                                                </div>
                                            </div>
                                        </td>
                                        <td><h5 class="font-weight-bold mb-0">{{$work->total_value}}</h5></td>
                                        <td><h5 class="font-weight-bold mb-0">{{$work->total_qib}}</h5></td>
                                    </tr>
                                @endforeach
                                @else
                                    @foreach($works as $work)
                                        <tr>
                                            <td class="text-dark bold">TEST</td>
                                            <td class="w-75 px-0">
                                                <div class="progress progress-md mx-4">
                                                    <div class="progress-bar"
                                                         style="width: {{15 / 725 * 100}}%;
                                                     background-color: {{rand_color()}}!important;"
                                                         role="progressbar">
                                                    </div>
                                                </div>
                                            </td>
                                            <td><h5 class="font-weight-bold mb-0">15</h5></td>
                                        </tr>
                                    @endforeach
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

