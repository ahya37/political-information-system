  $(function(){
   var catJobs = {!! json_encode($cat_jobs) !!};
   var jobs    = $("#job");
   var job_label = [];
   var job_data = [];
   var ict_unit = [];
   var efficiency = [];
   var coloR = [];

   var dynamicColors = function() {
                    var r = Math.floor(Math.random() * 255);
                    var g = Math.floor(Math.random() * 255);
                    var b = Math.floor(Math.random() * 255);
                    return "rgb(" + r + "," + g + "," + b + ")";
                 };

   for(var i in catJobs){
     job_label.push(catJobs[i].label);
     job_data.push(catJobs[i].data);

     ict_unit.push(catJobs[i].ict_unit);
     efficiency.push(catJobs[i].efficiency);
     coloR.push(dynamicColors([1]));
   }
    //pie chart data
      var data = {
        labels: job_label,
        datasets: [
          {
            label: "Users Count",
            data: job_data,
            backgroundColor: coloR,
          }
        ]
      }
      //options
      var options = {
        responsive: true,
        title: {
          display: true,
          position: "top",
          text: "Last Week Registered Users -  Day Wise Count",
          fontSize: 18,
          fontColor: "#111"
        },
        legend: {
          display: true,
          position: "bottom",
          labels: {
            fontColor: "#333",
            fontSize: 16
          }
        }
      };
      var chartJobs = new Chart(jobs,{
        type:'pie',
        data:data,
        options: options
      });
  });