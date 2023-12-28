let selectListArea = $("#selectListArea").val();
let selectDistrictId = $("#selectDistrictId").val();
let selectVillageId = $("#selectVillageId").val();
let selectRT = $("#selectRt").val();
let selectTps = $("#selectTps").val();
$(".pengurus").hide();
$(".tpsnotexist").hide();
$(".tpsexist").hide();
// KABKOT , langsung get dapil by kab lebak

async function initialGetAnggotaCover(
    selectListAreaId,
    selectDistrictId,
    selectVillageId,
    selectRT
) {

    return new Promise((resolve, reject) => {
        const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
        $.ajax({
            url: "/api/datacoverkortps",
            method: "POST",
            cache: false,
            data: {
                _token: CSRF_TOKEN,
                dapil: selectListAreaId,
                district: selectDistrictId,
                village: selectVillageId,
                rt: selectRT,
            },
            beforeSend: function () {
            //    $("#pengurusId").show();

                $("#anggota")
                    .append(`<div class="spinner-grow" style="width: 1rem; height: 1rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>`);
                $("#tercover")
                    .append(`<div class="spinner-grow" style="width: 1rem; height: 1rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>`);
                $("#blmtercover")
                    .append(`<div class="spinner-grow" style="width: 1rem; height: 1rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>`);

            $("#loaddataPengurusTable")
                    .append(`<div class="spinner-grow" style="width: 1rem;height: 1rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>`);
            $("#loadlisttpsnotexists")
                    .append(`<div class="spinner-grow" style="width: 1rem; height: 1rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>`);
            $("#loadlisttpsexists")
                    .append(`<div class="spinner-grow" style="width: 1rem; height: 1rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>`);
            $("#targetanggota")
                    .append(`<div class="spinner-grow" style="width: 1rem; height: 1rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>`);
            $("#jmldpt")
                    .append(`<div class="spinner-grow" style="width: 1rem; height: 1rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>`);
                $("#kortpsterisi")
                        .append(`<div class="spinner-grow" style="width: 1rem; height: 1rem;" role="status">
                    <span class="sr-only">Loading...</span>
                </div>`);
                    $("#kurangtpsterisi")
                        .append(`<div class="spinner-grow" style="width: 1rem; height: 1rem;" role="status">
                    <span class="sr-only">Loading...</span>
                </div>`);
                    $("#targetkortps")
                        .append(`<div class="spinner-grow" style="width: 1rem; height: 1rem;" role="status">
                    <span class="sr-only">Loading...</span>
                </div>`);
                    $("#jmltps")
                        .append(`<div class="spinner-grow" style="width: 1rem; height: 1rem;" role="status">
                    <span class="sr-only">Loading...</span>
                </div>`);
            

            },
            success: function () {
                $("#anggota").empty();
                $("#targetanggota").empty();
                $("#jmldpt").empty();
                $("#tercover").empty();
                $("#blmtercover").empty();
                $("#pengKetua").empty();
                $("#pengBendahara").empty();
                $("#loadlisttpsnotexists").empty();
                $("#loadlisttpsexists").empty();
                $("#loaddataPengurusTable").empty();
            },
            complete: function (data) {
                return data;
            },
        })
            .done(resolve)
            .fail(reject);
    });
}

let blmTerCover = "";
async function initialGetAnggotaCoverFirst() {
    $("#targetanggota").empty();
    $("#jmldpt").empty();
    $("#anggota").empty();
    $("#tercover").empty();
    $("#kortpsterisi").empty();
    $("#kurangtpsterisi").empty();
    $("#targetkortps").empty();
    const dataCover = await initialGetAnggotaCover(
        selectListArea,
        selectDistrictId,
        selectVillageId,
        selectRT
    );

    $("#jmldpt").text(dataCover.jml_dpt);
    $("#targetanggota").text(dataCover.target_anggota);
    $("#anggota").text(`${numberWithDot(dataCover.data.anggota)}`);
   $("#tercover").text(`${numberWithDot(dataCover.data.tercover)}`);

    $("#blmtercover").empty();
    // blmTerCover =
    //     parseInt(dataCover.data.anggota) - parseInt(dataCover.data.tercover);
    blmTerCover    = dataCover.data.fix_anggota_belum_tercover;

    $("#blmtercover").text(`${numberWithDot(blmTerCover)}`);


    $("#jmltps").text(`${numberWithDot(dataCover.data.tps)}`);
    $("#targetkortps").text(`${numberWithDot(dataCover.target_kortps)}`);
    $("#kortpsterisi").text(`${numberWithDot(dataCover.data.kortps_terisi)}`);
    $("#kurangtpsterisi").text(
        ` ${numberWithDot(dataCover.kurang_kortps)}`
    );
    
}

initialGetAnggotaCoverFirst();


