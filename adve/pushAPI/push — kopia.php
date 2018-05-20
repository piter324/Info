<?php
set_time_limit(0); //removes time limit for script execution
ignore_User_Abort(True); //disable automatic script exit if user disconnects. you can set it to false if you want the script to stop executing when user exits. But its better to exit the script manually if you want to save some data or make some other changes.
session_start();
$config_array = parse_ini_file("../../../config_info.ini");
    $con = mysqli_connect($config_array['address'],$config_array['username'],$config_array['password'],$config_array['db_name']);
    if(!$con)
    {
    die("Connection to MySQL Database failed");
    }
    $con->set_charset("utf8");
while(!connection_aborted())//this function checks if user is online.
{
    $currDate=date('Y-m-d');
    $currTime=date('H:i:s');
    $queryCheckLastUpdate="SELECT * FROM modifications WHERE table_name='".$_SESSION['push_table']."'";
    $result=mysqli_query($con,$queryCheckLastUpdate);
    $row=mysqli_fetch_array($result);
    if(isset($_SESSION['lastUpdateDate'])&&($_SESSION['lastUpdateDate']<$row['date_field']||$_SESSION['lastUpdateTime']<$row['time_field']))//here check if an update is available or not. If its available echo it and exit the script so that browser will recieve a complete response (status code 200)
    {
        echo 1;
        $_SESSION['lastUpdateDate']=$currDate;
        $_SESSION['lastUpdateTime']=$currTime;
        ob_get_flush();
        flush();//if it sees that user is offline (fails to send response) then it terminates the script if ignore_User_About is not set to True. Here it will ignore the failed response as its set to True.
        exit;
    }
    else if(!isset($_SESSION['lastUpdateDate']))
    {
        echo 1;
        $_SESSION['lastUpdateDate']=$currDate;
        $_SESSION['lastUpdateTime']=$currTime;
        ob_get_flush();
        flush();//if it sees that user is offline (fails to send response) then it terminates the script if ignore_User_About is not set to True. Here it will ignore the failed response as its set to True.
        exit;
    }
    else
    {
        sleep(5);//wait for 1 second before checking for update and finding out if user is online or not.
    }
    
}
?>