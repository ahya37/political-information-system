$(document).ready(function () {
    jQuery("#datetimepicker6").datetimepicker({
        timepicker: false,
        format: "d-m-Y",
    });
    $.datetimepicker.setLocale("id");
});

Vue.use(Toasted);
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
            jobs: null,
            educations: null,
            education_id: "{{ $profile->education_id }}",
            job_id: "{{ $profile->job_id }}",
            provinces_id: "{{ $profile->province_id }}",
            regencies_id: "{{ $profile->regency_id }}",
            districts_id: "{{ $profile->district_id }}",
            villages_id: "{{ $profile->village_id }}",
        };
    },
    methods: {
        getEducationsData() {
            var self = this;
            axios.get("/api-educations").then(function (response) {
                self.educations = response.data;
            });
        },
        getJobsData() {
            var self = this;
            axios.get("/api/jobs").then(function (response) {
                self.jobs = response.data;
            });
        },
        getProvincesData() {
            var self = this;
            axios.get("/api-provinces").then(function (response) {
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
