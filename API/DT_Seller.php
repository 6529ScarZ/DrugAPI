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
$conn_DB = new EnDeCode();
$read = "../connection/conn_DB.txt";
$conn_DB->para_read($read);
$conn_db = $conn_DB->Read_Text();
$conn_DB->conn_PDO();
set_time_limit(0);
$rslt = array();
$series = array();
$sql="select comp_id,comp_name,comp_address,comp_tell,comp_mobile
from seller"; 
$conn_DB->imp_sql($sql);
    $num_risk = $conn_DB->select();
    for($i=0;$i<count($num_risk);$i++){
        $series['ID'] = $num_risk[$i]['comp_id'];
        $series['name'] = $num_risk[$i]['comp_name'];
        $series['address']= $num_risk[$i]['comp_address'];
        $series['tell']= $num_risk[$i]['comp_tell'];
        $series['mobile']= $num_risk[$i]['comp_mobile'];
    array_push($rslt, $series);    
    }
print json_encode($rslt);
$conn_DB->close_PDO();