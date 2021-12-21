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
    return `<div class="card bg-white rounded mb-3">
                          <div class="card-body ">
                            <div class="col-md-12 col-sm-12">
                                <div class="row">
                                    <div class="col-md-6 col-sm-6">
                                        <a
                                            class="nav-link-cs"
                                            href="#referal"
                                            data-toggle="collapse"
                                            data-target="#referal${
                                                m.province_id
                                            }"
                                            style="color: #000000; text-decoration:none"
                                            >
                                            ${m.province} 
                                        </a>
                                    </div>
                                    <div class="col-md-4 col-sm-4">
                                        Target : ${m.target} 
                                    </div>
                                    
                                </div>
      
                                          <div class="collapse" id="referal${
                                              m.province_id
                                          }" aria-expanded="false">
                                          ${m.regencies.map(
                                              (reg) =>
                                                  `<div class="card-body">
                                                    <div class="col-md-12 col-sm-12">
                                                    <div class="row border-bottom">
                                                      <div class="col-md-7 col-sm-7">
                                                          <a  class="nav-link-cs " 
                                                              href="#referalregs"
                                                             
                                                              data-target="#referalregs${
                                                                  reg.id
                                                              }" 
                                                              style="color: #000000; text-decoration:none">
                                                              ${reg.name}
                                                          </a>
                                                      </div>
                                                      <div class="col-md-3 col-sm-3">
                                                         Target : ${reg.target}
                                                      </div>
                                                    </div>
                                                        <div class="" id="#referalregs${
                                                            reg.id
                                                        }" aria-expanded="false">
                                                        ${reg.districts.map(
                                                            (dist) =>
                                                                `
                                                                <div class="card-body">
                                                                     <div class="col-md-12 col-sm-12">
                                                                        <div class="row border-bottom">
                                                                            <div class="col-md-8 col-sm-8">
                                                                                <a  class="nav-link-cs " 
                                                                                    href="#referalreg" 
                                                                                    data-target="#referalreg${
                                                                                        dist.id
                                                                                    }" 
                                                                                    style="color: #000000; text-decoration:none">
                                                                                    KEC. ${
                                                                                        dist.name
                                                                                    }
                                                                                </a>
                                                                            </div>
                                                                            <div class="col-md-3 col-sm-3">
                                                                                    Target : ${
                                                                                        dist.target
                                                                                    }
                                                                            </div>
                                                                        </div>
                                                                        <div class=""  aria-expanded="false">
                                                                        ${dist.villages.map(
                                                                            (
                                                                                vill
                                                                            ) =>
                                                                                ` <div class="card-body shadow">
                                                                                    <div class="col-md-12 col-sm-12">
                                                                                        <div class="row border-bottom">
                                                                                            <div class="col-md-9 col-sm-9">
                                                                                                <a  class="nav-link-cs " 
                                                                                                    href="#referalreg" 
                                                                                                    data-target="#referalreg${vill.id}" 
                                                                                                    style="color: #000000; text-decoration:none">
                                                                                                    Ds. ${vill.name}
                                                                                                </a>
                                                                                            </div>
                                                                                            <div class="col-md-3 col-sm-3">
                                                                                                    Target : ${vill.target}
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                  </div>
                                                                                `
                                                                        )}
                                                                        </div>
                                                                     </div>
                                                                </div>
                                                                `
                                                        )}
                                                        </div>
                                                    </div>
                                                  </div>
                                                  `
                                          )}
                                          </div>
                                      </div>
                                  </div>
                              </div>
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
