
function go(date) {
    location.href = "index.php?today=" + date;
}
function edit_list(number) {
    $.ajax({
        url: '?mode=edit&number=' + number,
        type: 'post',
        data: { 'todo': $('#edit_' + number + "_input").val() },
        success: function (data) {
            location.reload();
        }
    });
}
function del_list(number) {
    $('#list_' + number).remove();
    $('#edit_' + number).remove();

    $.ajax({
        url: '?mode=del&number=' + number,
        type: 'get',
        success: function (data) {
        }
    });
}
function show_edit(number) {
    $('#list_' + number).addClass("hidden");
    $('#edit_' + number).removeClass("hidden");
}
$('.todo_check').click(function () {
    id = $(this).attr('id');
    var done = 0;
    if ($(this).is(":checked") == true) {
        $('#' + id + '-label').addClass('done_list');
        done = 1
    } else {
        $('#' + id + '-label').removeClass('done_list');
    }
    $.ajax({
        url: '?mode=edit&number=' + id,
        type: 'post',
        data: { 'done': done },
        success: function (data) {
        }
    });
});
var audio = new Audio('static/mp3/modakbul.mp3');
audio.loop = true;

function audio_play(x){
    var inner = '';
    if(!audio.paused){ //check audio is playing
        audio.pause();
        inner = '<i class="fa fa-volume-off fa-2x" aria-hidden="true"></i>';
        
    }else{
        audio.play();
        inner = '<i class="fa fa-volume-up fa-2x" aria-hidden="true"></i>';
    }
    x.innerHTML = inner;
}