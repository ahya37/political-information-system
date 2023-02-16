<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>Form Intlegensi Politik Jalur AAW</title>

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
    <link href="{{ asset('assets/style/main.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/style/sidebar.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />

</head>

<body>
    <div class="page-dashboard">
        <div class="d-flex" id="wrapper" data-aos="fade-right">
            <!-- Page Content -->
            <div id="page-content-wrapper">

                <div class="section-content section-dashboard-home mb-4" data-aos="fade-up">
                    <div class="container-fluid">

                        <div class="dashboard-content mt-4" id="transactionDetails">
                            <div class="row">
                                <div class="col-md-3">
                                </div>
                                <div class="col-md-6">
                                    <div class="dashboard-heading">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="text-center">
                                                    <img src="{{ asset('assets/images/logo2.jpeg') }}" width="150"
                                                        class="mb-lg-none" />
                                                </div>
                                                <h2 class="dashboard-title text-center">Form Intelegensi Politik (Jalur
                                                    AAW)</h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-3"></div>
                                <div class="col-md-6">
                                    @include('layouts.message')
                                    <div class="card">
                                        <div class="card-body">
                                            <form id="register" action="{{ route('saveformintelegence') }}"
                                                method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="form-group">
                                                    <label class="col-sm-12 col-form-label">Nama</label>
                                                    <div class="col-sm-12">
                                                        <input type="text" name="name" class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-12 col-form-label">Kampung</label>
                                                    <div class="col-sm-12">
                                                        <input type="text" name="address" class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-12 col-form-label">RT</label>
                                                    <div class="col-sm-12">
                                                        <input type="number" name="rt" class="form-control" required>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-12 col-form-label">Kabpuaten/Kota</label>
                                                    <div class="col-sm-12">
                                                        <select id="regencies_id" name="regency_id" class="form-control select2" disabled
                                                            v-model="regencies_id" v-if="regencies">
                                                            <option v-for="regency in regencies" :value="regency.id">
                                                                @{{ regency.name }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-12 col-form-label">Kecamatan</label>
                                                    <div class="col-sm-12">
                                                        <select id="districts_id" name="district_id" class="form-control"
                                                            v-model="districts_id" v-if="districts" required>
                                                            <option v-for="district in districts" :value="district.id">
                                                                @{{ district.name }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-12 col-form-label">Desa</label>
                                                    <div class="col-sm-12">
                                                        <select name="village_id" id="villages_id" required
                                                            class="form-control" v-model="villages_id" v-if="districts" required>
                                                            <option v-for="village in villages" :value="village.id">
                                                                @{{ village.name }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-12 col-form-label">Profesi</label>
                                                    <div class="col-sm-12">
                                                        <div class="row">
                                                            <div class="col-md-12 mb-2">
                                                                <input type="checkbox" class="profession" name="profession[]"
                                                                    value="Tokoh Masyarakat"> Tokoh Masyarakat
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <input type="checkbox"  class="profession" name="profession[]"
                                                                    value="Tokoh Politik"> Tokoh Politik
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <input type="checkbox" class="profession" name="profession[]"
                                                                    value="Tokoh Pemuda"> Tokoh Pemuda
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <input type="checkbox" class="profession" name="profession[]"
                                                                    value="Pengusaha"> Pengusaha
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <input type="checkbox"  class="profession" name="profession[]"
                                                                    value="Ustadz / Ulama / Kiyai"> Ustadz / Ulama /
                                                                Kiyai
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <input type="checkbox" class="profession" name="profession[]"
                                                                    value="Petani"> Petani
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <input type="checkbox" class="profession" name="profession[]"
                                                                    value="Nelayan"> Nelayan
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <div class="row">
                                                                    <div class="col-md-3">
                                                                        <input type="checkbox" id="profession" class="profession" onclick="onProfession()"> Lainnya
                                                                    </div>
                                                                    <div class="col-md-9">
                                                                        <input type="text" id="otherprofession" style="display:none;" name="profession[]" class="form-control" placeholder="Sebutkan">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-12 col-form-label">Pernah Menjabat
                                                        Sebagai</label>
                                                    <div class="col-sm-12">
                                                        <div class="row">
                                                            <div class="col-md-12 mb-2">
                                                                <input type="checkbox" name="onceserved[]"
                                                                    value="Kepala Desa"> Kepala Desa
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <input type="checkbox" name="onceserved[]"
                                                                    value="DPRD Kabupaten"> DPRD Kabupaten
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <input type="checkbox" name="onceserved[]"
                                                                    value="DPRD Provinsi"> DPRD Provinsi
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <input type="checkbox" name="onceserved[]"
                                                                    value="DPR RI"> DPR RI
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <input type="checkbox" name="onceserved[]"
                                                                    value="PNS"> PNS
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <div class="row">
                                                                    <div class="col-md-3">
                                                                        <input type="checkbox" id="onceserved" class="onceserved" onclick="onOnceserved()"> Lainnya
                                                                    </div>
                                                                    <div class="col-md-9">
                                                                        <input type="text" id="otheronceserved" style="display:none;" name="onceserved[]" class="form-control" placeholder="Sebutkan">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-12 col-form-label">No. Telepon (Jika
                                                        ada)</label>
                                                    <div class="col-sm-12">
                                                        <input type="number" name="notelp" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-12 col-form-label">Pernah Mencalonkan Diri Sebagai (Optional)</label>
                                                    <div class="col-sm-12">
                                                        <div class="row">
                                                            <div class="col-md-12 mb-2">
                                                                <input type="checkbox" name="politicname[]"
                                                                    value="Kepala Desa"> Kepala Desa
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <input type="checkbox" name="politicname[]"
                                                                    value="DPRD Kabupaten"> DPRD Kabupaten
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <input type="checkbox" name="politicname[]"
                                                                    value="DPRD Provinsi"> DPRD Provinsi
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <input type="checkbox" name="politicname[]"
                                                                    value="Calon PNS"> Calon PNS
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <div class="row">
                                                                    <div class="col-md-3">
                                                                        <input type="checkbox" id="politicname" class="politicname"   onclick="onPoliticname()"> Lainnya
                                                                    </div>
                                                                    <div class="col-md-9">
                                                                        <input type="text" id="otherpoliticname" style="display:none;" name="politicname[]" class="form-control" placeholder="Sebutkan">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-12 col-form-label">Tahun</label>
                                                    <div class="col-sm-12">
                                                        <input type="number" name="politic_year"
                                                            class="form-control">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-12 col-form-label">Status</label>
                                                    <div class="col-sm-12">
                                                        <input type="radio" name="politic_status" value="Menang">
                                                        Menang
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <input type="radio" name="politic_status" value="Kalah">
                                                        Kalah
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-12 col-form-label">Perolehan Jumlah
                                                        Suara (ketik angka saja)</label>
                                                    <div class="col-sm-12">
                                                        <input type="number" name="politic_potential"
                                                            class="form-control" required>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-12 col-form-label">Keterangan</label>
                                                    <div class="col-sm-12">
                                                        <textarea name="descr"  class="form-control"></textarea>
                                                    </div>
                                                </div>

                                                  <div class="form-group">
                                                    <label class="col-sm-12 col-form-label">Potensi Jumlah
                                                        Suara (ketik angka saja)</label>
                                                    <div class="col-sm-12">
                                                        <input type="number" name="politic_potential"
                                                            class="form-control" required>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-12 col-form-label">Sumber Informasi</label>
                                                    <div class="col-sm-12">
                                                        <input type="text" name="resource" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-12 col-form-label">Apakah Nama Diatas Anggota Jaringan Kang Asep Awaludin</label>
                                                    <div class="col-sm-12">
                                                        <input type="radio" class="Anggota" name="ismember" value="Anggota" onclick="onAnggota(this)">
                                                        Anggota
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <input type="radio" class="Anggota" name="ismember" value="Bukan" onclick="onAnggota(this)">
                                                        Bukan
                                                    </div>
                                                </div>

                                                <div class="form-group" style="display:none;" id="devnomember">
                                                    <label class="col-sm-12 col-form-label" >Nomor Anggota</label>
                                                    <div class="col-sm-12">
                                                        <input type="number"  id="nomember" name="nomember" placeholder="Isikan nomor anggota" class="form-control">
                                                    </div>
                                                </div>

                                                <hr>

                                                <div class="form-group row">
                                                    <div class="col-md-12">
                                                        <button type="submit" class="ml-3 btn btn-sc-primary text-white text-center">Simpan</button>
                                                    </div>
                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Bootstrap core JavaScript -->
    <script src="{{ asset('assets/vendor/vue/vue.js') }}"></script>
    <script src="{{ asset('assets/vendor/axios/axios.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
    <script src="{{ asset('/assets/script/navbar-scroll.js') }}"></script>
    <script>
        $("#menu-toggle").click(function(e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
        });
    </script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-20W3HD4JHH"></script>
    <script src="{{ asset('assets/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('/js/init-location.js') }}"></script>
    <script src="{{ asset('js/create-intelegensi-init.js') }}"></script>

</body>

</html>
