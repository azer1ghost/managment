<div class="col-md-4 stretch-card grid-margin">
{{--    <div class="row">--}}
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body" style="overflow-y: scroll; min-height: 535px !important;">
                    <p class="card-title">Charts</p>
                    <div class="charts-data">
                       @foreach($users as $user)

                            <div class="mt-3">
                                <p class="mb-0">{{$user->getAttribute('fullname')}}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="progress progress-md flex-grow-1 mr-4">
                                        <div class="progress-bar {{$colors[$loop->iteration]}}" role="progressbar" style="width: {{$user->getAttribute('inquiries_count')/\App\Models\Inquiry::count()*100}}%; color: red" aria-valuenow="48" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <p class="mb-0">{{$user->getAttribute('inquiries_count')}}</p>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
{{--        <div class="col-md-12 stretch-card grid-margin grid-margin-md-0">--}}
{{--            <div class="card data-icon-card-primary">--}}
{{--                <div class="card-body">--}}
{{--                    <p class="card-title text-white">Number of Meetings</p>--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-8 text-white">--}}
{{--                            <h3>34040</h3>--}}
{{--                            <p class="text-white font-weight-500 mb-0">The total number of sessions within the date range.It is calculated as the sum . </p>--}}
{{--                        </div>--}}
{{--                        <div class="col-4 background-icon">--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
</div>