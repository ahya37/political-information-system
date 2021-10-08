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
                    <td>${data[i].name}</td>
                    <td>${data[i].total}</td>
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
                    <td>${data[i].name}</td>
                    <td>${data[i].total}</td>
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
    return $("#showInputData").html(html);
}

function BeforeSend(idLoader) {
    $("#" + idLoader + "").removeClass("d-none");
}

function Complete(idLoader) {
    $("#" + idLoader + "").addClass("d-none");
}
