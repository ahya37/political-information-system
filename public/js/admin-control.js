async function Admin() {
    try {
        const getDataAdmin = await getAdmin();
        const dataAdmin = getDataAdmin.data;
        updateTableUi(dataAdmin);
    } catch (err) {
        console.log(err);
    }
}

Admin();

function getAdmin() {
    return fetch("/api/admins")
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

function updateTableUi(dataAdmin) {
    let divHtml = "";
    dataAdmin.forEach((m) => {
        divHtml += showDivHtml(m);
    });
    const divHtmlContainer = document.getElementById("showData");
    divHtmlContainer.innerHtml = divHtml;
}

function showDivHtml(m) {
    return `
        <tr>
        <td>${m.name}</td>
        <td>${m.district}</td>
        <td>${m.level}</td>
        <td>${m.total_data}</td>
        </tr>
    `;
}
