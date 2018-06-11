<?php
class model{

	protected $class_name = 'model';
	protected $paramater;

	// set value with paramater
	public function set( $parameter, $val) {
		$this->$parameter = $val;
		return true;
	}
	// get value with paramater
	public function get( $parameter ) {
		return $this->$parameter;
	}

	// get value with paramater
	public function get_detail() {
		global $db;
		
		$sql = "SELECT * FROM `". $this->class_name ."` WHERE `id` = '".$this->get('id')."'";
		$result = $db->executeQuery( $sql, 1);

		return $result;
	}

	public function delete() {
		global $db;
		
		$result = $db->record_delete( $this->class_name, " `id` = '".$this->get('id')."'");

		return $result;
	}

	public function list_all( $ofset = 0, $limit = '' ) {
		global $db;

		if( $limit != '' ) $limit = " LIMIT $ofset, $limit ";
		
		$sql = "SELECT * FROM `". $this->class_name ."` $limit ";

		$result = $db->executeQuery_list_id( $sql );

		return $result;
	}

	public function list_all_count() {
		global $db;

		$sql = "SELECT COUNT(*) total FROM `". $this->class_name ."` ";

		$result = $db->executeQuery( $sql, 1 );

		return $result['total']+0;
	}

	public function get_detail_by_field( $field ){
		global $db;
		
		$val = $this->get( $field );
		
		$sql = "SELECT  *
				FROM `". $this->class_name ."`
				WHERE `$field` = '$val'
				LIMIT 1";
		
		$kq = $db->executeQuery( $sql, 1 );

		return $kq;
	}
	
	public function update_field( $field_name ){
		global $db;
		
		$id = $this->get('id');

		$arr[$field_name] = $this->get( $field_name );
		
		$db->record_update( $db->tbl_fix.'`'.$this->class_name.'`', $arr, " `id` = '$id' " );
		
		return true;
	}

	public function echo_($str) {
		echo $str;
		return false;
	}

	public function exit_($str) {
		global $db;
		$db->close();
		exit( $str );
	}

	public function print_r_($str) {
		print_r( $str );
		return false;
	}

}