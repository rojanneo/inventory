<?php

class PurchaseorderModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function insertGroup($date, $status, $total, $is_realtime, $employee_id)
	{
		$sql = "SELECT `auto_increment` FROM INFORMATION_SCHEMA.TABLES WHERE table_name = 'purchaseorder_groups'";
		$id = $this->connection->Query($sql);
		if($id)
		{
			$id = $id[0]['auto_increment'];
			$sku = 'PO-'.$id;
			$now = date('Y-m-d');
			$sql = "INSERT INTO `purchaseorder_groups`(`sku`, `po_entered_date`, `po_status`, `total_price`, `is_realtime`, `employee_id`, `updated_date`) 
			VALUES ('".$sku."','".mysql_escape_string($date)."','".mysql_escape_string($status)."','".mysql_escape_string($total)."','".mysql_escape_string($is_realtime)."','".mysql_escape_string($employee_id)."','".mysql_escape_string($now)."')";

			$this->connection->InsertQuery($sql);
			return $this->connection->GetInsertID();
		}
		else return false;
	}

	public function getPOGroupCollection($status = "")
	{
		$sql = "SELECT * FROM purchaseorder_groups WHERE po_status LIKE '%".$status."%' ORDER BY id DESC";
		$pos = $this->connection->Query($sql);
		if($pos) return $pos;
		else return false;
	}

	public function reopen($id, $employee_id)
	{
		$sql = "UPDATE purchaseorder_groups SET is_complete = '0', po_status = 1, po_completed_date = 'NULL', po_cancel_date = 'NULL', employee_id = $employee_id WHERE id = ".$id;
		$this->connection->UpdateQuery($sql);

		$sql = "UPDATE purchaseorder SET is_complete = '0', completed_date = 'NULL', employee_id = $employee_id WHERE po_group_id = ".$id;
		$this->connection->UpdateQuery($sql);
	}

	public function insert($data)
	{
		if($data)
		{
			extract($data);
			$now = date('Y-m-d');
			$sql = "INSERT INTO `purchaseorder`(`po_group_id`, `supplier_id`, `product_id`, `unit_price`, `quantity`, `quantity_recieved`, `unit`, `total_price`, `is_complete`, `completed_date`, `update_date`,`employee_id`) 
			VALUES ('".$po_group_id."','".mysql_escape_string($supplier_id)."','".mysql_escape_string($product_id)."','".mysql_escape_string($unit_price)."','".mysql_escape_string($quantity)."','0','".mysql_escape_string($unit)."','".mysql_escape_string($total_price)."','".mysql_escape_string($is_complete)."','NULL','".mysql_escape_string($now)."','".mysql_escape_string($employee_id)."')";

			$this->connection->InsertQuery($sql);

			$ret_id = $this->connection->GetInsertID();
                        
                        $sql = "INSERT INTO employee_po_history(employee_id, po_id, date, action) VALUES($employee_id, $ret_id,'$entered_date','insert')";
                        $this->connection->InsertQuery($sql);
                        
                        return $ret_id;
		}
		else return false;
	}

	public function loadPurchaseOrderGroup($id)
	{
		$sql = "SELECT * FROM purchaseorder_groups WHERE id = ".$id." LIMIT 1";
		$po_group = $this->connection->Query($sql);
		if($po_group)
			return $po_group[0];
		else return false;
	}

	public function loadPurchaseOrderByGroup($group_id)
	{
		$sql = "SELECT * FROM purchaseorder WHERE po_group_id = ".$group_id;
		$orders = $this->connection->Query($sql);
		if($orders) return $orders;
		else return false;
	}

	public function save($data = false)
	{
		if($data)
		{
			extract($data);
			foreach($quantity_recieved as $po_id => $q)
			{
				$sql = "UPDATE purchaseorder SET unit_price = '".  mysql_escape_string($unit_price[$po_id])."', quantity_recieved = '".mysql_escape_string($q)."',employee_id = '".mysql_escape_string($employee_id)."',is_save = '1',update_date = '".mysql_escape_string($po_date)."',total_price_to_pay = '".mysql_escape_string($total_price_to_pay[$po_id])."' WHERE id = ".$po_id;
				$this->connection->UpdateQuery($sql);
				
			}
			


			$sql = "UPDATE purchaseorder_groups SET updated_date = '".mysql_escape_string($po_date)."' WHERE id = ".$po_group_id;
			$this->connection->UpdateQuery($sql);
		}
		else return false;
	}

	public function complete($data = false)
	{
		if($data)
		{
			extract($data);
			$po = $this->loadPurchaseOrderByGroup($po_group_id);
			$now = date('Y-m-d');
			$total_price = array_sum($total_price_to_pay);
			$sql = "UPDATE purchaseorder_groups SET is_complete = '1', po_completed_date = '".$po_date."', po_status = '2', total_price = '".$total_price."' WHERE id = ".$po_group_id;
			$this->connection->UpdateQuery($sql);


			if($po)
			{
							foreach($po as $p)
			{
				$sql = "UPDATE purchaseorder SET is_save = '0', is_complete = '1', completed_date = '".$po_date."' ,employee_id = '".mysql_escape_string($employee_id)."' WHERE id = ".$p['id'];
				$this->connection->UpdateQuery($sql);				
			}

			}

		}
		else
			return false;
	}

	public function cancel($id, $employee_id)
	{
		$now = date('Y-m-d');
		$sql = "UPDATE purchaseorder_groups SET is_complete = '0', po_status = 3, po_cancel_date = '".$now."',employee_id = '".mysql_escape_string($employee_id)."' WHERE id = ".$id;
		$this->connection->UpdateQuery($sql);
                $sql = "UPDATE purchaseorder SET employee_id = $employee_id WHERE  po_group_id = $id";
                $this->connection->Query($sql);
	}
        
        public function isEdited($po_id)
        {
            $sql = "SELECT * FROM `employee_po_history` WHERE po_group_id = $po_id AND (action = 'qty' OR action = 'total_price') ORDER BY `id` ASC";
            $history = $this->connection->Query($sql);
            if($history) return true;
            else return false;
        }
}