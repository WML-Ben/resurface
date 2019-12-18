/** 2017-08-31 */

$(document).ready(function () {
    "use strict";

    /** Spinner  (needs spinner.css) **/
    $(function(){
        var $window = $(window),
            width = $window.width(),
            height = $window.height();

        setInterval(function() {
            if ((width != $window.width()) || (height != $window.height())) {
                width = $window.width();
                height = $window.height();

                updateSpinnerDivSize();
            }
        }, 200);
    });
    /* END Spinner */
    
     $(window).resize(function() {
        $('.ui-pnotify').each(function() {
            $(this).css(get_center_pos($(this).width(), $(this).position().top))
        });
    });

    /** define new validators */

    $.validator.addMethod('address', function(value, element) {
        return this.optional(element) || isAddress(value);          //  function exists on validators.js
    }, 'Invalid address');
    $.validator.addMethod('city', function(value, element) {
        return this.optional(element) || isLocation(value);         //  function exists on validators.js
    }, 'Invalid city');
    $.validator.addMethod('state', function(value, element) {
        return this.optional(element) || isLocation(value);         //  function exists on validators.js
    }, 'Invalid state');
    $.validator.addMethod('location', function(value, element) {
        return this.optional(element) || isLocation(value);         //  function exists on validators.js
    }, 'Invalid location');
    $.validator.addMethod('postalCode', function(value, element) {
        return this.optional(element) || isPostalCode(value);       //  function exists on validators.js
    }, 'Invalid postal code');
    $.validator.addMethod('float', function(value, element) {
        return this.optional(element) || isFloat(value);            //  function exists on validators.js
    }, 'Invalid entry.');
    $.validator.addMethod('routeName', function(value, element) {
        return this.optional(element) || isRouteName(value);
    }, 'Invalid code');
    $.validator.addMethod('code', function(value, element) {
        return this.optional(element) || isCode(value);
    }, 'Invalid code');
    $.validator.addMethod('needle', function(value, element) {
        return this.optional(element) || isNeedle(value);
    }, 'Invalid entry.');
    $.validator.addMethod('fileName', function(value, element) {
        return this.optional(element) || isFileName(value);
    }, 'Invalid filename');
    $.validator.addMethod('spDateTime', function(value, element) {
        return this.optional(element) || isSPDateTime(value);
    }, 'El formato v&aacute;lido es d-m-Y h:i ampm');
    $.validator.addMethod('usDateTime', function(value, element) {
        return this.optional(element) || isUSDateTime(value);
    }, 'Valid format is m/d/Y h:i ampm');
    $.validator.addMethod('time', function(value, element) {
        return this.optional(element) || isTime(value);
    }, 'Valid format is H:i or h:i ampm');

    $('[data-toggle="popover"]').popover({
        html: true
    });

    $('.validation-field').focus(function(){
        resetJFormInput($(this));

        $(this).parents('.jform-container').find('.jform-errors-container').addClass('hidden');
        $(this).parents('.jform-container').find('.jform-errors-content').html('');
    });

    $('body').on('click', '.actions .action[data-action="route"]', function(){
        window.location = $(this).data('route');
    });

    $('body .list-table').on('shown.bs.dropdown', function (e) {
        var $table = $(this),
            delta = 30,
            $menu = $(e.target).find('.dropdown-menu'),
            tableOffsetHeight = $table.offset().top + $table.height(),
            menuOffsetHeight = $menu.offset().top + $menu.outerHeight(true) - delta;

        if (menuOffsetHeight > tableOffsetHeight) {
            $table.css('margin-bottom', menuOffsetHeight - tableOffsetHeight);
        }
    });

    $('body .list-table').on('hide.bs.dropdown', function () {
        $(this).css('margin-bottom', 0);
    });

});

Number.prototype.format = function(n, x) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
    return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
};

/** other validation functions */

function isRouteName(value)
{
    return /^[0-9a-zA-Z]{1}[0-9a-zA-Z_]+$/.test(value);
}

function isCode(value)
{
    return /^[a-zA-Z0-9]{1}[a-zA-Z0-9\-]*[a-zA-Z0-9]{1}$/.test(value);
}