// DAPIL
$("#selectListArea").change(async function () {
    selectListArea = $("#selectListArea").val();
    $(".tpsnotexist").hide();


    if (selectListArea !== "") {
        const listDistricts = await getListDistrict(selectListArea);
        $("#selectDistrictId").empty();
        $("#selectVillageId").empty();
        $("#keterangan").empty();
        $("#kortpsterisi").empty();
        $("#kurangtpsterisi").empty();
        $("#targetkortps").empty();
        $("#jmltps").empty();

        $("#selectDistrictId").show();
        $("#selectDistrictId").append(
            "<option value=''>-Pilih Kecamatan-</option>"
        );
        getListDistrictUi(listDistricts);
        province = $("#province").val();
        selectListArea = $("#selectListArea").val();
        selectDistrictId = $("#selectDistrictId").val();
        selectVillageId = $("#selectVillageId").val();
        selectRT = $("#selectRt").val();
        geLocationDapil(selectListArea);

        $("#reqdapil").val(selectListArea);
        $("#reqdistrict").val("");
        $("#anggota").empty();
        $("#jmldpt").empty();
        $("#targetanggota").empty();
        $("#tercover").empty();
        $("#blmtercover").empty();

        const dataCover = await initialGetAnggotaCover(
            selectListArea,
            selectDistrictId,
            selectVillageId,
            selectRT
        );
        $("#jmldpt").text(`${dataCover.jml_dpt}`);
        $("#targetanggota").text(`${dataCover.target_anggota}`); 
        $("#anggota").text(`${numberWithDot(dataCover.data.anggota)}`);
        $("#tercover").text(`${numberWithDot(dataCover.data.tercover)}`);

        blmTerCover    = dataCover.data.fix_anggota_belum_tercover;
        $("#blmtercover").text(`${numberWithDot(blmTerCover)}`);

        // jumlah kortps dan kurangnya

        $("#jmltps").text(` ${numberWithDot(dataCover.data.tps)}`);
        $("#targetkortps").text(` ${numberWithDot(dataCover.target_kortps)}`);
        $("#kortpsterisi").text(` ${numberWithDot(dataCover.data.kortps_terisi)}`);
        $("#kurangtpsterisi").text(
            ` ${numberWithDot(dataCover.kurang_kortps)}`
        );

        table.ajax.reload(null, false);
    } else {
        $("#selectDistrictId").empty();
        $("#selectVillageId").empty();
        province = $("#province").val();
        selectListArea = $("#selectListArea").val();
        selectDistrictId = $("#selectDistrictId").val();
        selectVillageId = $("#selectVillageId").val();
        $("#reqdapil").val("");
        $("#reqdistrict").val("");
        $("#reqvillage").val("");

        $("#anggota").empty();
        $("#targetanggota").empty();
        $("#jmldpt").empty();
        $("#tercover").empty();
        $("#keterangan").text("Kor TPS");
        $("#blmtercover").empty();
        $("#kortpsterisi").empty();
        $("#kurangtpsterisi").empty();
        $("#targetkortps").empty();
        $("#jmltps").empty();

        const dataCover = await initialGetAnggotaCover(
            selectListArea,
            selectDistrictId,
            selectVillageId,
            selectRT
        );
        $("#jmldpt").text(`${dataCover.jml_dpt}`);
        $("#targetanggota").text(`${dataCover.target_anggota}`); 
        $("#anggota").text(`${numberWithDot(dataCover.data.anggota)}`);
        $("#tercover").text(`${numberWithDot(dataCover.data.tercover)}`);

        blmTerCover    = dataCover.data.fix_anggota_belum_tercover;
        $("#blmtercover").text(`${numberWithDot(blmTerCover)}`);
 
        // jumlah kortps dan kurangnya
       
        $("#jmltps").text(` ${numberWithDot(dataCover.data.tps)}`);
        $("#targetkortps").text(` ${numberWithDot(dataCover.target_kortps)}`);
        $("#kortpsterisi").text(` ${numberWithDot(dataCover.data.kortps_terisi)}`);
        $("#kurangtpsterisi").text(
            ` ${numberWithDot(dataCover.kurang_kortps)}`
        );
        

        table.ajax.reload(null, false);
    }
});

