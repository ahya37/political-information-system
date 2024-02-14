// Data for the bar graph
let options = {
    responsive: true,
    title: {
      display: true,
      position: "top",
      text: "Bar Graph",
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
    },
    scales: {
		x: {
				  ticks: {
				  min: 0
				}
           },
        y: {
			 ticks: {
			  min: 0
			}
            }
    } 
  };
 
let data = {
            labels: ["Dapil 1", "Dapil 2", "Dapil 3"],
            datasets: [
				{
					label: "Sales",
					backgroundColor: "rgba(75, 192, 192, 0.2)", // Background color of bars
					borderColor: "rgba(75, 192, 192, 1)", // Border color of bars
					borderWidth: 1,
					data: [65, 59, 80, 81, 56, 55, 40] // Example data values
				},
				{
					label: "sv",
					backgroundColor: "rgba(75, 192, 192, 0.2)", // Background color of bars
					borderColor: "rgba(75, 192, 192, 1)", // Border color of bars
					borderWidth: 1,
					data: [65, 59, 80, 81, 56, 55, 40] // Example data values
				},
				{
					label: "mv",
					backgroundColor: "rgba(75, 192, 192, 0.2)", // Background color of bars
					borderColor: "rgba(75, 192, 192, 1)", // Border color of bars
					borderWidth: 1,
					data: [65, 59, 80, 81, 56, 55, 40] // Example data values
				}
			]
};

// Get the context of the canvas element
let ctx = document.getElementById("myChart").getContext("2d");

        // Create the bar graph
let myChart = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: options
});