<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />
    <title>Struktur Organisasi Jalur AAW</title>


    <style>
        html,
        body {
            margin: 0px;
            padding: 0px;
            width: 100%;
            height: 100%;
            overflow: hidden;
            font-family: Helvetica;
        }

        #tree {
            width: 100%;
            height: 100%;
        }
    </style>
</head>

<body>
    <div class="container-fluid" id="container-fluid">
        <div class="row mb-4">
            <nav class="navbar fixed-top navbar-light bg-light">
                <div class="col-md-2">
                    <div class="form-group">
                        {{-- <select name="" id="selectArea" class="form-control filter" required></select> --}}
                        <input value="{{ $regency->id }}" type="hidden" id="regencyId" class="form-control">
                        <input value="{{ $regency->name }}" type="text" readonly class="form-control">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <select name="dapil_id" id="selectListArea" class="form-control filter" required></select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <select name="district_id" id="selectDistrictId" class="form-control filter"></select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <select name="village_id" id="selectVillageId" class="form-control filter">
                            {{-- <option value="">-Pilih desa-</option>
                            <option value="3602011001">MUARA</option>
                            <option value="3602011002">WANASALAM</option> --}}
                        </select>
                    </div>
                </div>

            </nav>
        </div>
        <div class="row mt-4"></div>
        <div class="row mt-4"></div>
        <div class="row mt-4">
            <div class="col-md-12 mt-4">
                <div id="loading"></div>
                <div id="tree"></div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/sankey.js"></script>
    <script src="https://code.highcharts.com/modules/organization.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    {{-- <script type="text/javascript" src="{{ asset('assets/vendor/orgchart/OrgChart.js') }}"></script> --}}
    <script src="{{ asset('assets/sweetalert2/dist/sweetalert2.all.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset('js/orgdiagram-test.js') }}"></script>
    {{-- <script type="text/javascript" src="{{ asset('js/select-area.js') }}"></script> --}}

</body>
</html>
