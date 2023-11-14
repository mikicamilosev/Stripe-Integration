<?php 
include 'templates/header.php';

$currentUser = app('current_user');
?>

<div class="row">
    <?php
        $sidebarActive = 'negotiations';
        require 'templates/sidebar.php';
    ?>
    <div class="col-lg-9">
        <?php if (! $currentUser->is_admin) : ?>
            <div class="alert alert-warning">
                <strong><?= trans('note') ?>! </strong>
                <?= trans('to_change_email_username') ?>
            </div>
        <?php endif; ?>
        <div class="row">
            <div class="col-12">
                <span class="text-danger"><i class="fa-solid fa-circle-exclamation"></i> Conduct business at your own risk. Do not share personal information or pay anybody unless you are absolutely sure about the transaction. You will have no recourse if you are the victim of a scam. We cannot recover your money or products, and we cannot compensate any loss you incur through using our website.</span>
            </div>
        </div>
        <br><br>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="fa-solid fa-comments"></i> My negotiations
                    </div>
                    <div class="card-body" style="padding:0;">
                        <div class="container">
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="negotiate chat-app">
            <div id="plist" class="people-list">
            <!--
                <div class="input-group">
                    <div class="input-group-prepend">
                        <button class="input-group-text"><i class="fa fa-search" style="padding:.75rem .75rem;"></i></button>
                    </div>
                    <input type="text" class="form-control" placeholder="Search...">
                </div>
            -->
                <ul class="list-unstyled chat-list mt-2 mb-0">
                    <li class="clearfix">
                        <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="avatar">
                        <div class="about">
                            <div class="name">Vincent Porter</div>
                            <div class="status"> <i class="fa fa-circle offline"></i> left 7 mins ago </div>                                            
                        </div>
                    </li>
                    <li class="clearfix active">
                        <img src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="avatar">
                        <div class="about">
                            <div class="name">Aiden Chavez</div>
                            <div class="status"> <i class="fa fa-circle online"></i> online </div>
                        </div>
                    </li>
                    <li class="clearfix">
                        <img src="https://bootdey.com/img/Content/avatar/avatar3.png" alt="avatar">
                        <div class="about">
                            <div class="name">Mike Thomas</div>
                            <div class="status"> <i class="fa fa-circle online"></i> online </div>
                        </div>
                    </li>                                    
                    <li class="clearfix">
                        <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="avatar">
                        <div class="about">
                            <div class="name">Christian Kelly</div>
                            <div class="status"> <i class="fa fa-circle offline"></i> left 10 hours ago </div>
                        </div>
                    </li>
                    <li class="clearfix">
                        <img src="https://bootdey.com/img/Content/avatar/avatar8.png" alt="avatar">
                        <div class="about">
                            <div class="name">Monica Ward</div>
                            <div class="status"> <i class="fa fa-circle online"></i> online </div>
                        </div>
                    </li>
                    <li class="clearfix">
                        <img src="https://bootdey.com/img/Content/avatar/avatar3.png" alt="avatar">
                        <div class="about">
                            <div class="name">Dean Henry</div>
                            <div class="status"> <i class="fa fa-circle offline"></i> offline since Oct 28 </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="chat">
                <div class="chat-header clearfix">
                    <div class="row">
                        <div class="col-lg-6">
                            <a href="javascript:void(0);" data-toggle="modal" data-target="#view_info">
                                <img src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="avatar">
                            </a>
                            <div class="chat-about">
                                <h6 class="m-b-0">Aiden Chavez</h6>
                                <small>Last seen: 2 hours ago</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="chat-history" id="message-list">
                    <ul class="m-b-0" id="chat_box">
                    <?php
                        $stmt = $db->prepare("SELECT * FROM chats WHERE chat_id=?");
                        $stmt->bind_param("s", $chat_id);

                        $chat_id = "0897h3efjhasdfy9278yh23";

                        $stmt->execute();
                        $result = $stmt->get_result();

                        while ($row = $result->fetch_assoc()) {

                            $date_raw = strtotime($row['datetime']);
                            $date_formatted = date('g:m A, M j, Y', $date_raw);
                    ?>
                            <li class="clearfix">
                                <div class="message-data">
                                    <span class="message-data-time"><?php echo $date_formatted; ?></span>
                                </div>
                                <div class="message my-message"><?php echo $row['message']; ?></div>                                    
                            </li>    
                    <?php
                        }
                    ?>                             
                    </ul>
                </div>
                <div class="chat-message clearfix" style="padding:0 0 0 20px;">
                    <div class="input-group mb-0">
                        <textarea id="newmessage" class="form-control" rows=3 placeholder="Enter text here..."></textarea>   
                        <div class="input-group-append">
                            <button class="input-group-text" id="sendmessage" style="padding:.75rem .75rem;"><i class="fa-solid fa-paper-plane"></i></button>
                        </div>                                
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <script src="assets/js/vendor/sha512.js"></script>
    <?php include 'templates/footer.php'; ?>
    <script src="assets/js/app/profile.js"></script>
    <script>
    $(document).ready(function() {
        var d = $('#message-list');
        d.scrollTop(d.prop("scrollHeight"));
    });
    </script>
    <script>
$('document').ready(function(){
    $('#newmessage').keypress(function(e){
        if(e.which == 13){ //Enter key pressed
            $('#sendmessage').click(); //Trigger search button click event
        }
    });

});
    </script>
    <script>
    $(document).ready(function(){
        $('#sendmessage').click(function(){
                if ($.trim($("#newmessage").val())) {
                var chatid = "0897h3efjhasdfy9278yh23",
                userid = "<?php echo $userid; ?>",
                recipientid = "123",
                offerid = "23s87ya8osdufhjl",
                newmessage = $('#newmessage').val(),
                date = new Date(),
                hours = date.getHours(),
                minutes = date.getMinutes(),
                ampm = hours >= 12 ? 'PM' : 'AM',
                hours = hours % 12,
                hours = hours ? hours : 12, // the hour '0' should be '12'
                minutes = minutes < 10 ? '0'+minutes : minutes,
                strTime = hours + ':' + minutes + ' ' + ampm,
                datetime = strTime + ", Today";
            
                $.ajax({
                    type: 'POST', 
                    url:"/auth/ajax/add_chat_message.php",
                    data:{ 
                    chatid: chatid,
                    userid: userid,
                    recipientid: recipientid,
                    offerid: offerid,
                    newmessage: newmessage
                    },
                    success: function() {
                        $("#message-list").append('<li class="clearfix" style="list-style:none;"><div class="message-data"><span class="message-data-time">' + datetime + '</span><img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="avatar"></div><div class="message my-message">' + newmessage + '</div></li>').fadeIn(2000);
                        var d = $('#message-list');
                        d.scrollTop(d.prop("scrollHeight"));
                    } 
                });
        } else {
            $("#newmessage").css("border", "2px solid red");
        }
            $("#newmessage").val('');
        });       
});
</script>
  </body>
</html>
