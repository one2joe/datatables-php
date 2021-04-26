<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    include_once '../config/database.php';
    include_once '../class/product.php';
    
    $database = new Database();
    $db = $database->getConnection();
$operation='Insert';

    $product=new Orders($db);
    $excludedfilter=array('draw','length','sort','start','search'); 
    $sqlQuery1 = "insert into product(";
    $sqlQuery2 = "values (";
    $badInput=false;
    foreach( array_keys($_POST) as $stuff ) {
        if( $_POST[$stuff]!=''  && !(preg_match("/^[\w. ]+$/",$_POST[$stuff]) == 1 && preg_match("/^[\w. ]+$/",$stuff) == 1 )) {
            $badInput=true;
         }
                if($stuff!='ID'){
                $sqlQuery1.=  $stuff.",";
                $sqlQuery2.= " :" . $stuff. ",";
                $product->$stuff=htmlspecialchars(strip_tags($_POST[$stuff]));}
    }
    $sqlQuery1=substr($sqlQuery1,0,strlen($sqlQuery1)-1).")";
    $sqlQuery2=substr($sqlQuery2,0,strlen($sqlQuery2)-1).")";
    if(!$badInput){
$stmt = $db->prepare($sqlQuery1.$sqlQuery2);
foreach( array_keys($_POST) as $stuff ) {
            if($stuff!='ID')$stmt->bindParam(":".$stuff, $product->$stuff);
        
}
if($stmt->execute()){
    echo json_encode('1');;
}
else
echo json_encode('0');;
    }
?>