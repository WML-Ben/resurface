$(document).ready(function () {
    "use strict";

    // Init Theme Core
    Core.init();

    handleBackToTop();
    showHidebackToTop();

    $(window).scroll(function() {
        showHidebackToTop();
    });

    $('form').on('change', 'select', function () {
        if (!$(this).val()) {
            $(this).addClass('grayed');
        } else {
            $(this).removeClass('grayed');
        }
    });

    $('#pageItems').change(function(){
        window.location.href = $(this).val();
    });

    $('#needle').blur(function(){
        $(this).parents('label').removeClass('state-error').next('em').remove();
    });

    $( "#searchForm" ).validate({
        rules: {
            needle: {
                minlength: 3,
                text     : true
            }
        }
    });
    
    $('.search-container').on('click', '.reset-button', function(){
        window.location.href = $(this).data('route');
    });

    /*  is on app-global
    $('body').on('click', '.actions .action[data-action="route"]', function(){
        window.location = $(this).data('route');
    });
    */

    $('body').on('click', '.actions .action[data-action="delete"]', function(){
        if ($(this).attr('data-text')) {
            uiConfirm({callback: 'confirmDelete', text: $(this).attr('data-text'), params: [$(this).attr('data-id')]});
        } else {
            uiConfirm({callback: 'confirmDelete', params: [$(this).attr('data-id')]});
        }
    });

    $('body').on('click', '.actions .action[data-action="eliminar"]', function(){
        if ($(this).attr('data-text')) {
            uiConfirm({callback: 'confirmDelete', text: $(this).attr('data-text'), params: [$(this).attr('data-id')], spanish: true});
        } else {
            uiConfirm({callback: 'confirmDelete', params: [$(this).attr('data-id')], spanish: true});
        }
    });

    // table list with column having action dropdown menu:

    $('table.list-table tbody tr td.actions').on('click', 'li.dropdown', function(){
        $('table.list-table tbody tr td.actions ul > li > a i.fa-angle-up').removeClass('fa-angle-up').addClass('fa-angle-down');
        if (!$(this).hasClass('open')) {
            $(this).find('a > i').removeClass('fa-angle-down').addClass('fa-angle-up');
        }
    });

    $(window).click(function(){
        $('table.list-table tbody tr td.actions ul > li > a i.fa-angle-up').removeClass('fa-angle-up').addClass('fa-angle-down');
    });

    $('.x-editable').editable({
        validate: function(value) {
            var jsValidationFunction = $(this).data('js-validation-function'),
                jsValidationErrorMessage = $(this).data('js-validation-error-message'),
                phpValidationRule = $(this).data('php-validation-rule'),
                required = phpValidationRule.indexOf('required') >= 0;

            if (required && $.trim(value) == '') {
                return 'This field is required';
            }
            if ($.trim(value) != '' && ! window[jsValidationFunction](value)) {
                return (jsValidationErrorMessage) ? jsValidationErrorMessage : 'Invalid entry';
            }

            // Additional params for submit. It is appended to original ajax data (pk, name and value).
            $(this).editable('option', 'params', {
                rule: phpValidationRule
            });
        },
        success: function(response, newValue) {
            if (response.status == 'error') {
                var phpValidationErrorMessage = $(this).data('php-validation-error-message');
 
                if (typeof phpValidationErrorMessage != 'undefined') {
                    return phpValidationErrorMessage;
                } else {
                    return response.message;
                }
            }

            // user defined callback function to do something on success:
            if (typeof xeditCallback === "function") {
                xeditCallback($(this), response, newValue);
            }
        }
    });


    /* example of event handler
    $('table.list-table tbody tr td.actions').on('click', 'a.action', function(){
        alert($(this).attr('data-action') + ': id= ' + $(this).attr('data-id'));
    });
    */

    $('.date-picker').datepicker({
        prevText: '<i class="fa fa-chevron-left"></i>',
        nextText: '<i class="fa fa-chevron-right"></i>',
        showButtonPanel: false
    });

    /** popover for i.help */
    $('.i-help').popover({
        placement: $(this).data('placement'),
        html: 'true',
        title: '<span class="text-info"><strong>Hint</strong></span> <button type="button" class="close">&times;</button>',
        content : $(this).data('content')
    });
    $('body').on('click', '.popover .close', function(){
        $(this).parents('.popover').popover('hide');
    });

    /** Implement datepicker and datetimepicker with months and year selection using bootstrap
     *
     * needs:
     *   CSS: {!! Html::style($siteUrl . '/vendor/vendor/plugins/datepicker/css/bootstrap-datetimepicker.min.css') !!}
     *   JS:  {!! Html::script($siteUrl . '/vendor/vendor/plugins/datepicker/js/bootstrap-datetimepicker.min.js') !!}
     */
    $('.bootstrap-datetime-picker').datetimepicker();

    $('.bootstrap-date-picker').datetimepicker({
        pickTime: false
    });

    /** for spanish
     *
     * needs:
     *     {!! Html::script($publicUrl . '/backend/vendor/plugins/datepicker/js/bootstrap-datetimepicker.es.js') !!}
     */
    $('.bootstrap-datetime-picker-es').datetimepicker({
        format  : 'DD-MM-YYYY h:mm A',
        language: 'es'
    });
    $('.bootstrap-date-picker-es').datetimepicker({
        pickTime: false,
        format  : 'DD-MM-YYYY',
        language: 'es'
    });

    // remove previous validation error when select an option
    $('.select2-widget').change(function(ev){
        if ($(this).val() != 0) {
            var container = $(this).parents('.field').removeClass('state-error');
            if ($('#' + $(this).attr('id') + '-error')) {
                $('#' + $(this).attr('id') + '-error').remove();
            }
        }
    });
    
    $('.char-counter').each(function(el) {
        var $textarea = $(this).find('textarea');
        $(this).find('.counter').text($(this).data('max') - $textarea.val().length);
    });

    $('.char-counter').on('keyup', 'textarea', function () {
        var charCounter = $(this).parents('.char-counter');
        var counter = charCounter.find('.counter');
        var charsLeft = charCounter.data('max') - $(this).val().length;
        var field = charCounter.find('label.field');

        counter.text(charsLeft);

        if (charsLeft > 0) {
            field.removeClass('state-error').addClass('state-success');
            charCounter.find('em').remove();
        } else {
            field.removeClass('state-success').addClass('state-error');
            if (charCounter.find('em.state-error').size() == 0) {
                field.after('<em id="'+ $textarea.attr('id') +'-error" class="state-error">You have reached the maximun characters allowed.</em>')
            }
        }
    });

    $('.char-counter').on('keydown', 'textarea', function (ev) {

        //console.log([8, 27, 37, 338, 39, 40, 46].indexOf(ev.which));  // enter-13, del-46, backsp-8, esc-27 leftarr-37, rightarr-39, uparr-38, downarr-40

        var charCounter = $(this).parents('.char-counter');
        var charsLeft = charCounter.data('max') - $(this).val().length;
        var $textarea = $(this).find('textarea');

        if (charsLeft <= 0) {
            if ([8, 27, 37, 338, 39, 40, 46].indexOf(ev.which) == -1) {
                ev.preventDefault();
                ev.stopPropagation();

                var $field = charCounter.find('label.field');

                $field.removeClass('state-success').addClass('state-error');

                if (charCounter.find('em.state-error').size() == 0) {
                    $field.after('<em id="'+ $textarea.attr('id') +'-error" class="state-error">You have reached the maximun characters allowed.</em>')
                }
            }
        }
    });

    $('.char-counter').on('blur', 'textarea', function (ev) {
        var charCounter = $(this).parents('.char-counter');

        charCounter.find('label.field').removeClass('state-error');
        charCounter.find('em').remove();
    });
});

