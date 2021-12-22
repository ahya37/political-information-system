async function getListTarget() {
    BeforeSend("Loadachievment");
    try {
        const target = await getListDataTarget();
        const dataTarget = target.data;
        listTargetUI(dataTarget);
    } catch (err) {}
    Complete("Loadachievment");
}

getListTarget();

function getListDataTarget() {
    return fetch(`/api/list/target`)
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

function listTargetUI(dataTarget) {
    let divHtml = "";
    dataTarget.forEach((m) => {
        divHtml += showDivHtml(m);
    });

    const divHtmlContainer = document.getElementById("showData");
    divHtmlContainer.innerHTML = divHtml;
}

function showDivHtml(m) {
    return `
                          <tr>
                            <td colspan="4">${m.province}</td><td>${
        m.target
    }</td>
                          </tr>
                          ${m.regencies.map(
                              (reg) =>
                                  `
                                <tr>
                                    <td></td><td colspan="3">${
                                        reg.name
                                    }</td><td>${reg.target}</td>
                                </tr>
                                ${reg.districts.map(
                                    (dist) =>
                                        `
                                        <tr>
                                            <td></td><td></td><td colspan="2">KECAMATAN ${
                                                dist.name
                                            }</td><td>${dist.target}</td>
                                        </tr>
                                        ${dist.villages.map(
                                            (vill) =>
                                                `<tr>
                                                    <td></td><td></td><td></td><td>DESA ${vill.name}</td><td>${vill.target}</td>
                                                </tr>
                                                `
                                        )}
                                    `
                                )}
                                `
                          )}
            `;
}

function BeforeSend(idLoader) {
    $("#" + idLoader + "").removeClass("d-none");
}

function Complete(idLoader) {
    $("#" + idLoader + "").addClass("d-none");
}

function formatRupiah(angka, prefix) {
    var number_string = angka.replace(/[^,\d]/g, "").toString(),
        split = number_string.split(","),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    // tambahkan titik jika yang di input sudah menjadi angka ribuan
    if (ribuan) {
        separator = sisa ? "." : "";
        rupiah += separator + ribuan.join(".");
    }

    rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
    return prefix == undefined ? rupiah : rupiah ? "Rp. " + rupiah : "";
}
