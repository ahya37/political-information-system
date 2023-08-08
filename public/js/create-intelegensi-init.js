function onProfession() {
    if (document.getElementById('profession').checked) {
        $('#otherprofession').show();

    } else {
        $('#otherprofession').hide();
        $('#otherprofession').val("");
    }
}

function onOnceserved() {
    if (document.getElementById('onceserved').checked) {
        $('#otheronceserved').show();

    } else {
        $('#otheronceserved').hide();
        $('#otheronceserved').val("");
    }
}

function onPoliticname() {
    if (document.getElementById('politicname').checked) {
        $('#otherpoliticname').show();

    } else {
        $('#otherpoliticname').hide();
        $('#otherpoliticname').val("");
    }
}


function onAnggota(element){

    const data = element.value;
    if (data === 'Anggota') {
        $('#devnomember').show();
    } else {
        $('#devnomember').hide();
        $('#nomember').val("");
    }

}

var register = new Vue({
    el: "#register",
    mounted() {
        AOS.init();
        this.getRegenciesData();
        this.getDistrictsData();
        this.getVillagesData();
    },
    data() {
        return {
            provinces: null,
            regencies: null,
            districts: null,
            villages: null,
            provinces_id: 36,
            regencies_id: 3602,
            districts_id: null,
            villages_id: null,
        };
    },
    methods: {
        getRegenciesData() {
            var self = this;
            axios
                .get("/api/regencies/" + self.provinces_id)
                .then(function (response) {
                    self.regencies = response.data;
                });
        },
        getDistrictsData() {
            var self = this;
            axios
                .get("/api/districts/" + self.regencies_id)
                .then(function (response) {
                    self.districts = response.data;
                });
        },
        getVillagesData() {
            var self = this;
            axios
                .get("/api/villages/" + self.districts_id)
                .then(function (response) {
                    self.villages = response.data;
                });
        },
    },
    watch: {
        provinces_id: function (val, oldval) {
            this.regencies_id = null;
            this.getRegenciesData();
        },
        regencies_id: function (val, oldval) {
            this.districts_id = null;
            this.getDistrictsData();
        },
        districts_id: function (val, oldval) {
            this.villages_id = null;
            this.getVillagesData();
        },
    },
});

//melakukan proses multiple input
    $("#addMore").click(function () {
        $.ajax({
            url: "/api/intelegency/addElement",
            type: "post",
            success: function (response) {
                // Append element
                $("#elements").append(response);

                // Initialize select2
                // initailizeSelect2();
            },
        });
        // if ($("body").find(".fieldGroup").length < maxGroup) {
        //     initailizeSelect2();
        //     var fieldHTML =
        //         '<div class="form-group fieldGroup">' +
        //         $(".fieldGroupCopy").html() +
        //         "</div>";
        //     $("body").find(".fieldGroup:last").after(fieldHTML);
        // } else {
        //     alert("Maksimal " + maxGroup + " data terlebih dahulu.");
        // }
    });
	
	// remove fields group
    $("body").on("click", ".remove", function () {
        $(this).parents(".fieldGroup").remove();
    });





