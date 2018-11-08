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
$sql="select user_id,CONCAT(user_fname,' ',user_lname) AS fullname
,CASE status_user
WHEN 'N' THEN 'ผู้ใช้งานทั่วไป'
WHEN 'Y' THEN 'ผู้ดูลระบบ'
ELSE 'ไม่ทราบ' END as status, user_name
from user"; 
$conn_DB->imp_sql($sql);
    $num_risk = $conn_DB->select();
    for($i=0;$i<count($num_risk);$i++){
        $series['ID'] = $num_risk[$i]['user_id'];
        $series['fullname'] = $num_risk[$i]['fullname'];
        $series['status']= $num_risk[$i]['status'];
        $series['user_name']= $num_risk[$i]['user_name'];
    array_push($rslt, $series);    
    }
print json_encode($rslt);
$conn_DB->close_PDO();