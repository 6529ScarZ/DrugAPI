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
$sql = "SELECT li.li_id,db.brand_name,li.item_price,li.item_amount,li.sell_price,li.barcode,li.expire_date
FROM lot_item li
INNER JOIN drug_brand db on db.db_id=li.db_id ".$code;

$conn_DB->imp_sql($sql);
$num_risk = $conn_DB->select();
for($i=0;$i<count($num_risk);$i++){
    $series['ID'] = $num_risk[$i]['li_id'];
    $series['name'] = $num_risk[$i]['brand_name'];
    $series['item_price'] = $num_risk[$i]['item_price'];
    $series['item_amount'] = $num_risk[$i]['item_amount'];
    $series['sell_price'] = $num_risk[$i]['sell_price'];
    $series['barcode'] = $num_risk[$i]['barcode'];
    $series['expire_date'] = DateThai1($num_risk[$i]['expire_date']);
array_push($rslt, $series);    
}
    print json_encode($rslt);
$conn_DB->close_PDO();
?>