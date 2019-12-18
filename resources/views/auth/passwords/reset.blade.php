@extends('layouts.login')

@section('content')
    <div id="main" class="animated fadeIn">
        <section id="content_wrapper">
            <section id="content">
                <div class="admin-form theme-info" id="login1">
                    <div class="row mb15 table-layout">
                        <div class="col-xs-4 va-m pln login-logo">
                            <a href="{{ asset('/') }}"><img alt="" src="{{ $publicUrl }}/images/{{ $config['logo_transparent'] }}"></a>
                        </div>
                        <div class="col-xs-8 text-right va-b pr5">
                            <div class="login-links fs20 text-right">
                                Change Password
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-info mt10 br-n">
                        {!!Form::open(['url' => route('forgot_password_reset'), 'id' => 'resetPasswordForm', 'name' => 'resetPasswordForm']) !!}
                        {!! Form::hidden('token', $token) !!}

                        <div class="panel-body bg-light p24">
                            <div class="row">
                                <div class="">
                                    @if (count($errors) > 0)
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            <i class="fa fa-hand-stop-o pr10"></i>
                                            @foreach ($errors->all() as $error)
                                                {{ $error }}<br />
                                            @endforeach
                                        </div>
                                    @endif
                                    <div class="section">
                                        <label for="email" class="field-label text-muted fs14 mb5">Email:<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="this field is required"></i></label>
                                        <label for="email" class="field prepend-icon">
                                            <input type="text" name="email" id="email" class="gui-input new-angle" placeholder="Email">
                                            <span class="field-icon"><i class="fa fa-envelope"></i></span>
                                        </label>
                                    </div>
                                    <div class="section">
                                        <label for="password" class="field-label text-muted fs14 mb5">Password:<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="this field is required"></i></label>
                                        <label for="password" class="field prepend-icon">
                                            <input type="password" name="password" id="password" class="gui-input new-angle" placeholder="Password">
                                            <span class="field-icon"><i class="fa fa-lock"></i></span>
                                        </label>
                                    </div>
                                    <div class="section">
                                        <label for="password_confirmation" class="field-label text-mute fs14 mb5">Confirm Password:<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="this field is required"></i></label>
                                        <label for="password_confirmation" class="field prepend-icon">
                                            <input type="password" name="password_confirmation" id="password_confirmation" class="gui-input new-angle" placeholder="Confirm Password">
                                            <span class="field-icon"><i class="fa fa-unlock-alt"></i></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer clearfix p10 ph15 text-center">
                            <button type="submit" class="btn btn-primary mr10 ">Accept</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </section>
        </section>
    </div>
@endsection

@section('js-files')
    <script>
        $(document).ready(function(){
            $('#resetPasswordForm').validate({
                rules: {
                    email: {
                        required: true,
                        email   : true
                    },
                    password:{
                        required : true,
                        password : true,
                        minlength: 6
                    },
                    password_confirmation:{
                        required : true,
                        password : true,
                        minlength: 6,
                        equalTo  : '#password'
                    }
                },
                messages: {
                    email: {
                        required: 'this field is required',
                        email: 'Invalid email'
                    },
                    password: {
                        required: 'this field is required',
                        rangelength: 'Password must have at least {0} characters.'
                    },
                    password_confirmation: 'Passwords don\'t match.'
                }
            });
        });
    </script>
@stop