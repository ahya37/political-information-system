const minlength = 3;
const search = document.getElementById("searchMember");
search.addEventListener("keyup", function (event) {
    if (event.which == "13") {
        event.preventDefault();
    }
});
search.addEventListener("keyup", async function () {
    BeforeSend("Loadachievment");
    try {
        const searchValue = this.value;
        const members = await getMembers(searchValue);
        updateMemberUi(members, searchValue);
    } catch (err) {}
    Complete("Loadachievment");
});

function getMembers(searchValue) {
    if (searchValue.length >= minlength) {
        return fetch(`/api/searchmember`, {
            method: "POST",
            headers: {
                Accept: "application/json",
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ data: searchValue }),
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error(response.statusText);
                }
                return response.json();
            })
            .then((response) => {
                if (response.Response === "False") {
                    throw new Error(response.statusText);
                }
                return response;
            });
    } else {
    }
}

function updateMemberUi(members, searchValue) {
    let divHtml = "";
    members.forEach((m) => {
        divHtml += showDivHtml(m, searchValue);
    });

    const divHtmlContainer = document.getElementById("showData");
    divHtmlContainer.innerHTML = divHtml;
}

function showDivHtml(m, searchValue) {
    return `<a    onclick='selectData(${m.id})' class="col-12">
                <img  class="rounded mt-2" width="40" src="/storage/${m.photo}">
                    ${m.name}: <strong>${m.code}</strong>
            </a>
            <br>
            `;
}

function BeforeSend(idLoader) {
    $("#" + idLoader + "").removeClass("d-none");
}

function Complete(idLoader) {
    $("#" + idLoader + "").addClass("d-none");
}

async function selectData(id) {
    let searchMember = $("#searchMember");
    searchMember.val(id);
    BeforeSend("LoadachievmentResult");
    try {
        $("#resultById").empty();
        const detailMember = await getMemberById(id);
        updateMemberUiById(detailMember);
    } catch (err) {}
    Complete("LoadachievmentResult");
    // $("#resultview").removeClass("d-none");
    // $("#showData").hide();
}

function getMemberById(id) {
    return fetch(`/api/memberbyid`, {
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

function updateMemberUiById(detailMember) {
    let searchMemberResult = $("#searchMemberResult");
    searchMemberResult.val(detailMember.id);

    let searchMember = $("#searchMember");
    searchMember.val(detailMember.name);

    let htmlResult = $("#resultById").append(
        `
            <div class="col-12 text-center mt-4">
                <img src="/storage/${
                    detailMember.photo
                }" width="200" class="rounded mb-3 img-thumbnail">
            </div>
            <div class="row mt-4">
                <div class="col-4">
                    <div class="product-title">NIK</div>
                    <div class="product-subtitle">${detailMember.nik}</div>
                    <div class="product-title">NAMA</div>
                    <div class="product-subtitle">${detailMember.name}</div>
                    <div class="product-title">DESA</div>
                    <div class="product-subtitle">${
                        detailMember.village.name ?? ""
                    }</div>
                    <div class="product-title">KECAMATAN</div>
                    <div class="product-subtitle">${
                        detailMember.village.district.name ?? ""
                    }</div>
                    <div class="product-title">KABUPATEN / KOTA</div>
                    <div class="product-subtitle">${
                        detailMember.village.district.regency.name ?? ""
                    }</div>
                    <div class="product-title">PROVINSI</div>
                    <div class="product-subtitle">${
                        detailMember.village.district.regency.province.name ??
                        ""
                    }</div>
                    <div class="product-title">ALAMAT</div>
                    <div class="product-subtitle">${detailMember.address}</div>
                </div>
                 <div class="col-4">
                    <div class="product-title">Status Pekerjaan</div>
                    <div class="product-subtitle">${detailMember.job.name}</div>
                    <div class="product-title">Pendidikan</div>
                    <div class="product-subtitle">${
                        detailMember.education.name
                    }</div>
                    <div class="product-title">Agama</div>
                    <div class="product-subtitle">${
                        detailMember.religion ?? ""
                    }</div>
                </div>
                 <div class="col-4">
                    <div class="product-title">Telpon</div>
                    <div class="product-subtitle">${
                        detailMember.phone_number
                    }</div>
                    <div class="product-title">Whatsapp</div>
                    <div class="product-subtitle">${detailMember.whatsapp}</div>
                    <div class="product-title">EMail</div>
                    <div class="product-subtitle">${
                        detailMember.email ?? ""
                    }</div>
                 </div>
            </div>
        `
    );
    return htmlResult;
}
