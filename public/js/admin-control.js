const minlength = 3;

// search provinsi
// const search = document.getElementById("formProvince");
// search.addEventListener("keyup", async function () {
//     const searchProvinceValue = this.value;
//     if (searchProvinceValue === null || searchProvinceValue === "") {
//         $("#showDataProvince").empty();
//     } else {
//         BeforeSend("LoadProvince");
//         try {
//             const provinces = await getProvince(searchProvinceValue);
//             updateMemberUiProvince(provinces);
//         } catch (err) {}
//         Complete("LoadProvince");
//     }
// });

// function getProvince(searchProvinceValue) {
//     if (searchProvinceValue.length >= minlength) {
//         return fetch(`/api/searchprovinces`, {
//             method: "POST",
//             headers: {
//                 Accept: "application/json",
//                 "Content-Type": "application/json",
//             },
//             body: JSON.stringify({ data: searchProvinceValue }),
//         }).then((response) => {
//             return response.json();
//         });
//     }
// }

// function updateMemberUiProvince(provinces) {
//     let divHtml = "";
//     divHtml += showDivHtml(provinces);

//     const divHtmlContainer = document.getElementById("showDataProvince");
//     divHtmlContainer.innerHTML = divHtml;
// }

// function showDivHtml(provinces) {
//     return `
//             <a    onclick='selectData(${provinces.id})' class="col-12">
//                     <div class="card mt-2">
//                     <div class="card-body">
//                     <i class="fa fa-check"></i> ${provinces.name}
//                     </div>
//                     </div>
//                     </a>
//             `;
// }

// async function selectData(id) {
//     let formProvince = $("#formProvince");
//     let formProvinceResult = $("#formProvinceResult");
//     // formProvince.val(id);
//     try {
//         const province = await getProvinceById(id);
//         formProvince.val(province.name);
//         formProvinceResult.val(province.id);
//         $("#showDataProvince").empty();
//     } catch (err) {}
// }

// function getProvinceById(id) {
//     return fetch(`/api/searchprovincesById`, {
//         method: "POST",
//         headers: {
//             Accept: "application/json",
//             "Content-Type": "application/json",
//         },
//         body: JSON.stringify({ data: id }),
//     }).then((response) => {
//         return response.json();
//     });
// }

// // search regency
// const searchRegency = document.getElementById("formRegency");
// searchRegency.addEventListener("keyup", async function () {
//     const searchRegencyValue = this.value;
//     if (searchRegencyValue === null || searchRegencyValue === "") {
//         $("#showDataRegency").empty();
//     } else {
//         try {
//             const regencies = await getRegency(searchRegencyValue);
//             updateMemberUiRegency(regencies);
//         } catch (err) {}
//     }
// });

// function getRegency(searchRegencyValue) {
//     if (searchRegencyValue.length >= minlength) {
//         return fetch(`/api/searchregencies`, {
//             method: "POST",
//             headers: {
//                 Accept: "application/json",
//                 "Content-Type": "application/json",
//             },
//             body: JSON.stringify({ data: searchRegencyValue }),
//         }).then((response) => {
//             return response.json();
//         });
//     }
// }

// function updateMemberUiRegency(regencies) {
//     let divHtml = "";
//     divHtml += showDivHtmlRegency(regencies);

//     const divHtmlContainer = document.getElementById("showDataRegency");
//     divHtmlContainer.innerHTML = divHtml;
// }

// function showDivHtmlRegency(regencies) {
//     return `
//                 <a    onclick='selectDataRegency(${regencies.id})' class="col-12">
//                     <div class="card mt-2">
//                     <div class="card-body">
//                     <i class="fa fa-check"></i> ${regencies.view}
//                     </div>
//                     </div>
//                     </a>
//             `;
// }

// async function selectDataRegency(id) {
//     let formRegency = $("#formRegency");
//     let formRegencyResult = $("#formRegencyResult");
//     try {
//         const regency = await getRegencyById(id);
//         formRegency.val(regency.name);
//         formRegencyResult.val(regency.id);
//         $("#showDataRegency").empty();
//     } catch (err) {}
// }

