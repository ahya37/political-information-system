function docReady(fn) {
    // see if DOM is already available
    if (
        document.readyState === "complete" ||
        document.readyState === "interactive"
    ) {
        // call on next available tick
        setTimeout(fn, 1);
    } else {
        document.addEventListener("DOMContentLoaded", fn);
    }
}

docReady(function () {
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    var lastResult,
        countResults = 0;

    // var html5QrcodeScanner = new Html5QrcodeScanner("qr-reader", {
    //     fps: 10,
    //     qrbox: 500,
    // });
    // html5QrcodeScanner.start({ facingMode: "user" });
    // html5QrcodeScanner.render(onScanSuccess);

    const html5QrCode = new Html5Qrcode(
        "qr-reader", { formatsToSupport: [ Html5QrcodeSupportedFormats.QR_CODE ] });
      const qrCodeSuccessCallback = async (decodedText, decodedResult) => {
          /* handle success */
          if (decodedText !== lastResult) {
            ++countResults;
            lastResult = decodedText;
            // const { value: text } = await Swal.fire({
            //     input: "text",
            //     inputLabel: "Masukan Nama Lengkap",
            //     showCancelButton: true,
            //     confirmButtonText: "Simpan",
            //     cancelButtonText: "Batal",
            // });
            const { value: formValues } = await Swal.fire({
                title: 'Masukan',
                html:
                  '<div class="form-group"><label class="col-sm-12 col-form-label">Nama</label><input id="swal-input1" class="form-control"></div>' +
                  '<div class="form-group"><label class="col-sm-12 col-form-label">Desa</label><input id="swal-input2" class="form-control"></div>',
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: "Simpan",
                cancelButtonText: "Batal",
                preConfirm: () => {
                  return [
                    document.getElementById('swal-input1').value,
                    document.getElementById('swal-input2').value
                  ]``
                }
              });

            if (formValues) {
                // ajax absensi
                $.ajax({
                    url: "/api/absensi/store",
                    method: "POST",
                    cache: false,
                    data: {
                        eventid: lastResult,
                        name: formValues[0],
                        address: formValues[1],
                        _token: CSRF_TOKEN,
                    },
                    success: function (data) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: `${data.data.message}`,
                            showConfirmButton: false,
                            timer: 1500
                          });
                          window.location.reload();
                    },

                });
            }else{
                Swal.fire({
                    position: 'center',
                    icon: 'warning',
                    title: `Masukan Nama !`,
                    showConfirmButton: false,
                    timer: 1500
                  });
            }
        }
      };
      const config = { fps: 10, qrbox: { width: 500, height: 500 } };
      
      // If you want to prefer back camera
    //   html5QrCode.start({ ideal: 'environment' }, config, qrCodeSuccessCallback);
      html5QrCode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback);
});
