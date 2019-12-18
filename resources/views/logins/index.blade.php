@extends('layouts.layout')

@section('content-header')
    <header id="topbar">
        <div class="topbar-left">
            <ol class="breadcrumb">
                <li class="crumb-active">
                    <a href="javascript:void(0)" class="no-link">Logins</a>
                </li>
                <li class="crumb-trail">List</li>
            </ol>
        </div>
        <div class="topbar-right">
        </div>
    </header>
@stop

@section('content')
        <!-- Image popup -->
    <div id="userAvatar" class="popup-basic popup-lg mfp-with-anim mfp-hide">
        <img class="img-responsive" src="{{ $publicUrl }}" alt="">
    </div>

    <div id="content" class="animated fadeIn list-items admin-form">
        @include('errors._list')
        <div class="clearfix">
            <div class="xs-hidden col-xs-12 col-sm-6 pl0 ml0 pr0 mr0 mb15">
            </div>
            <div class="col-xs-12 col-sm-6 pl0 ml0 pr0 mr0 mb15 search-container">
                @if (auth()->user()->hasPrivilege('search-login'))
                    {!! Form::jSearchForm($needle, route('login_search'), route('login_list')) !!}
                @endif
            </div>
        </div>

        <div class="panel" id="spy7">
            <div class="panel-body pn">
                <table class="table table-bordered list-table">
                    <thead>
                    <tr>
                        <th class="td-avatar">Image</th>
                        <th class="td-sortable td-name xs-hidden">{!! SortableTrait::link('first_name', 'User Name') !!}</th>
                        <th class="td-sortable">{!! SortableTrait::link('logged_in', 'Logged In At') !!}</th>
                        <th class="td-sortable">{!! SortableTrait::link('logged_out', 'Logged Out At') !!}</th>
                    </tr>
                    </thead>

                    <tbody id="sortable-body">
                        @foreach ($logins as $login)
                            <tr data-id="{{ $login->id }}" class="{{ !empty($login->disabled) ? 'disabled' : '' }}">
                                @if (!empty($login->user->avatar))
                                    <td>
                                        <a class="show-image" href="javascript:void(0)" data-toggle="tooltip" title="click to enlarge">
                                            <img src="{{ $mediaUrl.'/avatars/'.$login->user->avatar }}" alt="{{ $login->user->avatar }}" height="40"/>
                                        </a>
                                    </td>
                                @else
                                    <td></td>
                                @endif
                                <td class="xs-hidden">{{ $login->user->fullName }}</td>
                                <td class="">{{ $login->logged_in->format('m/d/Y - H:i') }}</td>
                                <td class="">{{ !empty($login->logged_out) ? $login->logged_out->format('m/d/y H:i') : 'Currently logged in' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {!! Form::jPaginator($logins, 'login_list') !!}
    </div>
@stop

@section('js-files')
    <script>
        $(function(){
            $('.show-image').click(function(){
                var src = $(this).find('img').attr('src');
                $('#userAvatar').find('img').attr('src', src).attr('alt', 'img');

                $(this).parents('tbody').find('.show-image').removeClass('active-animation');
                $(this).addClass('active-animation item-checked');

                $.magnificPopup.open({
                    removalDelay: 500, //delay removal by X to allow out-animation,
                    items: {
                        src: '#userAvatar'
                    },
                    callbacks: {
                        beforeOpen: function(e) {
                            this.st.mainClass = 'mfp-slideDown';
                        }
                    },
                    midClick: true
                });
            });
        });
    </script>
@stop