// function getRegencyById(id) {
//     return fetch(`/api/searchregencyById`, {
//         method: "POST",
//         headers: {
//             Accept: "application/json",
//             "Content-Type": "application/json",
//         },
//         body: JSON.stringify({ data: id }),
//     }).then((response) => {
//         return response.json();
//     });
// }

// search district
const searchDistrict = document.getElementById("formDistrict");
searchDistrict.addEventListener("keyup", async function () {
    const searchDistrictValue = this.value;
    if (searchDistrictValue === null || searchDistrictValue === "") {
        $("#showDataDistrict").empty();
    } else {
        try {
            const district = await getDistrict(searchDistrictValue);
            updateMemberUiDistrict(district);
        } catch (err) {}
    }
});

function getDistrict(searchDistrictValue) {
    if (searchDistrictValue.length >= minlength) {
        return fetch(`/api/searchdistricts`, {
            method: "POST",
            headers: {
                Accept: "application/json",
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ data: searchDistrictValue }),
        }).then((response) => {
            return response.json();
        });
    }
}
function updateMemberUiDistrict(district) {
    let divHtml = "";
    district.forEach((m) => {
        divHtml += showDivHtmlDistrict(m);
    });

    const divHtmlContainer = document.getElementById("showDataDistrict");
    divHtmlContainer.innerHTML = divHtml;
}
function showDivHtmlDistrict(m) {
    return `   
                <a    onclick='selectDataDistrict(${m.id})' class="col-12">
                    <div class="card mt-2">
                    <div class="card-body">
                    <i class="fa fa-check"></i> ${m.view}
                    </div>
                    </div>            
                    </a>
            `;
}
async function selectDataDistrict(id) {
    let formDistrict = $("#formDistrict");
    let formDistrictResult = $("#formDistrictResult");
    try {
        const district = await getDistrictById(id);
        formDistrict.val(
            `${district.name}, ${district.regency.name}, ${district.regency.province.name}`
        );
        formDistrictResult.val(district.id);
        $("#showDataDistrict").empty();
    } catch (err) {}
}
function getDistrictById(id) {
    return fetch(`/api/searchdistrictById`, {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ data: id }),
    }).then((response) => {
        return response.json();
    });
}

// search village
const searchVIllage = document.getElementById("formVillage");
searchVIllage.addEventListener("keyup", async function () {
    const searchVIllageValue = this.value;
    if (searchVIllageValue === null || searchVIllageValue === "") {
        $("#showDataVillage").empty();
    } else {
        try {
            const village = await getVillage(searchVIllageValue);
            updateMemberUiVillage(village);
        } catch (err) {}
    }
});
function getVillage(searchVIllageValue) {
    if (searchVIllageValue.length >= minlength) {
        return fetch(`/api/searchvillage`, {
            method: "POST",
            headers: {
                Accept: "application/json",
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ data: searchVIllageValue }),
        }).then((response) => {
            return response.json();
        });
    }
}
function updateMemberUiVillage(village) {
    let divHtml = "";
    village.forEach((m) => {
        divHtml += showDivHtmlVillage(m);
    });

    const divHtmlContainer = document.getElementById("showDataVillage");
    divHtmlContainer.innerHTML = divHtml;
}
function showDivHtmlVillage(m) {
    return `   
                <a onclick='selectDataVillage(${m.id})' class="col-12">
                    <div class="card mt-2">
                    <div class="card-body">
                    <i class="fa fa-check"></i> ${m.view}
                    </div>
                    </div>            
                    </a>
            `;
}
async function selectDataVillage(id) {
    let formVillage = $("#formVillage");
    let formVillageResult = $("#formVillageResult");
    try {
        const village = await getVillageById(id);
        formVillage.val(
            `${village.name},${village.district.name}, ${village.district.regency.name}, ${village.district.regency.province.name}`
        );
        formVillageResult.val(village.id);
        $("#showDataVillage").empty();
    } catch (err) {}
}
function getVillageById(id) {
    return fetch(`/api/searchVillageById`, {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ data: id }),
    }).then((response) => {
        return response.json();
    });
}

function BeforeSend(idLoader) {
    $("#" + idLoader + "").removeClass("d-none");
}

function Complete(idLoader) {
    $("#" + idLoader + "").addClass("d-none");
}
