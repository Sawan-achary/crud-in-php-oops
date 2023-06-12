<?php
include "connection.php";


$a= new Database();
// $a->insert('oops',['name'=>'ramanuj', 'age'=>'9', 'city'=>'tamilnadu']);
// $a->update('oops',['name'=>'sawan', 'age'=>'9', 'city'=>'tamilnadu'],'id="1"');
// $a->delete('oops','id="2" ');
// $a->select('oops','*',null,null,null,null);
// $a->sql("SELECT * FROM oops");
print_r($a->getresult());
?>