// KECAMATAN
$("#selectDistrictId").change(async function () {
    selectDistrictId = $("#selectDistrictId").val();
    $(".pengurus").show();
    $(".tpsnotexist").hide();
    $("#dataPengurusTable").empty();
    
    if (selectDistrictId !== "") {
        const dataVillages = await getListVillage(selectDistrictId);
        $("#selectVillageId").empty();
        $("#selectVillageId").show();
        $("#selectVillageId").append("<option value=''>-Pilih Desa-</option>");
        getListVillageUi(dataVillages);

        province = $("#province").val();
        selectListArea = $("#selectListArea").val();
        selectDistrictId = $("#selectDistrictId").val();
        selectVillageId = $("#selectVillageId").val();
        $("#keterangan").empty();
        geLocationDistrict(selectDistrictId);

        $("#reqprovince").val(province);
        $("#reqdapil").val(selectListArea);
        $("#reqdistrict").val(selectDistrictId);
        $("#reqvillage").val("");

        $("#anggota").empty();
        $("#targetanggota").empty();
        $("#jmldpt").empty();
        $("#tercover").empty();
        $("#blmtercover").empty();
        $("#kortpsterisi").empty();
        $("#kurangtpsterisi").empty();
        $("#targetkortps").empty();
        $("#jmltps").empty();

        const dataCover = await initialGetAnggotaCover(
            selectListArea,
            selectDistrictId,
            selectVillageId,
            selectRT
        );
        $("#dataPengurusTable").show();
        getPengurusUi(dataCover.pengurus.data_pengurus);

        
        $("#jmldpt").text(`${dataCover.jml_dpt}`);
        $("#targetanggota").text(`${dataCover.target_anggota}`);
        $("#anggota").text(`${numberWithDot(dataCover.data.anggota)}`);
        $("#tercover").text(`${numberWithDot(dataCover.data.tercover)}`);

        // blmTerCover =
        //     parseInt(dataCover.data.anggota) -
        //     parseInt(dataCover.data.tercover);
        blmTerCover    = dataCover.data.fix_anggota_belum_tercover;

        $("#blmtercover").text(`${numberWithDot(blmTerCover)}`);

        // jumlah kortps dan kurangnya
       
        $("#jmltps").text(` ${numberWithDot(dataCover.data.tps)}`);
        $("#targetkortps").text(` ${numberWithDot(dataCover.target_kortps)}`);
        $("#kortpsterisi").text(` ${numberWithDot(dataCover.data.kortps_terisi)}`);
        $("#kurangtpsterisi").text(
            ` ${numberWithDot(dataCover.kurang_kortps)}`
        );

        table.ajax.reload(null, false);
    } else {
    $(".pengurus").hide();
        $("#selectVillageId").empty();
        province = $("#province").val();
        selectListArea = $("#selectListArea").val();
        selectDistrictId = $("#selectDistrictId").val();
        selectVillageId = $("#selectVillageId").val();

        $("#reqdistrict").val("");
        $("#reqvillage").val("");
        $("#keterangan").empty();
        geLocationDapil(selectListArea);

        $("#anggota").empty();
        $("#targetanggota").empty();
        $("#jmldpt").empty();
        $("#tercover").empty();
        $("#blmtercover").empty();
        $("#kortpsterisi").empty();
        $("#kurangtpsterisi").empty();
        $("#targetkortps").empty();
        $("#jmltps").empty();

        const dataCover = await initialGetAnggotaCover(
            selectListArea,
            selectDistrictId,
            selectVillageId,
            selectRT
        );
        $("#jmldpt").text(`${dataCover.jml_dpt}`);
        $("#targetanggota").text(`${dataCover.target_anggota}`);
        $("#anggota").text(`${numberWithDot(dataCover.data.anggota)}`);
        $("#tercover").text(`${numberWithDot(dataCover.data.tercover)}`);

        blmTerCover    = dataCover.data.fix_anggota_belum_tercover;

        $("#blmtercover").text(`${numberWithDot(blmTerCover)}`);

        // jumlah kortps dan kurangnya
       
        $("#jmltps").text(` ${numberWithDot(dataCover.data.tps)}`);
        $("#targetkortps").text(` ${numberWithDot(dataCover.target_kortps)}`);
        $("#kortpsterisi").text(` ${numberWithDot(dataCover.data.kortps_terisi)}`);
        $("#kurangtpsterisi").text(
            ` ${numberWithDot(dataCover.kurang_kortps)}`
        );
    
       

        table.ajax.reload(null, false);
    }
});

function getPengurusUi(responseData) {
    let divHtmlPengurus = "";
    responseData.forEach((m) => {
        divHtmlPengurus += showDivHtmlPengurus(m);
    });
    const divHtmlPengurusContainer = $("#dataPengurusTable");
    divHtmlPengurusContainer.append(divHtmlPengurus);
}

function showDivHtmlPengurus(m) {
    return `
            <tr>
                <td>
                    <img src='/storage/${m.photo}' width='40px' class='rounded mb-2'>
                    ${m.name}
                </td>
                <td>${m.title}</td>
                <td align="center">${m.referal}</td>
                <td>${m.address},DS.${m.village}, KEC.${m.district}</td>
    `;
}

