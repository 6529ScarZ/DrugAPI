<?php
session_save_path("../session/");
//session_start(); 

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET,POST");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json;charset=utf-8');

function __autoload($class_name) {
    include_once '../class/'.$class_name.'.php';
}
include '../function/string_to_ascii.php';
//$user_account = md5(trim(filter_input(INPUT_POST, 'user_account',FILTER_SANITIZE_ENCODED)));
$user_account = md5(string_to_ascii(trim(filter_input(INPUT_POST, 'user_account',FILTER_SANITIZE_STRING))));
//$user_pwd = md5(trim(filter_input(INPUT_POST, 'user_pwd',FILTER_SANITIZE_ENCODED)));
$pass = trim(filter_input(INPUT_POST, 'user_pwd',FILTER_SANITIZE_STRING));
$user_pwd = md5(string_to_ascii($pass));

$dbh=new dbPDO_mng();
$read="../connection/conn_DB.txt";
$dbh->para_read($read);
$dbh->conn_PDO();

$sql = "select user_id,concat(user_fname,' ',user_lname)fullname,status_user,photo from user 
           where   user_account= :user_account && user_pwd= :user_pwd";
$execute=array(':user_account' => $user_account, ':user_pwd' => $user_pwd);
$dbh->imp_sql($sql);
$result=$dbh->select_a($execute);

if ($result) {

if($user_account!='b9a4ffe8f7aacdac3e8bfcf24bb8ba4f'
 and $user_pwd!='048b6ae1d417351c46d74b7b1244ecdc'){
$date = new DateTime(null, new DateTimeZone('Asia/Bangkok'));//กำหนด Time zone
$date_login = $date->format('Y-m-d');
$time_login = time();
                $table = "user";
                $data = array($date_login,$time_login);
                $field = array("date_login","time_login");
                $where = "user_account= :user_account && user_pwd= :user_pwd";
                $execute = array(':user_account' => $user_account, ':user_pwd' => $user_pwd);
                $edit_address = $dbh->update($table, $data, $where, $field, $execute);
 }
    print json_encode($result);
}else{
    $res = array("messege"=>'ชื่อหรือรหัสผ่านผิด กรุณาตรวจสอบอีกครั้ง!');
	print json_encode($res);
    exit();
}
$dbh->close_PDO();
?>
