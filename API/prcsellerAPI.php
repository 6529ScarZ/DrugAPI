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
if ($method == 'add_seller') {
    $comp_name = $_POST['comp_name'];
    $comp_vax = $_POST['comp_vax'];
    $comp_address = $_POST['comp_address'];
    $comp_tel = $_POST['comp_tel'];
    $comp_mobile = $_POST['comp_mobile'];
    $comp_fax = $_POST['comp_fax'];
    
    $data = array($comp_name, $comp_vax, $comp_address, $comp_tel,$comp_mobile,$comp_fax);
    $table = "seller";
    $add_seller = $connDB->insert($table, $data);
    $connDB->close_PDO();
    if ($add_seller) {
        $res = array("messege"=>'เพิ่มร้านซื้อเรียบร้อยครับ!!!!');
	    print json_encode($res);
    } else {
        $res = array("messege"=>'Insert not complete ' .$add_user->errorInfo());
	    print json_encode($res);
    }
}elseif ($method == 'edit_seller') {
    $comp_id = $_POST['comp_id'];
    $comp_name = $_POST['comp_name'];
    $comp_vax = $_POST['comp_vax'];
    $comp_address = $_POST['comp_address'];
    $comp_tel = $_POST['comp_tel'];
    $comp_mobile = $_POST['comp_mobile'];
    $comp_fax = $_POST['comp_fax'];

    $data = array($comp_name, $comp_vax, $comp_address, $comp_tel,$comp_mobile,$comp_fax);
    $table = "seller";
    $where="comp_id=:comp_id";
    $execute=array(':comp_id' => $comp_id);
    $edit_seller=$connDB->update($table, $data, $where, '', $execute);    
    if ($edit_seller) {
        $res = array("messege"=>'แก้ไขผู้ใช้สำเร็จครับ!!!!');
	    print json_encode($res);
    } else {
        $res = array("messege"=>'Update not complete ' .$add_user->errorInfo());
	    print json_encode($res);
    }
}
$connDB->close_PDO();