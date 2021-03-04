<?php 
include 'db/dbcon.php';

function show_date($x){
    $today = (isset($_GET['today']))? $_GET['today'] : date('Y-m-d'); 

    if($x == 1){
        return date("Y-m-d", strtotime($today."+1 day"));
    }else if($x==-1){
        return date("Y-m-d", strtotime($today."-1 day"));
    }
}

$mode = $_GET['mode'];

if($mode == "add"){

    $qr = "INSERT INTO todo_list
            SET todo = :todo,
                reg_date = now(),
                mod_date = now(),
                date = :date";
    $stmt = $conn->prepare($qr);
    $stmt->bindParam(':todo',$_POST['todo']);
    $stmt->bindParam(':date',$_POST['date']);
    $stmt->execute();

    echo "SUCCESS"; 
    exit;
}
else if($mode=="edit"){
    if(isset($_POST['done'])){
        $qr = "UPDATE todo_list SET done = $_POST[done], mod_date=now() WHERE number = $_GET[number]";
        $stmt = $conn->prepare($qr);
        $stmt->execute();

        echo "SUCCESS";
        exit;
    }else if(isset($_POST['todo'])){
        $qr = "UPDATE todo_list SET todo=:todo , mod_date=now() WHERE number = $_GET[number]";
        $stmt = $conn->prepare($qr);
        $stmt->bindParam(':todo',$_POST['todo']);
        $stmt->execute();

        echo "SUCCESS";
        exit;
    }
}
else if($mode=="del"){
    $qr = "DELETE FROM todo_list WHERE number = $_GET[number]";
    $stmt = $conn->prepare($qr);
    $stmt->execute();

    echo "SUCCESS";
    exit;
}
else if($mode == ''){
    $today = (isset($_GET['today']))? $_GET['today'] : date('Y-m-d');
    $week = array("Sun" , "Mon"  , "Tue" , "Wed" , "Thu" , "Fri" ,"Sat") ;
    $weekday = $week[ date('w'  , strtotime($today)  ) ] ;
    
    $end_date = '2021-06-05';
    $d_day =  intval((strtotime($end_date)-strtotime($today)) / 86400);

    $d_day = ($d_day==0)? "day" : $d_day ;

    $qr = "SELECT * FROM todo_list WHERE date = '$today' ORDER BY reg_date";
    $stmt = $conn-> prepare($qr);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $lists = '';

    if($stmt->rowCount()==0){
        $lists = "<div align='center'>등록된 리스트가 없습니다.</div>";
    }
    else{
        foreach($result as $data){
            $checked = ($data['done']==1)? "checked" : "";
            $done = ($data['done']==1)? "done_list" : "";
            $lists .= '
            <div id="list_'.$data['number'].'" class="custom-control custom-checkbox todo_list_contents my-4" >
                        <input type="checkbox" class=" todo_check custom-control-input" id="'.$data['number'].'" '.$checked.'>
                        <label id="'.$data['number'].'-label" class=" custom-control-label todo_label '.$done.'" for="'.$data['number'].'">'.$data['todo'].'</label>
                        <div class="todo_btn">
                            <span class="pointer icon_btn" onclick="show_edit('.$data['number'].')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>&nbsp;&nbsp;
                            <span class="pointer icon_btn" onclick="del_list('.$data['number'].')" ><i class="fa fa-trash " aria-hidden="true"></i></span>
                        </div>
                </div>
                <div id="edit_'.$data['number'].'" class="todo_list_edit pl-0 hidden my-4" >
                    <div class="row">
                        <div class="col-md-11">
                            <input id="edit_'.$data['number'].'_input"  type="text" class="form-control" value="'.$data['todo'].'">
                        </div>
                        <div class="col-md-1 pl-0">
                            <span onclick="edit_list('.$data['number'].')" class="ml-3 pointer icon_btn"><i class="fa fa-check" aria-hidden="true"></i></span>
                        </div>
                    </div>
                    
                        
                        
                       
                </div>
            ';
        }
    }

    
    $todo_list = '
        <div class="col-md-12 my-4 todo_list">
        '.$lists.'
        </div>
    ';

    include 'templates/index.html';

}

?>