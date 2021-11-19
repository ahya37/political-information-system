const minlength = 3;

// search provinsi
const search = document.getElementById("formProvince");
search.addEventListener("keyup", async function () {
    const searchProvinceValue = this.value;
    if (searchProvinceValue === null || searchProvinceValue === "") {
        $("#showDataProvince").empty();
    } else {
        BeforeSend("LoadProvince");
        try {
            const provinces = await getProvince(searchProvinceValue);
            updateMemberUiProvince(provinces);
        } catch (err) {}
        Complete("LoadProvince");
    }
});

function getProvince(searchProvinceValue) {
    if (searchProvinceValue.length >= minlength) {
        return fetch(`/api/searchprovinces`, {
            method: "POST",
            headers: {
                Accept: "application/json",
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ data: searchProvinceValue }),
        }).then((response) => {
            return response.json();
        });
    }
}

function updateMemberUiProvince(provinces) {
    let divHtml = "";
    divHtml += showDivHtml(provinces);

    const divHtmlContainer = document.getElementById("showDataProvince");
    divHtmlContainer.innerHTML = divHtml;
}

function showDivHtml(provinces) {
    return `
            <a    onclick='selectData(${provinces.id})' class="col-12">
                    <div class="card mt-2">
                    <div class="card-body">
                    <i class="fa fa-check"></i> ${provinces.name}
                    </div>
                    </div>            
                    </a>
            `;
}

async function selectData(id) {
    let formProvince = $("#formProvince");
    let formProvinceResult = $("#formProvinceResult");
    // formProvince.val(id);
    try {
        const province = await getProvinceById(id);
        formProvince.val(province.name);
        formProvinceResult.val(province.id);
        $("#showDataProvince").empty();
    } catch (err) {}
}

function getProvinceById(id) {
    return fetch(`/api/searchprovincesById`, {
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

// search regency
const searchRegency = document.getElementById("formRegency");
searchRegency.addEventListener("keyup", async function () {
    const searchRegencyValue = this.value;
    if (searchRegencyValue === null || searchRegencyValue === "") {
        $("#showDataRegency").empty();
    } else {
        try {
            const regencies = await getRegency(searchRegencyValue);
            updateMemberUiRegency(regencies);
        } catch (err) {}
    }
});

function getRegency(searchRegencyValue) {
    if (searchRegencyValue.length >= minlength) {
        return fetch(`/api/searchregencies`, {
            method: "POST",
            headers: {
                Accept: "application/json",
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ data: searchRegencyValue }),
        }).then((response) => {
            return response.json();
        });
    }
}

function updateMemberUiRegency(regencies) {
    let divHtml = "";
    divHtml += showDivHtmlRegency(regencies);

    const divHtmlContainer = document.getElementById("showDataRegency");
    divHtmlContainer.innerHTML = divHtml;
}

function showDivHtmlRegency(regencies) {
    return `   
                <a    onclick='selectDataRegency(${regencies.id})' class="col-12">
                    <div class="card mt-2">
                    <div class="card-body">
                    <i class="fa fa-check"></i> ${regencies.view}
                    </div>
                    </div>            
                    </a>
            `;
}

async function selectDataRegency(id) {
    let formRegency = $("#formRegency");
    let formRegencyResult = $("#formRegencyResult");
    try {
        const regency = await getRegencyById(id);
        formRegency.val(regency.name);
        formRegencyResult.val(regency.id);
        $("#showDataRegency").empty();
    } catch (err) {}
}

function getRegencyById(id) {
    return fetch(`/api/searchregencyById`, {
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