// DESA
$("#selectVillageId").change(async function () {
    selectVillageId = $("#selectVillageId").val();
    $("#dataPengurusTable").empty();

    if (selectVillageId !== "") {
        const dataRT = await getListRT(selectVillageId);

        selectListArea = $("#selectListArea").val();
        selectDistrictId = $("#selectDistrictId").val();
        selectVillageId = $("#selectVillageId").val();
        $("#selectRt").append("<option value=''>-Pilih RT-</option>");
        getListRTUi(dataRT);
        
        const dataTps = await getListTps(selectVillageId);
        $("#selectTps").append("<option value=''>-Pilih TPS-</option>");
        getListTpsUi(dataTps);

        table.ajax.reload(null, false);

       
        $("#reqdapil").val(selectListArea);
        $("#reqdistrict").val(selectDistrictId);
        $("#reqvillage").val(selectVillageId);
        $("#selectRt").val("");
        $("#keterangan").empty();
        geLocationVillage(selectVillageId);

        $("#anggota").empty();
        $("#targetanggota").empty();
        $("#jmldpt").empty();
        $("#tercover").empty();
        $("#blmtercover").empty();
        $("#kortpsterisi").empty();
        $("#kurangtpsterisi").empty();
        $("#kortpsterisi").empty();
        $("#kurangtpsterisi").empty();
        $("#targetkortps").empty();
        $("#jmltps").empty();
        $("#listtpsnotexists").empty();
        $("#jmltpsExistsAnggota").empty();
        $("#datalisttpsexists").empty();
        $("#jmltpsExistsKortps").empty();
        $("#jmlHasilSuara").empty();
        $("#jmlSelisih").empty();


        
        $('.tpsexist').show();
        $('.tpsnotexist').show();
        $("#dataPengurusTable").show();

        const dataCover = await initialGetAnggotaCover(
            selectListArea,
            selectDistrictId,
            selectVillageId,
            selectRT
        );


        getPengurusUi(dataCover.pengurus.data_pengurus)
        getLitTpsNotExistUi(dataCover.tpsnotexists);
        getLitTpsExistUi(dataCover.tpsExists);

        $('.pengurus').show();
        $("#jmltpsExistsAnggota").show();
        $("#jmltpsExistsKortps").show();
        $("#jmlHasilSuara").show();
        $("#jmlSelisih").show();


        $("#datalisttpsexists").show();

        
        $("#jmldpt").text(`${dataCover.jml_dpt}`);
        $("#targetanggota").text(`${dataCover.target_anggota}`);
        $("#anggota").text(`${numberWithDot(dataCover.data.anggota)}`);
        $("#tercover").text(`${numberWithDot(dataCover.data.tercover)}`);
        $("#jmltpsExistsKortps").append(`<b>${dataCover.jml_kortps} Orang</b>`);
        $("#jmltpsExistsAnggota").append(`<b>${dataCover.jmltpsExists_anggota} Orang</b>`);
        $("#jmlHasilSuara").append(`<b>${dataCover.jml_hasil_suara} Orang</b>`);
        $("#jmlSelisih").append(`<b>${dataCover.jml_selisih} Orang</b>`);

        // blmTerCover = parseInt(dataCover.data.anggota) - parseInt(dataCover.data.tercover);
        blmTerCover    = dataCover.data.fix_anggota_belum_tercover;
        $("#blmtercover").text(`${numberWithDot(blmTerCover)}`);

        // jumlah kortps dan kurangnya
       
        $("#jmltps").text(` ${numberWithDot(dataCover.data.tps)}`);
        $("#targetkortps").text(` ${numberWithDot(dataCover.target_kortps)}`);
        $("#kortpsterisi").text(` ${numberWithDot(dataCover.data.kortps_terisi)}`);
        $("#kurangtpsterisi").text(
            ` ${numberWithDot(dataCover.kurang_kortps)}`
        );

    } else {
       $(".tpsnotexist").hide();
       $(".tpsexist").hide();

        selectListArea = $("#selectListArea").val();
        selectDistrictId = $("#selectDistrictId").val();
        selectVillageId = $("#selectVillageId").val();
        selectRT = $("#selectRT").val();

        table.ajax.reload(null, false);

        $("#reqdapil").val(selectListArea);
        $("#reqdistrict").val(selectDistrictId);
        $("#reqvillage").val("");
        $("#selectRt").val("");
        $("#keterangan").empty();
        geLocationDistrict(selectDistrictId);

        $("#anggota").empty();
        $("#targetanggota").empty();
        $("#jmldpt").empty();
        $("#tercover").empty();
        $("#blmtercover").empty();
        $("#kortpsterisi").empty();
        $("#kurangtpsterisi").empty();
        $("#targetkortps").empty();
        $("#jmltps").empty();

        const dataCover = await initialGetAnggotaCover(
            selectListArea,
            selectDistrictId,
            selectVillageId,
            selectRT
        );

        getPengurusUi(dataCover.pengurus.data_pengurus);

        $("#jmldpt").text(`${dataCover.jml_dpt}`);
        $("#targetanggota").text(`${dataCover.target_anggota}`);
        $("#anggota").text(`${numberWithDot(dataCover.data.anggota)}`);
        $("#tercover").text(`${numberWithDot(dataCover.data.tercover)}`);

        blmTerCover    = dataCover.data.fix_anggota_belum_tercover;

        $("#blmtercover").text(`${numberWithDot(blmTerCover)}`);

        // jumlah kortps dan kurangnya
        

        $("#jmltps").text(` ${numberWithDot(dataCover.data.tps)}`);
        $("#targetkortps").text(` ${numberWithDot(dataCover.target_kortps)}`);
        $("#kortpsterisi").text(` ${numberWithDot(dataCover.data.kortps_terisi)}`);
        $("#kurangtpsterisi").text(
            ` ${numberWithDot(dataCover.kurang_kortps)}`
        );
    }
});

