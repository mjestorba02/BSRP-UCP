@extends('layouts.app')

@section('content')

<div class="hero_area">
    <!-- header section strats -->
    <header class="header_section">
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top custom_nav-container" style="background: #fff;">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('landing') }}">
                <img src="{{ asset('images/logo.png') }}" class="img-fluid rounded-circle me-2" style="height: 40px;" alt="logo"/>
                <span class="brand-text" style="color: var(--scrolled-color);">Bloodline Streets Roleplay</span>
            </a>

            <!-- Right-aligned toggle button -->
            <button class="navbar-toggler ms-auto border-0" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars text-white fs-2"></i>
            </button>

            <!-- Collapsible content -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto text-center">
                <li class="nav-item active"><a class="nav-link" style="color: var(--scrolled-color);" href="{{ route('landing') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link" style="color: var(--scrolled-color);" href="#listPackages">Packages</a></li>
                <li class="nav-item"><a class="nav-link" style="color: var(--scrolled-color);" href="#featuredPackages">Featured Package</a></li>
                <li class="nav-item"><a class="nav-link" style="color: var(--scrolled-color);" href="#aboutus">About Us</a></li>
                <li class="nav-item"><a class="nav-link" style="color: var(--scrolled-color);" href="{{ route('login') }}">Sign In</a></li>
                </ul>
            </div>
            </nav>

    </div>
    </header>

  @include('partials.slider')
  </div>

  <section id="listPackages" class="job_section layout_padding-bottom">
      <div class="container">
          <div class="heading_container">
              <h2>
              <span>
                  Available Packages
              </span>
              </h2>
          </div>
          <div class="tab-content" id="myTabContent">
              <div class="job_board tab-pane fade show active" id="jb-1" role="tabpanel" aria-labelledby="jb-1-tab">
                  <div class="content-box">
                      <div class="content layout_padding2-top">
                          @foreach ($packages as $package)
                              <div class="box job-card">
                                  <h3>{{ $package->package_name }}</h3>
                                  <div class="job-details">
                                      <p><strong>Description:</strong> {{ $package->package_description }}</p>
                                      <p><strong>Price Range:</strong> {{ $package->package_price }}</p>
                                  </div>
                                  <a href="{{ url('application_form?package_id=' . $package->package_id) }}">Buy Now</a>
                              </div>
                          @endforeach
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </section>

  <section class="feature_section" id="featuredPackages">
    <div class="container-fluid">
      <div class="row">
        @if ($featuredPackage)
          <div class="col-md-5 offset-md-1">
            <div class="detail-box">
              <h2>Featured Package</h2>
              <h5><strong>{{ $featuredPackage->package_name }}</strong></h5>
              <p>{{ $featuredPackage->package_description }}</p>
              <p><strong>Price Range:</strong> {{ $featuredPackage->package_price }}</p>
              <a href="#" class="btn btn-primary mt-3">Buy Now</a>
            </div>
          </div>
          <div class="col-md-6 px-0">
            <div class="img-box">
              <img src="{{ asset('images/packages/' . $featuredPackage->package_image) }}" alt="{{ $featuredPackage->package_name }}" class="img-fluid">
            </div>
          </div>
        @else
          <div class="col-md-12 text-center">
            <p class="text-muted">No featured job at the moment.</p>
          </div>
        @endif
      </div>
    </div>
  </section>


  @include('partials.about')

    <footer class="container-fluid footer_section">
      <p>&copy; Copyright <strong>Cali</strong>. All Rights Reserved</p>
    </footer>
  @endsection

  @push('styles')
    <link rel="stylesheet" href="{{ asset('css/slider.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstraps.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mainstyle.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,700|Roboto:400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  @endpush

  @push('scripts')
    <script src="{{ asset('js/slider.js') }}"></script>
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="{{ asset('js/bootstraps.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
  @endpush