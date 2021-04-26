<?php
    class Orders{

        // Connection
        private $conn;

        // Table
        private $db_table = "orders";

        // Columns
        public $ID;
public $ORD_NUM;
public $ORD_AMOUNT;
public $ADVANCE_AMOUNT;
public $ORD_DATE;
public $CUST_CODE;
public $AGENT_CODE;
public $ORD_DESCRIPTION;

        // Db connection
        public function __construct($db){
            $this->conn = $db;
        }

        
        // GET ALL
        public function getOrders(){
            $sqlQuery = "SELECT * FROM " . $this->db_table . "";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt;
        }
        public function getOrderColumns(){
            $sqlQuery = "DESCRIBE " . $this->db_table . "";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt;
        }
        

    }
?>

