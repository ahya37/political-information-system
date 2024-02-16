// Data for the bar graph

function getSipDataRegency(){
	return fetch("/api/sip/regency",{
			method: 'POST',
			headers: {
			  'Accept': 'application/json',
			  'Content-Type': 'application/json'
			},  
		}).then((response) => { 
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
		
		const results = await getSipDataRegency();
		getSipGrafikRegencyUi(results)
		
	}catch(err){
		console.log(err);
	} 
}
function getSipGrafikRegencyUi(results){
	const label = results.label;
	const suara = results.suara;
	const anggota = results.anggota;
	const peserta_kunjungan = results.peserta_kunjungan;
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
		} ,
		onClick(e, activeEl){
			console.log(e)
		}
		
	  };
	  
	let data = {  
				labels: label,
				datasets: [
					{
						label: "Anggota",
						backgroundColor: "rgba(255, 99, 132, 0.2)", // Background color of bars
						borderColor: "rgba(255, 99, 132, 1)", // Border color of bars
						borderWidth: 1,
						data: anggota  // Example data values
					},
					{
						label: "Peserta Kunjungan",
						backgroundColor: "rgba(255, 205, 86, 0.2)", // Background color of bars
						borderColor: "rgba(255, 205, 86, 1)", // Border color of bars
						borderWidth: 1,
						data: peserta_kunjungan  // Example data values
					}, 
					{
						label: "Suara",
						backgroundColor: "rgba(75, 192, 192, 0.2)", // Background color of bars
						borderColor: "rgba(75, 192, 192, 1)", // Border color of bars
						borderWidth: 1,
						data: suara  // Example data values
					},
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
	
	
	
	
};

getSipGrafikRegency();