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
$conn_DB = new EnDeCode();
$read = "../connection/conn_DB.txt";
$conn_DB->para_read($read);
$conn_db = $conn_DB->Read_Text();
$conn_DB->conn_PDO();
$rslt = array();
$series = array();
$data = isset($_GET['data'])?$_GET['data']:'';
if(empty($data)){
    $code='';
} else {
    $code="WHERE db_id=".$data;
}
$sql = "SELECT db_id,brand_name FROM drug_brand ".$code;

    $conn_DB->imp_sql($sql);
    $num_risk = $conn_DB->select();
    for($i=0;$i<count($num_risk);$i++){
        $series['id'] = $num_risk[$i]['db_id'];
        $series['name'] = $num_risk[$i]['brand_name'];
    array_push($rslt, $series);    
    }
    print json_encode($rslt);
$conn_DB->close_PDO();
?>