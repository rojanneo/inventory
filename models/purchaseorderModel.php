<?php
class PurchaseorderModel extends Model
{
	public $tableName = 'purchase_orders';
	public function __construct()
	{
		parent::__construct();
	}

	public function insert($data = false)
	{
			$date = date('Y-m-d');
			$sql = "INSERT INTO `".$this->tableName."`(
				`employee_id`, 
				`purchase_order_date`, 
				`purchase_order_recieve_date`, 
				`purchase_order_assigned_date`) 
				VALUES (
				'0',
				'".$date."',
				'".$date."',
				'".$date."')";
			$this->connection->InsertQuery($sql);
			return $this->connection->GetInsertID();
	}
}