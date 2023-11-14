/**
 * Validate and submit a new comment form.
 */
$("#new-comment-form").validate({
    rules: {
        comment: "required"
    },
    submitHandler: function(form) {
        AS.Http.submit(form, {
            action: "postComment",
            comment: form['comment'].value
        }, function (response) {
            if($("#comment-list figure").length >= 7) {
                $("#comment-list figure").last().remove();
            }

            $("#comment-list").prepend(buildNewCommentElement(response));
        });
    }
});

/**
 * Build a new comment HTML from a response received from the server.
 * @param response
 * @returns {jQuery|HTMLElement}
 */
function buildNewCommentElement(response) {
    var html  = "<figure class='mb-4 border-start ps-3'>";
        html += "   <blockquote class='blockquote pt-2 pb-2'>";
        html += "       <p>"+response.comment+"</p>";
        html += "   </blockquote>";
        html += "   <figcaption class='blockquote-footer'>";
        html += "       <strong>" + response.user + "</strong>";
        html += "       <em>" + $_lang.at + " " + response.postTime + "</em>";
        html += "   </figcaption>";
        html += "</figure>";

    return $(html);
}
