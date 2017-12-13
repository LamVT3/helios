@extends('layouts.master')

@section('content')
    <!-- ==========================CONTENT STARTS HERE ========================== -->
    <!-- MAIN PANEL -->
    <div id="main" role="main" style="margin: 0">

        <!-- MAIN CONTENT -->

        {{--<form class="lockscreen animated flipInY" role="form" method="POST" action="">
            <div class="logo">
                <h1 class="semi-bold"><img src="{{ URL::to('img/logo-o.png') }}" alt=""/> SmartAdmin</h1>
            </div>
            <div>
                <img src="{{ URL::to('img/avatars/sunny-big.png') }}" alt="" width="120" height="120"/>
                <div>
                    <h1><i class="fa fa-user fa-3x text-muted air air-top-right hidden-mobile"></i>John Doe
                        <small><i class="fa fa-lock text-muted"></i> &nbsp;Locked</small>
                    </h1>
                    <p class="text-muted">
                        <a href="mailto:simmons@smartadmin">john.doe@smartadmin.com</a>
                    </p>

                    <div class="input-group">
                        <input class="form-control" type="password" placeholder="Password">
                        <div class="input-group-btn">
                            <button class="btn btn-primary" type="submit">
                                <i class="fa fa-key"></i>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
            <p class="font-xs margin-top-5">
                Copyright SmartAdmin 2014-2020.

            </p>
        </form>--}}
        <div class="well no-padding lockscreen">
            <div class="text-center">
                <h1><img src="{{ URL::to('img/logo.png') }}" alt="Logo"/></h1>
            </div>
            <form method="post" action="{{ route('login') }}" id="login-form" class="smart-form client-form">
                <header>
                    Sign In
                </header>

                <fieldset>
                    {{ csrf_field() }}
                    <section class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label class="label">E-mail</label>
                        <label class="input"> <i class="icon-append fa fa-user"></i>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus>
                            <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Please enter
                                email address/username</b></label>
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                    </section>

                    <section class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label class="label">Password</label>
                        <label class="input"> <i class="icon-append fa fa-lock"></i>
                            <input type="password" name="password" required>
                            <b class="tooltip tooltip-top-right"><i class="fa fa-lock txt-color-teal"></i> Enter your
                                password</b> </label>
                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                        {{--<div class="note">
                            <a href="forgotpassword.php">Forgot password?</a>
                        </div>--}}
                    </section>

                    <section>
                        <label class="checkbox">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <i></i>Stay signed in</label>
                    </section>
                </fieldset>
                <footer>
                    <button type="submit" class="btn btn-primary">
                        Sign in
                    </button>
                </footer>
            </form>
        </div>

    </div>
    <!-- END MAIN PANEL -->
    <!-- ==========================CONTENT ENDS HERE ========================== -->
@endsection
