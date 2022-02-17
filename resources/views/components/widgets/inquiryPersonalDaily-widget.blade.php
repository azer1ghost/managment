<div class="{{$widget->class_attribute}}">
    <div class="card border-0 widget-container">
        <div class="py-2 px-1">
            <canvas id="{{$widget->key}}" style="width: 100%;{{$widget->style_attribute}}"></canvas>

        </div>
    </div>
</div>

<script>
    const {{$model}}Labels = ['day1', 'day2', 'day3', 'day4', 'day2', 'day3', 'day4', 'day2', 'day3', 'day4'];

    const {{$model}}Data = {
        labels: {{$model}}Labels,
        datasets: [
            {
                label: '{{$widget->details}}',
                data: [1,2,3,4,2,3,4,2,3,5],
                borderColor: '#454554',
                backgroundColor: '#21a481',
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