<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>Verifikasi Email</title>

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
    <link href="{{ asset('assets/style/main.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/style/style.css') }}" rel="stylesheet" />
  </head>

  <body style="background-color: #0C0D36">
  <div class="page-content page-auth d-flex justify-content-center">
      <div class="section-store-auth" data-aos="fade-down">
        <div class="container">
          <div class="card shadow bg-white rounded">
            <div class="card-body">
              <h5 class="mb-3 text-center">
                <img src="{{ asset('assets/images/email.svg') }}" width="70">
                <div class="col-lg-12">
                  <div class="mt-3 alert alert-success" align="left" style="font-size: 14px">
                    <p>Anda Berhasil Mendaftar </p>
                    <p>Silahkan Cek Email untuk melakukan verifikasi</p>
                    <p>Jika Anda kesulitan atau tidak ada pesan masuk ke email Anda dari sistem<br>
                      Silahkan hubungi Admin di : +62 815-7300-0897 (Telp & Whatsapp)</p>
                  </div>
                </div>
              </h5>
              <div class="text-right">
                <a
                    href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();"
                    class="btn text-right btn-sc-secondary text-white mt-4"
                    >
                          Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
                </form>
            </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Bootstrap core JavaScript -->
    <script src="/vendor/jquery/jquery.slim.min.js"></script>
    <script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
       AOS.init();
    </script>
    <script src="/script/navbar-scroll.js"></script>
  </body>
</html>
