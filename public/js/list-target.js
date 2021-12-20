async function getListTarget() {
    BeforeSend("Loadachievment");
    try {
        const target = await getListDataTarget();
        const dataTarget = target.data;
        console.log("data:", dataTarget);
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
    return `<div class="card shadow bg-white rounded mb-3">
                          <div class="card-body">
                            <div class="col-md-12 col-sm-12">
                                <div class="row">
                                    <div class="col-md-8 col-sm-8">
                                        <a
                                            class="nav-link-cs collapsed  "
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
                                        Target : [On Progress]
                                    </div>
                                    
                                </div>
      
                                          <div class="collapse" id="referal${
                                              m.province_id
                                          }" aria-expanded="false">
                                          ${m.regencies.map(
                                              (reg) =>
                                                  `<div class="card-body shadow">
                                                    <div class="col-md-12 col-sm-12">
                                                    <div class="row">
                                                      <div class="col-md-9 col-sm-9">
                                                          <a  class="nav-link-cs collapsed" 
                                                              href="#referalreg" data-toggle="collapse"
                                                              data-target="#referalreg${reg.regency_id}" 
                                                              style="color: #000000; text-decoration:none">
                                                              ${reg.name}
                                                          </a>
                                                      </div>
                                                      <div class="col-md-3 col-sm-3">
                                                         Target : [On Progress]
                                                      </div>
                                                    </div>
                                                        <div class="collapse" 
                                                            id="#referalreg${reg.regency_id}" 
                                                            aria-expanded="false">
                                                            OK
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
