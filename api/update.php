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
    $operation='Update';
    
    $badInput=false;
        
    $excludedfilter=array('draw','length','sort','start','search'); 
    

    $json = file_get_contents('php://input');
    $data = json_decode($json);
    
    foreach( $data as $x){
        $orders=new Orders($db);
        $sqlQuery = "UPDATE  orders SET ";
    foreach( $x as $y => $val) {
        if( $val!=''  && !(preg_match("/^[\w. ]+$/",$val) == 1 && preg_match("/^[\w. ]+$/",$y) == 1 )) {

            /* echo "\n" . $y;
            echo "\n" . $val;
            echo "\n" . var_export($val != '', 1);
            echo "\n" . var_export(!(preg_match("/^[\w. ]+$/",$val) == 1), 1);
            echo "\n" . var_export(preg_match("/^[\w. ]+$/",$y) == 1, 1);
            exit; */

            // $badInput=true;
         }
        if($y=='rowid'){
            $orders->ID=htmlspecialchars(strip_tags($val));
        }else 
        {
            $sqlQuery.=  $y." = :" . $y. ",";
            $orders->$y=htmlspecialchars(strip_tags($val));
        
        }
    }
    if(!$badInput){
    $sqlQuery=substr($sqlQuery,0,strlen($sqlQuery)-1);
    $sqlQuery.=  " where ID = :ID ";

    $stmt = $db->prepare($sqlQuery);
    foreach( $x as $y => $val) {
        if($y=='rowid')
                $stmt->bindParam(":ID", $orders->ID);
            else    $stmt->bindParam(":".$y, $orders->$y);
    }
    $stmt->execute();
}
echo json_encode('1');
}
?>