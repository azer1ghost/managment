<div class="{{$widget->class_attribute}} stretch-card grid-margin">
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
                                    <div class="progress-bar" role="progressbar" style="width: {{round(($user->getAttribute('inquiries_count')/$users->sum('inquiries_count'))*100, 2)}}%; background-color: {{rand_color()}}!important;"></div>
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