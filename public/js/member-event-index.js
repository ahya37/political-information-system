function onDelete(data) {
    const id = data.id;
    const name = data.value;
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    Swal.fire({
      title: `Yakin hapus event : ${name}?`,
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Hapus",
      cancelButtonText: "Batal",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: `/event/delete`,
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
          },
          error: function (error) {
            Swal.fire({
              position: "center",
              icon: "warning",
              title: `Gagal hapus!`,
              showConfirmButton: false,
              width: 500,
              timer: 1500,
            });
          },
        });
      }
      const table = $("#data").DataTable();
      table.ajax.reload();
    });
  }