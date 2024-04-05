$('#datatable').DataTable({
	pageLength: 50,
});
 
// Data for the bar graph
const query = document.URL;
const districtId = query.substring(query.lastIndexOf("/") + 1);
function getSipDataRegency(){
	return fetch(`/api/sip/district/${districtId}`).then((response) => { 
                if (!response.ok) {
                    throw new Error(response.statusText);
                }
                return response.json();
            })
            .then((response) => {
                if (response.Response === "False") {
                    throw new Error(response.Error);
                }
                return response;
    }); 
}

async function getSipGrafikRegency(){
	try{
		$('#laodingChart').append(`<div class="d-flex justify-content-center">
		  <div class="spinner-border text-primary" role="status">
			<span class="sr-only">Loading...</span>
		  </div>
		</div>`)
		const results = await getSipDataRegency();
		getSipGrafikRegencyUi(results)
		$('#laodingChart').empty();
		
	}catch(err){
		console.log(err);
	} 
}
function getSipGrafikRegencyUi(results){
	let data = results

	// Get the context of the canvas element
	let ctx = document.getElementById("myChart"); 

			// Create the bar graph
	const config = {
		type: 'bar',
				data: data,
				options: {
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
					},
					onClick: (event, chartElement) => {
						if(chartElement.length > 0){
							const index = chartElement[0].index;
							const url = data.datasets[0].urls[index];  
							// go to tps by desa
						}
					} 
				}
	}
	
	let myChart = new Chart(
		ctx,
		config
	); 
};
getSipGrafikRegency();