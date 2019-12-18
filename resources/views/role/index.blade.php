@extends('layouts.layout')

@section('breadcrumbs')
    <ul class="page-breadcrumb breadcrumb">
        <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
        <li><span>AP Roles</span></li>
    </ul>
@stop

@section('content')
    <div class="page-content-inner">
        <div class="row">
            <div class="col-md-12">
                @include('errors._list')
                <div class="portlet box list-items admin-form">
                    <div class="portlet-body">
                        <div class="admin-form theme-primary">
                            <div class="panel roles-tree">
                                <div  class="panel-body">
                                    <div class="row mb5">
                                        <div class="col-sm-4 col-md-3 mb10">
                                            <span class="form-field-label">Own Role:</span>
                                            <div><strong class="fs16">{{ auth()->user()->authUserOwnRole() }}</strong></div>
                                        </div>
                                        <div id="own-privilege-container" class="col-sm-8 col-md-9">
                                            <div class="row">
                                                <div class="col-sm-6 col-xs-12">
                                                    <div class="xs-w100perc not-xs-w300 not-sm-tl">
                                                        <span class="form-field-label">Own Privileges:</span>
                                                        @if (auth()->user()->role->privileges->count())
                                                            {{ Form::jSelect('privilege', $ownProvileges) }}
                                                        @else
                                                            Role "{{ auth()->user()->role->name }}" does not have assigned any privilege.
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 xs-hidden"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row collapsable text-right pt10">
                                        @if (auth()->user()->hasPrivileges(['create-role', 'create-privilege']))
                                            <span class="collapsable-handler" data-toggle="tooltip" title="show form"><span class="collapsable-status">Show</span> "New Role/Privilege" Form<i class="fa fa-angle-down ml5"></i></span>
                                            <div class="collapse collapse-hidden mt10">
                                                <div id="new-role-container" class="col-sm-6 col-md-6 mb15">
                                                    @if (auth()->user()->hasPrivilege('create-role'))
                                                        {!! Form::open(['url' => route('role_store'), 'id' => 'createRoleForm']) !!}
                                                            {!! Form::hidden('parentId', auth()->user()->role_id, ['id' => 'parentId']) !!}
                                                            <span class="form-field-label text-left">Create new role below "<span id="em-parent-role">{{ auth()->user()->authUserOwnRole() }}"</span>:<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="this field is required"></i></span>
                                                            <label for="name" class="field prepend-icon">
                                                                {!! Form::text('name', null, ['class' => 'gui-input pull-left mb5', 'id' => 'name', 'placeholder' => 'Role Name']) !!}
                                                                <span class="field-icon"><i class="fa fa-key"></i></span>
                                                            </label>
                                                            {!! Form::submit('Add', ['class' => 'button btn-primary mb10 pull-left mb5', 'id' => 'new-role-button']) !!}
                                                        {!! Form::close() !!}
                                                    @endif
                                                </div>
                                                <div id="new-privilege-container" class="col-sm-6 col-md-6">
                                                    @if (auth()->user()->hasPrivilege('create-privilege'))
                                                        {!! Form::open(['url' => route('privilege_store'), 'id' => 'createPrivilegeForm']) !!}
                                                            {!! Form::hidden('from_role_form', 1) !!}
                                                            <span class="form-field-label text-left">Create New Privilege:<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="this field is required"></i></span>
                                                            <label for="privilege" class="field prepend-icon">
                                                                {!! Form::text('name', null, ['class' => 'gui-input pull-left mb5', 'id' => 'privilege_name', 'placeholder' => 'Privilege Name']) !!}
                                                                <span class="field-icon"><i class="fa fa-tag"></i></span>
                                                            </label>
                                                            <div class="col-xs-8 switch-container pl0 pr0">
                                                                <label class="switch switch-primary">
                                                                    <input type="checkbox" name="createCrud" id="createCrud" value="1" {{ !empty($user->disabled) ? 'checked' : ''}}>
                                                                    <label for="createCrud" data-on="YES" data-off="NO"></label>
                                                                    <span>Create CRUD set</span>
                                                                </label>
                                                            </div>
                                                            <div class="col-xs-4 privilege-button-container text-right pl0 pr0 mb15">
                                                                {!! Form::submit('Add', ['class' => 'button btn-primary', 'id' => 'new-privilege-button']) !!}
                                                            </div>
                                                        {!! Form::close() !!}
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-4 col-md-3 role-tree-container">
                                            <div class="col-header">Children Role Tree
                                                @if (auth()->user()->hasPrivileges(['update-role', 'modify-role']))
                                                    <i class="i-help fa fa-question"
                                                       data-content="<ul><li>Drag and drop to rearrange role tree.</li><li>Double click to modify role name</li></ul>"
                                                       data-placement="top"
                                                       title='<span class="text-info"><strong>Update Role Tree</strong></span> <button type="button" class="close">&times;</button>'>
                                                    </i>
                                                @endif
                                                @if (auth()->user()->hasPrivilege('delete-role'))
                                                    <a id="delete-role-link" href="#" data-toggle="tooltip" title="remove selected role"><img src="{{ $publicUrl }}/images/delete.png" alt="delete.png"></a>
                                                @endif
                                            </div>
                                            <div class="col-content">
                                                <div class="" id="roles-tree"></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-8 col-md-6" id="current-privileges">
                                            <div class="col-header">Selected Role&#039;s Privileges</div>
                                            <div class="col-content" id="privileges-own"></div>
                                        </div>
                                        <div class="col-sm-12 col-md-3" id="inherited-privileges">
                                            <div class="col-header">Selected Role&#039;s Inherited Privileges</div>
                                            <div class="col-content">
                                                <div id="privileges-inherited"></div>
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
    </div>
