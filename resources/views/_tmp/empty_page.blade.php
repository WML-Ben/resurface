@extends('layouts.layout')

@section('breadcrumbs')
    <div class="mb15">
        <ul class="page-breadcrumb breadcrumb">
            <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
            <li><span>Empty Page</span></li>
        </ul>
    </div>
@stop

@section('content')
    <section id="content" class="animated fadeIn list-items admin-form plr0">
        <div class="row">
            <div class="col-md-12" id="proposal_steps">
                <div id="main-wrapper p0">
                    <div id="content-wrapper">
                        <div class="stat-panel">
                            <div class="stat-row">

                                <div class="portlet mt-element-ribbon royal-blue box">
                                    <div class="portlet-title">
                                        <div id="porlet_caption_proposal_name" class="caption">First Box</div>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                Info here
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="portlet blue-hoki box">
                                    <div class="portlet-title">
                                        <div class="caption">Second Box</div>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <p>Second data here</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <a href="javascript:;" id="show_modal_button" class="btn btn-primary">Show Modal</a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="modal_example" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content jform-container">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                    <h4 class="modal-title">Select Service</h4>
                </div>
                <div class="modal-body jform-body p20 fs15">
                    <div class="row">
                        <div class="col-sm-12">
                            modal body
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button id="modal_accept_button" type="button" class="btn btn-primary">Accept</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css-page-level-plugins')
    {!! Html::style($publicUrl . '/css/tomcss.css') !!}
@stop

@section('js-page-level-scripts')
@stop

@section('js-files')
    <script>
        $(function(){

            $('#show_modal_button').click(function(){
                $('#modal_example').modal('show');
            });
        });

    </script>
@stop