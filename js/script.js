$(document).ready(function(){
    $.get('ajax/chat.php?keyword=' + $('#keywordsChat').val(), function(data) {
        $('#area-chating').html(data);
    })

    var chatScroll = document.getElementById("area-chating");
        
    chatScroll.scrollBy(0, 9999999999999999999);
});