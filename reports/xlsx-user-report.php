<?php
header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=".date("Y-m-d")."-user-report.xls");

include_once '../classes/class.user.php';
include '../config/config.php';

$user = new User();
$user_id_login = $user->get_user_id($_SESSION['user_email']);
$user_access_level = $user->get_user_access($user_id_login);

echo 'Nbr' . "\t" . 'Name' . "\t" . 'Access Level' . "\t" . 'E-Mail' . "\n";

$count = 1;
if($user->list_users() != false){
    foreach($user->list_users() as $value){
        extract($value);
        if($user_access_level == 'Manager' || $user_access_level == 'Supervisor'){
                
            echo $count . "\t" .$rel_date_added.', '.$user_firstname . "\t" . $user_access . "\t" . $user_email . "\n";
            
                $count++;
        }
    }
}