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
                                <div class="col-md-6 mt-2">
                                    @include('layouts.message')
                                    <div class="card">
                                        <div class="card-body">
                                            <form id="register" action="{{ route('saveformintelegence') }}"
                                                method="POST" enctype="multipart/form-data">
                                                @csrf 
                                                <div class="form-group">
                                                    <label class="col-sm-12 col-form-label">Nomor Kode Referal JALUR AAW Anda</label>
                                                    <div class="col-sm-12">
                                                        <input type="text" name="kta" class="form-control" required>
                                                    </div>
												<div class="form-group row">
														<div class="col-md-12">
															<button type="button" class="ml-3 btn btn-sm btn-success text-white text-center" id="addMore" value="Add"><i class="fa fa-plus"></i>Tambah Calon Pesaing</button>
														</div>
													</div>

													<div class="form-group row">
														<div class="col-md-12">
															<button type="submit" class="ml-3 btn btn-sc-primary text-white text-center">Simpan</button>
														</div>
													</div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
									
								</div>
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
