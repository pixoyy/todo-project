@extends('base')

@section('content')
    <div class="container">
        <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-sm-8 d-flex flex-column align-items-center justify-content-center">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="py-2">
                                    <div class="d-flex flex-column align-items-center justify-content-center logo w-100">
                                        <img src="{{ asset('images/logo.png') }}" alt="Logo">
                                        <h5 class="card-title text-center text-blue py-0 fs-4">Simple tool to manage your daily tasks</h5>
                                    </div>
                                    <p class="text-center small">Enter your email & password to login</p>
                                </div>
                                <form class="row g-3" method="POST" action="{{ route('login_post') }}">
                                    @csrf
                                    @error('auth')
                                        <div class="col-12">
                                            <div class="alert alert-danger py-1">{{ $message }}</div>
                                        </div>
                                    @enderror

                                    <div class="col-12">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control border-grey @error('auth') is-invalid @enderror"
                                            id="email" placeholder="Email address" value="{{ old('email') }}" required>
                                    </div>

                                    <div class="col-12">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" name="password" class="form-control border-grey @error('auth') is-invalid @enderror"
                                            id="password" placeholder="Password" required>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-check">
                                        <input class="form-check-input border-grey" type="checkbox" name="remember" value="true" id="remember" @checked(old('remember'))>
                                        <label class="form-check-label" for="remember">Remember me</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button class="btn btn-blue w-100" type="submit">Login</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
