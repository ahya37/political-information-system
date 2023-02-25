$(document).ready(function () {
    $('#data').DataTable();
    // $('#data').tableDnD({
    //     onDragStop: function (table, row) {
    //         // UPDATE / TUKAR URUTAN KE DB JIKA DI DROP, UPDATE IDX NYA
    //         let rows = table.tBodies[0].rows;
    //         let datas = [];
    //         for (let i = 0; i < rows.length; i++) {
    //             datas.push(rows[i].id);
    //         }
    //         console.log('data: ', datas);

    //         return new Promise((resolve, reject) => {
    //             $.ajax({
    //                 url: `/api/org/pusat/setorder/save`,
    //                 method: 'POST',
    //                 data: { data: datas },
    //                 beforeSend: function () {
    //                     console.log('loading...')
    //                 },
    //                 success: function (data) {
    //                     console.log('data: ', data);
    //                 },
    //                 complete: function () {
    //                     console.log('done')
    //                 }
    //             }).done(resolve).fail(reject);
    //         });
    //     }
    // });

    $('#exampleModal').on('show.bs.modal', function (event) {

    });
});

