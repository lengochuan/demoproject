<?php
class order_list extends model {

	protected $class_name = 'order_list';

	protected $id;
	protected $created_at;

	public function add(){
		global $db;

        $arr['id'] 			    = $this->get('id');
        $arr['created_at'] 		= time();
        $db->record_insert( $this->class_name, $arr );
        unset( $arr );

		return true;
	}
    
}
$order_list = new order_list();









