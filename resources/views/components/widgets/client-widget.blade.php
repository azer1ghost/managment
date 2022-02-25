<div class="col-md-4 mb-4">  {{--classin yeirne bunu yazmaq lazimdi  {{$widget->class_attribute}}--}}
    <div class="card border-0 widget-container">
        <div class="py-2 px-1">
            <canvas id="{{$widget->key}}" style="width: 100%;"></canvas>
        </div>
    </div>
</div>
<script>
    const {{$model}}Data = {
        labels: ['{{$results[0]->label}}', '{{$results[1]->label}}'],
        datasets: [
            {
                label: '{{$widget->details}}',
                data: [{{$results[0]->total}}, {{$results[1]->total}}],
                backgroundColor: ['#D38583', '#84A7D0'],
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