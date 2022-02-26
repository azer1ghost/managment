<div class="{{$widget->class_attribute}}">
    <div class="card border-0 widget-container">
        <div class="py-2 px-1">
            <canvas id="{{$widget->key}}" style="width: 100%;"></canvas>
        </div>
    </div>
</div>
<script>
    const {{$model}}Data = {
        labels: @json($results['labels']),
        datasets: [
            {
                label: '{{$widget->details}}',
                data: @json($results['data']),
                backgroundColor: @json($results['colors']),
            }
        ]
    };

    const {{$model}}Config = {
        type: 'pie',
        data: {{$model}}Data,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
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