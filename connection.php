<?php

class Database{

    private $db_host="localhost";
    private $db_user="root";
    private $db_pass="";
    private $db_name="oops_crud";

    private $mysqli="";
    private $con = false;
    private $result = array();

    public function __construct()
    {
        if(!$this->con)
        {
            $this->mysqli= new mysqli($this->db_host,$this->db_user,$this->db_pass,$this->db_name);
            $this->con=true;
            if($this->mysqli->connect_error){
                array_push($this->result,$this->mysqli->connect_error );
                echo "error in connection";
                return false;
            }
            else{
                echo "connected ";
            }
        }
        else{
            return true;
        }
        
    }
    public function insert($table,$params=array()){
        // echo $table;
        // print_r($params);
        if($this->tableExist($table))
        {
            print_r($params);
            $table_columns=implode(', ',array_keys($params)) ;
            $table_values= implode("', '" ,$params );
            // echo $table_columns;
            // echo $table_values;
            $sql = "INSERT INTO $table ($table_columns) VALUES ('$table_values')";
            if($this->mysqli->query($sql))
            {
                array_push($this->result,$this->mysqli->insert_id);
                return true;
            }
            else{
                array_push($this->result, $this->mysqli->error);
                return false;
            }
        }
        else{
            return false;
        }
      

    }

    public function update($table,$params=array(),$where = null){
        if($this->tableExist($table)){
            // print_r($params);
            $args = array();
            foreach($params as $key => $value)
            {
                $args[]="$key = '$value'" ;

            }
            // print_r($args);
            
            $sql= "UPDATE $table SET ". implode(',' , $args);
            if($where != null)
            {
                $sql .= "where $where";
            }
            if($this->mysqli->query($sql)){
                array_push($this->result, $this->mysqli->affected_rows);
                return true;
            }
            else{
                array_push($this->result, $this->mysqli->error);
            }
        }
        else{
            return false;
        }
        
    }
    public function delete($table,$where = null){
        if($this->tableExist($table)){
            $sql ="DELETE FROM $table";
            if($where !=null)
            {
                 $sql.=" WHERE $where";
            }
            echo $sql;
            if($this->mysqli->query($sql))
            {
                array_push($this->result,$this->mysqli->affected_rows);
                return true;
            }
            else{
                array_push($this->result,$this->mysqli->error);
                return false;
            }
        }
        else{
            return false;
        }
    }

    public function select($table, $row="*",$join = null, $where = null, $order=null, $limit=null){
        if($this->tableExist($table))
        {
            $sql= "SELECT $row from $table";
            if($join != null)
            {
                $sql .= " JOIN $join ";
            }
            if($where != null)
            {
                $sql .= " WHERE $where ";
            }
            if($order != null)
            {
                $sql .= " ORDER BY $order ";
            }
            if($limit !=null)
            {
                $sql .= " LIMIT 0,$limit ";
            }
            // echo $sql;
            $query= $this->mysqli->query($sql);
            if($query){
                $this->result = $query->fetch_all(MYSQLI_ASSOC);
                return true;
            }
            else{
                array_push($this->result, $this->mysqli->error);
                return false;
            }
        }
        else{
            return false;
        }
    }

    public function sql($sql){
        $query= $this->mysqli->query($sql);
        if($query){
            $this->result = $query->fetch_all(MYSQLI_ASSOC);
            return true;
        }
        else{
            array_push($this->result,$this->mysqli->error);
            return false;
        }
    }

    private function tableExist($table){
        $sql="SHOW TABLES FROM $this->db_name LIKE '$table' ";
        $tableinDB= $this->mysqli->query($sql);
        if($tableinDB){
            if($tableinDB->num_rows == 1)
            {
                return true;
            }
            else
            {
                array_push($this->result, "table does not exist"  );
                return false;
            }
        }
    }
    public function getresult(){
        $var=$this->result;
        $this->result= array();
        return $var;
    }
    public function __destruct()
    {
        if($this->con){
            if($this->mysqli->close())
            {
                $this->con= false;
                return true;
            }
            
        }
        else{
            return false;
        }
    }
}


?>
