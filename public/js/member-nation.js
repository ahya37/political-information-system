// filter anggota terdaftar
// get event changer
const selectFillter = document.getElementById("filterMember");
selectFillter.addEventListener("change", async function () {
    BeforeSend("Loadachievment");
    try {
        const selectKeyWord = selectFillter.value;
        const members = await getMembers(selectKeyWord);

        updateMemberUi(members, selectKeyWord);

        if (selectKeyWord === "referal") {
            $("#nama").text("Nama");
            $("#jml").text("Jumlah Referal");
            $("#aksi").text("Aksi");
        }
        if (selectKeyWord === "input") {
            $("#jml").text();
            $("#nama").text("Nama");
            $("#jml").text("Jumlah Input");
            $("#aksi").text("Aksi");
        }
    } catch (err) {
        console.log(err);
    }
    Complete("Loadachievment");
});
// get api data
function getMembers(selectKeyWord) {
    return fetch(`/api/memberreferalup/${selectKeyWord}`)
        .then((response) => {
            if (!response.ok) {
                throw new Error(response.statusText);
            }
            return response.json();
        })
        .then((response) => {
            if (response.Response === "False") {
            }
            return response;
        });
}

// get update ui
function updateMemberUi(members, selectKeyWord) {
    let divHtml = "";
    members.forEach((m) => {
        divHtml += showDivHtml(m, selectKeyWord);
    });
    const divHtmlContainer = document.getElementById("showData");
    divHtmlContainer.innerHTML = divHtml;
}

// get ui
function showDivHtml(m, selectKeyWord) {
    if (selectKeyWord === "referal") {
        return `
        <tr>
        <td>${m.name}</td>
        <td>${m.total}</td>
        <td>
        <div class="btn-group">
            <div class="dropdown">
            <button class="btn btn-sm btn-sc-primary text-white dropdown-toggle mr-1 mb-1" type="button" data-toggle="dropdown">...</button>
            <div class="dropdown-menu">
                <a href='/admin/member/by_referal/${m.id}'  class="dropdown-item">
                Detail
                </a> 
            </div>
            </div>
            </div>
            </td>
        </tr>
        `;
    }
    if (selectKeyWord === "input") {
        return `
        <tr>
        <td>${m.name}</td>
        <td>${m.total}</td>
        <td>
        <div class="btn-group">
            <div class="dropdown">
            <button class="btn btn-sm btn-sc-primary text-white dropdown-toggle mr-1 mb-1" type="button" data-toggle="dropdown">...</button>
            <div class="dropdown-menu">
                <a href='/admin/'  class="dropdown-item">
                Detail
                </a> 
            </div>
            </div>
            </div>
        </td>
        </tr>
        `;
    }
}

// funsgsi efect loader
function BeforeSend(idLoader) {
    $("#" + idLoader + "").removeClass("d-none");
}

function Complete(idLoader) {
    $("#" + idLoader + "").addClass("d-none");
}
