<?php
class PurchaseorderproductModel extends Model
{
	public $tableName = 'purchaseorder_product';
	public function __construct()
	{
		parent::__construct();
	}

	public function insert($data = false)
	{
		if($data != false)
		{
			extract($data);
			$sql = "INSERT INTO `".$this->tableName."`(
				`purchase_order_id`, 
				`product_id`, 
				`unit_price`, 
				`quantity`, 
				`total_price`) 
				VALUES (
				'".mysql_escape_string($purchase_order_id)."',
				'".mysql_escape_string($product_id)."',
				'".mysql_escape_string($unit_price)."',
				'".mysql_escape_string($quantity)."',
				'".mysql_escape_string($total_price)."')";
			$this->connection->InsertQuery($sql);
			return $this->connection->GetInsertID();
		}	
		else return false;
	}
}