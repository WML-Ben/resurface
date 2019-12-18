@extends('layouts.layout')

@section('breadcrumbs')
    <div class="mb15">
        <ul class="page-breadcrumb breadcrumb">
            <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
            <li><a href="{{ route('proposal_list') }}">Proposals</a><i class="fa fa-angle-right"></i></li>
            <li><span>Details</span></li>
        </ul>
    </div>
@stop

@section('content')
    <section id="content" class="animated fadeIn list-items admin-form plr0">
        <div class="row">
            <div class="col-md-12" id="progress_steps">
                {{--
                <div class="tab-block mb25 progress-steps">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#client_tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-user mr10"></i>Client</a>
                        </li>
                        <li class="">
                            <a href="#services_tab" data-toggle="tab" aria-expanded="true"><i class="icon-cog-alt mr10"></i>Services</a>
                        </li>
                        <li class="">
                            <a href="#upload_tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-upload mr10"></i>Media</a>
                        </li>
                        <li class="">
                            <a href="#email_tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-envelope mr10"></i>Email</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="client_tab" class="tab-pane active">
                            <div class="portlet blue-hoki box">
                                <div class="portlet-title">
                                    <div class="caption">Name of Proposal</div>
                                </div>

                                <div class="portlet-body">
                                    <p><b>Services - </b>Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.</p>
                                </div>
                            </div>
                            <div class="portlet blue-hoki box">
                                <div class="portlet-title">
                                    <div class="caption">Assigned To</div>
                                </div>

                                <div class="portlet-body">
                                    <p><b>Services - </b>Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.</p>
                                </div>
                            </div>
                        </div>
                        <div id="services_tab" class="tab-pane">
                            <h2 class="form-section-divider">Name of Proposal</h2>
                            <p><b>Services - </b>Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.</p>
                        </div>
                        <div id="upload_tab" class="tab-pane">
                            <p><b>Upload - </b>Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.</p>
                        </div>
                        <div id="email_tab" class="tab-pane">
                            <p><b>Email - </b>Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.</p>
                        </div>
                    </div>
                </div>
                --}}

                <div class="panel">
                    <div class="panel-heading">
                        <ul class="nav panel-tabs-border panel-tabs panel-tabs-left">
                            <li class="active"><a href="#client_tab" data-toggle="tab">Client</a></li>
                            <li><a href="#services_tab" data-toggle="tab">Services</a></li>
                            <li><a href="#upload_tab" data-toggle="tab">Media</a></li>
                            <li><a href="#preview_tab" data-toggle="tab">Preview</a></li>
                            <li><a href="#email_tab" data-toggle="tab">Email</a></li>
                        </ul>
                    </div>

                    <div class="panel-body pt10">
                        <div class="tab-content pn br-n">
                            <div id="client_tab" class="tab-pane active">
                                <div class="row">
                                    <div class="col-md-12 plr0">

                                    </div>
                                </div>
                            </div>
                            <div id="services_tab" class="tab-pane">
                                <div class="row">
                                    <div class="col-md-12 plr0">

                                    </div>
                                </div>
                            </div>
                            <div id="upload_tab" class="tab-pane">
                                <div class="row">
                                    <div class="col-md-12 plr0">

                                    </div>
                                </div>
                            </div>
                            <div id="preview_tab" class="tab-pane">
                                <div class="row">
                                    <div class="col-md-12 plr0">

                                    </div>
                                </div>
                            </div>
                            <div id="email_tab" class="tab-pane">
                                <div class="row">
                                    <div class="col-md-12 plr0">

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@stop

@section('css-page-level-plugins')
    {!! Html::style($publicUrl . '/css/tomcssK.css') !!}
@stop

@section('js-page-level-scripts')

@stop

@section('js-files')
    <script>


        $(function(){

        });

    </script>
@stop