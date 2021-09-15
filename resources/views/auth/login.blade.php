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

    <title>System</title>

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
    <link href="{{ asset('assets/style/main.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/style/style.css') }}" rel="stylesheet" />
  </head>

  <body>
    <div class="page-content page-auth">
      <div class="section-store-auth" data-aos="fade-up">
        <div class="container">
          <div class="row align-items-center row-login">
            <div class="col-lg-6 text-center">
              <img src="{{ asset('assets/images/logo2.jpeg') }}" width="350" class="mb-4 mb-lg-none" />
            </div>
            <div class="col-lg-6 col-sm-12">
              <form method="POST" action="{{ route('login') }}" class="mt-3">
                @csrf
                <h2>Login</h2>
                <br />
                <div class="form-group">
                  <label for="email">Email Address</label>
                  <input type="email" id="email" name="email" class="form-control col-lg-9 col-sm-12" />
                </div>
                <div class="form-group">
                  <label for="password">Password</label>
                  <input type="password" id="password" name="password" class="form-control col-lg-9 col-sm-12" />
                </div>
                
                <button
                  class="btn btn-sc-primary btn-block mt-4 col-lg-9 col-sm-12"
                >
                  Login
                </button>
                 <a
                  href="{{ route('register') }}"
                  class="btn btn-sc-secondary btn-block mt-4 col-lg-9 col-sm-12"
                >
                  Buat Akun Baru
                </a>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <footer>
      <div class="container">
        <div class="row">
          <div class="col-12 text-center">
            <p class="pt-4 pb-2">2021 Copyright System. All Right Reserved</p>
          </div>
        </div>
      </div>
    </footer>

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
