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
		
		const results = await getSipDataRegency();
		getSipGrafikRegencyUi(results)
		
	}catch(err){
		console.log(err);
	} 
}
function getSipGrafikRegencyUi(results){
	// const label = results.label;
	// const suara = results.hasilsuara; 
	// const anggota = results.anggota;
	// const peserta_kunjungan = results.peserta_kunjungan;
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
 
async function getTps(districtId){
	const result = await getApiTps(districtId);
	$('#district').text(`KECAMATAN ${result.district}`)
	
	
    let divHtmTps = "";
    result.villages.forEach((m) => {
        divHtmTps += showDivTpsUi(m);
    }); 

    const divHtmTpsContainer = document.getElementById(
        "datasuara"
    );
	 
    divHtmTpsContainer.innerHTML = divHtmTps;
	$('#jmltps').append(`<b>${result.jmltps}</b>`);
	$('#jmlanggota').append(`<b>${result.jmlanggota}</b>`);
	$('#jmlpesertakunjungan').append(`<b>${result.jmlpesertakunjungan}</b>`);
	$('#jmlhasilsuara').append(`<b>${result.jmlhasilsuara}</b>`);
	$('#jmlpersentage').append(`<b>${result.persentage}%</b>`); 
}
   
// function get data tps per desa
function getApiTps(districtId){
	return fetch(`/api/sip/rekap/district/${districtId}`,{
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
function showDivTpsUi(m) {
    return `<tr>
            <td class="text-center">${m.no}</td>
            <td>${m.name}</td>
            <td class="text-center">${m.tps}</td>
            <td  class="text-center">${m.anggota}</td>
            <td  class="text-center">${m.peserta_kunjungan}</td> 
            <td  class="text-center">${m.hasil_suara}</td>
            <td  class="text-center">${m.persentage}%</td> 
            </tr>`;
}
getSipGrafikRegency();
getTps(districtId);