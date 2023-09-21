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
    async function onScanSuccess(decodedText, decodedResult) {
        if (decodedText !== lastResult) {
            ++countResults;
            lastResult = decodedText;
            const { value: text } = await Swal.fire({
                input: "text",
                inputLabel: "Masukan Nama Lengkap",
                showCancelButton: true,
                confirmButtonText: "Simpan",
                cancelButtonText: "Batal",
            });
            if (text) {
                // ajax absensi
                $.ajax({
                    url: "/api/absensi/store",
                    method: "POST",
                    cache: false,
                    data: {
                        eventid: lastResult,
                        name: text,
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
    }

    // var html5QrcodeScanner = new Html5QrcodeScanner("qr-reader", {
    //     fps: 10,
    //     qrbox: 500,
    // });
    // html5QrcodeScanner.start({ facingMode: "user" });
    // html5QrcodeScanner.render(onScanSuccess);

    const html5QrCode = new Html5Qrcode(
        "qr-reader", { formatsToSupport: [ Html5QrcodeSupportedFormats.QR_CODE ] });
      const qrCodeSuccessCallback = (decodedText, decodedResult) => {
          /* handle success */
      };
      const config = { fps: 10, qrbox: { width: 250, height: 250 } };
      
      // If you want to prefer front camera
      html5QrCode.start({ ideal: 'environment' }, config, qrCodeSuccessCallback);
});
