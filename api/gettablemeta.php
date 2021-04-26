<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    
    include_once '../config/database.php';
    include_once '../class/product.php';
    include_once '../class/tableMeta.php';

    $database = new Database();
    $db = $database->getConnection();

    $items = new Orders($db);

    $stmt = $items->getOrderColumns();
    $itemCount = $stmt->rowCount();
    $columns=array();

    if($itemCount > 0){
        
        $tableMeta = new tableMeta();
        $tableMeta->Deletable = true;
        $tableMeta->Insertable = true;
        $tableMeta->Name = "product";

        $table_names = $stmt->fetchAll(PDO::FETCH_COLUMN);
        foreach($table_names as $col) {
            $col1=   new colMeta();
            $col1->Name=$col;
            if (true) {
                $col1->Editable = true;
                $col1->Searchable = false;
                $col1->Class=$col;
            }
            array_push($columns, $col1);
        }
        $tableMeta->Column = $columns;
        echo json_encode($tableMeta);
    }

    else{
        http_response_code(404);
        echo json_encode(
            array("message" => "No record found.")
        );
    }
?>