async function edValueKeyPress() {
    let edValue = document.getElementById("nik");
    let val = edValue.value;

    let stringLength = val.length;

    let name = $('#name');
    let phone = $('#phone');
    let address = $('#address');

    if (stringLength === 16) {

        // get api ajax call name by nik
        await getData(val).then((data) => {
            name.val(data.name ?? '')
            phone.val(data.phone_number ?? '')
            address.val(data.address ?? '')

        }).catch((err) => {

        });

    } else {

        name.val("");
        phone.val("");
        address.val("");
    }


}

function getData(val) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: `/api/searchmemberniktype`,
            method: "POST",
            data: { data: val },
            beforeSend: function () {
                $('#name').val("")
                $('#phone').val("")
                $('#address').val("")
            },
            success: function (result) {
                resolve(result)
            },
            error: function (error) {
                reject(error)
            }
        });
    })


}