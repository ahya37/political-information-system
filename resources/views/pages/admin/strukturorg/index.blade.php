<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <title>Struktur Organisasi Sekolah</title>

    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
      integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="{{asset('assets/strukturorg/js/jquery/ui-lightness/jquery-ui-1.10.2.custom.css')}}"
    />
    <link
      href="{{asset('assets/strukturorg/css/primitives.latest.css')}}"
      media="screen"
      rel="stylesheet"
      type="text/css"
    />
  </head>
  <body>
    <div class="container-fluid">
      <div class="row mb-4">
        <nav class="navbar fixed-top navbar-light bg-light">
          <div class="col-md-2">
            <div class="form-group">
                <select name="level" id="province" required class="form-control filter" required>
                    <option value="">-Pilih Provinsi-</option>
                    @foreach ($province as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
                <select name="" id="selectArea"  class="form-control filter" required></select>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
                <select name="dapil_id" id="selectListArea"  class="form-control filter" required></select>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
                <select name="district_id" id="selectDistrictId"  class="form-control filter"></select>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
                <select name="village_id" id="selectVillageId"  class="form-control filter"></select>
            </div>
          </div>
        </nav>
      </div>
      <div class="row mt-4">
        <div class="col-md-12 mt-4" >
            <div class="col-md-12 mt-4" >
                <div id="loading"></div>
              </div>
          <div
            id="korpus"
            style="height: 1000px"
            class="mt-4"
          ></div>
        </div>
      </div>
    </div>

    <script type="text/javascript" src="{{asset('assets/strukturorg/js/jquery/jquery-1.9.1.js')}}"></script>
    <script
      type="text/javascript"
      src="{{asset('assets/strukturorg/js/jquery/jquery-ui-1.10.2.custom.min.js')}}"
    ></script>

    <script type="text/javascript" src="{{asset('assets/strukturorg/js/primitives.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/orgdiagram.js')}}"></script>
  </body>
</html>
