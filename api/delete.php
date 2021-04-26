<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    include_once '../config/database.php';
    include_once '../class/orders.php';
    
    $database = new Database();
    $db = $database->getConnection();
    $operation='Delete';
        $orders=new Orders($db);
    $json = file_get_contents('php://input');
    $data = json_decode($json);
    
    foreach( $data as $x){
    foreach( $x as $y => $val) {

       $sqlQuery = "delete from orders where id=".$val." ";

    $stmt = $db->prepare($sqlQuery);
    $stmt->execute();
    }
    
}
echo json_encode(" deleted.");
?>