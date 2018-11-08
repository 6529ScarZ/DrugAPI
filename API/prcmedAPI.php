<?php
session_save_path("../session/");
session_start(); 

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
if ($method == 'add_med') {
    $med_name = $_POST['med_name'];
    
    $data = array($med_name);
    $table = "medicinal";
    $add_med = $connDB->insert($table, $data);
    $connDB->close_PDO();
    if ($add_med) {
        $res = array("messege"=>'เพิ่มยาเรียบร้อยครับ!!!!');
	    print json_encode($res);
    } else {
        $res = array("messege"=>'Insert not complete ' .$add_med->errorInfo());
	    print json_encode($res);
    }
}elseif ($method == 'edit_med') {
    $med_id = $_POST['med_id'];
    $med_name = $_POST['med_name'];

    $data = array($med_name);
    $table = "medicinal";
    $where="med_id=:med_id";
    $execute=array(':med_id' => $med_id);
    $edit_med=$connDB->update($table, $data, $where, '', $execute);    
    if ($edit_med) {
        $res = array("messege"=>'แก้ไขยาสำเร็จครับ!!!!');
	    print json_encode($res);
    } else {
        $res = array("messege"=>'Update not complete ' .$add_med->errorInfo());
	    print json_encode($res);
    }
}
$connDB->close_PDO();