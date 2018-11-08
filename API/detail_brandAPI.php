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
$sql="select * from drug_brand WHERE db_id= :db_id";
$conn_DB->imp_sql($sql);
$execute=array(':db_id' => $data);
$result=$conn_DB->select_a($execute);
//print_r($result);
print json_encode($result);
$conn_DB->close_PDO();
?>