function confirmDelete(item_id)
{
    $('#form_delete_item_id').val(item_id);
    $('#deleteForm').submit();
}

function handleBackToTop()
{
    $('#back-to-top').click(function(){
        $('html, body').animate({scrollTop:0}, 'slow');
        return false;
    });
}
function showHidebackToTop()
{
    if ($(window).scrollTop() > $(window).height() / 2 ) {
        $("#back-to-top").removeClass('gone');
        $("#back-to-top").addClass('visible');
    } else {
        $("#back-to-top").removeClass('visible');
        $("#back-to-top").addClass('gone');
    }
}
function validateDropzoneBeforeSubmit(file, image)
{
    var result = false;

    if (file.attr('aria-required') == 'true') {
        result = image.val() != '' && isFileName(image.val());
    } else {
        result = image.val() == '' || isFileName(image.val());
    }

    var dropzone = file.parents('.dropzone');
    
    if (result) {
        dropzone.next('em.state-error').remove();
        dropzone.removeClass('state-error');
    } else {
        image.val('');
        file.val('');

        if (! dropzone.hasClass('state-error')) {
            dropzone.addClass('state-error');
        }
        if ($('#'+ file.attr('id') +'-error').size() == 0) {
            dropzone.after('<em class="state-error" id="'+  file.attr('id') +'-error">This field is required.</em>');
        }
    }
    
    return result;
}
