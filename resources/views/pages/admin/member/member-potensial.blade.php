@extends('layouts.admin')
@section('title','Anggota Potensial')
@push('addon-style')
 <link
      href="{{ asset('assets/style/style.css') }}"
      rel="stylesheet"
    />
         <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css"/>
      <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

@endpush
@section('content')
<!-- Section Content -->
 <div
            class="section-content section-dashboard-home mb-4"
            data-aos="fade-up"
          >
            <div class="container-fluid">
              <div class="dashboard-heading">
                <h2 class="dashboard-title">Anggota Potensial</h2>
                <p class="dashboard-subtitle">
                </p>
              </div>
              <div class="row mt-4">
                <div class="col-12">
                  <div class="card shadow bg-white rounded">
                    <div class="card-body">
                     <div class="col-4">
                       <form>
                         <div class="form-group">
                           <i class="fa fa-filter" aria-hidden="true"></i>
                           <label>Berdasarkan</label>
                           <select id="filterMember" name="filter" class="form-control form-control-sm">
                             <option value="">--</option>
                             <option value="referal">Referal</option>
                             <option value="input">Input</option>
                           </select>
                         </div>
                       </form>
                     </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="dashboard-content mt-4" id="transactionDetails">
                
                <div class="row">
                  <div class="col-12">
                    @include('layouts.message')
                    <div class="card">
                      <div class="card-body">
                        <div id="members"></div>
                       <div class="table-responsive">
                                  <table id="data"  class="table table-sm table-striped" width="100%">
                                    <thead>
                                      <tr>
                                        <th scope="col" id="nama"></th>
                                        <th scope="col" id="jml"></th>
                                        <th scope="col" id="aksi"></th>
                                      </tr>
                                    </thead>
                                    <tbody id="showData">
                                     
                                    </tbody>
                                    <tfoot>
                                     <tr>
                                       <td colspan="5" id="Loadachievment" class="d-none lds-dual-ring hidden overlay">
                                       </td>
                                      </tr>
                                   </tfoot>
                                  </table>
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
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
<script src="{{ asset('js/member-nation.js') }}" ></script>
<script>
</script>
@endpush