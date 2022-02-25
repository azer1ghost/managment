<div class="{{$widget->class_attribute}} grid-margin stretch-card col-md-8" style="">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <p class="card-title">{{$widget->details}}</p>
                <a href="#" class="text-info">View all</a>
            </div>
            <p class="font-weight-500">The total number of sessions within the date range. It is the period time a user
                is actively engaged with your website, page or app, etc</p>
            <div></div>
            <canvas id="{{$widget->key}}"></canvas>
        </div>
    </div>
</div>
<script>
    const {{$model}}Data = {
        labels: @json($results['labels']),
        datasets: @json($results['data'])
    };

    const {{$model}}Config = {
        type: 'bar',
        data: {{$model}}Data,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                title: {
                    display: true,
                    text: '{{$widget->details}}'
                }
            }
        },
    };

    const {{$model}}MyChart = new Chart(
        document.getElementById('{{$widget->key}}'),
            {{$model}}Config
    );
</script>