<div class="{{$widget->class_attribute}} grid-margin stretch-card col-md-6" style="">
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
    const labels = ['day1', 'day2', 'day3'];
    const data = {
        labels: labels,
        datasets: [
            {
                label: 'Verified',
                data: [11, 16, 12],
                borderColor: '#2d66f6',
                backgroundColor: '#53c477',
            },
            {
                label: 'Total',
                data: [18, 27, 32],
                borderColor: '#537bc4',
                backgroundColor: '#537bc4',
            }
        ]
    };

    const {{$model}}Config = {
        type: 'bar',
        data: data,
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