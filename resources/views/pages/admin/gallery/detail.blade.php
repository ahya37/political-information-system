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
                @include('layouts.message')
              <div class="dashboard-heading">
                <h2 class="dashboard-title">Detail Galeri Event</h2>
                <p class="dashboard-subtitle">
                </p>
              </div>
              <div class="row mt-2">
                  <div class="col-12 mt-4">
                      <div class="card">
                          <div class="card-body">
                              <img class="img-fluid" src="{{ asset('storage/'.$event_gallery->file) }}" width="500px">
                             <form action="{{route('admin-event-gallery-update-foto', $event_gallery->id)}}" method="POST" enctype="multipart/form-data">
								@csrf
									<div class="form-group">
										<label for="recipient-name" class="col-form-label">Judul</label>
										<input type="text" name="title" class="form-control" id="recipient-name" value="{{ $event_gallery->title }}" required>
									</div>
									<div class="form-group">
										<label for="message-text" class="col-form-label">Deskripsi</label>
										<textarea class="form-control" name="desc" id="message-text" required>{{ $event_gallery->descr }}</textarea>
									</div>
									<div class="form-group">
										<label for="recipient-name" class="col-form-label" required>File (gambar)</label>
										<br>
										<input type="file" name="file" >
									</div>
								</div>
								<div class="modal-footer">
									<button type="submit" class="btn btn-sm btn-sc-primary text-white">Ubah</button>
								</div>
							</form>
                          </div>
                      </div>
                  </div>
              </div>
            </div>
          </div>
@endsection
