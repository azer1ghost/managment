<div class="col-md-8 mb-4">  {{--classin yeirne bunu yazmaq lazimdi  {{$widget->class_attribute}}--}}
    <div class="card border-0 widget-container">
        <div class="py-2 px-1">
            <canvas id="{{$widget->key}}" style="width: 100%; height: 500px"></canvas> {{--heightin yeirne bunu yazmaq lazimdi  {{{{$widget->style_attribute}}}}--}}
        </div>
    </div>
</div>
<script>
    const {{$model}}Labels = @json(array_keys($results));

    const {{$model}}Data = {
        labels: {{$model}}Labels,
        datasets: [
            {
                label: 'Say',
                data: @json(array_values($results)),
                borderColor: '#2262D9',
                backgroundColor: '#2262D9',
            },

        ]
    };
    const {{$model}}Config = {
        type: 'bar',
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