// RT
$("#selectRt").change(async function () {
    selectRT = $("#selectRt").val();

    if (selectRT !== "") {
        selectListArea = $("#selectListArea").val();
        selectDistrictId = $("#selectDistrictId").val();
        selectVillageId = $("#selectVillageId").val();
        table.ajax.reload(null, false);

        $("#reqdapil").val(selectListArea);
        $("#reqdistrict").val(selectDistrictId);
        $("#reqvillage").val(selectVillageId);
        $("#keterangan").empty();
        geLocationVillageWithRt(selectVillageId, selectRT);

        $("#anggota").empty();
        $("#targetanggota").empty();
        $("#jmldpt").empty();
        $("#tercover").empty();
        $("#blmtercover").empty();

        $("#kortpsterisi").empty();
        $("#kurangtpsterisi").empty();
        $("#targetkortps").empty();
        $("#jmltps").empty();


        const dataCover = await initialGetAnggotaCover(
            selectListArea,
            selectDistrictId,
            selectVillageId,
            selectRT
        );
        $("#jmldpt").text(`${dataCover.jml_dpt}`);
        $("#targetanggota").text(`${dataCover.target_anggota}`);
        $("#anggota").text(`${numberWithDot(dataCover.data.anggota)}`);
        $("#tercover").text(`${numberWithDot(dataCover.data.tercover)}`);

       
        $('.pengurus').hide();

       blmTerCover    = dataCover.data.fix_anggota_belum_tercover;
        $("#blmtercover").text(`${numberWithDot(blmTerCover)}`);

        $("#jmltps").text(` ${numberWithDot(dataCover.data.tps)}`);
        $("#targetkortps").text(` ${numberWithDot(dataCover.target_kortps)}`);
        $("#kortpsterisi").text(` ${numberWithDot(dataCover.data.kortps_terisi)}`);
        $("#kurangtpsterisi").text(
            ` ${numberWithDot(dataCover.kurang_kortps)}`
        );
        
    } else {
        selectListArea = $("#selectListArea").val();
        selectDistrictId = $("#selectDistrictId").val();
        selectVillageId = $("#selectVillageId").val();
        selectRT = $("#selectRt").val();

        table.ajax.reload(null, false);

        $("#reqdapil").val(selectListArea);
        $("#reqdistrict").val(selectDistrictId);
        $("#reqvillage").val("");
        $("#keterangan").empty();
        geLocationVillage(selectVillageId);

        $("#anggota").empty();
        $("#targetanggota").empty();
        $("#jmldpt").empty();
        $("#tercover").empty();
        $("#blmtercover").empty();
        $("#kortpsterisi").empty();
        $("#kurangtpsterisi").empty();
        $("#targetkortps").empty();
        $("#jmltps").empty();

        const dataCover = await initialGetAnggotaCover(
            selectListArea,
            selectDistrictId,
            selectVillageId,
            selectRT
        );

        $("#jmldpt").text(`${dataCover.jml_dpt}`);
        $("#targetanggota").text(`${dataCover.target_anggota}`);
        $("#anggota").text(`${numberWithDot(dataCover.data.anggota)}`);
        $("#tercover").text(`${numberWithDot(dataCover.data.tercover)}`);
        
        $('.pengurus').show();

        blmTerCover   = dataCover.data.fix_anggota_belum_tercover;
        $("#blmtercover").text(`${numberWithDot(blmTerCover)}`);

        $("#jmltps").text(` ${numberWithDot(dataCover.data.tps)}`);
        $("#targetkortps").text(` ${numberWithDot(dataCover.target_kortps)}`);
        $("#kortpsterisi").text(` ${numberWithDot(dataCover.data.kortps_terisi)}`);
        $("#kurangtpsterisi").text(
            ` ${numberWithDot(dataCover.kurang_kortps)}`
        );
    } 
});

// TPS
$("#selectTps").change(async function () {
    selectTps = $("#selectTps").val();

    if (selectTps !== "") {
        selectListArea = $("#selectListArea").val();
        selectDistrictId = $("#selectDistrictId").val();
        selectVillageId = $("#selectVillageId").val();
        selectTps = $("#selectTps").val();
        table.ajax.reload(null, false);

        $("#reqdapil").val(selectListArea);
        $("#reqdistrict").val(selectDistrictId);
        $("#reqvillage").val(selectVillageId);
        $("#keterangan").empty();
        geLocationVillageWithRt(selectVillageId, selectRT);

        $("#anggota").empty();
        $("#jmldpt").empty();
        $("#targetanggota").empty();
        $("#tercover").empty();
        $("#blmtercover").empty();

        const dataCover = await initialGetAnggotaCover(
            selectListArea,
            selectDistrictId,
            selectVillageId,
            selectRT
        );
        $("#jmldpt").text(`${dataCover.jml_dpt}`);
        $("#anggota").text(`${numberWithDot(dataCover.data.anggota)}`);
        $("#tercover").text(`${numberWithDot(dataCover.data.tercover)}`);

        $('.pengurus').hide();

        blmTerCover   = dataCover.data.fix_anggota_belum_tercover;

        $("#blmtercover").text(`${numberWithDot(blmTerCover)}`);
    } else {
        selectListArea = $("#selectListArea").val();
        selectDistrictId = $("#selectDistrictId").val();
        selectVillageId = $("#selectVillageId").val();
        selectRT = $("#selectRt").val();
        selectTps = $("#selectTps").val();

        table.ajax.reload(null, false);

        $("#reqdapil").val(selectListArea);
        $("#reqdistrict").val(selectDistrictId);
        $("#reqvillage").val("");
        $("#keterangan").empty();
        geLocationVillage(selectVillageId);

        $("#anggota").empty();
        $("#jmldpt").empty();
        $("#targetanggota").empty();
        $("#tercover").empty();
        $("#blmtercover").empty();
        $("#kortpsterisi").empty();
        $("#kurangtpsterisi").empty();
        $("#targetkortps").empty();
        $("#jmltps").empty();

        const dataCover = await initialGetAnggotaCover(
            selectListArea,
            selectDistrictId,
            selectVillageId,
            selectRT
        );
        $("#jmldpt").text(`${dataCover.jml_dpt}`);
        $("#anggota").text(`${numberWithDot(dataCover.data.anggota)}`);
        $("#tercover").text(`${numberWithDot(dataCover.data.tercover)}`);
        
        $('.pengurus').show();

        blmTerCover   = dataCover.data.fix_anggota_belum_tercover;
        $("#blmtercover").text(`${numberWithDot(blmTerCover)}`);
    }
});

