/**
 * Put button to a loading state.
 * @param {Object} button Button to be putted.
 * @param {string} loadingText Text that will be displayed while loading.
 */
AS.Util.loadingButton = function(button, loadingText) {
    button.data("original-content", button.html())
        .text(loadingText)
        .addClass("disabled")
        .attr('disabled', "disabled");
};

/**
 * Returns button from loadin state to normal state.
 * @param {Object} button Button object.
 */
AS.Util.removeLoadingButton = function (button) {
    button.html(button.data("original-content"))
        .removeClass("disabled")
        .removeAttr("disabled")
        .removeAttr("rel");
};

/**
 * Append success message to provided parent element.
 * @param {Object} parentElement Parent element where message will be appended.
 * @param {String} message Message to be displayed.
 */
AS.Util.displaySuccessMessage = function (parentElement, message) {
    $(".alert-success").remove();
    var div = ("<div class='alert alert-success mb-3'>"+message+"</div>");
    parentElement.prepend(div);
};


/**
 * Append error message to an input element. If message is omitted, it will be set to empty string.
 * @param {Object} element Input element on which error message will be appended.
 * @param {String} message Message to be displayed.
 */
AS.Util.displayErrorMessage = function(element, message) {
    element.addClass('is-invalid').removeClass('is-valid');

    if(typeof message !== "undefined") {
        element.after(
            $("<em class='invalid-feedback'>"+message+"</em>")
        );
    }
};


/**
 * Removes all error messages from all input fields.
 */
AS.Util.removeErrorMessages = function () {
    $("form input").removeClass('is-invalid').removeClass('is-valid');
    $(".invalid-feedback").remove();
};

/**
 * Get an parameter from URL.
 * @param {string} name Parameter name.
 * @returns {string} Value of parameter with given name.
 */
AS.Util.urlParam = function(name) {
    return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)')
        .exec(location.search)||[,""])[1]
        .replace(/\+/g, '%20'))||null;
};


/**
 * Show errors received from the server.
 * @param form
 * @param error
 */
AS.Util.showFormErrors = function (form, error) {
    $.each(error.responseJSON.errors, function (key, error) {
        AS.Util.displayErrorMessage($(form).find("input[name="+key+"]"), error);
    });
};

/**
 * Hash a given value using SHA512 hashing algorithm.
 * @param value
 * @returns {string}
 */
AS.Util.hash = function (value) {
    return value.length ? CryptoJS.SHA512(value).toString() : "";
};

/**
 * Submit a specified form using AJAX.
 *
 * @param form A form object.
 * @param data JSON data to be sent via the AJAX request.
 * @param success Success callback.
 * @param error Error callback.
 * @param complete Complete callback.
 */
AS.Http.submit = function (form, data, success, error, complete) {
    AS.Util.removeErrorMessages();

    var $submitBtn = $(form).find("button[type=submit]");

    if ($submitBtn) {
        AS.Util.loadingButton($submitBtn, $submitBtn.data('loading-text') || $_lang.working);
    }

    $.ajax({
        url: "ASEngine/ASAjax.php",
        type: "POST",
        dataType: "json",
        data: data,
        success: function (response) {
            form.reset();

            if (typeof success === "function") {
                success(response);
            }
        },
        error: error || function (errorResponse) {
            AS.Util.showFormErrors(form, errorResponse);
        },
        complete: complete || function () {
            if ($submitBtn) {
                AS.Util.removeLoadingButton($submitBtn);
            }
        }
    });
};

/**
 * Make a HTTP POST request via AJAX.
 *
 * @param data JSON data to be sent via the AJAX request.
 * @param success Success callback.
 * @param error Error callback.
 * @param complete Complete callback.
 */
AS.Http.post = function (data, success, error, complete) {
    $.ajax({
        url: "ASEngine/ASAjax.php",
        type: "POST",
        dataType: "json",
        data: data,
        success: success || function () {},
        error: error || function () {},
        complete: complete || function () {}
    });
};
