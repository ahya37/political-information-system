const districtId = $('#districtId').val();


let table = $("#data").DataTable({
    pageLength: 10,
    paging: true,
    bLengthChange: true,
    bFilter: true,
    bInfo: true,
    processing: true,
    bServerSide: true,
    order: [[1, "asc"]],
    autoWidth: true,  
    ajax: {
        url: "/api/list/sapaanggota/desa",
        type: "POST",
        data: function (d) {
            d.district_id = districtId;
            return d;
        },
    },
    columnDefs: [
        {
            searchable: false,
            orderable: false,
            targets: 0,
            render: function(data, type, row, meta){
                return row.no;
                // return row.no;
            }
           
        },
        {
            targets: 1,
            render: function (data, type, row, meta) {
                return `
					<a href="#" onclick="showTitikByVillage(this)"  id="${row.id}" data-toggle="modal" data-target="#exampleModal" data-name="${row.name}">${row.name}</a>
				`;
            },
        },
		{
            targets: 2,
            render: function (data, type, row, meta) {
                return row.jml_titik;
            },
        },
		{
            targets: 3,
            render: function (data, type, row, meta) {
                return row.titik_terkunjungi;
            },
        },
		{
            targets: 4,
            render: function (data, type, row, meta) {
                return row.jml_titik - row.titik_terkunjungi;
            },
        },
		{
            targets: 5,
            render: function (data, type, row, meta) {
                return row.peserta;
            },
        },
		{
            targets: 6,
            render: function (data, type, row, meta) {
                return `<button id="${row.id}" data-name="${row.name}"  onclick="onEditJumlahTitik(this)" class="btn btn-sm btn-info">Edit Jumlah Titik</button>`;
            },
        },
        
    ],
});

async function onEditJumlahTitik(data) {
    const id = data.id;
    const name = data.getAttribute("data-name");

    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");

    const { value: jml } = await Swal.fire({
        title: `Edit Jumlah Titik ${name}`,
        input: "number",
        inputPlaceholder: "Jumlah Titik",
        focusConfirm: false,
        showCancelButton: true,
        cancelButtonText: "Batal",
        confirmButtonText: "Simpan",
        timerProgressBar: true,
    });

    if (jml) { 
        $.ajax({
            url: "/api/event/sapaanggota/jumlahtitik/update",
            method: "POST",
            cache: false,
            data: {
                id: id,
                jml: jml,
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

// show modal
function showTitikByVillage(data){
	
	 const id = data.id;
     const name = data.getAttribute("data-name");
	 showModal(id, name);
}

function showModal(id, name){
	
	// get data by ajax
	$(`#exampleModal`).on('show.bs.modal', function (event) {
	  let button = $(event.relatedTarget); 
	  let recipient = button.data('whatever');
	  
	  let modal = $(this);
	  modal.find('.modal-title').text('TITIK KUNJUNGAN ' + name);
	  modal.find('.modal-body').val(recipient);
	  
	});
}

