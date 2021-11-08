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
    return `
            <option value="${m.id}">${m.name}</option>
            `;
}

function BeforeSend(idLoader) {
    $("#" + idLoader + "").removeClass("d-none");
}

function Complete(idLoader) {
    $("#" + idLoader + "").addClass("d-none");
}

async function selectData(id) {
    $("#searchMember").val(id);

    try {
        const detailMember = await getMemberById(id);
        console.log("detail member: ", detailMember);
    } catch (err) {
        console.log("err: ", err);
    }

    $("#resultview").removeClass("d-none");
    $("#showData").hide();
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
        console.log(response);
        return response.json();
    });
}
