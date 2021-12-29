@extends('layouts.admin')
@section('title','Detail Reward')
@push('addon-style')
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
                <h2 class="dashboard-title">Detail Reward Referal {{ $member->name }}</h2>
                <p class="dashboard-subtitle">
                </p>
              </div>
              <div class="dashboard-content mt-4" id="transactionDetails">
                <div class="row">
                    @foreach ($listVucher as $item)
                    <div class="col-md-4 col-sm-4">
                      <div class="card">
                        <div class="card-body">
                            <div class="fa fa-tags"></div>
                            <h5 class="text-center">Poin</h5>
                            <h5 class="text-center">{{ $item->point }}</h5>
                            <div class="mt-4">
                                 <small class="float-right">Tanggal : {{ $item->created_at }}</small>
                            </div>
                            <div class="mr-2">
                                 <a href="{{ route('voucherreferal-download', $item->id) }}"><i class="fa fa-download"></i></a>
                            </div>
                        </div>
                        <div class="card-footer bg-info">
                            <h5 class="text-center text-white"> Rp. {{ $gF->decimalFormat($item->nominal) }}</h5>
                           
                        </div>
                      </div>
                    </div>
                    @endforeach
                </div>
              </div>
            </div>
          </div>
@endsection

@push('addon-script')
@endpush