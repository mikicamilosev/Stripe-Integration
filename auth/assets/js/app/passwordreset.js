/**
 * Validate and submit forgot password form.
 */
$("#forgot form").validate({
    rules: {
        email: {
            required: true,
            email: true
        }
    },
    submitHandler: function(form) {
        AS.Http.submit(form, {
            action: "forgotPassword",
            email: form['email'].value
        }, function () {
            AS.Util.displaySuccessMessage($(form), $_lang.password_reset_email_sent);
        });
    }
});

/**
 * Validate and submit password reset form.
 */
$("#password-reset-form").validate({
    rules: {
        new_password: {
            required: true,
            minlength: 6
        }
    },
    submitHandler: function(form) {
        AS.Http.submit(form, getPasswordResetFormData(form), function () {
            AS.Util.displaySuccessMessage($(form), $_lang.password_updated_successfully_login);
        });
    }
});

/**
 * Build password reset form data as JSON.
 * @param form
 * @returns {{action: string, new_password: string, key: string}}
 */
function getPasswordResetFormData(form) {
    return {
        action: "resetPassword",
        new_password: AS.Util.hash(form['new_password'].value),
        key: AS.Util.urlParam("k")
    };
}
