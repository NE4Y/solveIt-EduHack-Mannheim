
function appendMessage(text, username){
    var date = new Date();
    var dd = date.getDate();
    var mm = date.getMonth()+1; //January is 0!

    var yyyy = date.getFullYear();
    var h = date.getHours();
    var m = date.getMinutes();
    var lastMessage =  $('body .content p:last-child');
    lastMessage = $(lastMessage[lastMessage.length -1]);
    var lastUser = lastMessage.parent().parent().last().children().first().children().first().children().last().text();
    var newText =  '<p>' + text + '<span class="rightTime">' + (dd < 10 ? "0" + dd : dd) + '.'
                + (mm < 10 ? "0" + mm : mm) + '.' + yyyy + ' - ' 
                + (h < 10 ? "0" + h : h) + ':' + (m < 10 ? "0" + m: m) + '</span></p>';
    if(lastUser == username) { 
        lastMessage.parent().append(newText);
    } else{
        var newMessage = '<div class="col-md-12 post clear">'
            + '<div class="col-md-2">'
            + '<div class="user1 user-data">'
            + ' <img src="img/login_bild.png" alt="Benutzerbild" />'
            + '<a href="">' + username + '</a>'
            + '</div></div>'
            + '<div class="col-md-10 content" id="chatFeed">'
            + newText
            + '</div></div></div>';  
        lastMessage.parent().parent().parent().append(newMessage);
    }
}

function update(){
    var inMessage = $('#chatMessage');
    var message = inMessage.val();
    var userId = $('#sessionId').val();
    var toId = $('#toID').val();
    var chatId = /id=([0-9]+)/.exec(window.location + "");
    var chatId = chatId[1];
    $.ajax({
        url: 'http://ne4y-dev.de/DEV/EduHack/EduHack/websocketclient/sender.php?to='+toId+'&from='+userId+'&message='+message+'&chatID='+chatId,
        success: function(result) {
            
            appendMessage(message, $('#username').val());
            inMessage.val("");
            $('html, body').animate({
                scrollTop: $("#chatSend").offset().top
            }, 1000);
        }
    });
}

$(document).delegate("button#chatSend", "click", function() {
    update();
    return false;

});

$(document).delegate("input#chatMessage", "keypress", function(e){
    if(e.which === 13) {
       update();
      return false; 
    }
});
