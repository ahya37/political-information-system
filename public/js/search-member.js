const minlength = 3;
const search = document.getElementById("searchMember");
search.addEventListener("keypress", function (event) {
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
        $("#result").removeClass("d-none");
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
        $("#result").addClass("d-none");
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
    return `<p>
                <img  class="rounded" width="40" src="/storage/${m.photo}">
                    ${m.name}: <strong>${m.code}</strong>
                    <br>
            </p>
            `;
}

function BeforeSend(idLoader) {
    $("#" + idLoader + "").removeClass("d-none");
}

function Complete(idLoader) {
    $("#" + idLoader + "").addClass("d-none");
}
