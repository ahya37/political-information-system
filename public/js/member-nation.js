// filter anggota terdaftar
// get event changer
const selectFillter = document.getElementById("filterMember");
selectFillter.addEventListener("change", async function () {
    try {
        const selectKeyWord = selectFillter.value;
        const members = await getMembers(selectKeyWord);
        updateMemberUi(members);
    } catch (err) {
        alert(err);
    }
});

// get api data
function getMembers(keyword) {
    return fetch("/api/memberall" + "/" + keyword)
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
function updateMemberUi(members) {
    let divHtml = "";
    members.forEach((m) => {
        divHtml += showDivHtml(m);
    });
    const divHtmlContainer = document.getElementById("showData");
    divHtmlContainer.innerHTML = divHtml;
}

// get ui
function showDivHtml(m) {
    return `
    <tr>
    <td>
    <a href="/admin/member/profile/${m.id}">
    <img class="rounded" width="40" src="/storage/${m.photo}">
    ${m.name}
    </a>
    </td>
    <td>${m.village.district.regency.name}</td>
    <td>${m.village.district.name}</td>
    <td>${m.village.name}</td>
    <td>${m.reveral.name}</td>
    <td>${m.create_by.name}</td>
    <td>${m.create_by}</td>
    <td>
    </td>
    </tr>
    `;
}