async function getDapilRegency(province) {
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    const response = await fetch(`/api/dapilbyprovinceid/${province}`);
    return await response.json();
}

function getDapilRegencyUi(responseData) {
    let divHtmldapil = "";
    responseData.forEach((m) => {
        divHtmldapil += showDivHtmlDapil(m);
    });
    const divHtmldapilContainer = $("#selectArea");
    divHtmldapilContainer.append(divHtmldapil);
}

function showDivHtmlDapil(m) {
    return `<option value="${m.id}">${m.name}</option>`;
}

async function getDapilNames(regencyId) {
    $("#selectListArea").append("<option value=''>Loading..</option>");
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    return await fetch(`/api/getlistdapil`, {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "appliacation/json",
        },
        body: JSON.stringify({ token: CSRF_TOKEN, regencyId: regencyId }),
    })
        .then((response) => {
            $("#selectListArea").empty();
            $("#selectListArea").append(
                "<option value=''>-Pilih Dapil-</option>"
            );
            return response.json();
        })
        .catch((error) => {});
}
function getDapilNamesUi(listDapils) {
    let divListDapil = "";
    listDapils.forEach((m) => {
        divListDapil += showDivHtmlListDapil(m);
    });
    const divListDapilContainer = $("#selectListArea");
    divListDapilContainer.append(divListDapil);
}
function showDivHtmlListDapil(m) {
    return `<option value="${m.id}">${m.name}</option>`;
}

async function getDapil(regencyId) {
    const results = await getDapilNames(regencyId);
    getDapilNamesUi(results);
}

let regencyId = $("#regencyId").val();
getDapil(regencyId);

async function getListDistrict(selectListAreaValue) {
    $("#selectDistrictId").append("<option value=''>Loading..</option>");
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    const response = await fetch(`/api/getlistdistrictdapil`, {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "appliacation/json",
        },
        body: JSON.stringify({
            token: CSRF_TOKEN,
            dapilId: selectListAreaValue,
        }),
    });
    $("#selectDistrictId").empty();
    return await response.json();
}
function getListDistrictUi(listDistricts) {
    let divListDistrict = "";
    listDistricts.forEach((m) => {
        divListDistrict += showDivHtmlListDistrict(m);
    });
    const divListDistrictContainer = $("#selectDistrictId");
    divListDistrictContainer.append(divListDistrict);
}

function showDivHtmlListDistrict(m) {
    return `<option value="${m.district_id}">${m.name}</option>`;
}
async function getListVillage(selectDistrictId) {
    $("#selectVillageId").append("<option value=''>Loading..</option>");
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
    $("#selectVillageId").empty();
    return await response.json();
}
function getListVillageUi(dataVillages) {
    let divVillage = "";
    dataVillages.forEach((m) => {
        divVillage += showDivHtmlVillage(m);
    });
    const divVillageContainer = $("#selectVillageId");
    divVillageContainer.append(divVillage);
}
function showDivHtmlVillage(m) {
    return `<option value="${m.id}">${m.name}</option>`;
}

function getLitTpsNotExistUi(dataTps){
    let divTpsNotExists = "";
    const countItem = dataTps.length;
    if (countItem > 0) {
        dataTps.forEach((m) => {
            divTpsNotExists += showDivHtmlTpsNotExists(m);
        });
        const divTpsNotExistsContainer = $("#listtpsnotexists");
        divTpsNotExistsContainer.append(divTpsNotExists);

    }else{

        const divTpsNotExistsContainer = $("#listtpsnotexists");
        divTpsNotExistsContainer.append(`<li class='text-success'>Terpenuhi</>`);

    }
}

function showDivHtmlTpsNotExists(m){
    return `
            <li class='text-danger'>TPS ${m.tps}</li>
    `;
}

function getLitTpsExistUi(dataTps){
    let divTpsExists = "";
    const countItem = dataTps.length;
    if (countItem > 0) {
        dataTps.forEach((m) => {
            divTpsExists += showDivHtmlTpsExists(m);
        });
        const divTpsExistsContainer = $("#datalisttpsexists");
        divTpsExistsContainer.append(divTpsExists);

    }else{

        const divTpsExistsContainer = $("#datalisttpsexists");
        divTpsExistsContainer.append(`<li class='text-danger'>Belum ada</>`);

    }
}

function showDivHtmlTpsExists(m){
    return `
           
                <tr>
                    <td class='text-center'>${m.tps}</td>
                    <td class='text-center'>${m.kortps} Orang</td>
                    <td class='text-center'>${m.jml_anggota_kortps} Orang</td>
                    <td class='text-center'>${m.hasil_suara}</td>
                    <td class='text-center'>${m.selisih}</td>
                </tr>
           
    `;
}

