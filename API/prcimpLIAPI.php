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
include '../function/string_to_ascii.php';
set_time_limit(0);
$connDB = new EnDeCode();
$read = "../connection/conn_DB.txt";
$connDB->para_read($read);
$connDB->Read_Text();
$connDB->conn_PDO();

function insert_date($take_date_conv) {
    $take_date = explode("-", $take_date_conv);
    $take_date_year = @$take_date[2] - 543;
    $take_date = "$take_date_year-" . @$take_date[1] . "-" . @$take_date[0] . "";
    return $take_date;
}

$method = isset($_POST['method']) ? $_POST['method'] : $_GET['method'];
if ($method == 'add_lotitem') {
        //$i=0;
        $lot_price=0;
        $count = $_POST['count'];
        $lot_amount = count($count);
     foreach ($count as $key => $value) {
        $lot_id[$value] = $_POST['lot_id'][$value];
        $db_id[$value] = $_POST['db_id'][$value];
        $item_price[$value] = $_POST['item_price'][$value];
        $item_amount[$value] = $_POST['item_amount'][$value];
        $sell_price[$value] = $_POST['sell_price'][$value];
        $barcode[$value] = $_POST['barcode'][$value];
        $expire_date[$value] = insert_date($_POST['expire_date'][$value]);

        $sql = "select receive,sell from drug_brand where db_id= :db_id";
        $connDB->imp_sql($sql);
        $execute=array(':db_id' => $db_id[$value]);
        $receive=$connDB->select_a($execute);
        $total_receive = (int) $item_amount[$value] + (int) $receive['receive'];
        $total_now = $total_receive - (int) $receive['sell'];

        $data = array($lot_id[$value],$db_id[$value],$item_price[$value],$item_amount[$value],$sell_price[$value],$barcode[$value],$expire_date[$value],$total_now);
        $field = array("lot_id","db_id","item_price","item_amount","sell_price","barcode","expire_date","total_now");
        $table = "lot_item";
        $add_lot_item = $connDB->insert($table, $data, $field);

        if($add_lot_item){
            $data2 = array($total_receive);
            $field = array("receive");
            $table2 = "drug_brand";
            $where="db_id=:db_id";
            $execute2=array(':db_id' => $db_id[$value]);
            $edit_drug_brand=$connDB->update($table2, $data2, $where, $field, $execute2); 

            $lot_price += $item_price[$value]*$item_amount[$value];
        }
        else if(!$add_lot_item){
            $res = array("messege"=>'เพิ่มรายการไม่สำเร็จ!!!! '.$add_lot_item->errorInfo());
	        print json_encode($res);
        }
        //$i++;
        $L_id = $lot_id[$value];
    }
        $sql = "SELECT lot_price,lot_amount FROM lot WHERE lot_id= :lot_id";
        $connDB->imp_sql($sql);
        $execute=array(':lot_id' => $L_id);
        $chk_lot=$connDB->select_a($execute);

        $lot_price = $chk_lot['lot_price']+$lot_price;
        $lot_amount = $chk_lot['lot_amount']+$lot_amount;

        $data3 = array($lot_price,$lot_amount);
        $field3 = array("lot_price","lot_amount");
        $table3 = "lot";
        $where3="lot_id=:lot_id";
        $execute3=array(':lot_id' => $L_id);
        $edit_lot=$connDB->update($table3, $data3, $where3, $field3, $execute3); 

        $res = array("messege"=>'เพิ่มรายการสำเร็จ!!!!');
        print json_encode($res);
        $connDB->close_PDO();
}elseif ($method == 'edit_lotitem') {
    $lot_price=0;
    $li_id = $_POST['li_id'];
    $lot_id = $_POST['lot_id'];
    $db_id = $_POST['db_id'];
    $item_price = $_POST['item_price'];
    $item_amount = $_POST['item_amount'];
    $sell_price = $_POST['sell_price'];
    $barcode = $_POST['barcode'];
    $expire_date = insert_date($_POST['expire_date']);

    $sql = "select receive,sell from drug_brand where db_id= :db_id";
    $connDB->imp_sql($sql);
    $execute=array(':db_id' => $db_id);
    $receive=$connDB->select_a($execute);
    $total_receive = (int) $item_amount + (int) $receive['receive'];
    $total_now = $total_receive - (int) $receive['sell'];

    $data = array($db_id,$item_price,$item_amount,$sell_price,$barcode,$expire_date,$total_now);
    $field = array("db_id","item_price","item_amount","sell_price","barcode","expire_date","total_now");
    $table = "lot_item";
    $where="li_id=:li_id";
    $execute=array(':li_id' => $li_id);
    $edit_lot_item = $connDB->update($table, $data, $where, $field, $execute);

    if($edit_lot_item){
        $data2 = array($total_receive);
        $field = array("receive");
        $table2 = "drug_brand";
        $where="db_id=:db_id";
        $execute2=array(':db_id' => $db_id);
        $edit_drug_brand=$connDB->update($table2, $data2, $where, $field, $execute2); 

        $lot_price += $item_price*$item_amount;
    }
    else if(!$add_lot_item){
        $res = array("messege"=>'เพิ่มรายการไม่สำเร็จ!!!! '.$edit_lot_item->errorInfo());
        print json_encode($res);
    }
    $sql = "SELECT lot_price,lot_amount FROM lot WHERE lot_id= :lot_id";
    $connDB->imp_sql($sql);
    $execute=array(':lot_id' => $lot_id);
    $chk_lot=$connDB->select_a($execute);

    $lot_price = $chk_lot['lot_price']+$lot_price;
    $lot_amount = $chk_lot['lot_amount'];

    $data3 = array($lot_price,$lot_amount);
    $field3 = array("lot_price","lot_amount");
    $table3 = "lot";
    $where3="lot_id=:lot_id";
    $execute3=array(':lot_id' => $lot_id);
    $edit_lot=$connDB->update($table3, $data3, $where3, $field3, $execute3); 

    $res = array("messege"=>'เพิ่มรายการสำเร็จ!!!!');
    print json_encode($res);
    $connDB->close_PDO();
}