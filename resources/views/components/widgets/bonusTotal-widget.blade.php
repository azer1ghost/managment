<div class="{{$widget->class_attribute}}">
    <div class="card  ">
        <div class="py-2 px-1">
            <canvas id="{{$widget->key}}" width="250" height="250"></canvas>
        </div>
    </div>
</div>

<script>
    var ctx = document.getElementById("{{$widget->key}}");
    var {{$model}}MyChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ["Red", "Blue",'sads'],
            datasets: [{
                label: '# of Votes',
                data: [12, 19, 55],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(54, 162, 235, 0.2)',

                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(54, 162, 235, 1)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            circumference: 58 * Math.PI,
            rotation: -29 * Math.PI
        }
    });
</script>