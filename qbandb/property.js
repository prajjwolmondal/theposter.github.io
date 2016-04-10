$(document).ready(function(){

    $("#btn").click(function(){

        $("#ownerReplyTextArea").toggle();
        //document.getElementById("ownerReplyTextArea").style.display = 'block';
    });
});
// function showReply(){
//     document.getElementById("owner_reply").innerHTML = "<tr><td><div class='row'><form class='col s12' action='property.php' method='post'><div class='row'><div class='input-field col s12'><textarea id='comment' length='500' class='materialize-textarea' name='user_comment'></textarea><label for='comments'>Your Comment (Optional)</label></div></div><button class='btn waves-effect waves-light' type='submit' name='action'>Reply<i class='material-icons right'>trending_flat</i></button></form></div></td></tr>";
// }