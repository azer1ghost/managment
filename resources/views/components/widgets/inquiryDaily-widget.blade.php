<div class="col-md-12 mb-4">  {{--classin yeirne bunu yazmaq lazimdi  {{$widget->class_attribute}}--}}
    <div class="card border-0 widget-container">
        <div class="py-2 px-1">
            <canvas id="{{$widget->key}}" style="width: 100%; height: 500px"></canvas> {{--heightin yeirne bunu yazmaq lazimdi  {{{{$widget->style_attribute}}}}--}}
        </div>
    </div>
</div>
<script>
    const {{$model}}Labels = ['day1','day2','day3','day4'];

    const {{$model}}Data = {
        labels: {{$model}}Labels,
        datasets: [
            {
                label: 'Dataset 1',
                data: [1,2,3,4],
                borderColor: '#454554',
                backgroundColor: '#a45421',
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