<?php  
    header('content-type:text/html;charset=utf-8');
    $pdo=new PDO('mysql:host=localhost;dbname=databasename;','root','root');
    $pdo->exec('set names utf8');
    $username=$_POST['username'];
    $sqlQuery="select * from user where username='$username'";
    $row=$pdo->query($sqlQuery)->fetch(PDO::FETCH_ASSOC);
    if($row){
        $sign_time=$row['sign_time'];
        $sign_time=strtotime($sign_time);
        $int=date('Y-m-d');
        $int=strtotime($int);//5
        $ints=$int+86400;    //6
        $int_s=$int-86400;   //4
        //当天已签到
        if($int<$sign_time&&$sign_time<$ints){
            // echo '您已签到';
        }
        //昨天未签到，积分，天数在签到修改为1
        if($sign_time<$int_s){
            $count=1;
            $point=1;
            $sign_time=date('Y-m-d H:s:i');
            $sqlRow="update user set count='$count',point='$point',sign_time='$sign_time' where username='$username'";
            $res=$pdo->exec($sqlRow);
            // echo '签到成功修改为1';
        }
        //请签到
        if($int_s<$sign_time&&$sign_time<$int){
            $count=$row['count']+1;
            $point=$row['point']+1;
            $sign_time=date('Y-m-d H:s:i');
            $sqlupdate="update user set count='$count',point='$point',sign_time='$sign_time' where username='$username'";
            $res=$pdo->exec($sqlupdate);
            // echo '签到成功+1';
        }
    }else{
        $count=1;
        $point=1;
        $sign_time=date('Y-m-d H:s:i');
        $sqlAdd="insert into user values (null,'$username','$count','$point','$sign_time')";
        $res=$pdo->exec($sqlAdd);
        // echo '恭喜你签到成功----1';
    }
    //响应
    $sqlEnd="select * from user where username='$username'";
    $info=$pdo->query($sqlEnd)->fetch(PDO::FETCH_ASSOC);
    echo json_encode(array('success'=>1,'msg'=>$info));die;
?>
