<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Seguimiento_piezas {
	var $EE;
	var $version	= 0.1;
	
	function __construct()
	{
		$this->EE =& get_instance();
		
		//Cargamos los recursos
		$this->EE->load->model("seguimiento");
		$this->EE->load->model("pieza");
	}
	
	public function listar() {
		
		$seg_id = $this->EE->TMPL->fetch_param('seg_id');

		//Si no hay un usuario logueado no hay que hacer nada
		if ( ($usuario = $this->EE->session->userdata('member_id')) === FALSE || ($usuario = $this->EE->session->userdata('member_id')) === 0 ) return;
		
		//Buscamos el grupo al cual pertenece el usuario. En caso de ser un SUPER ADMIN, mostramos todos
		//los seguimientos que esten activos.
		//TODO: Ver si hace falta agregar algun otro grupo que puedan ver todos los seguimientos
		$this->EE->load->model('usuarios');
		$grupo = $this->EE->usuarios->get_grupos_usuario( $usuario );

		//Vemos si el usuario pertenece al grupo de super admins
		if( in_array(1, $grupo) ) {
			//Si entramos aca no filtramos los seguimientos por usuario (Se muetran todos)
			$filtrado = false;
		} else {
			$filtrado = true;
		} 
		
		//Si se especifico un seg_id, buscamos ese solo
		if( $seg_id ) {
			
			$seguimientosEE = $this->EE->seguimiento->get_seguimientos_by_id_by_usuario( $seg_id, $usuario, $filtrado );
			
		} 
		//Sino buscamos todos los seguimientos
		else {
				
			$seguimientosEE = $this->EE->seguimiento->get_seguimientos_by_usuario( $usuario, $filtrado );	
			
		}
		
		if ( is_null($seguimientosEE) ) {
			return $this->EE->TMPL->no_results();
		}

		foreach ($seguimientosEE->result_array() as $id => $row)
		{
			$variables[] = array(
				'seguimiento_id' 	=> $row['seguimiento_id'],
				'codigo' 			=> $row['seguimiento_codigo'],
				'estado' 			=> $row['estado']
			);					
			
		}

		$output = $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $variables); 
		
		return $output;
		
	}
}