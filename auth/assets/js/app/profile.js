/**
 * Prevent's forms with class "no-submit" to be submitted.
 */
$(".no-submit").submit(function () {
    return false;
});

/**
 * Validate and submit change password form.
 */
$("#change-password-form").validate({
    rules: {
        old_password: "required",
        new_password: {
            required: true,
            minlength: 6
        },
        new_password_confirmation: "required"
    },
    submitHandler: function(form) {
        AS.Http.submit(form, getChangePasswordFormData(form), function () {
            AS.Util.displaySuccessMessage($(form), $_lang.password_updated_successfully);
        });
    }
});

/**
 * Builds a change password form data.
 * @param form
 * @returns *
 */
function getChangePasswordFormData(form) {
    return {
        action: "updatePassword",
        old_password: AS.Util.hash(form['old_password'].value),
        new_password: AS.Util.hash(form['new_password'].value),
        new_password_confirmation: AS.Util.hash(form['new_password_confirmation'].value)
    };
}

/**
 * Submits the form to update user details.
 */
$("#update_details").click(function () {
    var $form = $("#form-details"),
        form = $form[0];

    AS.Http.post({
        action : "updateDetails",
        details: {
            first_name: form['first_name'].value,
            last_name: form['last_name'].value,
            address: form['address'].value,
            phone: form['phone'].value
        }
    }, function () {
        AS.Util.displaySuccessMessage($form, $_lang.details_updated);
    }, function () {
        AS.Util.displayErrorMessage($form.find("input"));
        AS.Util.displayErrorMessage(
            $form.find("input[name=phone]"),
            $_lang.error_updating_db
        );
    });
});
