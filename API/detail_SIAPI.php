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
//include_once ('../template/plugins/funcDateThai.php');
set_time_limit(0);
$conn_DB= new EnDeCode();
$read="../connection/conn_DB.txt";
$conn_DB->para_read($read);
$conn_DB->Read_Text();
$conn_DB->conn_PDO();
//$rslt=array();
$result=array();
$data = isset($_GET['data'])?$_GET['data']:$_POST['data'];
$sql="SELECT bi.bi_id,bi.db_id,li.sell_price
FROM bill_item bi
INNER JOIN lot_item li on li.db_id=bi.db_id
WHERE bill_id = :bill_id
GROUP BY bi.bi_id";
$conn_DB->imp_sql($sql);
$execute=array(':bill_id' => $data);
$result=$conn_DB->select($execute);
//print_r($result);
if(!empty($result)){
    print json_encode($result);
}else{
    print json_encode("ไม่มีข้อมูล");
}

$conn_DB->close_PDO();
?>