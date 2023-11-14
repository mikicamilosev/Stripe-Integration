<?php
    include_once "../../../ASEngine/AS.php";
    header('Content-Type: application/javascript');
?>

var $_lang = <?= ASLang::all() ?>;
var _data = {};
_data["<?= ASCsrf::TOKEN_NAME ?>"] = "<?= ASCsrf::getToken() ?>";
jQuery.ajaxSetup({ data: _data, type: "POST" });

jQuery.validator.setDefaults({
    errorElement: "em",
    errorPlacement: function(error, element) {
        error.addClass("invalid-feedback");

        element.prop("type") === "checkbox"
            ? error.insertAfter(element.next("label"))
            : error.insertAfter(element);
    },
    highlight: function(element, errorClass, validClass) {
        $(element).addClass("is-invalid");
    },
    unhighlight: function(element, errorClass, validClass) {
        $(element).removeClass("is-invalid");
    }
});


var AS = {
    App: {
        lang: "<?= ASLang::getLanguage() ?>"
    },
    Util: {},
    Http: {}
};