<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
*
*/
class Seguimiento extends CI_Model {
	var $EE;

	//Config
	private $_table_name = "seguimiento_piezas";

	function __construct() {
		$this->EE =& get_instance();
	}
	
	//Metodo utilizado para insertar seguimientos en el sistema
	public function insertar_seguimiento( $data ) {
		
		$data['seguimiento_codigo'] = uniqid( $data['pieza_id'] );
		$data['date_added'] = $this->EE->localize->now;
		$data['estado'] = 'enviada';
		
		$this->EE->db->insert( $this->_table_name , $data);
		
		if($this->EE->db->affected_rows() > 0) {
			
			return true;
			
		} else {
			
			return false;
			
		}
	}
	
	public function get_seguimientos() {
		$this->EE->db->select();
		$this->EE->db->from( $this->_table_name );
		
		$query = $this->EE->db->get();

		if ($query->num_rows() > 0)
			return $query;
		return NULL;
	}
	
	public function get_seguimientos_by_usuario( $user, $filtrado = true ) {
		$this->EE->db->select();
		if( $filtrado ) {
			$this->EE->db->where('usuario_id', $user);
		}
		$this->EE->db->from( $this->_table_name );
		
		$query = $this->EE->db->get();

		if ($query->num_rows() > 0)
			return $query;
		return NULL;
	}
	
	public function editar_seguimientos( $ids, $datos ) {
		
		$this->EE->db->trans_start();

    	foreach ($ids as $key => $value) {
			$this->EE->db->where( 'seguimiento_id', $value );
			$this->EE->db->update( $this->_table_name, $datos[ $value ] );
		}
		
		$this->EE->db->trans_complete();
    	return $this->EE->db->trans_status();
	}
	
	public function get_seguimientos_by_id( $ids ) {
		$this->EE->db->select();
		$this->EE->db->from( $this->_table_name );
		$this->EE->db->where_in('seguimiento_id', $ids);
		$this->EE->db->order_by("seguimiento_id", "desc"); 

		$query = $this->EE->db->get();
		
		if ($query->num_rows() > 0)
			return $query;
		return NULL;
	}
	
	public function get_seguimientos_by_id_by_usuario( $ids, $user, $filtrado = true ) {
		$this->EE->db->select();
		$this->EE->db->from( $this->_table_name );
		if( $filtrado ) {
			$this->EE->db->where('usuario_id', $user);
		}
		$this->EE->db->where_in('seguimiento_id', $ids);
		$this->EE->db->order_by("seguimiento_id", "desc"); 

		$query = $this->EE->db->get();
		
		if ($query->num_rows() > 0)
			return $query;
		return NULL;
	}
	
	public function eliminar_seguimiento( $data ) {
		
		$this->EE->db->where_in('seguimiento_id', $data);
		$this->EE->db->delete( $this->_table_name );
		
		if ($this->EE->db->affected_rows() > 0)
			return TRUE;
		return FALSE;
	}
}