function startTime() {
    const today = new Date();
    let h = today.getHours();
    let m = today.getMinutes();

    m = checkTime(m);

    document.getElementById('dashboard-clock').innerHTML =  h + ":" + m;
}

function checkTime(i) {
    if (i < 10) {i = "0" + i}  // add zero in front of numbers < 10

    return i;
}

setInterval(startTime, 1000);