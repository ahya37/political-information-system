@extends('layouts.sip')
@section('title', 'Sistem Informasi Politik')
@push('addon-style')
    <link href="{{ asset('assets/style/style.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatable/datatables.min.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
        integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('content')
    <!-- Section Content -->
    <div class="section-content section-dashboard-home mb-4" data-aos="fade-up">
        <div class="container-fluid">
            <div class="dashboard-heading">
                <h2 class="dashboard-title">Sistem Informasi Politik</h2>
            </div>
            <div class="dashboard-content mt-4" id="transactionDetails">

                <div class="row">
                    <div class="col-md-12 mt-2 mb-2">
                        <div class="card">
                            <div class="card-body">
                                <div class="col-md-12">
                                    <h5>Grafik Perbandingan</h5>
                                    <hr>
                                </div>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <h5>Area :</h5>
                                        </div>
                                        <div class="col-md-2">
                                            <select name="level" id="province" required class="form-control filter"
                                                required>
                                                <option value="">-Pilih Provinsi-</option>
                                                {{-- @foreach ($province as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach --}}
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12 ">
                                                        <select name="" id="selectArea" class="form-control filter"
                                                            required>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12 ">
                                                        <select name="dapil_id" id="selectListArea"
                                                            class="form-control filter" required>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12 ">
                                                        <select name="district_id" id="selectDistrictId"
                                                            class="form-control filter">
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12 ">
                                                        <select name="village_id" id="selectVillageId"
                                                            class="form-control filter">
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-4">
                                    <div id="container"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('addon-script')
    <script type="text/javascript" src="{{ asset('assets/vendor/datatable/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.full.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/highcharts/highcharts.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Highcharts.chart('container', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Corn vs wheat estimated production for 2020',
                align: 'left'
            },
            subtitle: {
                text: 'Source: <a target="_blank" ' +
                    'href="https://www.indexmundi.com/agriculture/?commodity=corn">indexmundi</a>',
                align: 'left'
            },
            xAxis: {
                categories: ['USA', 'China', 'Brazil', 'EU', 'India', 'Russia'],
                crosshair: true,
                accessibility: {
                    description: 'Countries'
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: '1000 metric tons (MT)'
                }
            },
            tooltip: {
                valueSuffix: ' (1000 MT)'
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                    name: 'Corn',
                    data: [406292, 260000, 107000, 68300, 27500, 14500]
                },
                {
                    name: 'Wheat',
                    data: [51086, 136000, 5500, 141000, 107180, 77000]
                }
            ]
        });
    </script>
@endpush