@stop

@section('css-files')
    {!! Html::style($publicUrl . '/backend/vendor/plugins/fancytree/skin-win8/ui.fancytree.min.css') !!}
    {!! Html::style($publicUrl . '/backend/vendor/plugins/fancytree/skin-win8/ui.fancytree.min.css') !!}
    {!! Html::style($publicUrl . '/widgets/bootstrap-transfer/css/bootstrap-transfer.css') !!}
@stop

@section('js-plugin-files')
    {!! Html::script($publicUrl . '/backend/vendor/plugins/fancytree/jquery.fancytree-all.min.js') !!}
    {!! Html::script($publicUrl . '/backend/vendor/plugins/fancytree/extensions/jquery.fancytree.childcounter.js') !!}
    {!! Html::script($publicUrl . '/backend/vendor/plugins/fancytree/extensions/jquery.fancytree.dnd.js') !!}
    {!! Html::script($publicUrl . '/backend/vendor/plugins/fancytree/extensions/jquery.fancytree.edit.js') !!}
    {!! Html::script($publicUrl . '/widgets/bootstrap-transfer/js/bootstrap-transfer.js') !!}
@stop

@section('js-files')
    <script>
        var selectedRoleId,
            selectedRoleName,
            roleTreeChanged = false,
            canUpdatePrivilege = "{{ auth()->user()->hasPrivilege('update-role') }}" == "1";

        $(function(){

            if (!canUpdatePrivilege) {
                $('#current-privileges .save-link').css('display', 'none');
                $('#current-privileges .save-link').hide();
            }

            $('#createRoleForm').validate({
                rules: {
                    name: {
                        required : true,
                        slug     : true,
                        minlength: 3
                    }
                },
                messages: {
                    name: {
                        slug: 'Only letters and dash character (-) allowed.'
                    }
                }
            });
            $('#createPrivilegeForm').validate({
                rules: {
                    name: {
                        required : true,
                        slug     : true,
                        minlength: 3
                    }
                },
                messages: {
                    name: {
                        slug: 'Only letters and dash character (-) allowed.'
                    }
                }
            });

            $('.collapsable .collapsable-handler').click(function() {
                var container = $(this).parent('.collapsable'),
                    target = container.find('.collapse'),
                    handler = container.find('.collapsable-handler'),
                    status = handler.find('.collapsable-status'),
                    angle = handler.find('i');

                if (target.hasClass('collapse-hidden')) {
                    target.removeClass('collapse-hidden').addClass('collapse-visible');
                    target.slideDown('slow', function(){
                        angle.removeClass('fa-angle-down').addClass('fa-angle-up');
                        status.text('Hide');
                        handler.attr('title', 'hide form').tooltip('fixTitle').data('bs.tooltip').$tip.find('.tooltip-inner').text('hide form');
                    });
                } else {
                    target.removeClass('collapse-visible').addClass('collapse-hidden');
                    target.slideUp('slow', function(){
                        angle.removeClass('fa-angle-up').addClass('fa-angle-down');
                        status.text('Show');
                        handler.attr('title', 'show form').tooltip('fixTitle').data('bs.tooltip').$tip.find('.tooltip-inner').text('show form');
                    });
                }
            });

            var tree = $('#roles-tree').fancytree({
                rootVisible: false,
                activeVisible: true,
                autoActivate: true,
                extensions: ['dnd', 'edit', 'childcounter'],
                childcounter: {
                    deep: true,
                    hideZeros: true,
                    hideExpanded: true
                },
                source: {!! $roleTree !!},
                init: function(){
                    $('#roles-tree').fancytree('getRootNode').visit(function(node){
                        node.toggleExpanded();
                    });
                },
                focus: function(node, data){
                    selectedRoleId = data.node.key;
                    selectedRoleName = data.node.title;
                    data.node.setActive();
                    $.ajax({
                        data: {
                            'roleId': selectedRoleId
                        },
                        type: "POST",
                        url: "{{ route('ajax_get_role_privileges') }}",
                        beforeSend: function (request){
                            showSpinner();

                        },
                        complete: function(){
                            hideSpinner();
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#current-privileges .col-header').html(selectedRoleName.toUpperCase() + '&#039;s Current Privileges');
                                $('#inherited-privileges .col-header').html(selectedRoleName.toUpperCase() + '&#039;s Inherited Privileges');

                                // reset boxes:

                                $('#privileges-own').data().bootstrapTransfer.reset();

                                // populate "available" box with own and available privileges:
                                $('#privileges-own').data().bootstrapTransfer.populate(response.ownAndAvailablePrivileges);

                                // move own privileges to "chosen" box:
                                $('#privileges-own').data().bootstrapTransfer.set_values(response.ownPrivilegesIds);

                                $('#privileges-inherited').empty();

                                $.each(response.inheritedPrivileges, function(index, item){
                                    $('#privileges-inherited').append('<span class="list-span-item">'+ item +'</span>')
                                });

                                // update selected role in createRoleForm:
                                $('#parentId').val(selectedRoleId);
                                $('#em-parent-role').text(selectedRoleName);
                            } else {
                                uiAlert({type: 'error', text: response.error});
                            }
                        }
                    });
                },
                dnd: {
                    autoExpandMS: 400,
                    focusOnClick: true,
                    preventVoidMoves: true, // Prevent dropping nodes 'before self', etc.
                    preventRecursiveMoves: true, // Prevent dropping nodes on own descendants
                    dragStart: function(node, data) {
                        /** This function MUST be defined to enable dragging for the tree.
                         *  Return false to cancel dragging of node.
                         */
                        if ("{{ auth()->user()->hasPrivilege('update-role') }}" != "1") {
                            uiAlert({type: 'error', text: 'You are not allowed to update a role.'});
                            return false;
                        } else {
                            return true;
                        }
                    },
                    dragEnter: function(node, data) {
                        /** data.otherNode may be null for non-fancytree droppables.
                         *  Return false to disallow dropping on node. In this case
                         *  dragOver and dragLeave are not called.
                         *  Return 'over', 'before, or 'after' to force a hitMode.
                         *  Return ['before', 'after'] to restrict available hitModes.
                         *  Any other return value will calc the hitMode from the cursor position.
                         */
                        // Prevent dropping a parent below another parent (only sort
                        // nodes under the same parent)
                        /*           if(node.parent !== data.otherNode.parent){
                         return false;
                         }
                         // Don't allow dropping *over* a node (would create a child)
                         return ["before", "after"];
                         */
                        return true;
                    },
                    dragDrop: function(node, data) {
                        /** This function MUST be defined to enable dropping of items on
                         *  the tree.
                         */

                        data.otherNode.moveTo(node, data.hitMode);

                        $.ajax({
                            data: {
                                'roleTree': JSON.stringify($('#roles-tree').fancytree('getTree').toDict(false))
                            },
                            type: "POST",
                            url: "{{ route('ajax_update_role_tree') }}",
                            beforeSend: function (request){
                                showSpinner();
                            },
                            complete: function(){
                                hideSpinner();
                            },
                            success: function(response) {
                                if (response.success) {
                                    data.node.setFocus();

                                    uiAlert({type: 'info', text: 'Role tree successfully updated.'});
                                } else {
                                    uiAlert({type: 'error', text: response.error});
                                    return false;
                                }
                            }
                        });
                    },
                    onDrop: function(node, data, hitMode){
                        console.log(JSON.stringify($('#roles-tree').fancytree('getTree').toDict(false)));
                        return true;
                    }
                },
                activate: function(event, data) {
                    //        alert("activate " + data.node);
                },
                lazyLoad: function(event, data) {
                    data.result = {
                        url: 'ajax-sub2.json'
                    }
                },
                edit: {
                    triggerStart: ['f2', 'dblclick', 'shift+click', 'mac+enter'],
                    beforeEdit: function(event, data) {
                        // Return false to prevent edit mode\
                        if ("{{ auth()->user()->hasPrivilege('modify-role') }}" != "1") {
                            uiAlert({type: 'error', text: 'You are not allowed to modify a role.'});
                            return false;
                        }
                    },
                    edit: function(event, data) {
                        // Editor was opened (available as data.input)
                    },
                    beforeClose: function(event, data) {
                        // Return false to prevent cancel/save (data.input is available)
                    },
                    save: function(event, data) {
                        if (!isSlug(data.input.val())) {
                            data.node.editEnd(false);
                            uiAlert({type: 'error', text: 'Invalid role name. Only letters and dash character (-) allowed.'});
                        } else {
                            var oldTitle = data.node.title;
                            $.ajax({
                                data: {
                                    'roleId'  : data.node.key,
                                    'roleName': data.input.val()
                                },
                                type: "POST",
                                url: "{{ route('ajax_update_role_name') }}",
                                beforeSend: function (request) {
                                    showSpinner();
                                },
                                complete: function () {
                                    hideSpinner();
                                    $(data.node.span).removeClass('pending');
                                },
                                success: function (response) {
                                    if (!response.success) {
                                        uiAlert({type: 'error', text: response.error});
                                        data.node.setTitle(data.orgTitle);
                                    }
                                }
                            });

                            return true;
                        }
                    },
                    close: function(event, data) {
                        // Editor was removed
                        if (data.save) {
                            // Since we started an async request, mark the node as preliminary
                            $(data.node.span).addClass('pending');
                        }
                    }
                }

            });

            $('#delete-role-link').click(function(){
                if ("{{ auth()->user()->hasPrivilege('delete-role') }}" != "1") {
                    uiAlert({type: 'error', text: 'You are not allowed to delete a role.'});
                    return false;
                }

                var node = $('#roles-tree').fancytree('getActiveNode');
                if (!node) {
                    uiAlert({type: 'error', text: 'There is no role selected.'});
                    return false;
                }

                if (node.hasChildren()) {
                    uiAlert({type: 'error', text: 'This role has children.<br>To delete a role, you must move or delete all its children first.'});
                    return false;
                }

                uiConfirm({
                    text: 'Are you sure you want to delete the role: <strong>' + node.title + '</strong>?',
                    params: [],
                    callback: 'confirmDelete'
                });
            });

            $('#privileges-own').bootstrapTransfer({
                'available_label': 'Available Privileges',
                'chosen_label'   : 'Current Privileges',
                'save_label'     : 'Save'
            });

            $('#privileges-own').on('click', '.save-link', function(){
                if ("{{ auth()->user()->hasPrivilege('update-privilege') }}" != "1") {
                    uiAlert({type: 'error', text: 'You are not allowed to update a privilege.'});
                    return false;
                }

                // update current role's privileges:

                var privilegesStrCid = $('#privileges-own').data().bootstrapTransfer.get_values().join('|');

                $.ajax({
                    data: {
                        'roleId'          : selectedRoleId,
                        'privilegesStrCid': privilegesStrCid
                    },
                    type: "POST",
                    url: "{{ route('ajax_update_role_privileges') }}",
                    beforeSend: function (request){
                        showSpinner();
                    },
                    complete: function(){
                        hideSpinner();
                    },
                    success: function(response) {
                        if (!response.success) {
                            uiAlert({type: 'error', text: response.error});
                        }
                    }
                });
            });

            $('#new-role-button').click(function(){
                if (selectedRoleId) {
                    $('#parentId').val(selectedRoleId);
                }
            });
        });

        function confirmDelete()
        {
            var node = $('#roles-tree').fancytree('getActiveNode');
            $.ajax({
                data: {
                    'roleId': node.key
                },
                type: "POST",
                url: "{{ route('ajax_remove_role') }}",
                beforeSend: function (request){
                    showSpinner();
                },
                complete: function(){
                    hideSpinner();
                },
                success: function(response) {
                    if (response.success) {
                        node.remove();
                        uiAlert({type: 'info', text: 'Role successfully removed.'});
                    } else {
                        uiAlert({type: 'error', text: response.error});
                    }
                }
            });
        }
    </script>
@stop
