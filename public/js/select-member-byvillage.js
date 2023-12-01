let selectListAreaMember = $(".selectListAreaMember").val();
let selectDistrictIdMember = $(".selectDistrictIdMember").val();
let selectVillageIdMember = $(".selectVillageIdMember").val();
let selectRtMember = $(".selectRtMember").val();
let selectTpsMember = $(".selectTpsMember").val();

// DESA
$(".selectVillageIdMember").change(async function () {
    selectVillageIdMember = $(".selectVillageIdMember").val();

    if (selectVillageIdMember !== "") {
        const dataRT = await getListRTMember(selectVillageIdMember);
        console.log(dataRT)

        selectListAreaMember = $(".selectListAreaMember").val();
        selectDistrictIdMember = $(".selectDistrictIdMember").val();
        selectVillageIdMember = $(".selectVillageIdMember").val();
        $(".selectRtMember").append("<option value=''>-Pilih RT-</option>");
        getListRTUiMember(dataRT);
        
        const dataTpsMember = await getListTpsMember(selectVillageIdMember);
        $(".selectTpsMember").append("<option value=''>-Pilih TPS-</option>");
        getListTpsUiMember(dataTpsMember);

        $(".selectRtMember").val("");

        initialSelect2Member(selectVillageIdMember, selectRtMember);

    } else {

        selectListAreaMember = $(".selectListAreaMember").val();
        selectDistrictIdMember = $(".selectDistrictIdMember").val();
        selectVillageIdMember = $(".selectVillageIdMember").val();
        selectRtMember = $(".selectRtMember").val();

        $(".selectRtMember").val("");

    }
});

// RT
$(".selectRtMember").change(async function () {
    selectRtMember = $(".selectRtMember").val();

    if (selectRtMember !== "") {
        selectListAreaMember = $(".selectListAreaMember").val();
        selectDistrictIdMember = $(".selectDistrictIdMember").val();
        selectVillageIdMember = $(".selectVillageIdMember").val();

        initialSelect2Member(selectVillageIdMember, selectRtMember);

        
    } else {
        selectListAreaMember = $(".selectListAreaMember").val();
        selectDistrictIdMember = $(".selectDistrictIdMember").val();
        selectVillageIdMember = $(".selectVillageIdMember").val();
        selectRtMember = $(".selectRtMember").val();

        initialSelect2Member(selectVillageIdMember, selectRtMember);



    }
});

// GET data TPS
async function getListTpsMember(selectVillageIdMember){
    $(".selectTpsMember").append("<option value=''>Loading..</option>");
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    const response = await fetch(`/api/gettpsbyvillage`, {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "appliacation/json",
        },
        body: JSON.stringify({
            token: CSRF_TOKEN,
            village_id: selectVillageIdMember,
        }),
    });
    $(".selectTpsMember").empty();
    return await response.json();
}

function getListTpsUiMember(dataTps) {
    let divTps = "";
    dataTps.forEach((m) => {
        divTps += showDivHtmlTps(m);
    });
    const divTpsContainer = $(".selectTpsMember");
    divTpsContainer.append(divTps);
}
function showDivHtmlTps(m) {
    return `<option value="${m.id}">${m.tps_number}</option>`;
}

async function getListVillageMember(selectDistrictId) {
    $(".selectVillageIdMember").append("<option value=''>Loading..</option>");
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    const response = await fetch(`/api/getlistvillagetdapil`, {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "appliacation/json",
        },
        body: JSON.stringify({
            token: CSRF_TOKEN,
            district_id: selectDistrictId,
        }),
    });
    $(".selectVillageIdMember").empty();
    return await response.json();
}
function getListVillageUiMember(dataVillages) {
    let divVillage = "";
    dataVillages.forEach((m) => {
        divVillage += showDivHtmlVillageMember(m);
    });
    const divVillageContainer = $(".selectVillageIdMember");
    divVillageContainer.append(divVillage);
}
function showDivHtmlVillageMember(m) {
    return `<option value="${m.id}">${m.name}</option>`;
}

// GET data RT
async function getListRTMember(selectVillageIdMember) {
    $(".selectRtMember").append("<option value=''>Loading..</option>");
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    const response = await fetch(`/api/getrtbyvillage`, {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "appliacation/json",
        },
        body: JSON.stringify({
            token: CSRF_TOKEN,
            village_id: selectVillageIdMember,
        }),
    });
    $(".selectRtMember").empty();
    return await response.json();
}
function getListRTUiMember(dataRT) {
    let divRT = "";
    dataRT.forEach((m) => {
        divRT += showDivHtmlRTMember(m);
    });
    const divRTContainer = $(".selectRtMember");
    divRTContainer.append(divRT);
}
function showDivHtmlRTMember(m) {
    return `<option value="${m.rt}">${m.rt}</option>`;
}


$("#exampleModal").on("show.bs.modal", function (event) {
    var button = $(event.relatedTarget);
    var recipient = button.data("whatever");
    var modal = $(this);
    modal.find('.modal-body input[name="pidx"]').val(recipient);
});

function initialSelect2Member(selectVillageIdMember, selectRtMember) {
    // GET ANGGOTA BERDASARKAN SORTIR

    let URL = selectRtMember === null ? `/api/getdatamember/${selectVillageIdMember}` : `/api/getdatamemberrt/${selectVillageIdMember}/${selectRtMember}`
    $(".nik").select2({
        theme: "bootstrap4",
        width: $(this).data("width")
            ? $(this).data("width")
            : $(this).hasClass("w-100")
                ? "100%"
                : "style",
        placeholder: "Pilih Anggota",
        allowClear: Boolean($(this).data("allow-clear")),
        ajax: {
            dataType: "json",
            url: URL,
            method: 'GET',
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: `${item.nik}-${item.name}`,
                            id: item.id,
                        };
                    }),
                };
            },
        },
    });

}