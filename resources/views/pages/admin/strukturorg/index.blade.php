<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('assets/style/main.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Struktur Organisasi Jalur AAW</title>

    <style>
        html,
        /* body {
            margin: 0px;
            padding: 0px;
            width: 100%;
            height: 100%;
            overflow: hidden;
            font-family: Helvetica;
        } */

        #tree {
            width: 100%;
            height: 100%;
        }
    </style>
</head>

<body>
    <div class="container-fluid" id="container-fluid">
        <div class="col-md-12">
            <div class="row mb-4">
                <nav class="navbar fixed-top navbar-light bg-light">
                    <div class="col-md-1">
                        <div class="form-group">
                            <input value="{{ $regency->id }}" type="hidden" id="regencyId">
                            <input value="KOR PUSAT" type="text" id="btnKorPusat" readonly class="btn btn-sm btn-primary">
                        </div>
                    </div>
                    <div class="col-md-1"></div>
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
                            <select name="village_id" id="selectVillageId" class="form-control filter"></select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <select name="rt" id="selectRt" class="form-control filter"></select>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
        <div class="row mt-4"></div>
        <div class="row mt-4">
        </div>
        <div class="row mt-4">
            <div class="col-md-12 ml-2">
                <h5 id="descrLocation">KOORDINATOR PUSAT</h5>
            </div>
            <div class="col-md-12 mt-4">
                <div id="loading"></div>
                <div id="orgVillage"></div>
                <div id="orgDistrict"></div>
                <div id="orgDapil"></div>
                <div id="orgPusat"></div>
            </div>
        </div>
         <div class="col-md-12 mt-4">
            <h5 id="korrtlabel" class="text-center" style="display:none">KOORDINATOR RT</h5>
        </div>
         <div class="col-md-12 mt-4">
            <div class="row" id="orgRTChart">
            </div>
        </div>
        <div class="col-md-12 mt-4">
            <div class="row" id="orgRT">
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/highcharts/highcharts10.3.js') }}"></script>
    <script src="{{ asset('assets/vendor/highcharts/sankey.js') }}"></script>
    <script src="{{ asset('assets/vendor/highcharts/organization.js') }}"></script>
    <script src="{{ asset('assets/vendor/highcharts/accessibility10.3.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <script src="{{ asset('assets/sweetalert2/dist/sweetalert2.all.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset('js/select-area.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/orgdiagram-test.js') }}"></script>

</body>
</html>
