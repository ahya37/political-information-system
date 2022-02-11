Vue.use(Toasted);
var register = new Vue({
    el: "#register",
    mounted() {
        AOS.init();
    },
    data() {
        return {
            code: null,
        };
    },
    methods: {
        checkForReveralAvailability: function () {
            var self = this;
            axios
                .get("/api/reveral/check", {
                    params: {
                        code: this.code,
                    },
                })
                .then(function (response) {
                    if (response.data == "Available") {
                        // get name where code
                        axios
                            .get("/api/reveral/name/" + this.code.value)
                            .then(function (res) {
                                self.$toasted.success(
                                    "Reveral tersedia atas Nama " +
                                        res.data.name,
                                    {
                                        position: "top-center",
                                        className: "rounded",
                                        duration: 3000,
                                    }
                                );
                            });
                        self.code_unavailable = true;
                    } else {
                        self.$toasted.error("Reveral tidak tersedia.", {
                            position: "top-center",
                            className: "rounded",
                            duration: 3000,
                        });
                        self.code_unavailable = false;
                    }
                    // handle success
                    // console.log(response);
                });
        },
    },
});
