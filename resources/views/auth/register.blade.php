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

    <title>Buat Akun Baru</title>

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
    <link href="{{ asset('assets/style/main.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/style/style.css') }}" rel="stylesheet" />
  </head>

  <body style="background-color: #0C0D36">
  <div class="page-content page-auth d-flex justify-content-center">
      <div class="section-store-auth" data-aos="fade-down">
        <div class="container">
            @include('layouts.message')
          <div class="card shadow bg-white rounded">
            <div class="card-body">
              <h5 class="mb-3 text-center">Buat Akun Baru</h5>
              <div class="col-lg-12 mt-4">
                <form action="{{  route('register')  }}" method="POST" id="register" enctype="multipart/form-data">
                  @csrf
                  <div class="row row-login">
                    <div class="col-12">
                        <div class="form-group">
                            <span class="required">*</span>
                          <label>Nama</label>
                          <input type="text" name="name" required class="form-control" />
                        </div>
                        <div class="form-group">
                            <span class="required">*</span>
                          <label>Email</label>
                          <input id="email" 
                            v-model="email"
                            @change="checkForEmailAvailability()"
                            type="email" 
                            class="form-control @error('email') @enderror"
                            :class="{'is_invalid' : this.email_unavailable}" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required
                            autocomplete="email">
                            
                        </div>
                        <div class="form-group">
                            <span class="required">*</span>
                          <label>Password</label>
                          <input type="password" name="password" required class="form-control" />
                        </div>
                        <div class="form-group">
                            <button
                            type="submit"
                              class="btn btn-sc-primary text-white  btn-block w-00 mt-4"
                            >
                              Daftar
                          </button>
                        </div>
                    </div>
                  </div>
                </form>
              </div>
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
    <script src="{{ asset('assets/vendor/vue/vue.js') }}"></script>
    <script src="https://unpkg.com/vue-toasted"></script>
    <script src="{{ asset('assets/vendor/axios/axios.min.js') }}"></script>

    <script>
        Vue.use(Toasted);

        var register = new Vue({
            el:"#register",
            mounted(){
                AOS.init();
            },
            data(){
                return{
                    email:"",
                    email_unavailable: false,
                    code:"",
                    code_unavailable: true
                }
            },
            methods:{
                checkForEmailAvailability: function(){
                var self = this;
                axios.get('{{ route('api-register-check') }}', {
                params:{
                    email:this.email
                }
                })
                .then(function (response) {

                    if(response.data == 'Available'){
                        self.$toasted.success(
                            "Email Anda tersedia, silahkan lanjut langkah selanjutnya!",
                            {
                            position: "top-center",
                            className: "rounded",
                            duration: 2000,
                            }
                        );
                        self.email_unavailable = false;

                    }else{
                        self.$toasted.error(
                        "Maaf, tampaknya email sudah terdaftar pada sistem.",
                        {
                            position: "top-center",
                            className: "rounded",
                            duration: 2000,
                        }
                    );
                    self.email_unavailable = true;

                    }
                    // handle success
                    console.log(response);
                    });
            },
            }
        })
    </script>
    <script src="/script/navbar-scroll.js"></script>
  </body>
</html>
