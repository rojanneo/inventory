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

	public function getCollection()
	{
		$sql = "SELECT * FROM purchase_orders WHERE 1";
		$po = $this->connection->Query($sql);
		if($po)
			return $po;
		else return false;
	}

	public function load($po_id)
	{
		$sql = "SELECT po.purchase_order_id, po.purchase_order_date, po.employee_id, purchaseorder_product.product_id,purchaseorder_product.product_name,purchaseorder_product.product_sku, purchaseorder_product.unit_price, purchaseorder_product.quantity, purchaseorder_product.total_price FROM `purchase_orders` AS po JOIN `purchaseorder_product` ON `po`.purchase_order_id = `purchaseorder_product`.purchase_order_id WHERE po.purchase_order_id = ".$po_id;
		$po = $this->connection->Query($sql);
		if($po) return $po;
		else return false;
	}
}