<?php
session_save_path("../session/");
//session_start(); 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET,POST");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json;charset=utf-8');
$data = isset($_POST['data'])?$_POST['data']:'';
$rslt = array();
$series = array();
$series['data'] = $data;
array_push($rslt, $series);
print json_encode($series);

