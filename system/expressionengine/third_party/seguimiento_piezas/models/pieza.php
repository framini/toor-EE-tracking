<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
*
*/
class Pieza extends CI_Model {
	var $EE;

	//Config
	private $_table_name = "piezas";

	function __construct() {
		$this->EE =& get_instance();
	}
	
	//Metodo utilizado para insertar piezas en el sistema
	public function insertar_pieza( $data ) {
		
		$this->EE->db->insert( $this->_table_name , $data);
		
		if($this->EE->db->affected_rows() > 0) {
			
			return true;
			
		} else {
			
			return false;
			
		}
	}
	
	public function get_piezas() {
		
		$this->EE->db->select();
		$this->EE->db->from( $this->_table_name );
		
		$query = $this->EE->db->get();

		if ($query->num_rows() > 0)
			return $query;
		return NULL;
	}
	
	public function eliminar_pieza( $data ) {
		
		$this->EE->db->where_in('pieza_id', $data);
		$this->EE->db->delete( $this->_table_name );
		
		if ($this->EE->db->affected_rows() > 0)
			return TRUE;
		return FALSE;
	}
	
	public function editar_piezas( $ids, $datos ) {
		
		$this->EE->db->trans_start();

    	foreach ($ids as $key => $value) {
			$this->EE->db->where( 'pieza_id', $value );
			$this->EE->db->update( $this->_table_name, $datos[ $value ] );
		}
		
		$this->EE->db->trans_complete();
    	return $this->EE->db->trans_status();
	}
	
	public function get_piezas_by_id( $ids ) {
		$this->EE->db->select();
		$this->EE->db->from( $this->_table_name );
		$this->EE->db->where_in('pieza_id', $ids);
		$this->EE->db->order_by("pieza_id", "desc"); 

		$query = $this->EE->db->get();
		
		if ($query->num_rows() > 0)
			return $query;
		return NULL;
	}	
}