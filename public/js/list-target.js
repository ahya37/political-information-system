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
    return `<div class="card shadow bg-white rounded mb-3">
                          <div class="card-body">
                            <div class="col-md-12 col-sm-12">
                                <div class="row">
                                    <div class="col-md-8 col-sm-8">
                                        <a
                                            class="nav-link-cs collapsed  "
                                            href="#referal"
                                            data-toggle="collapse"
                                            data-target="#referal${m.province_id}"
                                            style="color: #000000; text-decoration:none"
                                            >
                                            ${m.province} 
                                        </a>
                                    </div>
                                    <div class="col-md-4 col-sm-4">
                                        Target : ${m.target} 
                                    </div>
                                    
                                </div>
      
                                          <div class="collapse" id="referal${m.province_id}" aria-expanded="false">
                                             <div class="card-body">Name</div>
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
