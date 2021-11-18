function valueChangeAdminArea() {
    if ($("#provinceCheck").is(":checked")) {
        $("#formProvince").show();
    } else {
        $("#formProvince").hide();
    }
}
