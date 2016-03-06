//wave.js

function wave() {
    $("#spinner").css("visibility", "visible");
    if (true) {
        setTimeout(function() {
            Materialize.toast('Wave sent! Be sure to get safely to the stop.', 5000);
            $("#wave_button").removeClass("blue").addClass("green");
            $("#spinner").css("visibility", "hidden");
        }, 1500);

    };
}
