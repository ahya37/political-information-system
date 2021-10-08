$("#referalData").DataTable({
    // processing: true,
    serverSide: true,
    ordering: true,
    ajax: {
        url: "/api/member/potensial/referal",
        method: "GET",
        cache: false,
        beforeSend: function () {
            BeforeSend("LoadReferal");
        },
        success: function (data) {
            showDataTableReferal(data);
        },
        complete: function () {
            Complete("LoadReferal");
        },
    },
});

function showDataTableReferal(data) {
    var html = "";
    for (var i in data) {
        html += `
                    <tr>
                    <td width="30%">
                    <a href="/admin/member/profile/${data[i].id}">
                            <img  class="rounded" width="40" src="/storage/${data[i].photo}">
                            ${data[i].name}
                        </a>
                    </td>
                    <td class="text-right">
                     <div class="badge badge-pill badge-success">
                     ${data[i].total}
                     <i class="fa fa-user ml-2"></i></div>
                    </td>
                    <td>${data[i].district}, ${data[i].regency}</td>
                    <td>
                        <div class="badge badge-pill badge-primary">
                        <i class="fa fa-phone"></i>
                        </div>
                        ${data[i].phone_number}
                        <br>
                        <div class="badge badge-pill badge-success"><i class="fa fa-whatsapp"></i>
                        </div>
                        ${data[i].whatsapp}
                    </td>
                    <td>
                    <div class="btn-group">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-sc-primary text-white dropdown-toggle mr-1 mb-1" type="button" data-toggle="dropdown">...</button>
                                    <div class="dropdown-menu">
                                         <a href='/admin/member/by_referal/${data[i].id}' class="dropdown-item">
                                                Detail
                                        </a> 
                                    </div>
                                </div>
                            </div>
                    </td>
                    </tr>
                `;
    }
    return $("#showReferalData").html(html);
}

$("#inputData").DataTable({
    // processing: true,
    serverSide: true,
    ordering: true,
    ajax: {
        url: "/api/member/potensial/input",
        method: "GET",
        cache: false,
        beforeSend: function () {
            BeforeSend("LoadInput");
        },
        success: function (data) {
            showDataTableInput(data);
        },
        complete: function () {
            Complete("LoadInput");
        },
    },
});

function showDataTableInput(data) {
    var html = "";
    for (var i in data) {
        html += `
                    <tr>
                    <td width="30%">
                    <a href="/admin/member/profile/${data[i].id}">
                            <img  class="rounded" width="40" src="/storage/${data[i].photo}">
                            ${data[i].name}
                        </a>
                    </td>
                    <td class="text-center">
                     <div class="badge badge-pill badge-success">
                     ${data[i].total}
                     <i class="fa fa-user ml-2"></i></div>
                    </td>
                    <td>${data[i].district}, ${data[i].regency}</td>
                    <td>
                        <div class="badge badge-pill badge-primary">
                        <i class="fa fa-phone"></i>
                        </div>
                        ${data[i].phone_number}
                        <br>
                        <div class="badge badge-pill badge-success"><i class="fa fa-whatsapp"></i>
                        </div>
                        ${data[i].whatsapp}
                    </td>
                    <td>
                    <div class="btn-group">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-sc-primary text-white dropdown-toggle mr-1 mb-1" type="button" data-toggle="dropdown">...</button>
                                    <div class="dropdown-menu">
                                         <a href='/admin/member/by_input/${data[i].id}' class="dropdown-item">
                                                Detail
                                        </a> 
                                    </div>
                                </div>
                            </div>
                    </td>
                    </tr>
                `;
    }
    return $("#showInputData").html(html);
}

function BeforeSend(idLoader) {
    $("#" + idLoader + "").removeClass("d-none");
}

function Complete(idLoader) {
    $("#" + idLoader + "").addClass("d-none");
}
