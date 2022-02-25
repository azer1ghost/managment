<div class="col-md-4 mb-4">  {{--classin yeirne bunu yazmaq lazimdi  {{$widget->class_attribute}}--}}
    <div class="card  ">
        <div class="py-2 px-1">
            <canvas id="{{$widget->key}}"></canvas>
        </div>
    </div>
</div>

<script>
    let ctx = document.getElementById("{{$widget->key}}");
    let {{$model}}MyChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['{{$results[0]->label}}', '{{$results[1]->label}}'],
            datasets: [{
                label: '{{$widget->details}}',
                data: [{{$results[0]->total}}, {{$results[1]->total}}],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            circumference: 58 * Math.PI,
            rotation: -29 * Math.PI,
            plugins: {
                title: {
                    display: true,
                    text: '{{$widget->details}}'
                }
            }
        }
    });
</script>