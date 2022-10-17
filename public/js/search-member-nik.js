const minlength = 3;
const searchBtn = document.getElementById("searchMemberBtn");
// search.addEventListener("keypress", function (event) {
//     if (event.which == "13") {
//         event.preventDefault();
//     }
// });
searchBtn.addEventListener("click", async function () {
    const search = document.getElementById("searchMember");
    const searchValue = search.value;
    if (searchValue === '') {
        $('#showData').addClass("d-none");
        $('#myAnggota').removeClass("d-none");
    }else{
        $('#myAnggota').addClass("d-none");
        $('#showData').addClass("d-none");
        const userId = document.getElementById("userId");
        BeforeSend("Loadachievment");
        try {
    
            const userIdValue = userId.value;
            const members = await getMembers(searchValue, userIdValue);
            updateMemberUi(members);
        } catch (err) { }
        Complete("Loadachievment");
    }
});

function getMembers(searchValue, userIdValue) {
        $("#result").removeClass("d-none");
        return fetch(`/api/searchmembernik`, {
            method: "POST",
            headers: {
                Accept: "application/json",
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ data: searchValue, userId: userIdValue }),
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
  
}

function updateMemberUi(members) {
    if (members.length > 0) {
        $('#myAnggota').addClass("d-none");
        $('#showData').removeClass("d-none");
        let divHtml = "";
        members.forEach((m) => {
            divHtml += showDivHtml(m);
        });
    
        const divHtmlContainer = document.getElementById("showData");
        divHtmlContainer.innerHTML = divHtml;
    }else{
       $('#myAnggota').removeClass("d-none");
    }
}

function showDivHtml(m) {
    return `<a href="/user/member/registered/edit/${m.id}">
                <img  class="rounded" width="40" src="/storage/${m.photo}">
                    ${m.name}, Kode Referal : <strong>${m.code}</strong>
                    <br>
            </a>
            `;
}

function BeforeSend(idLoader) {
    $("#" + idLoader + "").removeClass("d-none");
}

function Complete(idLoader) {
    $("#" + idLoader + "").addClass("d-none");
}
