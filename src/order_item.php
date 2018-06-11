<?php
class order_item extends model {

	protected $class_name = 'order_item';

	protected $id;
	protected $item_name;
	protected $quantity;
	protected $price;
	protected $order_list_id;

	public function add(){
		global $db;
        
        $arr['id'] 			    = $this->get('id');
        $arr['item_name'] 		= $this->get('item_name');
        $arr['quantity'] 		= $this->get('quantity');
        $arr['price'] 		    = $this->get('price');
        $arr['order_list_id'] 	= $this->get('order_list_id');
        $db->record_insert( $this->class_name, $arr );
        unset( $arr );

		return true;
	}
    
}
$order_item = new order_item();
