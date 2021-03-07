var count = 0;
var time = 0;
var choice = 0;
var bell_sound = new Audio('static/mp3/bell.mp3');

function check(min,sec){
    if(sec > 60 || sec< 0){
        alert("초는 0이상 60미만으로 설정해주세요!");
        return false;
    }
    if(min<0){
        alert("분은 0이상으로 설정해주세요!");
        return false;
    }
    if(min==0 && sec==0){
        alert("분/초를 설정해주세요!");
        return false;
    }
    return true;
}
function reset(){
    $('#timer').html('');
    clearInterval(time); // 시간 초기화
}
function timer(){
    var min = ($('input[name=min]').val()=='')? 0 : $('input[name=min]').val();
    var sec = ($('input[name=sec]').val()=='')? 0: $('input[name=sec]').val();
    count = parseInt(min*60)+parseInt(sec);

    if(check(min,sec)){
        clearInterval(time); 
        time = setInterval("myTimer()", 1000);
    }
    
}
function noodle() {

  clearInterval(time); 

  choice = document.frm.myChoice.selectedIndex;

  count = parseInt(document.frm.myChoice.options[choice].value);

  time = setInterval("myTimer()", 1000);

}

function myTimer() {
  count = count - 1; // 타이머 선택 숫자에서 -1씩 감산함(갱신되기 때문)
  min = parseInt(count/60);

  sec = count%60;

  var text_style='';
  if(count <= 10){
      text_style = 'style="color:red"';
  }

  document.getElementById("timer").innerHTML = " <span "+ text_style+">" + min + " 분 " + sec + " 초 </span>";
  if (count == 0) {
    clearInterval(time); // 시간 초기화
    bell_sound.play();
  }
}