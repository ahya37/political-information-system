$('#exampleModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    var recipient = button.data('whatever') // Extract info from data-* attributes
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    var modal = $(this)
    modal.find('.modal-title').text('Buat Koordinator Pusat')
    modal.find('.modal-body input').val(recipient)
})


Vue.use(Toasted);

var register = new Vue({
    el: "#register",
    data() {
        return {
            nik: null,
            nikSektretaris: null,
            nikBendahara: null,
        };
    },
    methods: {
        checkForNikAvailability: function () {
            var self = this;
            axios
                .get("/api/nik/check", {
                    params: {
                        nik: this.nik,
                    },
                })
                .then(function (response) {
                    if (response.data == "Available") {
                        self.$toasted.error(
                            "NIK Ketua Tidak Terdaftar!",
                            {
                                position: "top-center",
                                className: "rounded",
                                duration: 2000,
                            }
                        );
                        self.nik_unavailable = false;
                    } else {
                        self.$toasted.show(
                            "NIK Ketua Terdaftar, Lanujutkan!",
                            {
                                position: "top-center",
                                className: "rounded",
                                duration: 2000,
                            }
                        );
                        self.nik_unavailable = true;
                    }
                });
        },

        checkForNikAvailabilitySekretaris: function () {
            var self = this;
            axios
                .get("/api/nik/check", {
                    params: {
                        nik: this.nikSektretaris,
                    },
                })
                .then(function (response) {
                    if (response.data == "Available") {
                        self.$toasted.error(
                            "NIK Sekretaris Tidak Terdaftar!",
                            {
                                position: "top-center",
                                className: "rounded",
                                duration: 2000,
                            }
                        );
                        self.nik_unavailable = false;
                    } else {
                        self.$toasted.show(
                            "NIK Sekretaris Terdaftar, Lanujutkan!",
                            {
                                position: "top-center",
                                className: "rounded",
                                duration: 2000,
                            }
                        );
                        self.nik_unavailable = true;
                    }
                    // handle success
                });
        },
        checkForNikAvailabilityBendahara: function () {
            var self = this;
            axios
                .get("/api/nik/check", {
                    params: {
                        nik: this.nikBendahara,
                    },
                })
                .then(function (response) {
                    if (response.data == "Available") {
                        self.$toasted.error(
                            "NIK Bendahara Tidak Terdaftar!",
                            {
                                position: "top-center",
                                className: "rounded",
                                duration: 2000,
                            }
                        );
                        self.nik_unavailable = false;
                    } else {
                        self.$toasted.show(
                            "NIK Bendahara Terdaftar, Lanujutkan!",
                            {
                                position: "top-center",
                                className: "rounded",
                                duration: 2000,
                            }
                        );
                        self.nik_unavailable = true;
                    }
                    // handle success
                });
        },
    },
});

