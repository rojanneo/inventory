<?php
class StockModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getstockdatafortoday()
	{
		$date=date("Y-m-d");
		$sql='SELECT `product_id`,`Quantity` FROM `stocks` WHERE `date`="'.$date.'"'; 
		$value=$this->connection->Query($sql); 
		if(empty($value))
		{
			return false;
		}
		else return $value;
		
	}

	public function addpost($postdata)
	{		
		$date=$postdata['date'];
		unset($postdata['date']); 
		//$postdata=array_filter($arr, function($var){return !is_null($var);} );
		$postdata=array_filter($postdata,'strlen'); 
		foreach ($postdata as $key => $value) {
			$sqlcheck="SELECT * FROM `stocks` WHERE `product_id`=$key AND `date`='$date'";
			$sqlcheckvalue=$this->connection->Query($sqlcheck);  
			if(empty($sqlcheckvalue))
			{				
				$sql='INSERT INTO `stocks`(`product_id`, `Quantity`, `date`) VALUES ('.$key.','.$value.',"'.$date.'")'; 
				$query=$this->connection->InsertQuery($sql);	
			}
			else
			{				
			$sql='UPDATE `stocks` SET `Quantity`='.$value.' WHERE `product_id`='.$key.' AND `date`="'.$date.'"'; 
			$query=$this->connection->UpdateQuery($sql);
			}
			if(!$query){ return false;}			
		}		
		return true;
		
	}

	public function searchforstock($product_id,$columnname,$resultcolumnvalue,$date)
	{

		$sql="SELECT `$resultcolumnvalue` FROM `stocks` WHERE `$columnname`=$product_id AND `date`='$date'"; 
		$result=$this->connection->Query($sql);
		if(empty($result)){ return false;} else return $result[0];
	}




}