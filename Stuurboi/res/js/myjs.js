function destroyPopOver() {
    $('[data-toggle="popover"]').popover('destroy');
}

function customPost(url, postData, callback) {
    postData = postData + "&pT=ajax";
    $.post(url, postData, function (data) {
        if (data.res === 'success') {
            if (typeof data.views === 'undefined') {
                var container = $(data.container);
                container.html(data.data);
            } else {
                $(data.views).each(function (property, value) {
                    var container = $(value.container);
                    container.html(value.data);
                });
            }
            if (typeof callback === 'function') {
                callback(data);
            }
        } else if (data.res === 'redirect') {
            location.assign(data.url);
        } else {
            var errorContainer = $("#messageContainer");
            errorContainer.html(data.error);
        }
    }, "json");
}

function customPostCallBack(data) {
    destroyPopOver();
}

function initialiseTinymce() {
    tinymce.init({
        selector: 'textarea.tinymceDescription',
        theme: 'modern',
        branding: false,
        statusbar: false,
        plugins: [
            'advlist autolink link lists charmap print preview hr anchor pagebreak spellchecker placeholder',
        ],
        menubar: false,
        toolbar: 'undo redo | styleselect | bold italic | underline | strikethrough \n\
| alignleft aligncenter alignright | blockquote | bullist'
    });
}

function initialiseDatePicker(field) {
    var field = pick(field, 'datepicker');
    var picker = $("#mainDivContainer").find('#' + field);
    picker.datepicker({
        format: 'dd/mm/yyyy'
    });

    if (field !== 'datepicker') {
        picker.on("changeDate", function () {
            $('#my_date_input').val(picker.datepicker('getFormattedDate'));
        });
    }
}

function initialiseKnob() {
    var myknob = $("#mainDivContainer").find(".knob");
    myknob.knob({
        "format": function (value) {
            return '';
        }
    });
}

function initialiseAvator() {
    katweKibsAvatar.init();
}


$(function () {

    initialiseTinymce();
    initialiseAvator();
    var cBack = function customPostCallBack(data) {
        destroyPopOver();
        initialiseKnob();
        initialiseTinymce();
        initialiseAvator();
    };

    $("#mainDivContainer").on("click", function (e) {
        var clicked = $(e.target);
        if (clicked.data('toggle') !== 'popover'
                && clicked.parents('[data-toggle="popover"]').length === 0
                && clicked.parents('.popover.in').length === 0) {
            destroyPopOver();
        }
    });

    $("#mainDivContainer").on("click", ".popCancel", function (e) {
        e.preventDefault();
        destroyPopOver();
    });

    $("#mainDivContainer").on("click", ".tools,.cancel, .mytool", function (e) {
        e.preventDefault();
    });

    $("#mainDivContainer").on("submit", ".ajaxForm", function (e) {
        e.preventDefault();
        var url = $(this).attr("action");
        customPost(url, $(this).serialize(), cBack);
    });

    $("#mainDivContainer").on("mouseenter", ".singleCommentWraper", function () {
        $(this).find(".toolsContainer").show();
    });

    $("#mainDivContainer").on("mouseleave", ".singleCommentWraper", function () {
        var pop = $(this).find(".toolsContainer div.in");
        if (pop.length === 0) {
            $(this).find(".toolsContainer").hide();
        }

    });

    $("#mainDivContainer").on("click", "a.deleteTool, a.subscribeCBox, a.checkBox", function (e) {
        e.preventDefault();
        var url = $(this).attr("href");
        customPost(url, {}, cBack);
    });

    $("#mainDivContainer").on("click", ".messageButton", function (e) {
        var btns = $("#mainDivContainer").find(".messageButton");
        btns.removeClass("btn-success active");
        $(this).addClass("btn-success active");
    });

    $("#mainDivContainer").on("click", ".imojA", function (e) {
        e.preventDefault();
        var imojIcon = $("#mainDivContainer").find("#imojIcon");
        var imojField = $("#mainDivContainer").find("#imojField");
        var selectedImojClass = $(this).find("i").attr("class");

        imojIcon.removeClass();
        imojIcon.addClass(selectedImojClass);
        
        imojField.val($(this).attr("id"));
         $('[mydata="emojPop"]').popover('destroy');

    });

    $("#mainDivContainer").on("focus", ".searchForm", function (e) {
        e.preventDefault();
        var url = $(this).attr("action");
        customPost(url, $(this).serialize(), initialiseAvator);
    });

    $("#mainDivContainer").on("keyup", ".searchForm", function (e) {
        e.preventDefault();
        var url = $(this).attr("action");
        customPost(url, $(this).serialize(), initialiseAvator);

    });

    $("#uploaderButton").on("click", function (e) {
        e.stopPropagation();
        var pop = $("#uploader");
        pop.trigger("click");
    });

    $("#mainDivContainer").on('inserted.bs.popover', function (e) {
        $("#mainDivContainer").find('#duedatePicker').datetimepicker({
            format: 'DD-MM-YYYY HH:mm',
            sideBySide: true,
            defaultDate: moment(new Date()).add(1, 'day').hours(0).minutes(0).seconds(0).milliseconds(0),
        });

        $("#mainDivContainer").find('#until').datetimepicker({
            format: 'DD-MM-YYYY HH:mm',
            sideBySide: true,
            //defaultDate: moment(new Date()).add(1, 'day').hours(0).minutes(0).seconds(0).milliseconds(0),
        });

        initialiseAvator();
    });
});