// GET data TPS
async function getListTps(villageId){
    $("#selectTps").append("<option value=''>Loading..</option>");
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    const response = await fetch(`/api/gettpsbyvillage`, {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "appliacation/json",
        },
        body: JSON.stringify({
            token: CSRF_TOKEN,
            village_id: villageId,
        }),
    });
    $("#selectTps").empty();
    return await response.json();
}

function getListTpsUi(dataTps) {
    let divTps = "";
    dataTps.forEach((m) => {
        divTps += showDivHtmlTps(m);
    });
    const divTpsContainer = $("#selectTps");
    divTpsContainer.append(divTps);
}
function showDivHtmlTps(m) {
    return `<option value="${m.id}">${m.tps_number}</option>`;
}


// GET data RT
async function getListRT(villageId) {
    $("#selectRt").append("<option value=''>Loading..</option>");
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    const response = await fetch(`/api/getrtbyvillage`, {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "appliacation/json",
        },
        body: JSON.stringify({
            token: CSRF_TOKEN,
            village_id: villageId,
        }),
    });
    $("#selectRt").empty();
    return await response.json();
}
function getListRTUi(dataRT) {
    let divRT = "";
    dataRT.forEach((m) => {
        divRT += showDivHtmlRT(m);
    });
    const divRTContainer = $("#selectRt");
    divRTContainer.append(divRT);
}
function showDivHtmlRT(m) {
    return `<option value="${m.rt}">${m.rt}</option>`;
}

// let table = $("#data").DataTable({
//     processing: true,
//     serverSide: true,
//     ajax: {
//       url: "/api/org/list/rt",
//       type: "POST",
//       data: function (d) {},
//     },
//     columns: [
//         {data: 'id', name: 'id'},
//         {data: 'name', name: 'name'},
//         {data: 'address', name: 'address'},
//         {data: 'rt', name: 'rt'},
//         {data: 'tps_number', name: 'tps_number'},
//     ]
// });
// table.draw();

let i = 1;
let table = $("#data").DataTable({
    pageLength: 10,
    paging: true,
    bLengthChange: true,
    bFilter: true,
    bInfo: true,
    processing: true,
    bServerSide: true,
    order: [[6, "desc"]],
    autoWidth: true,
    ajax: {
        url: "/api/org/list/rt",
        type: "POST",
        data: function (d) {
            d.dapil = selectListArea;
            d.district = selectDistrictId;
            d.village = selectVillageId;
            d.rt = selectRT;
            d.tps = selectTps;
            return d;
        },
    },
    columnDefs: [
        {
            searchable: false,
            orderable: false,
            targets: 0,
            render: function(data, type, row, meta){
                return i++;
                // return row.no;
            }
           
        },
        {
            targets: 1,
            sortable: true,
            render: function (data, type, row, meta) {
                return `<a href="/admin/member/profile/${row.user_id}"> <img  class="rounded" width="40" src="/storage/${row.photo}"> ${row.name}</a>`;
            },
        },
        {
            targets: 2,
            render: function (data, type, row, meta) {
                return `<p>${row.address}, DS.${row.village}, KEC.${row.district}</p>`;
            },
        },
        {
            targets: 3,
            orderable: true,
            render: function (data, type, row, meta) {
                return `<p class='text-center'>${row.rt ?? ""}</p>`;
            },
        },
        {
            targets: 4,
            orderable: true,
            render: function (data, type, row, meta) {
                return row.tps_number;
            },
        },
        // {
        //     targets: 5,
        //     render: function (data, type, row, meta) {
        //         return `<p>KORTPS</p>`;
        //     },
        // },
        {
            targets: 5,
            render: function (data, type, row, meta) {
                return `<p class="text-center">${row.count_anggota}</p>`;
            },
        },
        {
            targets: 6,
            render: function (data, type, row, meta) {
                return `<p class="text-center">${row.referal}</p>`;
            },
        },
        {
            targets: 7,
            render: function (data, type, row, meta) {
                return `<p class="text-center">${row.form_kosong}</p>`;
            },
        },
        {
            targets: 8,
            render: function (data, type, row, meta) {
                return `<p class="text-center">${row.keluargaserumah}</p>`;
            }, 
        },
        {
            targets: 9,
            render: function (data, type, row, meta) {
                return `<p class="text-center">${row.formmanual}</p>`;
            }, 
        },
        {
            targets: 10,
            render: function (data, type, row, meta) {
                return `<p>${row.phone_number ?? ""}</p>`;
            },
        },
        {
            targets: 11,
            render: function (data, type, row, meta) {
                return `<div class="btn-group">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-sc-primary text-white dropdown-toggle mr-1 mb-1" type="button" data-toggle="dropdown" aria-haspopup="true">...</button>
                            <div class="dropdown-menu">
                                <a href='/admin/struktur/rt/create/anggota/${row.idx}' class="dropdown-item">
                                    Tambah Anggota
                                </a>
                                <a href='/admin/struktur/rt/detail/anggota/${row.idx}' class="dropdown-item ">
                                Detail Anggota
                                </a>
                                <a href='/admin/struktur/list/sticker/${row.idx}' class="dropdown-item ">
                                Daftar Stiker
                                </a>
                                <a href='/admin/struktur/rt/detail/anggota/download/excel/${row.idx}' class="dropdown-item ">
                                Download Anggota Excel
                                </a>
								<a href='/admin/struktur/rt/detail/anggota/download/pdf/${row.idx}' class="dropdown-item ">
                                Download Anggota PDF
                                </a>
                                <a href='/admin/struktur/rt/detail/anggotakeluargaseryumah/download/pdf/${row.idx}' class="dropdown-item ">
                                Download Keluarga Serumah
                                </a>
								<a href='/admin/struktur/rt/detail/anggota/suratpernyatan/${row.idx}' class="dropdown-item ">
                                Download Surat Pernyataan
                                </a>
								<a href='/admin/struktur/rt/detail/anggota/tpsttimpemenangan/download/pdf/${row.idx}' class="dropdown-item ">
                                Download TPS Tim Pemenangan Suara
                                </a>
								<a href='/admin/struktur/rt/detail/anggota/tpsttimpemenangan/download/pdf/${row.idx}' class="dropdown-item ">
                                Download Surat Undangan
                                </a>
								<a href='/admin/struktur/rt/anggota/formmanual/${row.idx}' class="dropdown-item">
                                    Form Manual
                                </a>
                                <a href='/admin/struktur/rt/edittps/${row.id}' class="dropdown-item ">
                                Edit TPS
                                </a>
                                <a href='/admin/struktur/rt/edit/${row.id}' class="dropdown-item ">
                                Edit
                                </a>
                                <button type="button" data-toggle="modal" onclick="onDelete(this)" data-name="${row.name}" data-id="${row.id}" class="dropdown-item btn btn-sm btn-danger text-danger">
                                Hapus
                                </button>
                            </div>
                        </div>
                    </div>`;
            },
        },
    ],
    fnDrawCallback: function(row, data, start, end, display){

        let totalCountAnggota = 0;
        let totalCountReferal = 0;
        let totalFormKosong   = 0;
        let totalkgs          = 0;
        let totalFormManual   = 0;
        row.aoData.forEach(element => {
             totalCountAnggota += parseFloat(element._aData.count_anggota);
             totalCountReferal += parseFloat(element._aData.referal);
             totalFormKosong += parseFloat(element._aData.form_kosong);

        });
        $('#totalCountAnggota').empty();
        $('#totalCountReferal').empty();
        $('#totalFormKosong').empty();
        $('#totalkgs').empty();
        $('#totalFormManual').empty();
        $('#totalCountAnggota').append(`<p class="text-center"><b>${totalCountAnggota}</b></p>`);
        $('#totalCountReferal').append(`<p class="text-center"><b>${totalCountReferal}</b></p>`);
        $('#totalFormKosong').append(`<p class="text-center"><b>${totalFormKosong}</b></p>`);
        $('#totalkgs').append(`<p class="text-center"><b>${totalkgs}</b></p>`);
        $('#totalFormManual').append(`<p class="text-center"><b>${totalFormManual}</b></p>`);
    }
});


