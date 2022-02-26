<div class="{{$widget->class_attribute}} grid-margin stretch-card col-md-6" style="">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <p class="card-title">{{$widget->details}}</p>
                <a href="#" class="text-info">View all</a>
            </div>
            <p class="font-weight-500"></p>
            <div></div>
            <canvas id="{{$widget->key}}"></canvas>
        </div>
    </div>
</div>
<script>
    const labels = ['day1', 'day2', 'day3'];
    const data = {
        labels: @json($results['keys']),
        datasets: [
            {
                label: 'Total',
                data: @json($results['data']),
                borderColor: '#53c477',
                backgroundColor: '#53c477',
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