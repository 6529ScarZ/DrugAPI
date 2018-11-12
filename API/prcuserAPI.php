<?php
session_save_path("../session/");
//session_start(); 

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET,POST");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json;charset=utf-8');

function __autoload($class_name) {
    include '../class/' . $class_name . '.php';
}
include '../function/string_to_ascii.php';
set_time_limit(0);
$connDB = new EnDeCode();
$read = "../connection/conn_DB.txt";
$connDB->para_read($read);
$connDB->Read_Text();
$connDB->conn_PDO();

function insert_date($take_date_conv) {
    $take_date = explode("-", $take_date_conv);
    $take_date_year = @$take_date[2] - 543;
    $take_date = "$take_date_year-" . @$take_date[1] . "-" . @$take_date[0] . "";
    return $take_date;
}

$method = isset($_POST['method']) ? $_POST['method'] : $_GET['method'];
if ($method == 'add_user') {
    $user_fname = $_POST['user_fname'];
    $user_lname = $_POST['user_lname'];
    $admin = $_POST['admin'];
    $user_name = filter_input(INPUT_POST, 'user_account',FILTER_SANITIZE_STRING);
    $user_account = md5(string_to_ascii(trim($user_name)));
    $user_pwd = md5(string_to_ascii(trim(filter_input(INPUT_POST, 'user_pwd',FILTER_SANITIZE_STRING))));
    $path = $_POST['path'];
    //$newname = new upload_resizeimage("file", "../USERimgs", "USimage".date("dmYHis"));
    if (!empty($_FILES["file"]["type"])) {
    $newname = new upload_resizeimage("file", $path."USERimgs/", "USimage".date("dmYHis"));
    $img = $newname->upload(); 
    if($img != FALSE){
        $photo = $img;
    } else {
        $photo = '';
    }}else{
        $photo = '';
    }
    $data = array($user_fname, $user_lname, $user_name, $user_account,$user_pwd,$admin,NULL,NULL,$photo);
    $table = "user";
    $add_user = $connDB->insert($table, $data);
    $connDB->close_PDO();
    if ($add_user) {
        $res = array("messege"=>'เพิ่มผู้ใช้เรียบร้อยครับ!!!!');
	    print json_encode($res);
    } else {
        $res = array("messege"=>'Insert not complete ' .$add_user->errorInfo());
	    print json_encode($res);
    }
}elseif ($method == 'edit_user') {
    $user_id = $_POST['user_id'];
    $user_fname = $_POST['user_fname'];
    $user_lname = $_POST['user_lname'];
    $admin = isset($_POST['admin'])?$_POST['admin']:'';
    $user_name = filter_input(INPUT_POST, 'user_account',FILTER_SANITIZE_STRING);
    $user_account = md5(string_to_ascii(trim($user_name)));
    $user_pwd = $_POST['user_pwd']!=''?md5(string_to_ascii(trim(filter_input(INPUT_POST, 'user_pwd',FILTER_SANITIZE_STRING)))):'';
    $path = $_POST['path'];
    if(empty($user_pwd)){
    $data = array($user_fname, $user_lname, $user_name, $user_account);
    $field=array("user_fname", "user_lname", "user_name", "user_account");
    }else{
    $data = array($user_fname, $user_lname, $user_name, $user_account,$user_pwd);
    $field=array("user_fname", "user_lname", "user_name", "user_account","user_pwd");
    }
    if (!empty($_FILES["file"]["type"])) {
    $del_photo="select photo from user where user_id=:user_id";
                $connDB->imp_sql($del_photo);
                $execute=array(':user_id' => $user_id);
                $result=$connDB->select_a($execute);
                if(!empty($result['photo'])){
                $location=$path."USERimgs/".$result['photo'];
                include '../function/delet_file.php';
                fulldelete($location);}
                $newname = new upload_resizeimage("file", $path."USERimgs/", "USimage".date("dmYHis"));
                $img = $newname->upload(); 
                if($img != FALSE){
                    array_push($data,$img);
                    array_push($field,"photo");
                } 
}    
    if(!empty($admin)){
                    array_push($data,$admin);
                    array_push($field,"status_user");
                } 
    $table = "user";
    $where="user_id=:user_id";
    $execute=array(':user_id' => $user_id);
    $edit_user=$connDB->update($table, $data, $where, $field, $execute);    
    if ($edit_user) {
        $res = array("messege"=>'แก้ไขผู้ใช้สำเร็จครับ!!!!');
	    print json_encode($res);
    } else {
        $res = array("messege"=>'Update not complete ' .$add_user->errorInfo());
	    print json_encode($res);
    }
}
$connDB->close_PDO();