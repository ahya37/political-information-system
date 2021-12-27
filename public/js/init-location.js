var register = new Vue({
    el: "#register",
    mounted() {
        AOS.init();
        this.getProvincesData();
        this.getRegenciesData();
        this.getDistrictsData();
        this.getVillagesData();
        this.getJobsData();
        this.getEducationsData();
    },
    data() {
        return {
            provinces: null,
            regencies: null,
            districts: null,
            villages: null,
            provinces_id: null,
            regencies_id: null,
            districts_id: null,
            villages_id: null,
        };
    },
    methods: {
        getProvincesData() {
            var self = this;
            axios.get("/api/provinces").then(function (response) {
                self.provinces = response.data;
            });
        },
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