function isPassword(value, len)
{
    if (typeof len == 'undefined') {
        len = 6;
    }

    var regex = new RegExp("^.{" + len + ",}$");

    return regex.test(value);
}

function isNeedle(value)
{
    return isText(value) && value.length > 2;
}

function isFileName(value)
{
    return /^[\d,\.,\w,\s-]+\.[A-Za-z]+$/.test(value);
}

function isUSDateTime(value) //  m/d/Y H:i:s format (or h:i:s am|pm)
{
    return /^(([1-9])|(0[1-9])|(1[0-2]))(\-|\/)(([1-9])|([0-2][0-9])|(3[0-1]))(\-|\/)[0-9]{2,4} [0-9]{1,2}:[0-9]{2}(:[0-9]{2})?( (am|pm|AM|PM))?$/.test(value) ;
}

function isSPDateTime(value) //  d-m-Y H:i:s format (or h:i:s am|pm)
{
    return /^(([1-9])|([0-2][0-9])|(3[0-1]))(\-|\/)(([1-9])|(0[1-9])|(1[0-2]))(\-|\/)[0-9]{2,4} [0-9]{1,2}:[0-9]{2}(:[0-9]{2})?( (am|pm|AM|PM))?$/.test(value) ;
}

function isTime(value) //  H:i:s or h:i:s am|pm format
{
    return /^[0-9]{1,2}:[0-9]{2}(:[0-9]{2})?( (am|pm|AM|PM))?$/.test(value) ;
}


/**
 *  jvc valitation functions
 *
 *  version: 2018-09-13   updated validateJInput to show validation error messageas placeholder
 *  version: 2018-04-03   fixed some code style errors
 *  version: 2017-12-01   updated validateJInput  (added checking to return true if element is hidden)
 *  version: 2017-09-16   updated resetJFormInput
 *  version: 2017-08-31   added resetJFormInput  (reset input when click on it)
 *  version: 2017-08-29   fixed  resetJFormInputs
 */

function validateJForm(form)
{
    var result = true;
    form.find('.validation-field').each(function(){
        if (!validateJInput($(this))) {
            result = false;
        }
    });

    return result;
}

