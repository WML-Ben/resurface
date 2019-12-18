@extends('layouts.login')

@section('content')
    <div id="main" class="animated fadeIn">
        <section id="content_wrapper">
            <section id="content">
                <div class="admin-form theme-info" id="login1">
                    <div class="row mb15 table-layout">
                        <div class="col-xs-4 va-m pln login-logo">
                            <a href="{{ route('dashboard') }}"><img src="{{ $publicUrl }}/images/{{ $config['logo_transparent'] }}" alt=""></a>
                        </div>
                        <div class="col-xs-8 text-right va-b pr5">
                            <div class="login-links fs20 text-right">
                                Change Password
                            </div>
                        </div>
                    </div>

                    @if (session('status'))
                        <div class="panel panel-info mt10 br-n">
                            <div class="panel-body bg-light p24">
                                <div class="row">
                                    <div class="">
                                        <div class="alert alert-success">
                                            {{ session('status') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="panel panel-info mt10 br-n">
                            {!!Form::open(['url' => route('forgot_password_send_reset_link'), 'id' => 'forgotPasswordForm', 'name' => 'forgotPasswordForm']) !!}
                            <div class="panel-body bg-light p24">
                                <div class="row">
                                    <div class="">
                                        @if (session('status'))
                                            <div class="alert alert-success">
                                                {{ session('status') }}
                                            </div>
                                        @endif

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
                                                <input type="text" name="email" id="email" class="gui-input new-angle" placeholder="Email" value="{{ old('email') }}">
                                                <span class="field-icon"><i class="fa fa-envelope"></i></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer clearfix p10 ph15 text-center">
                                <button type="submit" class="btn btn-primary mr10 ">Send Link</button>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    @endif
                </div>
            </section>
        </section>
    </div>
@endsection

@section('js-files')
    <script>
        $(document).ready(function(){
            $('#forgotPasswordForm').validate({
                rules: {
                    email: {
                        required: true,
                        email   : true
                    }
                },
                messages: {
                    email: {
                        required: 'this field is required',
                        email: 'Invalid email'
                    }
                }
            });
        });
    </script>
@stop
