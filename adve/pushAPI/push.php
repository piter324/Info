<?php
set_time_limit(0); //removes time limit for script execution
ignore_User_Abort(True); //disable automatic script exit if user disconnects. you can set it to false if you want the script to stop executing when user exits. But its better to exit the script manually if you want to save some data or make some other changes.
session_start();
while(!connection_aborted())//this function checks if user is online.
{

    if($_SESSION['x']%10==0)//here check if an update is available or not. If its available echo it and exit the script so that browser will recieve a complete response (status code 200)
    {
        echo "Update Found ".$_SESSION['x'];
        ob_get_flush();
        flush();//if it sees that user is offline (fails to send response) then it terminates the script if ignore_User_About is not set to True. Here it will ignore the failed response as its set to True.
        $_SESSION['x']++;
        exit;
    }
    else
    {
        sleep(1);//wait for 1 second before checking for update and finding out if user is online or not.
        $_SESSION['x']++;
    }
    
}
$plik=fopen("lol.txt","w");
fwrite($plik, "abcd");
fclose($plik);
?>