$("#exampleModal").on("show.bs.modal", function (event) {
    var button = $(event.relatedTarget);
    var recipient = button.data("whatever");
    var modal = $(this);
    modal.find('.modal-body input[name="pidx"]').val(recipient);
});

async function onEdit(data) {
    const id = data.id;
    const name = data.getAttribute("data-name");

    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");

    const { value: nik } = await Swal.fire({
        title: `Edit ${name}`,
        input: "number",
        inputPlaceholder: "NIK",
        focusConfirm: false,
        showCancelButton: true,
        cancelButtonText: "Batal",
        confirmButtonText: "Simpan",
        timerProgressBar: true,
    });

    if (nik) {
        $.ajax({
            url: "/api/org/rt/update",
            method: "POST",
            cache: false,
            data: {
                id: id,
                nik: nik,
                _token: CSRF_TOKEN,
            },
            success: function (data) {
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: `${data.data.message}`,
                    showConfirmButton: false,
                    width: 500,
                    timer: 900,
                });
                const table = $("#data").DataTable();
                table.ajax.reload();
            },
            error: function (error) {
                Swal.fire({
                    position: "center",
                    icon: "error",
                    title: `${error.responseJSON.data.message}`,
                    showConfirmButton: false,
                    width: 500,
                    timer: 1000,
                });
            },
        });
    }
}

async function onDelete(data) {
    // const id = data.id;
    const name = data.getAttribute("data-name");
    const id = data.getAttribute("data-id");

    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    Swal.fire({
        title: `Yakin hapus ${name}`,
        text: "Menghapus KOR RT, dapat menghapus beserta anggotanya!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Hapus",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/api/org/korrt/delete",
                method: "POST",
                cache: false,
                data: {
                    id: id,
                    _token: CSRF_TOKEN,
                },
                success: function (data) {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: `${data.data.message}`,
                        showConfirmButton: false,
                        width: 500,
                        timer: 900,
                    });
                    const table = $("#data").DataTable();
                    table.ajax.reload();
                },
                error: function (error) {
                    Swal.fire({
                        position: "center",
                        icon: "error",
                        title: `${error.responseJSON.data.message}`,
                        showConfirmButton: false,
                        width: 500,
                        timer: 1000,
                    });
                },
            });
        }
    });
}

function numberWithDot(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}
