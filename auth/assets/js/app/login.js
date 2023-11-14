$(document).ready(function () {
    // Set focus on username field when page is loaded.
    $("#login-username").focus();
});

/**
 * Validate and submit the login form.
 */
$("#login form").validate({
    rules: {
        username: "required",
        password: "required"
    },
    submitHandler: function(form) {
        AS.Http.submit(form, getLoginFormData(form), function (result) {
            window.location = result.page;
        });
    }
});

/**
 * Builds a login form JSON data that should be sent to the server.
 * @returns {{action: string, username: string, password: string}}
 */
function getLoginFormData(form) {
    return {
        action: "checkLogin",
        username: form['username'].value,
        password: AS.Util.hash(form['password'].value)
    };
}

/**
 * Handle switching between login, register and forgot password forms.
 */
$(".form-change").click(function (e) {
    e.preventDefault();

    $(".form-wrapper").removeClass("active");
    $($(this).attr('href')).addClass('active');

    if($(".form-wrapper.active").attr('id') == 'create') {
        $(".sign-up-controls").hide();
        $(".sign-in-controls").show();
    } else {
        $(".sign-up-controls").show();
        $(".sign-in-controls").hide();
    }
});
