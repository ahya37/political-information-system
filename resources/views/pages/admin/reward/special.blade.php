@extends('layouts.admin')
@section('title','Reward')
@push('addon-style')
 <link
      href="{{ asset('assets/style/style.css') }}"
      rel="stylesheet"
    />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css"/>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/daterangepicker/daterangepicker.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="{{ asset('assets/vendor/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet">
@endpush
@section('content')
<!-- Section Content -->
 <div
            class="section-content section-dashboard-home mb-4"
            data-aos="fade-up"
          >
            <div class="container-fluid">
              <div class="dashboard-heading">
                <h2 class="dashboard-title">Reward Khusus Referal</h2>
                <p class="dashboard-subtitle">
                </p>
              </div>
              <div class="dashboard-content mt-4" id="transactionDetails">
                <div class="row">
                    <div class="input-group mb-3 col-md-3 ">
                        <a href="{{route('admin-reward-special-referal-report')}}" class="btn btn-sm btn-sc-primary text-light float-right">Download</a>
                    </div>
                  </div>
                <div class="row">
                  <div class="col-12">
                    <div class="card">
                      <div class="card-body">
                        <div class="row">
                          <div class="col-12">
                            <div class="table-responsive">
                              <table id="datatable" class="table table-sm table-striped" width="100%">
                                  <thead>
                                    <tr>
                                      <th class="col-2">NAMA</th>
                                      <th class="col-1">ALAMAT</th>
                                      <th class="col-1">TOTAL REFERAL</th>
                                      <th class="col-1">NOMINAL</th>
                                    </tr>
                                  </thead>
                                <tbody id="showReferalPoint"></tbody>
                              </table>
                             </div>
                        </div>
                      </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
@endsection
{{-- @push('prepend-script')
  <div class="modal fade" id="setPoint" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('admin-customvoucheradmin') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <input type="hidden" name="userId" id="userId" class="form-control">
            <input type="hidden" name="point" id="point" class="form-control">
            <input type="hidden" name="nominal" id="nominal" class="form-control">
            <input type="hidden" name="input"  id="input" class="form-control">
            <input type="number" name="pointReq" id="pointReq" class="form-control" placeholder="Jumlah poin yang akan diberikan">
          </div>
              <div class="form-group float-right">
                <button type="submit" class="btn btn-sc-primary">Simpan</button>
              </div>
            </div>
          </form>
        </div>
    </div>
  </div>
</div>
<div class="modal fade" id="setBank" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <label>Nomor Rekening</label>
            <input type="text"  id="bankNumber" class="form-control" readonly>
          </div>
          <div class="form-group">
            <label>Nama Pemilik</label>
            <input type="text"  id="bankOwner" class="form-control" readonly>
          </div>
          <div class="form-group">
            <label>Nama Bank</label>
            <input type="text"  id="bankName" class="form-control" readonly>
          </div>
          </div>
        </div>
    </div>
  </div>
@endpush --}}
@push('addon-script')
<script type="text/javascript" src="{{ asset('assets/vendor/moments/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendor/daterangepicker/daterangepicker.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
<script src="{{ asset('assets/vendor/sweetalert2/dist/sweetalert2.min.js') }}"></script>
<script src="{{ asset('js/reward-special-referal.js') }}"></script>
{{-- <script type="text/javascript" src={{ asset('/js/reward-member-admin.js') }}></script> --}}
 {{-- <script>
      $('#setPoint').on('show.bs.modal', function (event) {
        let button = $(event.relatedTarget) 
        let recipient = button.data('name') 
        let userId = button.data('id')
        let point = button.data('point')
        let nominal = button.data('nominal')
        let input = button.data('input')
        let modal = $(this)
        modal.find('.modal-title').text('Sesuaikan poin kepada : ' + recipient)
        modal.find('.modal-body #userId').val(userId)
        modal.find('.modal-body #point').val(point)
        modal.find('.modal-body #nominal').val(nominal)
        modal.find('.modal-body #input').val(input)
        modal.find('.modal-body #pointReq').attr({"max" : point})
      })

      $('#setBank').on('show.bs.modal', function (event) {
        let button = $(event.relatedTarget) 
        let recipient = button.data('name') 
        let bankNumber = button.data('banknumber')
        let bankOwner = button.data('bankowner')
        let bankName = button.data('bankname')
        let modal = $(this)
        modal.find('.modal-body #bankNumber').val(bankNumber)
        modal.find('.modal-body #bankOwner').val(bankOwner)
        modal.find('.modal-body #bankName').val(bankName)
        modal.find('.modal-title').text('Rekening Bank : ' + recipient)
      })
    </script> --}}
@endpush