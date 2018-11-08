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
$sql="SELECT db.db_id,db.brand_name
,(SELECT m.med_name FROM medicinal m WHERE m.med_id=db.mash_pri)pri
,(SELECT m.med_name FROM medicinal m WHERE m.med_id=db.mash_sec)sec
,(SELECT m.med_name FROM medicinal m WHERE m.med_id=db.mash_th)th
,dk.dk_name,db.max,db.min,(db.receive-db.sell)balance
FROM drug_brand db
INNER JOIN medicinal m on (m.med_id=db.mash_pri or m.med_id=db.mash_sec or m.med_id=db.mash_th)
INNER JOIN drug_kind dk on dk.dk_id=db.drug_kind
GROUP BY db.db_id"; 
$conn_DB->imp_sql($sql);
    $num_risk = $conn_DB->select();
    for($i=0;$i<count($num_risk);$i++){
        $series['ID'] = $num_risk[$i]['db_id'];
        $series['name'] = $num_risk[$i]['brand_name'];
        $series['pri'] = $num_risk[$i]['pri'];
        $series['sec'] = $num_risk[$i]['sec'];
        $series['th'] = $num_risk[$i]['th'];
        $series['dk_name'] = $num_risk[$i]['dk_name'];
        $series['max'] = $num_risk[$i]['max'];
        $series['min'] = $num_risk[$i]['min'];
        $series['balance'] = $num_risk[$i]['balance'];
    array_push($rslt, $series);    
    }
print json_encode($rslt);
$conn_DB->close_PDO();