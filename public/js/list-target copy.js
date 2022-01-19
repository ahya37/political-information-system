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
function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, ",");
}

function number_format(number, decimals, decPoint, thousandsSep) {
    number = (number + "").replace(/[^0-9+\-Ee.]/g, "");
    var n = !isFinite(+number) ? 0 : +number;
    var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals);
    var sep = typeof thousandsSep === "undefined" ? "." : thousandsSep;
    var dec = typeof decPoint === "undefined" ? "." : decPoint;
    var s = "";

    var toFixedFix = function (n, prec) {
        var k = Math.pow(10, prec);
        return "" + (Math.round(n * k) / k).toFixed(prec);
    };

    // @todo: for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : "" + Math.round(n)).split(".");
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || "").length < prec) {
        s[1] = s[1] || "";
        s[1] += new Array(prec - s[1].length + 1).join("0");
    }

    return s.join(dec);
}

function showDivHtml(m) {
    return `
                          <tr class="table-primary">
                            <td colspan="4">${
                                m.province
                            }</td><td>${number_format(m.target)}</td>
                          </tr>
                          ${m.regencies.map(
                              (reg) =>
                                  `
                                <tr class="table-info">
                                    <td></td><td colspan="3">${
                                        reg.name
                                    }</td><td>${number_format(reg.target)}</td>
                                </tr>
                                ${reg.districts.map(
                                    (dist) =>
                                        `
                                        <tr class="table-success">
                                            <td></td><td></td><td colspan="2">KECAMATAN ${
                                                dist.name
                                            }</td><td>${number_format(
                                            dist.target
                                        )}</td>
                                        </tr>
                                        ${dist.villages.map(
                                            (vill) =>
                                                `<tr class="table-secondary">
                                                    <td></td><td></td><td></td><td>DESA ${
                                                        vill.name
                                                    }</td><td>${number_format(
                                                    vill.target
                                                )}</td>
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
