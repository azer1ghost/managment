<div class="col-md-4 stretch-card grid-margin">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body" style="overflow-y: scroll; min-height: 535px !important;">
                <p class="card-title">{{$widget->details}}</p>
                <div class="charts-data">
                   @foreach($users as $user)
                        <div class="mt-3">
                            <p class="mb-0">{{$user->getAttribute('fullname')}}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="progress progress-md flex-grow-1 mr-4">
                                    <div class="progress-bar {{$colors[$loop->iteration]}}" role="progressbar" style="width: {{($user->getAttribute('inquiries_count')/\App\Models\Inquiry::count())*100}}%; color: red" aria-valuenow="48" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="mb-0">{{$user->getAttribute('inquiries_count')}}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>