function validateJInput(el)
{
    if (!el.is(':visible')) {
        return true;
    }

    var validatorIsNotRequired = (typeof el.data('validator-required') != 'undefined' && el.data('validator-required') === false);

    if (validatorIsNotRequired && el.val() == '') {
        return true;
    }

    var validatorIsRequired = ! validatorIsNotRequired,
        validatorMessageError = [];

    var validatorFunction = typeof el.data('validator-function') != 'undefined' ? el.data('validator-function') : false,
        validatorRegExp = typeof el.data('validator-regexp') != 'undefined' ? new RegExp(el.data('validator-regexp')) : false,
        validatorEqualTo = typeof el.data('validator-equalto') != 'undefined' ? el.data('validator-equalto') : false,
        result;

    if (validatorFunction) {
        if (typeof window[validatorFunction] != 'function') {
            result = false;
        } else {
            result = window[validatorFunction](el.val());
        }
    } else if (validatorRegExp) {
        result = validatorRegExp.test(el.val());
    } else if (validatorEqualTo) {
        result = el.val() == $('#' + validatorEqualTo).val();
    }

    var validatorMinLength = typeof el.data('validator-minlength') != 'undefined' ? el.data('validator-minlength') : false,
        validatorMaxLength = typeof el.data('validator-maxlength') != 'undefined' ? el.data('validator-maxlength') : false;

    var minLengthOk = true;
    var maxLengthOk = true;

    if (validatorIsRequired && result) {
        if (validatorMinLength) {
            minLengthOk = el.val().length >= validatorMinLength;
        }
        if (validatorMaxLength) {
            maxLengthOk = el.val().length <= validatorMaxLength;
        }

        result = minLengthOk && maxLengthOk;

        if (!result) {
            if (! minLengthOk && ! maxLengthOk) {
                var validatorMinMaxLengthMessage = typeof el.data('validator-min-max-length-message') != 'undefined' ? el.data('validator-min-max-length-message') : 'Entry must be between '+ validatorMinLength + ' and ' + validatorMaxLength + ' characters.';
                validatorMessageError.push(validatorMinMaxLengthMessage);
            } else if (!minLengthOk) {
                var validatorMinLengthMessage = typeof el.data('validator-minlength-message') != 'undefined' ? el.data('validator-minlength-message') : 'Entry must have al least '+ validatorMinLength + ' characters.';
                validatorMessageError.push(validatorMinLengthMessage);
            } else {
                var validatorMaxLengthMessage = typeof el.data('validator-maxlength-message') != 'undefined' ? el.data('validator-maxlength-message') : 'Entry must not have more than '+ validatorMaxLength + ' characters.';
                validatorMessageError.push(validatorMaxLengthMessage);
            }
        }
    }

    var validatorMin = typeof el.data('validator-min') != 'undefined' ? el.data('validator-min') : false,
        validatorMax = typeof el.data('validator-max') != 'undefined' ? el.data('validator-max') : false;

    var minOk = true;
    var maxOk = true;

    if (validatorIsRequired && result) {
        if (validatorMin) {
            minOk = el.val() >= validatorMin;
        }
        if (validatorMax) {
            maxOk = el.val() <= validatorMax;
        }

        result = minOk && maxOk;

        if (result) {
            return true;
        }

        var validatorMinMaxMessage;

        if (! minOk && ! maxOk) {
            validatorMinMaxMessage = typeof el.data('validator-min-max-message') != 'undefined' ? el.data('validator-min-max-message') : 'Entry must be between '+ validatorMin + ' and ' + validatorMax + '.';
            validatorMessageError.push(validatorMinMaxMessage);
        } else if (!minOk) {
            var validatorMinMessage = typeof el.data('validator-min-message') != 'undefined' ? el.data('validator-min-message') : 'Entry must be equal or greater than '+ validatorMin + ' .';
            validatorMessageError.push(validatorMinMessage);
        } else {
            validatorMinMaxMessage = typeof el.data('validator-max-message') != 'undefined' ? el.data('validator-max-message') : 'Entry must be equal or less than '+ validatorMax + '.';
            validatorMessageError.push(validatorMinMaxMessage);
        }
    }

    if (result) {
        return true;
    }

    var formGroup = el.parents('.form-group'),
        validatorMessageRequired = typeof el.data('validator-message-required') != 'undefined' ? el.data('validator-message-required') : 'This field is required.';

    if (validatorMessageError.length == 0) {
        validatorMessageError.push(typeof el.data('validator-message-error') != 'undefined' ? el.data('validator-message-error') : 'Invalid entry.');
    }

    if (formGroup.find('.error-message').size() == 0 && typeof el.data('validator-error-message-on-placeholder') == 'undefined') {
        formGroup.append('<span class="error-message"></span>');
    }

    var errorMessage;

    if (el.val() == '') {
        errorMessage = validatorMessageRequired;
    } else {
        errorMessage = validatorMessageError.join('. ');
    }

    if (typeof el.data('validator-error-message-on-placeholder') == 'undefined') {
        formGroup.find('.error-message').html(errorMessage);
    } else {
        el.val('');
        el.addClass('input-error').attr('placeholder', errorMessage);
    }

    formGroup.addClass('validation-error');

    return false;
}

// for forms using JQuery validator plugin
function resetFormInputs(form)
{
    form.find('input:not(:hidden),textarea,select').each(function(){
        $(this).val('');
    });
    form.find('em.state-error').remove();
    form.find('.state-error').removeClass('state-error');
}

// for forms using JVC validator plugin
function resetJFormInputs(form)
{
    form.find('input:not(:hidden),textarea,select').each(function(){
        $(this).val('');
        if (typeof $(this).data('validator-error-message-on-placeholder') != 'undefined') {
            $(this).attr('placeholder', $(this).data('validator-default-placeholder'));
        }
    });
    form.find('.validation-error').removeClass('validation-error');
    form.find('.error-message').html('');
}

function resetJFormInput(field)
{
    var container = field.parents('.validation-field-container');

    container.removeClass('validation-error').find('.validation-error').removeClass('validation-error');
    container.find('.error-message').html('');
}

