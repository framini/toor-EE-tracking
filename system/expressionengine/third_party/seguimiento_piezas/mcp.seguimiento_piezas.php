<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Seguimiento_piezas_mcp {
	var $EE;
	var $return_data;

	var $module_label = "Seguimiento Piezas";
	var $page_title = "Seguimiento Piezas";
	
	function __construct() {
		
		$this->EE =& get_instance();

		//Nos aseguramos que el modulo de seguimiento solo pueda ser visto por superadmins
		if($this->EE->session->userdata('group_id') == 2) {
			show_error( $this->EE->lang->line('acceso_no_autorizado') );
		}
		
		//CSS
		//$this->EE->cp->add_to_head("<link href='".$this->EE->config->item("theme_folder_url")."third_party/seguimiento_piezas/css/smoothness/jquery-ui-1.9.2.custom.min.css' rel='stylesheet'/>");
		$this->EE->cp->add_to_head("<link href='".$this->EE->config->item("theme_folder_url")."third_party/seguimiento_piezas/css/jquery.dataTables_themeroller.css' rel='stylesheet'/>");
		$this->EE->cp->add_to_head("<link href='".$this->EE->config->item("theme_folder_url")."third_party/seguimiento_piezas/css/dataTable.custom.css' rel='stylesheet'/>");
		$this->EE->cp->add_to_head("<link href='".$this->EE->config->item("theme_folder_url")."third_party/seguimiento_piezas/css/chosen.css' rel='stylesheet'/>");
		$this->EE->cp->add_to_head("<link href='".$this->EE->config->item("theme_folder_url")."third_party/seguimiento_piezas/css/general.css' rel='stylesheet'/>");
		
		//JS
		$this->EE->cp->add_to_head("<script src='".$this->EE->config->item("theme_folder_url")."third_party/seguimiento_piezas/js/lib/jquery-ui-1.9.2.custom.min.js'></script>");
		$this->EE->cp->add_to_head("<script src='".$this->EE->config->item("theme_folder_url")."third_party/seguimiento_piezas/js/lib/jquery.dataTables.js'></script>");
		$this->EE->cp->add_to_head("<script src='".$this->EE->config->item("theme_folder_url")."third_party/seguimiento_piezas/js/lib/chosen.jquery.js'></script>");
		$this->EE->cp->add_to_head("<script src='".$this->EE->config->item("theme_folder_url")."third_party/seguimiento_piezas/js/varios/mensajes.js'></script>");
		$this->EE->cp->add_to_head("<script src='".$this->EE->config->item("theme_folder_url")."third_party/seguimiento_piezas/js/lib/jquery.validate.js'></script>");
		$this->EE->cp->add_to_head("<script src='".$this->EE->config->item("theme_folder_url")."third_party/seguimiento_piezas/js/lib/jquery.form.js'></script>");

		//Cargamos los recursos
		$this->EE->load->model("seguimiento");
		$this->EE->load->model("pieza");
		$this->EE->load->library('table');
		$this->EE->load->helper('form');

	}
	
	public function index() {
		//Agregamos los botones
		$this->EE->cp->set_right_nav(array(
				'nuevo_seguimiento'		=> BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=seguimiento_piezas'.AMP.'method=crear_seguimiento',
				'gestion_piezas'        => BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=seguimiento_piezas'.AMP.'method=gestionar_piezas'
		));
		
		$this->EE->cp->set_variable('cp_page_title', $this->EE->lang->line('seguimiento_piezas_module_name'));
		
		//Obtenemos todos los seguimientos cargados en el sistema
		$seguimientosEE = $this->EE->seguimiento->get_seguimientos();
		//Parseamos los resultados devueltos
		foreach($seguimientosEE->result() as $seguimiento)
		{
			$seguimientos[$seguimiento->seguimiento_id] = array(
				"id"  		=> $seguimiento->seguimiento_id, 
				"codigo" 	=> $seguimiento->seguimiento_codigo,
				"estado" 	=> $seguimiento->estado,
				"detalle" 	=> $seguimiento->detalle,
				"usuario"   => $seguimiento->usuario_id
			);
		}	
		
		$data['seguimientos'] = $seguimientos;
		$data['urlDataSource'] = html_entity_decode ( base_url() . BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=seguimiento_piezas'.AMP.'method=getTableSeguimientos' );	
		
		if( $this->EE->session->flashdata('message') ) {
			$data['message'] = $this->EE->session->flashdata('message');
		}

		$data['options'] = array(
			'edit'  	=> lang('edit_selected'),
			'delete'    => lang('delete_selected')
		);

		$data['action_url'] = html_entity_decode ( base_url(). BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=seguimiento_piezas'.AMP.'method=editar_borrar_seguimientos');
	
		return $this->EE->load->view('index/index', $data, TRUE);
	}

	public function gestionar_piezas() {
		$this->EE->cp->set_breadcrumb(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=seguimiento_piezas', $this->EE->lang->line('seguimiento_piezas_module_name'));
		
		$this->EE->cp->set_right_nav(array(
				'nueva_pieza'        => BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=seguimiento_piezas'.AMP.'method=crear_pieza'
		));
		
		$this->EE->cp->set_variable('cp_page_title', $this->EE->lang->line('titulo_gestion_piezas'));
		
		$piezasEE = $this->EE->pieza->get_piezas();
		
		//Parseamos los resultados devueltos
		foreach($piezasEE->result() as $pieza)
		{
			$piezas[$pieza->pieza_id] = array(
				"id"  		=> $pieza->pieza_id, 
				"nombre" 	=> $pieza->nombre,
				"detalle" 	=> $pieza->detalle,
			);
		}
		
		$data['piezas'] = $piezas;
		
		$data['urlDataSource'] = html_entity_decode ( base_url() . BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=seguimiento_piezas'.AMP.'method=getTablePiezas' );	
		
		if( $this->EE->session->flashdata('message') ) {
			$data['message'] = $this->EE->session->flashdata('message');
		}

		$data['options'] = array(
			'edit'  	=> lang('edit_selected'),
			'delete'    => lang('delete_selected')
		);
		
		$data['action_url'] = html_entity_decode ( base_url(). BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=seguimiento_piezas'.AMP.'method=editar_borrar_piezas');
	
		return $this->EE->load->view('piezas/index', $data, TRUE);
		
		
	}

	public function editar_borrar_seguimientos() {
		$this->EE->load->library("form_validation");
		
		$this->EE->form_validation->set_rules("item_seleccionado", "Item", "required|xss_clean");
		
		if( $this->EE->form_validation->run() ) {
			//print_r($this->EE->form_validation->set_value('item_seleccionado')); die();
			
			if( $this->EE->input->get_post('action', true) === 'edit' ) {
				return $this->editar_seguimiento_confirmar();
				
			} elseif( $this->EE->input->get_post('action', true) === 'delete' ) {
				return $this->eliminar_seguimiento_confirmar();
			}
			
		}
	}

	public function editar_borrar_piezas() {
		$this->EE->load->library("form_validation");
		
		$this->EE->form_validation->set_rules("item_seleccionado", "Item", "required|xss_clean");
		
		if( $this->EE->form_validation->run() ) {
			//print_r($this->EE->form_validation->set_value('item_seleccionado')); die();
			
			if( $this->EE->input->get_post('action', true) === 'edit' ) {
				return $this->editar_pieza_confirmar();
				
			} elseif( $this->EE->input->get_post('action', true) === 'delete' ) {
				return $this->eliminar_pieza_confirmar();
			}
			
		}
	}
	
	public function editar_pieza() {
		
		//Si no existen piezas seleccionadas mostramos un mensaje de error
		if ( ! $this->EE->input->post('pieza_id'))
		{
			$this->EE->session->set_flashdata('message', lang('no_valid_selections'));
			$this->EE->functions->redirect(BASE.AMP.'C=addons_modules'.AMP
				.'M=show_module_cp'.AMP.'module=seguimiento_piezas'.AMP.'method=gestionar_piezas');
		}
		
		//Parametros a modificar
		$nombres = $this->EE->input->post('nombre');
		$detalles = $this->EE->input->post('detalle');
		
		$errores = array();
		
		foreach ($this->EE->input->post('pieza_id') as $key => $value) {
			//Por ahora el unico campo requerido es el nombre
			if( $nombres[ $value ] != '' ) {
				$datos[$value] = array(
					'nombre'	=> 		$nombres[ $value ],
					'detalle'	=> 		$detalles[ $value ]
				);
			} else {
				$errores[] = array( $value => lang('campo_requerido') );
			}
			
		}
		
		if( count( $errores ) == 0 ) {
			$estado = $this->EE->pieza->editar_piezas( $this->EE->input->post('pieza_id'), $datos );
			
			//Dependiendo el valor de estado mostramos el mensaje correspondiente
			$message = $estado ? $this->EE->lang->line('pieza_editada') : $this->EE->lang->line('pieza_no_editada');
			
			$this->EE->session->set_flashdata('message', $message);
			$this->EE->functions->redirect(BASE.AMP.'C=addons_modules'.AMP
				.'M=show_module_cp'.AMP.'module=seguimiento_piezas'.AMP.'method=gestionar_piezas');
		} else {
				$msg = '';

				foreach($errores as $val)
				{
					foreach ($val as $key => $value) {
						$msg .= '<div class="itemWrapper">'.$value.'</div>';
					}
						
				}

				return show_error($msg);
		}
		
	}


	public function editar_seguimiento() {
		
		//Si no existen seguimientos seleccionadas mostramos un mensaje de error
		if ( ! $this->EE->input->post('seguimiento_id'))
		{
			$this->EE->session->set_flashdata('message', lang('no_valid_selections'));
			$this->EE->functions->redirect(BASE.AMP.'C=addons_modules'.AMP
				.'M=show_module_cp'.AMP.'module=seguimiento_piezas');
		}
		
		//Parametros a modificar
		$estados = $this->EE->input->post('estado');
		$detalles = $this->EE->input->post('detalle');
		$usuarios = $this->EE->input->post('usuario');
		$piezas = $this->EE->input->post('pieza');
		$fechas_llegada = $this->EE->input->post('date_llegada');

		$errores = array();
		
		foreach ($this->EE->input->post('seguimiento_id') as $key => $value) {
			//Por ahora el unico campo requerido es el nombre
			if( $estados[ $value ] != '' ) {
				$datos[$value] = array(
					'estado'		=> 		$estados[ $value ],
					'detalle'		=> 		$detalles[ $value ],
					'usuario_id'	=> 		$usuarios[ $value ],
					'pieza_id'		=> 		$piezas[ $value ],
					'date_llegada'	=>		strtotime ($fechas_llegada[ $value ] )
				);
			} else {
				$errores[] = array( $value => lang('campo_requerido') );
			}
			
		}
		
		if( count( $errores ) == 0 ) {
			$estado = $this->EE->seguimiento->editar_seguimientos( $this->EE->input->post('seguimiento_id'), $datos );
			
			//Dependiendo el valor de estado mostramos el mensaje correspondiente
			$message = $estado ? $this->EE->lang->line('seguimiento_editada') : $this->EE->lang->line('seguimiento_no_editada');
			
			$this->EE->session->set_flashdata('message', $message);
			$this->EE->functions->redirect(BASE.AMP.'C=addons_modules'.AMP
				.'M=show_module_cp'.AMP.'module=seguimiento_piezas');
		} else {
				$msg = '';

				foreach($errores as $val)
				{
					foreach ($val as $key => $value) {
						$msg .= '<div class="itemWrapper">'.$value.'</div>';
					}
						
				}

				return show_error($msg);
		}
		
	}

	
	private function editar_seguimiento_confirmar() {
		$this->EE->load->helper('date');
		//Inicializamos el breadcrumb
		$this->EE->cp->set_breadcrumb(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=seguimiento_piezas', $this->EE->lang->line('seguimiento_piezas_module_name'));
		
		$this->EE->cp->set_variable('cp_page_title', $this->EE->lang->line('titulo_editar_seguimiento'));
		
		$items = $this->input->post('item_seleccionado');
		
		//Si no hay items seleccionados mostramos mensaje de error
		if (count($items) == 0)
		{
			show_error(lang('unauthorized_to_edit'));
		}

		$seguimientosEE = $this->EE->seguimiento->get_seguimientos_by_id( $items );
		
		foreach($seguimientosEE->result() as $seguimiento)
		{
			
			//$usuario = $this->EE->member_model->get_member_data( $seguimiento->usuario_id )->result_array();
			//$pieza = $this->EE->pieza->get_piezas_by_id( $seguimiento->pieza_id )->result_array();
			
			$seguimientos[] = array(
				'seguimiento_id' 		=> 		$seguimiento->seguimiento_id,
				'seguimiento_codigo'	=> 		$seguimiento->seguimiento_codigo,
				'estado' 				=> 		$seguimiento->estado,
				'usuario_id'			=>		$seguimiento->usuario_id,
				'pieza_id'				=>		$seguimiento->pieza_id,
				//'username'				=>		$usuario[0]['username'],
				//'pieza'					=>		$pieza[0]['nombre'],
				'detalle'				=>		$seguimiento->detalle,
				'date_added'			=>		unix_to_human($seguimiento->date_added, TRUE, 'eu'),
				//'date_llegada'			=>		unix_to_human($seguimiento->date_llegada, TRUE, 'eu')
				'date_llegada'			=>		date('Y-m-d', $seguimiento->date_llegada)
			);
		}
		
		$data['seguimientos'] = $seguimientos;
		
		//Obtenemos todos los usuarios del sistema
		//TODO: Ver si hace falta filtrar la query a solo ciertos usuarios
		$usuariosEE = $this->EE->member_model->get_members();
		
		//Parseamos los resultados devueltos
		foreach($usuariosEE->result() as $usuario)
		{
			$usuarios[$usuario->member_id] = $usuario->username;
		}
		
		$piezasEE = $this->EE->pieza->get_piezas();
		
		//Parseamos los resultados devueltos
		foreach($piezasEE->result() as $pieza)
		{
			$piezas[$pieza->pieza_id] = $pieza->nombre;
		}
		
		$data['usuarios'] = $usuarios;
		$data['piezas'] = $piezas;

		return $this->EE->load->view('seguimientos/editar_multiple', $data, TRUE);		
	}
	
	
	private function editar_pieza_confirmar() {
		
		//Inicializamos el breadcrumb
		$this->EE->cp->set_breadcrumb(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=seguimiento_piezas'.AMP.'method=gestionar_piezas', $this->EE->lang->line('titulo_gestion_piezas'));
		
		$this->EE->cp->set_variable('cp_page_title', $this->EE->lang->line('titulo_editar_piezas'));
		
		$items = $this->input->post('item_seleccionado');
		
		//Si no hay items seleccionados mostramos mensaje de error
		if (count($items) == 0)
		{
			show_error(lang('unauthorized_to_edit'));
		}

		$piezasEE = $this->EE->pieza->get_piezas_by_id( $items );
		
		foreach($piezasEE->result() as $pieza)
		{
			$piezas[] = array(
				'pieza_id' 		=> 		$pieza->pieza_id,
				'nombre' 		=> 		$pieza->nombre,
				'detalle' 		=> 		$pieza->detalle
			);
		}
		
		$data['piezas'] = $piezas;

		return $this->EE->load->view('piezas/editar_multiple', $data, TRUE);		
	}

	
	private function eliminar_seguimiento_confirmar() {
		if ( ! $this->EE->input->post('item_seleccionado'))
		{
			$this->EE->functions->redirect(BASE.AMP.'C=addons_modules'.AMP
				.'M=show_module_cp'.AMP.'module=seguimiento_piezas');
		}

		$this->EE->cp->set_breadcrumb(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=seguimiento_piezas'.AMP.'method=gestionar_piezas', $this->EE->lang->line('seguimiento_piezas_module_name'));
		
		$this->EE->cp->set_variable('cp_page_title', $this->EE->lang->line('titulo_eliminar_seguimiento'));
		
		$damned = array();

		foreach ($this->EE->input->post('item_seleccionado') as $key => $val)
		{		
			if ($val != '')
			{
				$damned[] = $val;
			}
		}

		// Guardamos los seguimientos a ser eliminados
		$data['damned'] = $damned;
		
		if (count($damned) == 1)
		{
			$data['message'] = lang('delete_seg_confirm');
		}
		else
		{
			$data['message'] = lang('delete_segs_confirm');
		}
		
		$data['action_url'] = html_entity_decode ( base_url(). BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=seguimiento_piezas'.AMP.'method=eliminar_seguimiento');

		return $this->EE->load->view('seguimientos/eliminar_confirm', $data, TRUE);
	}
	
	
	private function eliminar_pieza_confirmar() {
		if ( ! $this->EE->input->post('item_seleccionado'))
		{
			$this->EE->functions->redirect(BASE.AMP.'C=addons_modules'.AMP
				.'M=show_module_cp'.AMP.'module=seguimiento_piezas'.AMP.'method=gestionar_piezas');
		}

		$this->EE->cp->set_breadcrumb(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=seguimiento_piezas'.AMP.'method=gestionar_piezas', $this->EE->lang->line('titulo_gestion_piezas'));
		
		$this->EE->cp->set_variable('cp_page_title', $this->EE->lang->line('titulo_eliminar_piezas'));
		
		$damned = array();

		foreach ($this->EE->input->post('item_seleccionado') as $key => $val)
		{		
			if ($val != '')
			{
				$damned[] = $val;
			}
		}

		// Guardamos las piezas a ser eliminadas
		$data['damned'] = $damned;
		
		if (count($damned) == 1)
		{
			$data['message'] = lang('delete_pieza_confirm');
		}
		else
		{
			$data['message'] = lang('delete_piezas_confirm');
		}
		
		$data['action_url'] = html_entity_decode ( base_url(). BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=seguimiento_piezas'.AMP.'method=eliminar_pieza');

		return $this->EE->load->view('piezas/eliminar_confirm', $data, TRUE);
	}
	
	public function eliminar_pieza() {
		
		//Si no existe 'delete' mostramos un mensaje de error
		if ( ! $this->EE->input->post('delete'))
		{
			$this->EE->session->set_flashdata('message', lang('no_valid_selections'));
			$this->EE->functions->redirect(BASE.AMP.'C=addons_modules'.AMP
				.'M=show_module_cp'.AMP.'module=seguimiento_piezas'.AMP.'method=gestionar_piezas');
		}	
		
		$estado = $this->EE->pieza->eliminar_pieza( $this->EE->input->post('delete') );
			
		//Dependiendo el valor de estado mostramos el mensaje correspondiente
		$message = $estado ? $this->EE->lang->line('pieza_eliminada') : $this->EE->lang->line('pieza_no_eliminada');
		
		$this->EE->session->set_flashdata('message', $message);
		$this->EE->functions->redirect(BASE.AMP.'C=addons_modules'.AMP
			.'M=show_module_cp'.AMP.'module=seguimiento_piezas'.AMP.'method=gestionar_piezas');
		
	}
	
	public function eliminar_seguimiento() {
		
		//Si no existe 'delete' mostramos un mensaje de error
		if ( ! $this->EE->input->post('delete'))
		{
			$this->EE->session->set_flashdata('message', lang('no_valid_selections'));
			$this->EE->functions->redirect(BASE.AMP.'C=addons_modules'.AMP
				.'M=show_module_cp'.AMP.'module=seguimiento_piezas');
		}	
		
		$estado = $this->EE->seguimiento->eliminar_seguimiento( $this->EE->input->post('delete') );
			
		//Dependiendo el valor de estado mostramos el mensaje correspondiente
		$message = $estado ? $this->EE->lang->line('seg_eliminada') : $this->EE->lang->line('seg_no_eliminada');
		
		$this->EE->session->set_flashdata('message', $message);
		$this->EE->functions->redirect(BASE.AMP.'C=addons_modules'.AMP
			.'M=show_module_cp'.AMP.'module=seguimiento_piezas');
		
	}
	
	public function getTableSeguimientos() {
		
		$campos = array('seguimiento_id', 'seguimiento_codigo', 'estado');
		
		$this->getTable('seguimiento_piezas', $campos, TRUE, 'seguimiento_id');
	}
	
	public function getTablePiezas() {
		
		$campos = array('pieza_id', 'nombre', 'detalle');
		
		$this->getTable('piezas', $campos, TRUE, 'pieza_id');
	}
	
	public function crear_seguimiento() {
		$this->EE->load->helper('date');
		$this->EE->load->library("form_validation");
		
		//Inicializamos el breadcrumb
		$this->EE->cp->set_breadcrumb(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=seguimiento_piezas', $this->EE->lang->line('seguimiento_piezas_module_name'));
		
		$this->EE->cp->set_variable('cp_page_title', $this->EE->lang->line('titulo_crear_seguimiento'));
		
		$this->EE->form_validation->set_error_delimiters('<span class="error">', '</span>');
		$this->EE->form_validation->set_rules("usuario_id", "usuario", "trim|required|xss_clean");
		$this->EE->form_validation->set_rules("pieza_id", "pieza", "trim|required|xss_clean");
		$this->EE->form_validation->set_rules("detalle", "detalle", "trim|required|xss_clean");
		$this->EE->form_validation->set_rules("date_llegada", "Fecha llegada", "required");
		
		if( $this->EE->form_validation->run() ) {
	
			$registro = array(
				'usuario_id'     =>   $this->EE->form_validation->set_value('usuario_id'),
				'pieza_id'     	 =>   $this->EE->form_validation->set_value('pieza_id'),
				'detalle'     	 =>   $this->EE->form_validation->set_value('detalle'),
				'date_llegada'	 =>	  strtotime( $this->EE->form_validation->set_value('date_llegada') )
			);
			
			$estado = $this->EE->seguimiento->insertar_seguimiento( $registro );
			
			//Dependiendo el valor de estado mostramos el mensaje correspondiente
			$message = $estado ? $this->EE->lang->line('seguimiento_creado') : $this->EE->lang->line('seguimiento_no_creado');
			
			$this->EE->session->set_flashdata('message', $message);
			$this->EE->functions->redirect(BASE.AMP.'C=addons_modules'.AMP
				.'M=show_module_cp'.AMP.'module=seguimiento_piezas');	
		}
		
		//Obtenemos todos los usuarios del sistema
		//TODO: Ver si hace falta filtrar la query a solo ciertos usuarios
		$usuariosEE = $this->EE->member_model->get_members();
		
		//Parseamos los resultados devueltos
		foreach($usuariosEE->result() as $usuario)
		{
			$usuarios[$usuario->member_id] = $usuario->username;
		}
		
		$piezasEE = $this->EE->pieza->get_piezas();
		
		//Parseamos los resultados devueltos
		foreach($piezasEE->result() as $pieza)
		{
			$piezas[$pieza->pieza_id] = $pieza->nombre;
		}
		
		$data['usuarios'] = $usuarios;
		$data['piezas'] = $piezas;
		
		return $this->EE->load->view('seguimientos/crear_editar_seguimiento', $data , TRUE);
	}

	public function crear_pieza() {
		
		$this->EE->load->library("form_validation");
		
		//Inicializamos el breadcrumb
		$this->EE->cp->set_breadcrumb(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=seguimiento_piezas'.AMP.'method=gestionar_piezas', $this->EE->lang->line('titulo_gestion_piezas'));
		
		$this->EE->cp->set_variable('cp_page_title', $this->EE->lang->line('titulo_crear_pieza'));
		
		$this->EE->form_validation->set_error_delimiters('<span class="error">', '</span>');
		$this->EE->form_validation->set_rules("nombre", "nombre", "trim|required|xss_clean");
		$this->EE->form_validation->set_rules("detalle", "detalle", "trim|required|xss_clean");
		
		if( $this->EE->form_validation->run() ) {
			
			$registro = array( 
				'nombre'  => $this->EE->form_validation->set_value( "nombre" ),
				'detalle' => $this->EE->form_validation->set_value( "detalle" )
			 );
			 
			$estado = $this->EE->pieza->insertar_pieza( $registro );
			
			//Dependiendo el valor de estado mostramos el mensaje correspondiente
			$message = $estado ? $this->EE->lang->line('pieza_creada') : $this->EE->lang->line('pieza_no_creada');
			
			$this->EE->session->set_flashdata('message', $message);
			$this->EE->functions->redirect(BASE.AMP.'C=addons_modules'.AMP
				.'M=show_module_cp'.AMP.'module=seguimiento_piezas'.AMP.'method=gestionar_piezas');	
			
		}
		
		$data = array();
		return $this->EE->load->view('piezas/crear_editar_pieza', $data , TRUE);
	}

	private function getTable($tabla, $campos, $radio = FALSE, $col_id = NULL)
    {
     	/*
	  	* Array de columnas que van a ser devueltas al frontend
	  	*/
        $aColumns = $campos;
        
        // Tabla a ser usada
        $sTable = $tabla;
        //
    
        $iDisplayStart = $this->EE->input->get_post('iDisplayStart', true);
        $iDisplayLength = $this->EE->input->get_post('iDisplayLength', true);
        $iSortCol_0 = $this->EE->input->get_post('iSortCol_0', true);
        $iSortingCols = $this->EE->input->get_post('iSortingCols', true);
        $sSearch = $this->EE->input->get_post('sSearch', true);
        $sEcho = $this->EE->input->get_post('sEcho', true);
    
        // Paging
        if(isset($iDisplayStart) && $iDisplayLength != '-1')
        {
            $this->EE->db->limit($this->EE->db->escape_str($iDisplayLength), $this->EE->db->escape_str($iDisplayStart));
        }
        
        // Ordenamiento
        if(isset($iSortCol_0))
        {
            for($i=0; $i<intval($iSortingCols); $i++)
            {
                $iSortCol = $this->EE->input->get_post('iSortCol_'.$i, true);
                $bSortable = $this->EE->input->get_post('bSortable_'.intval($iSortCol), true);
                $sSortDir = $this->EE->input->get_post('sSortDir_'.$i, true);
    
                if($bSortable == 'true')
                {
                    $this->EE->db->order_by($aColumns[intval($this->EE->db->escape_str($iSortCol))], $this->EE->db->escape_str($sSortDir));
                }
            }
        }
        
        /* 
         * Filtrado
         */
        if(isset($sSearch) && !empty($sSearch))
        {
            for($i=0; $i<count($aColumns); $i++)
            {
                $bSearchable = $this->EE->input->get_post('bSearchable_'.$i, true);
                
                // Filtro individual de columnas
                if(isset($bSearchable) && $bSearchable == 'true')
                {
                    $this->EE->db->or_like($aColumns[$i], $this->EE->db->escape_like_str($sSearch));
                }
            }
        }
        
        // Select de los datos
        $this->EE->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $aColumns)), false);
        $rResult = $this->EE->db->get($sTable);
    
        // Data set length despues del filtrado
        $this->EE->db->select('FOUND_ROWS() AS found_rows');
        $iFilteredTotal = $this->EE->db->get()->row()->found_rows;
    
        // Total data set length
        $iTotal = $this->EE->db->count_all($sTable);
    
        // Output
        $output = array(
            'sEcho' => intval($sEcho),
            'iTotalRecords' => $iTotal,
            'iTotalDisplayRecords' => $iFilteredTotal,
            'aaData' => array()
        );
        
        foreach($rResult->result_array() as $aRow)
        {
            $row = array();
            
            foreach($aColumns as $col)
            {
                $row[] = $aRow[$col];
            }
			
			if( $radio ) {
				array_push($row, '<input type="checkbox" name="item_seleccionado[]" value="' . $aRow[ $col_id ] . '" class="item_seleccionado" />');
			}

            $output['aaData'][] = $row;
        }
    
        die(json_encode($output));
    }
	
}
