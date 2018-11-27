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
include '../plugins/funcDateThai.php';
$conn_DB = new EnDeCode();
$read = "../connection/conn_DB.txt";
$conn_DB->para_read($read);
$conn_db = $conn_DB->Read_Text();
$conn_DB->conn_PDO();
$rslt = array();
$series = array();
$data1 = isset($_POST['data1'])?$_POST['data1']:$_GET['data1'];
//$data = isset($_GET['data'])?$_GET['data']:'';
if(empty($data1)){
    $code='';
} else {
    $code="WHERE li.lot_id=".$data1;
}
$sql = "SELECT b.bill_id,SUBSTR(b.bill_date,1,10)bill_date,SUBSTR(b.bill_date,11,6)bill_time,b.bill_amount
,b.bill_price,CONCAT(u.user_fname,' ',u.user_lname)seller
FROM bill_item bi
INNER JOIN bill b on b.bill_id=bi.bill_id
INNER JOIN user u on u.user_id=b.seller
GROUP BY bill_time ORDER BY bill_time DESC";

$conn_DB->imp_sql($sql);
$num_risk = $conn_DB->select();
for($i=0;$i<count($num_risk);$i++){
    $series['ID'] = $num_risk[$i]['bill_id'];
    $series['bill_date'] = DateThai1($num_risk[$i]['bill_date']);
    $series['bill_time'] = $num_risk[$i]['bill_time'].' น.';
    $series['bill_amount'] = $num_risk[$i]['bill_amount'];
    $series['bill_price'] = $num_risk[$i]['bill_price'];
    $series['seller'] = $num_risk[$i]['seller'];
array_push($rslt, $series);    
}
    print json_encode($rslt);
$conn_DB->close_PDO();
?>