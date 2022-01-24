@extends('layouts.app')
@section('title','Buat Akun')
@push('addon-style')
    <link href="{{ asset('assets/style/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet" />
@endpush
@section('content')
<!-- Section Content -->
 <div
            class="section-content section-dashboard-home mb-4"
            data-aos="fade-up"
          >
            <div class="container-fluid">
                <div class="dashboard-heading">
                    <h2 class="dashboard-title">Buat Akun Baru</h2>
                <p class="dashboard-subtitle">
                </p>
            </div>
            <div class="dashboard-content mt-4" id="transactionDetails">
                <div class="row">
                    <div class="col-md-7 col-sm-12">
                      @include('layouts.message')
                                <form action="{{ route('member-create-account-store', $user->id) }}" id="register" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="card">
                                    <div class="card-body">
                                    <div class="row row-login">
                                            <div class="col-12">
                                    <div class="form-group">
                                        <span class="required">*</span>
                                    <label>Nama</label>
                                    <input type="text" readonly value="{{ $user->name }}" class="form-control" />
                                    </div>
                                    <div class="form-group">
                                        <span class="required">*</span>
                                    <label>Email</label>
                                    <input id="email" 
                                        v-model="email"
                                        @change="checkForEmailAvailability()"
                                        type="email" 
                                        class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                        :class="{'is_invalid' : this.email_unavailable}" 
                                        name="email" 
                                        value="{{ old('email') }}" 
                                        required
                                        autocomplete="email">

                                         @if ($errors->has('email'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                        
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
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
@endsection

@push('addon-script')
<script src="{{ asset('assets/vendor/vue/vue.js') }}"></script>
<script src="https://unpkg.com/vue-toasted"></script>
<script src="https://unpkg.com/vue-toasted"></script>
<script src="{{ asset('assets/vendor/axios/axios.min.js') }}"></script>
{{-- <script>
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
                            "Email Anda tersedia, silahkan lanjut!",
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
    </script> --}}
@endpush