<!DOCTYPE html>
<html lang="{{ app()->getLocale()=='ar'?'ar':'en' }}" dir="{{ app()->getLocale()=='ar'?'rtl':'' }}">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ url('dashboard') }}/assets/img/dashboard-icon.png">
    <link rel="icon" type="image/png" href="{{ url(config('dash.DASHBOARD_ICON')) }}">
    <title>{{ __('dash::dash.login') }}</title>
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Cairo:300,400,500,700,900|Cairo+Slab:400,700" />

    <!-- Font Awesome Icons -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"  />
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <!-- CSS Files -->
    <link id="pagestyle" href="{{ url('dashboard') }}/assets/css/material-dashboard.css?v=3.0.4" rel="stylesheet" />
  </head>
  <body class="bg-gray-200">
    <div class="container position-sticky z-index-sticky top-0">
      <div class="row">
        <div class="col-12">
          <!-- Navbar -->
          <!-- End Navbar -->
        </div>
      </div>
    </div>
    <main class="main-content  mt-0">
      <div class="page-header align-items-start min-vh-100" style="background-image: url('{{ url('dashboard/assets/img/bg.jpeg') }}');">
        <span class="mask bg-gradient-dark opacity-6"></span>
        <div class="container my-auto">
          <div class="row">
            <div class="col-lg-4 col-md-8 col-12 mx-auto">
              <div class="card z-index-0 fadeIn3 fadeInBottom">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                  <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1">
                    <h4 class="text-white font-weight-bolder text-center mt-2 mb-0"><i class="fa-solid fa-gauge"></i> {{ __('dash::dash.login') }}</h4>
                  </div>
                </div>
                <div class="card-body">
                  @if(session()->has('error'))
                  <div class="alert alert-warning">
                    {{ session('error') }}
                  </div>
                  @endif
                  <form role="form" method="post" action="{{ route(app('dash')['DASHBOARD_PATH'].'.login') }}" class="text-start">
                    @csrf
                    <input type="hidden" name="_method" value="post">
                    <div class="input-group input-group-outline my-3">
                      <label class="form-label">{{ __('dash::dash.email') }}</label>
                      <input type="email" name="email" value="{{ old('email') }}" class="form-control {{ $errors->has('email')?'is-invalid':'' }}">
                      @error('email')
                      <p class="invalid-feedback">{{ $message }}</p>
                      @enderror
                    </div>
                    <div class="input-group input-group-outline mb-3">
                      <label class="form-label">{{ __('dash::dash.password') }}</label>
                      <input type="password" name="password" class="form-control {{ $errors->has('password')?'is-invalid':'' }}">
                      @error('password')
                      <p class="invalid-feedback">{{ $message }}</p>
                      @enderror
                    </div>
                    <div class="form-check form-switch align-items-center mb-3">
                      <input class="form-check-input" name="remember_me" value="yes" type="checkbox" id="rememberMe"  >
                      <label class="form-check-label mb-0 ms-3" for="rememberMe">{{ __('dash::dash.remember_me') }}</label>
                    </div>
                    <div class="text-center">
                      <button type="submit" class="btn bg-gradient-primary w-100 my-4 mb-2">{{ __('dash::dash.signin') }} <i class="fa-solid fa-right-to-bracket"></i></button>
                    </div>
                    {{--  <p class="mt-4 text-sm text-center">
                      Don't have an account?
                      <a href="{{ url('dashboard') }}/pages/sign-up.html" class="text-primary text-gradient font-weight-bold">Sign up</a>
                    </p>  --}}
                    <p class="mt-4 text-sm text-center">
                        <a href="{{ route('ask.forgetpassword') }}" class="text-primary text-gradient font-weight-bold">   {{ __('dash::dash.forgetpassword') }}
                        </a>
                    </p>
              {{--  @if(!empty($DASHBOARD_LANGUAGES) && count($DASHBOARD_LANGUAGES) > 1)
              @foreach($DASHBOARD_LANGUAGES as $key=>$value)
               <a href="{{ url($DASHBOARD_PATH.'/change/language/'.$key) }}">
                  <small>{{ $value }}</small>
                </a>,
              @endforeach
              @endif  --}}
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
        <footer class="footer position-absolute bottom-2 py-2 w-100">
          <div class="container">
            <div class="row align-items-center justify-content-lg-between">
              <div class="col-12 col-md-6 my-auto">

              </div>
              <div class="col-12 col-md-6">
                <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                  <li class="nav-item">
                    @if(!empty(config('dash.copyright')))

              <a href="{{config('dash.copyright.link')}}" class="font-weight-bold" target="_blank">{!! config('dash.copyright.copyright_text') !!}</a>

              @else
                    {{--  <a href="https://phpdash.com" class="nav-link text-white" target="_blank">By Mahmoud Ibrahim - dash    </a>  --}}
              @endif
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </footer>
      </div>
    </main>
    <!--   Core JS Files   -->
    <script src="{{ url('dashboard') }}/assets/js/core/popper.min.js"></script>
    <script src="{{ url('dashboard') }}/assets/js/core/bootstrap.min.js"></script>
    <script src="{{ url('dashboard') }}/assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="{{ url('dashboard') }}/assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
    var options = {
    damping: '0.5'
    }
    Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
    </script>
         <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/js/all.min.js" ></script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ url('dashboard') }}/assets/js/material-dashboard.min.js?v=3.0.4"></script>
  </body>
</html>
