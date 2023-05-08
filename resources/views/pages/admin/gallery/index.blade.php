@extends('layouts.admin')
@section('title','Daftar Galeri Event')
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
                    <h2 class="dashboard-title">Galeri Event</h2>
                    <p class="dashboard-subtitle">
                    @include('layouts.message')
                </p>
              </div>
              <div class="row">
                  <div class="col-md-2 col-sm-12 mt-2">
                      <button class="btn btn-sm btn-sc-primary text-white" data-toggle="modal" data-target="#exampleModal">Tambah Foto / Gambar</button>
                  </div>
                  <div class="col-md-2 col-sm-12 mt-2">
                      <button class="btn btn-sm btn-sc-primary text-white" data-toggle="modal" data-target="#video">Tambah Video</button>
                  </div>
              </div>
              <div class="dashboard-content mt-4" id="transactionDetails">
                  <div id="loadResult" class="d-none lds-dual-ring hidden overlay">
                  </div>
                  <div class="row mt-4" id="result">
                  </div>
              </div>
            </div>
          </div>
@endsection

@push('prepend-script')
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Event {{ $event->title }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('admin-event-gallery-store', $event->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
                <div class="form-group">
                    <label for="recipient-name" class="col-form-label">Judul</label>
                    <input type="hidden" name="event_id" id="eventId" value="{{ $event->id }}">
                    <input type="text" name="title" class="form-control" id="recipient-name" required>
                </div>
                <div class="form-group">
                    <label for="message-text" class="col-form-label">Deskripsi</label>
                    <textarea class="form-control" name="desc" id="message-text" required></textarea>
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="col-form-label" required>File (gambar)</label>
                    <br>
                    <input type="file" name="file" >
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-sm btn-sc-primary text-white">Simpan</button>
            </div>
        </form>

        </div>
    </div>
</div>
    <div class="modal fade" id="video" tabindex="-1" role="dialog" aria-labelledby="videoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="videoModalLabel">Event {{ $event->title }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('admin-event-gallery-store-video', $event->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
                <div class="form-group">
                    <label for="recipient-name" class="col-form-label">Judul</label>
                    <input type="hidden" name="event_id" id="eventId" value="{{ $event->id }}">
                    <input type="text" name="title" class="form-control" id="recipient-name" required>
                </div>
                <div class="form-group">
                    <label for="message-text" class="col-form-label">Deskripsi</label>
                    <textarea class="form-control" name="desc" id="message-text" required></textarea>
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="col-form-label" required>File (Video)</label>
                    <br>
                    <input type="file" name="file" >
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-sm btn-sc-primary text-white">Simpan</button>
            </div>
        </form>

        </div>
    </div>
</div>
@endpush
@push('addon-script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/event.js') }}"></script>
@endpush