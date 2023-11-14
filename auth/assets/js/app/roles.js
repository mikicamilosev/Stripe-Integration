/**
 * Add new role form validation and submission.
 */
$("#add-role-form").validate({
    rules: {
        name: {
            required: true,
            maxlength: 20
        }
    },
    submitHandler: function(form) {
    	AS.Http.submit(form, {
            action: "addRole",
            role: form['name'].value
        }, function (response) {
            $(".roles-table").append(buildRoleRow(response));
        });
    }
});

/**
 * Bind click event to a button with ".delete-role" class.
 */
$(".delete-role").click(function () {
    deleteRole($(this).data('role'));
});

/**
 * Sends an AJAX request to remove the role with a given id from the system.
 * @param id
 */
function deleteRole(id) {
    if(! confirm($_lang.are_you_sure)) return;

    AS.Http.post({
        action: "deleteRole",
        roleId: id
    }, function () {
        $("button[data-role="+id+"]")
            .parents(".role-row")
            .fadeOut("slow", function () {
                $(this).remove();
            });
    });
}

/**
 * Generates new role table row.
 * @param response JSON response from the server when new role is created.
 * @returns {jQuery|HTMLElement}
 */
function buildRoleRow(response) {
    var html  = '<tr class="role-row">';
        html += '	<td>'+response.role_name+'</td>';
        html += '	<td>0</td>';
        html += '	<td>';
        html += '		<button type="button" class="btn btn-danger btn-sm" data-role="'+response.role_id+'">';
        html += '			<i class="fa fa-trash"></i>';
        html += '		</button>';
        html += '	</td>';
        html += '</tr>';

    var $row = $(html);

    $row.click(function () {
        deleteRole(response.role_id);
    });

    return $row;
}
