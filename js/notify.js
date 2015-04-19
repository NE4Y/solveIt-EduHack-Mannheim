function notify(text, cID) {
    var notifyArea = $('#notificationArea');
    if(notifyArea) {

        var notification = '<div class="notification" role="alert">' + text
            + '<br />'
            + '<a class="btn btn-success accept-btn" href="index.php?s=chat&id='+cID+'">Anzeigen</a>'
            + '<button class="btn btn-danger ignore-btn">Ignorieren</button>'
           + '</div>';
        notifyArea.append(notification);
    }
}

$(document).delegate(".ignore-btn", "click", function() {
    $(this).parent().remove();
});

$(document).delegate(".accept-btn", "click", function() {
    $(this).parent().remove();
});

notify("Test");
notify("Test2");
