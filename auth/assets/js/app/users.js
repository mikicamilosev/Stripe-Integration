var activeUser = null,
    editUserMode = false;

$(document).ready(function() {
    /**
     * Initialize datatables and hide the ajax loader.
     */
    $('#users-list').dataTable({
        initComplete: function() {
            $('#loading-users').remove();
            $("#users-list, #users-list_wrapper").show();
        },
        language: {
            url: AS.App.lang != "en" ? "assets/js/vendor/datatables/lang/"+AS.App.lang+".json" : null
        }
    });
});

/**
 * Change role button click.
 */
$(".change-role").click(function () {
    activeUser = $(this).data('user');

    // Set active option inside the select box.
    $("#select-user-role").val($(this).data('role'));
});

/**
 * Makes an AJAX call to change the role of a specific user.
 */
$("#change-role-button").click(function () {
    AS.Http.post({
        action: "changeRole",
        userId: activeUser,
        role: $("#select-user-role").val()
    }, function (response) {
        $(".change-role[data-user="+activeUser+"] .user-role").text(response.role);
    });
});

/**
 * Shows the create user modal.
 */
$("#btn-show-user-modal").click(function () {
    AS.Util.removeErrorMessages();
    activeUser = null;
    editUserMode = false;

    $("#modal-add-edit-user .modal-title").text($_lang.add_user);

    AS.Util.removeErrorMessages();

    $("#add-user-form")[0].reset();
    $("#add-user-form input[type=password]").removeAttr('placeholder');

    $("#btn-add-user").text($_lang.add);
});

/**
 * Validate and submit the create/update user form.
 */
$("#add-user-form").validate({
    rules: {
        email: {
            required: true,
            email: true
        },
        username: "required",
        password: {
            required: function () {
                return ! editUserMode;
            },
            minlength: 6
        },
        password_confirmation: {
            required: function () {
                return ! editUserMode;
            }
        }
    },
    submitHandler: function(form) {
        AS.Http.submit(form, getUserFormData(form), function () {
            location.reload();
        });
    }
});

/**
 * Builds the create/update user form data.
 * @param form
 */
function getUserFormData(form) {
    return  {
        action: editUserMode ? "updateUser" : "addUser",
        user: {
            user_id: editUserMode ? activeUser : null,
            email: form['email'].value,
            username: form['username'].value,
            password: AS.Util.hash(form['password'].value),
            password_confirmation: AS.Util.hash(form['password_confirmation'].value),
            first_name: form['first_name'].value,
            last_name: form['last_name'].value,
            address: form['address'].value,
            phone: form['phone'].value
        }
    };
}

/**
 * Show edit user modal.
 */
$(".edit-user").click(function () {
    AS.Util.removeErrorMessages();
    activeUser = $(this).data('user');
    editUserMode = true;

    var $modalTitle = $("#modal-add-edit-user .modal-title"),
        $modalBody = $("#modal-add-edit-user .modal-body"),
        $modalFooter = $("#modal-add-edit-user .modal-footer"),
        $ajaxLoader = $("#modal-add-edit-user .ajax-loading");

    $modalTitle.text($_lang.loading);
    $modalBody.hide();
    $modalFooter.hide();
    $ajaxLoader.show();

    AS.Http.post({
        action: "getUser",
        userId: activeUser
    }, function (res) {
        var form = $("#add-user-form")[0];

        $(form['email']).val(res.email);
        $(form['username']).val(res.username);
        $(form['first_name']).val(res.first_name);
        $(form['last_name']).val(res.last_name);
        $(form['address']).val(res.address);
        $(form['phone']).val(res.phone);

        $(form['password']).attr('placeholder', $_lang.leave_blank);
        $(form['password_confirmation']).attr('placeholder', $_lang.leave_blank);

        $(form['button']).text($_lang.update);

        $modalTitle.text(res.username);
        $modalBody.show();
        $modalFooter.show();
        $ajaxLoader.hide();
    });
});

/**
 * Show user details modal.
 */
$(".user-details").click(function () {
    var $modalTitle = $("#modal-user-details .modal-title"),
        $modalBody = $("#modal-user-details .modal-body"),
        $modalFooter = $("#modal-user-details .modal-footer"),
        $ajaxLoader = $("#modal-user-details .ajax-loading");

    $modalTitle.text($_lang.loading);
    $modalBody.hide();
    $modalFooter.hide();
    $ajaxLoader.show();

    AS.Http.post({
        action: "getUserDetails",
        userId: $(this).data('user')
    }, function (res) {
        $("#modal-details--email").text(res.email);
        $("#modal-details--first-name").text(res.first_name);
        $("#modal-details--last-name").text(res.last_name);
        $("#modal-details--address").text(res.address);
        $("#modal-details--phone").text(res.phone);
        $("#modal-details--last-login").text(res.last_login);

        $modalTitle.text(res.username);
        $modalBody.show();
        $modalFooter.show();
        $ajaxLoader.hide();
    });
});

/**
 * Sends and AJAX request to ban the specific user.
 */
$(".ban-user").click(function () {
    var $btn = $(this);

    AS.Http.post({
        userId: $btn.data('user'),
        action: "banUser"
    }, function () {
        $btn.addClass('d-none');
        $btn.parents('.btn-group').find(".unban-user").removeClass('d-none');
        $btn.parents('.btn-group').find(".btn-primary").each(function () {
            $(this).removeClass("btn-primary").addClass("btn-danger");
        });
    });
});

/**
 * Sends and AJAX request to unban the specific user.
 */
$(".unban-user").click(function () {
    var $btn = $(this);

    AS.Http.post({
        userId: $btn.data('user'),
        action: "unbanUser"
    }, function () {
        $btn.addClass('d-none');
        $btn.parents('.btn-group').find(".ban-user").removeClass('d-none');
        $btn.parents('.btn-group').find(".btn-danger").each(function () {
            $(this).removeClass("btn-danger").addClass("btn-primary");
        });
    });
});

/**
 * Delete user button handler. Sends an AJAX request
 * to remove the specified user from the system.
 */
$(".delete-user").click(function () {
    if(! confirm($_lang.are_you_sure)) return;

    var $btn = $(this);

    AS.Http.post({
        action: "deleteUser",
        userId: $btn.data('user')
    }, function () {
        $btn.parents(".user-row").fadeOut(600, function () {
            $(this).remove();
        });
    });
});
