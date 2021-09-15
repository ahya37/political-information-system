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

    <title>Reveral</title>

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
              <h5 class="mb-3 text-center">
                <img src="{{ asset('assets/images/id-card.svg') }}" width="70">
                <div class="col-lg-12">
                  <div class="mt-3 alert alert-danger" align="left" style="font-size: 14px">
                    <p>Kode Reveral adalah kode yang dimiliki <br> oleh orang yang merekomendasikan Anda.</p>
                    <p>Jika tidak mengetahui, silahkan tanyakan <br> kepada yang merekomendasikan Anda</p>
                  </div>
                </div>
              </h5>
              <div class="col-lg-12 mt-4">
                <form action="{{  route('user-store-reveral', $user->id)  }}" method="POST" id="register" enctype="multipart/form-data">
                  @csrf
                  <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <span class="required">*</span>
                          <label>Kode Reveral</label>
                          <input id="code" 
                            v-model="code"
                            @change="checkForReveralAvailability()"
                            type="text" 
                            class="form-control @error('code') @enderror"
                            :class="{'is_invalid' : this.code_unavailable}" 
                            name="code" 
                            value="{{ old('code') }}" 
                            required
                            >
                            
                        </div>

                        <div class="form-group">
                            <button
                            type="submit"
                              class="btn btn-sc-primary text-white  btn-block w-00 mt-4"
                            >
                              Simpan dan Lanjutkan
                          </button>
                        </div>
                    </div>
                  </div>
                </form>
              </div>
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
                    code:"",
                    code_unavailable: true
                }
            },
            methods:{
              checkForReveralAvailability: function(){
                  var self = this;
                  axios.get('{{ route('api-reveral-check') }}', {
                  params:{
                      code:this.code
                  }
                  })
                  .then(function (response) {

                      if(response.data == 'Available'){

                        // get name where code
                          axios.get('{{ url('api/reveral/name') }}/' + this.code.value)
                                  .then(function(res){   
                                    self.$toasted.success(
                                        "Reveral tersedia atas Nama " + res.data.name,
                                        {
                                        position: "top-center",
                                        className: "rounded",
                                        duration: 3000,
                                        }
                                    );
                                  });
                          self.code_unavailable = true;

                      }else{
                          self.$toasted.error(
                          "Reveral tidak tersedia.",
                          {
                              position: "top-center",
                              className: "rounded",
                              duration: 3000,
                          }
                      );
                      self.code_unavailable = false;

                      }
                      // handle success
                      // console.log(response);
                      });
              },
            }
        })
    </script>
    <script src="/script/navbar-scroll.js"></script>
  </body>
</html>
