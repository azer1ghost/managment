<div class="col-md-4 mb-4">  {{--classin yeirne bunu yazmaq lazimdi  {{$widget->class_attribute}}--}}
    <div class="card border-0 widget-container">
        <div class="py-2 px-1">
            <canvas id="{{$widget->key}}" style="width: 100%;"></canvas>
        </div>
    </div>
</div>
<script>
    const {{$model}}Data = {
        labels: ['ksd', 'slkdks', 'msdfks', 'slkdks', 'msdfks', 'slkdks', 'msdfks'],
        datasets: [
            {
                label: 'Dataset 1',
                data: [1, 2, 3, 2, 3, 2, 3],
                backgroundColor: ['#2a5125', '#7d5451', '#545a45', '#7d5451', '#545a45', '#7d5451', '#545a45'],
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