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
include_once '../plugins/funcDateThai.php';
$conn_DB = new EnDeCode();
$read = "../connection/conn_DB.txt";
$conn_DB->para_read($read);
$conn_db = $conn_DB->Read_Text();
$conn_DB->conn_PDO();
set_time_limit(0);
$rslt = array();
$series = array();
$data = isset($_GET['data'])?$_GET['data']:'';
if(!empty($data)){
    $where = "where lot_id=".$data;
}else{
    $where = "";
}
$sql="SELECT l.lot_id,s.comp_name,l.lot_date,l.lot_price,l.lot_amount
,CONCAT(u.user_fname,' ',u.user_lname)fullname
FROM lot l
INNER JOIN user u on u.user_id=l.importer
INNER JOIN seller s on s.comp_id=l.comp_id
$where"; 
$conn_DB->imp_sql($sql);
    $num_risk = $conn_DB->select();
    for($i=0;$i<count($num_risk);$i++){
        $series['ID'] = $num_risk[$i]['lot_id'];
        $series['name'] = $num_risk[$i]['comp_name'];
        $series['lot_date'] = DateThai1($num_risk[$i]['lot_date']);
        $series['lot_price'] = $num_risk[$i]['lot_price'];
        $series['lot_amount'] = $num_risk[$i]['lot_amount'];
        $series['fullname'] = $num_risk[$i]['fullname'];
    array_push($rslt, $series);    
    }
print json_encode($rslt);
$conn_DB->close_PDO();