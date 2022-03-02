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
                @include('layouts.message')
                <div class="row">
                    @foreach ($listVucher as $item)
                    <div class="col-md-4 col-sm-4">
                      <div class="card">
                        <div class="card-body">
                            <div class="fa fa-tags"></div>
                             <div class="row">
                                        <small class="col-12">Voucher Referal</small>
                                        </div>
                            <h5 class="text-center">Poin</h5>
                            <h5 class="text-center">{{ $item->point }}</h5>
                            <div class="mt-4">
                                 <small class="float-right">Tanggal : {{ $item->created_at }}</small>
                            </div>
                            @if ($item->tf == null)
                            <div class="mr-2">
                                 <button class="btn btn-sm btn-sc-primary text-white "  type="button" data-id="{{ $item->id }}"  data-toggle="modal" data-target="#setPoint{{ $item->id }}">Upload Bukti</button>
                            </div>
                            @else
                            <a href="{{ asset('storage/'.$item->tf) }}" target="_blank">
                              <i class="fa fa-download"></i>
                              <small>Bukti</small>
                            </a>
                            @endif
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
@push('prepend-script')
@foreach ($listVucher as $item)
  <div class="modal fade" id="setPoint{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Upload Bukti</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('admin-vouvhertf-upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <label>Nominal</label>
            <input type="hidden" name="VoucherId" id="VoucherId" value="{{ $item->id }}" class="form-control">
            <input type="text" name="nominal" value="{{ $item->nominal }}" class="form-control">
            <input type="hidden" name="type" value="referal">
          </div>
          <div class="form-group">
            <label>Bukti Transfer</label>
            <input type="file" name="file" class="form-control">
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
 @endforeach
@endpush

@push('addon-script')
@endpush