<?php
function getTasks($parent = 0){
    $tasks = array();
    mysql_connect('localhost','root','');
    mysql_select_db('inventory');
    $query = mysql_query("select * from purchase_categories where parent = $parent");
    $rows = array();
    while($row = mysql_fetch_assoc($query))
    {
    	$rows[] = $row;
    }
    if(count($rows)){
    	$tasks[$parent][] = $rows[0]['parent'];
    	//echo $rows[0]['parent'];
    } else {
        return $tasks;
    }
}

$tasks = getTasks(2);

var_dump($tasks);