/* tinyMCE */   // for spanish:  mceInit('#description', 'sp');
function mceInit(selector, lang, height)
{
    tinymce.init({
        selector: (typeof selector != 'undefined' ? selector : 'textarea'),
        height: (typeof height != 'undefined' ? height : 300),
        language: (typeof lang != 'undefined' ? (lang == 'sp' ? 'es' : lang) : 'en'),
        theme: 'modern',
        plugins: [
            'advlist autolink lists link image charmap preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen',
            'insertdatetime media nonbreaking save table contextmenu directionality',
            'template paste textcolor colorpicker textpattern imagetools'
        ],
        toolbar1: 'bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | forecolor backcolor | link image media',
        imagetools_toolbar: "imageoptions",
        image_advtab: true,
        templates: [
            { title: 'Test template 1', content: 'Test 1' },
            { title: 'Test template 2', content: 'Test 2' }
        ],
        content_css: [
            '//fonts.googleapis.com/css?family=Open+Sans:400,600,700',
            site_url + '/css/mce.css',
        ],
        //menubar: 'edit insert view format table tools',
        menu: {
            //file: {title: 'File', items: 'newdocument'},
            edit: {title: 'Edit', items: 'cut copy paste pastetext | selectall'},
            insert: {title: 'Insert', items: 'link media image | hr'},
            view: {title: 'View', items: 'preview | code'},
            format: {title: 'Format', items: 'bold italic underline strikethrough superscript subscript | formats | removeformat'},
            table: {title: 'Table', items: 'inserttable tableprops deletetable | cell row column'}
        },
        statusbar: false,
        file_browser_callback: function(field_name, url, type){
            if (type == 'image') {
                if ($('mceImageUploadForm').size() == 0) {
                    var html = '<form id="mceImageUploadForm" style="display: none;"><input class="mceImage" name="mceImage" id="mceImage" type="file"></form>';
                    $('body').append(html);
                }
                $('#mceImageUploadForm input[type="file"]').click();
            }
        }
    });
}
function mceUploadFileInit(url, token)
{
    $('body').on('change', '.mceImage', function(){
        var formData = new FormData($('#mceImageUploadForm')[0]);

        $.ajax({
            headers: {'X-XSRF-TOKEN': token},
            data: formData,
            type: "POST",
            url: url,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function (request){
                showSpinner();
            },
            complete: function(){
                hideSpinner();
                $('#mceImageUploadForm').remove();
            },
            success: function(response) {
                var result = $.parseJSON(response);
                if (result.success) {
                    $('.mce-combobox .mce-textbox').val(result.url);
                } else {
                    alert(result.message);
                }
            }
        });
    });
}
/* END tinyMCE */

function showSpinnerWithText(text)
{
    $('body').append('<div id="spinner_with_text"><div class="spinner-content"><i class="fa fa-refresh fa-spin"></i>'+ text +'</div></div>');
    updateSpinnerWithTextDivSize();
}
function updateSpinnerWithTextDivSize()
{
    if ($('#spinner_with_text')) {
        $('#spinner_with_text').css({
            width : $(window).width(),
            height: $(window).height()
        });
        var spinnerContent = $('#spinner_with_text').find('.spinner-content');
        var xCenter = (($('body').width() - spinnerContent.width() - 40) / 2) + 'px';
        spinnerContent.css({left: xCenter});
    }
}
function hideSpinnerWithText()
{
    $('#spinner_with_text').remove();
}

function showSpinner()
{
    $('body').append('<div id="spinner"><i class="fa fa-refresh fa-spin"></i></div>');
    updateSpinnerDivSize();
}
function updateSpinnerDivSize()
{
    if ($('#spinner')) {
        $('#spinner').css({
            width : $(window).width(),
            height: $(window).height()
        });
    }
}
function hideSpinner()
{
    $('#spinner').remove();
}

var pastedContent = false;

function cleanPastedText(str)
{
    $('body').prepend('<div id="tmpPastedTextContainer" style="display: none;"></div>');
    $('#tmpPastedTextContainer').html(str);
    str = $('#tmpPastedTextContainer').text();
    $('#tmpPastedTextContainer').remove();
    str = str.replace(/\n+/g, '<br>').replace(/.*<!--.*-->/g, '');
    for (i = 0; i < 10; i++) {
        if (str.substr(0, 4) == '<br>') {
            str = str.replace('<br>', '');
        }
    }
    return str;
}

