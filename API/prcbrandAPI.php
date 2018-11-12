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
if ($method == 'add_brand') {
    $brand_name = $_POST['brand_name'];
    $mash_pri = $_POST['mash_pri'];
    $mash_sec = $_POST['mash_sec'];
    $mash_th = $_POST['mash_th'];
    $drug_kind = $_POST['drug_kind'];
    $max = $_POST['max'];
    $min = $_POST['min'];
    
    $data = array($brand_name,$mash_pri,$mash_sec,$mash_th,$drug_kind,$max,$min);
    $table = "drug_brand";
    $add_brand = $connDB->insert($table, $data);
    $connDB->close_PDO();
    if ($add_brand) {
        $res = array("messege"=>'เพิ่มยี่ห้อเรียบร้อยครับ!!!!');
	    print json_encode($res);
    } else {
        $res = array("messege"=>'Insert not complete ' .$add_brand->errorInfo());
	    print json_encode($res);
    }
}elseif ($method == 'edit_brand') {
    $db_id = $_POST['db_id'];
    $brand_name = $_POST['brand_name'];
    $mash_pri = $_POST['mash_pri'];
    $mash_sec = $_POST['mash_sec'];
    $mash_th = $_POST['mash_th'];
    $drug_kind = $_POST['drug_kind'];
    $max = $_POST['max'];
    $min = $_POST['min'];
    $receive = $_POST['receive'];
    
    $data = array($brand_name,$mash_pri,$mash_sec,$mash_th,$drug_kind,$max,$min,$receive);
    $table = "drug_brand";
    $where="db_id=:db_id";
    $execute=array(':db_id' => $db_id);
    $edit_brand=$connDB->update($table, $data, $where, '', $execute);    
    if ($edit_brand) {
        $res = array("messege"=>'แก้ไขยี่ห้อสำเร็จครับ!!!!');
	    print json_encode($res);
    } else {
        $res = array("messege"=>'Update not complete ' .$edit_brand->errorInfo());
	    print json_encode($res);
    }
}
$connDB->close_PDO();