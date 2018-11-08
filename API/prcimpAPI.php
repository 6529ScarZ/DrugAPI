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
if ($method == 'add_import') {
    $comp_id = $_POST['comp_id'];
    $lot_date = insert_date($_POST['lot_date']);
    // $lot_price = $_POST['lot_price'];
    // $lot_amount = $_POST['lot_amount'];
    $importer = $_POST['importer'];
    
    $data = array($comp_id,$lot_date,0,0,$importer);
    $table = "lot";
    $add_import = $connDB->insert($table, $data);
    $connDB->close_PDO();
    if ($add_import) {
        $res = array("messege"=>'นำเข้ายาเรียบร้อยครับ!!!!');
	    print json_encode($res);
    } else {
        $res = array("messege"=>'Insert not complete ' .$add_import->errorInfo());
	    print json_encode($res);
    }
}elseif ($method == 'edit_import') {
    $lot_id = $_POST['lot_id'];
    $comp_id = $_POST['comp_id'];
    $lot_date = insert_date($_POST['lot_date']);
    $lot_price = $_POST['lot_price'];
    $lot_amount = $_POST['lot_amount'];
    $importer = $_POST['importer'];
    
    $data = array($comp_id,$lot_date,$lot_price,$lot_amount,$importer);
    $table = "lot";
    $where="lot_id=:lot_id";
    $execute=array(':lot_id' => $lot_id);
    $edit_imp=$connDB->update($table, $data, $where, '', $execute);    
    if ($edit_imp) {
        $res = array("messege"=>'แก้ไขการนำเข้าสำเร็จครับ!!!!');
	    print json_encode($res);
    } else {
        $res = array("messege"=>'Update not complete ' .$edit_imp->errorInfo());
	    print json_encode($res);
    }
}
$connDB->close_PDO();