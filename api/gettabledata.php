<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    
    include_once '../config/database.php';
    include_once '../class/orders.php';
    include_once '../class/tableMeta.php';

    $database = new Database();
    
    $sort= $_REQUEST["sort"];
    $search= $_REQUEST["search"];
    $start= $_REQUEST["start"];
    $length= $_REQUEST["length"];
    $whereclause='';
    $filterclause='';
    $excludedfilter=array('draw','length','sort','start','search'); 
    $badInput=false;
    foreach( array_keys($_POST) as $stuff ) {
        if(!in_array($stuff, $excludedfilter) &&  $_REQUEST[$stuff]!='' && !(preg_match("/^[\w. ]+$/",$stuff) == 1 && preg_match("/^[\w. ]+$/",$_REQUEST[$stuff]) == 1 )) {
           $badInput=true;
        }
                if(!in_array($stuff, $excludedfilter) && $_REQUEST[$stuff]!=''){
                $filterclause.= " and " .$stuff." like '%" . $_REQUEST[$stuff]. "%' ";
            }
    }
    if(!$badInput){
    if (strlen($search)  > 2)
    {
        $whereclause = " where (
ORD_NUM like '%".$search."%' or 
ORD_AMOUNT like '%".$search."%' or 
ADVANCE_AMOUNT like '%".$search."%' or 
CUST_CODE like '%".$search."%' or 
AGENT_CODE like '%".$search."%' or 
ORD_DESCRIPTION like '%".$search."%'
        ) and (1=1 ";
        $whereclause .=  $filterclause . " )";
    }
    else { $whereclause = "where 1=1 " . $filterclause; }



    $q = "select * from orders " . $whereclause . "  order by " . $sort . " limit " . $length . " offset " . $start . " ";
    $qCount = "select count(id) as count from orders ";
    $qFilterCount = "select count(id) as count from orders " . $whereclause ;
        
    $db = $database->getConnection();
    $stmtCount = $db->prepare($qCount);
    
    $stmtCount->execute();
    $resultCount = $stmtCount->fetchColumn();

    
    if($resultCount >= 0){
        
        $employeeArr = array();
        $employeeArr["draw"] = $_REQUEST["draw"];
        
        $employeeArr["recordsTotal"] = $resultCount;
        $stmtFilterCount = $db->prepare($qFilterCount);

        $stmtFilterCount->execute();
        $employeeArr["recordsFiltered"] =  $stmtFilterCount->fetchColumn();


        $employeeArr["data"] = array();
        $stmt = $db->prepare($q);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $e = array(
                "ID"=>$ID,
                "ORD_NUM"=>$ORD_NUM,
                "ORD_AMOUNT"=>$ORD_AMOUNT,
                "ADVANCE_AMOUNT"=>$ADVANCE_AMOUNT,
                "ORD_DATE"=>$ORD_DATE,
                "CUST_CODE"=>$CUST_CODE,
                "AGENT_CODE"=>$AGENT_CODE,
                "ORD_DESCRIPTION"=>$ORD_DESCRIPTION
            );

            array_push($employeeArr["data"], $e);
        }
        echo json_encode($employeeArr);
    }

    else{
        http_response_code(404);
        echo json_encode(
            array("message" => "No record found.")
        );
    }
    $db ->query('KILL CONNECTION_ID()');
        $db=null;
    }
?>