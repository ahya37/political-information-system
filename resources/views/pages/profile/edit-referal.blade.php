@extends('layouts.app')
@section('title','Edit Anggota')
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
                <h2 class="dashboard-title">Edit Referal Anggota {{ $profile->name }}</h2>
                <p class="dashboard-subtitle">
                    Informasi Detail Profil
                </p>
              </div>
              <div class="dashboard-content mt-4" id="transactionDetails">
                <div class="row">
                  <div class="col-md-7 col-sm-12">
                    @include('layouts.message')
                    <form action="{{ route('user-profile-update-referal', $profile->id) }}" id="register" method="POST" enctype="multipart/form-data">
                      @csrf
                      <div class="card">
                        <div class="card-body">
                         <div class="row row-login">
                                <div class="col-12">
                                    <div class="form-group">
                                            <span class="required">*</span>
                                                <label>Berikan alasan Anda mengapa ingin merubah referal</label>
                                            <textarea name="reason" required class="form-control"></textarea>
                                    </div>
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
                                                    required
                                                    >
                                            </div>
                                    <div class="form-group">
                                        <button
                                        type="submit"
                                        class="btn btn-sc-primary text-white  btn-block w-00 mt-4"
                                        >
                                        Simpan
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
{{-- <script src="{{asset('assets/select2/dist/js/select2.min.js')}}"></script> --}}
<script src="{{ asset('assets/vendor/vue/vue.js') }}"></script>
<script src="https://unpkg.com/vue-toasted"></script>
<script src="{{ asset('assets/vendor/axios/axios.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datetimepicker/jquery.datetimepicker.full.min.js') }}"></script>
<script>
      $(document).ready(function(){
        jQuery('#datetimepicker6').datetimepicker({
            timepicker:false,
            format:'d-m-Y'
            });
            $.datetimepicker.setLocale('id');
    });

      Vue.use(Toasted);
      var register = new Vue({
        el: "#register",
        mounted() {
          AOS.init();
        },
        data(){
          return  {
            code: "{{ $profile->referal_code }}"

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
      });
    </script>
@endpush