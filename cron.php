<?php
function get_path($category_id) 
{
	mysql_connect('localhost','root','');
	mysql_select_db('inventory');
    // look up the parent of this node
    $result = mysql_query("SELECT * FROM category WHERE parent = 1");
    $path = array();
    while($row = mysql_fetch_assoc($result))
    {
    	if($row['parent'] != NULL)
    	{

    	}
    }
  
   return $path;
}

function display_children($category_id, $level) 
{
	mysql_connect('localhost','root','');
	mysql_select_db('inventory');
	$sql = "SELECT * FROM category WHERE parent=".$category_id;
    // retrieve all children
    $result = mysql_query($sql);
    // display each child
    while ($row = mysql_fetch_array($result)) 
    {
        // indent and display the title of this child
       // if you want to save the hierarchy, replace the following line with your code
        echo str_repeat('  ',$level) . $row['name'] . "<br/>";
        
       // call this function again to display this child's children
       //display_children($row['category_id'], $level+1);
    }
}

(display_children(1,0));
?>