function urlQueryString(url)
{
    if (typeof url == 'undefined') {
        return false;
    }

    var varsArr = url.split('?');

    if (varsArr.length != 2) {
        return false;
    }

    return varsArr[1];
}

function get_center_pos(width, top){
    // top is empty when creating a new notification and is set when recentering
    if (!top){
        top = 30;
        // this part is needed to avoid notification stacking on top of each other
        $('.ui-pnotify').each(function() { top += $(this).outerHeight() + 20; });
    }

    return {"top": top, "left": ($(window).width() / 2) - (width / 2)}
}

function notify(params) 
{
    // params: message, title, type, top, class. delay = 0 => always visible
    new PNotify({
        title: (typeof params.title != 'undefined') ? params.title : 'Notification', // Notificaci&oacute;n
        text: (typeof params.message != 'undefined') ? params.message : 'Hello!',     // Hola!
        opacity: 0.90,
        type: (typeof params.type != 'undefined') ? params.type : 'info',
        delay: (typeof params.delay != 'undefined') ? params.delay : 5000,
        hide: (typeof params.delay == 'undefined' || params.delay != 0),
        addClass: (typeof params.class != 'undefined') ? params.class : '',     // Hola!'mt50'
        before_open: function (PNotify) {
            //  PNotify.get().css(get_center_pos(PNotify.get().width(), (typeof params.top != 'undefined' ? params.top : null)));
        },
        after_open: function (PNotify) {
            PNotify.get().css(get_center_pos(PNotify.get().width(), PNotify.get().css('top')));
        }
    });
}

function confirmation(params)
{
    // params: message, title, type, top, class. delay = 0 => always visible
    new PNotify({
        title: (typeof params.title != 'undefined') ? params.title : 'Warning',
        text: (typeof params.message != 'undefined') ? params.message : 'Are you sure?',
        opacity: 0.90,
        type: (typeof params.type != 'undefined') ? params.type : 'warning',
        addClass: (typeof params.class != 'undefined') ? params.class : 'admin-panel-note',
        hide: false,
        animate_speed: 'fast',
        buttons: {
            closer: false,
            sticker: false
        },
        confirm: {
            confirm: true,
            buttons: [{
                text: (typeof params.button_text != 'undefined') ? params.button_text : 'Confirm',
                addClass: 'btn-sm btn-primary mt10'
            }, {
                text: "Cancel",
                addClass: 'btn-sm btn-default mt10'
            }],
            history: {
                history: false
            }
        },
        before_open: function (PNotify) {
            //  PNotify.get().css(get_center_pos(PNotify.get().width(), (typeof params.top != 'undefined' ? params.top : null)));
        },
        after_open: function (PNotify) {
            PNotify.get().css(get_center_pos(PNotify.get().width(), PNotify.get().css('top')));
        }
    })
    .get()
    .on('pnotify.confirm', (typeof params.confirm_function != 'undefined') ? params.confirm_function : function(){
        console.log('confirmed');
        return;
    })
    .on('pnotify.cancel', (typeof params.cancel_function != 'undefined') ? params.cancel_function : function(){
        console.log('canceled');
        return;
    });
}

function executeFunctionByName(functionName, context /*, args */) {
    var args = Array.prototype.slice.call(arguments, 2);
    var namespaces = functionName.split(".");
    var func = namespaces.pop();
    for(var i = 0; i < namespaces.length; i++) {
        context = context[namespaces[i]];
    }
    return context[func].apply(context, args);
}

$.fn.serializeObject = function(){
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name]) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

function normalizeFloat(value)
{
    return (value && value != '') ? parseFloat(value) : 0
}
function normalizeInteger(value)
{
    return (value && value != '') ? parseInt(value) : 0
}

function formatMoney(n, c, d, t) {
    var c = isNaN(c = Math.abs(c)) ? 2 : c,
        d = d == undefined ? "." : d,
        t = t == undefined ? "," : t,
        s = n < 0 ? "-" : "",
        i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
        j = (j = i.length) > 3 ? j % 3 : 0;

    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};