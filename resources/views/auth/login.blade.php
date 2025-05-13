@extends('layouts.app')

@section('content')
    <div class="container">
        <div class=" d-flex align-items-center justify-content-center" style="margin-top:200px;">
            <div class="gap-4 d-flex align-items-center">
                <div class="d-block-inline">
                    <div class="gap-3 mb-5 d-flex align-items-center">
                        <img src="/assets/images/images.png" alt="asd" width="100">
                        <div class="d-block">
                            <h6 class="fw-semibold">Aplikasi Pengelolaan Barang</h6>
                            <p class="">PT JMC Indonesia</p>
                        </div>
                    </div>
                    <div class="flex-column d-flex align-items-left">
                        <h3 class="fw-bold text-primary">LOGIN</h3>
                        <span class="mb-3" style="width: 400px;">Selamat datang silahkan masukan username dan
                            password</span>
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3 row">

                                <div class="">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autocomplete="email" placeholder="Email"
                                        autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3 row">


                                <div class="">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="current-password" placeholder="Password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-0 row">
                                <div class="">
                                    <button type="submit" class="w-full rounded btn btn-primary btn-block">
                                        {{ __('Login') }}
                                    </button>

                                </div>
                            </div>
                        </form>
                    </div>

                </div>
                <img src="/assets/images/images-gudang.jpg" alt="asd">

            </div>
        </div>
    </div>
@endsection
