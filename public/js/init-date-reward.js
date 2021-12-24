$(document).ready(function () {
    $("#date-init").hide();
    $("#viewDatw").click(function () {
        $("#date-init").show();
    });
    $("#mmp").mmp();

    $("#ok").click(function () {
        let date = $("#mmp").mmp("value");
        console.log("date: ", date);
    });
});
