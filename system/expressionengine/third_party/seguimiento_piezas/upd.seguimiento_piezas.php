<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

// include base class
if ( ! class_exists('Modulo_init_base'))
{
	require_once(PATH_THIRD.'seguimiento_piezas/base.modulo_init.php');
}

class Seguimiento_piezas_upd extends Modulo_init_base {

	/**
	 * Class name
	 *
	 * @var        string
	 * @access     private
	 */
	private $class_name;
	private $table_name_seguimiento = "seguimiento_piezas";
	private $table_name_piezas = "piezas";


	/**
	 * Constructor
	 *
	 * @access     public
	 * @return     void
	 */
	public function __construct()
	{
		// Call parent constructor
		parent::__construct();

		// Set class name
		$this->class_name = ucfirst(FRR_PACKAGE);
	}
	
	//Metodo utilizado para instalar el modulo
	function install() {
		
		//Array a ser insertado en la tabla de modulos
		$data = array(
			'module_name'			=>	$this->name,
			'module_version'		=>	$this->version,
			'has_cp_backend'		=>	'y',
			'has_publish_fields'	=>	'n'
		);

		$this->EE->db->insert('modules', $data);
		
		//Tablas a ser utilizadas por el modulo
		$this->EE->load->dbforge();
		$fields = array(
			"seguimiento_id"  => array(
							"type" => "INT",
							"auto_increment" => TRUE,
						),
			"seguimiento_codigo" =>  array(
							"type" => "VARCHAR",
							"constraint" => "128"
						),
			"usuario_id" 	=>  array(
							"type" => "INT",
						),
			"estado" 	=> array(
							"type" => "VARCHAR",
							"constraint" => "128"
						),
			"pieza_id" 	=> array(
							"type" => "INT"
						),
			"detalle" 	=> array(
							"type" => "TEXT"
						),
			"date_added" => array(
							"type" => "INT"
						),
			'date_llegada' => array(
							'type' => 'INT'
						)
		);
		$this->EE->dbforge->add_field($fields);
		$this->EE->dbforge->add_key('seguimiento_id', TRUE);
		$this->EE->dbforge->create_table($this->table_name_seguimiento);
		
		unset($fields);
		
		$fields = array(
			"pieza_id"  => array(
							"type" => "INT",
							"auto_increment" => TRUE,
						),
			"nombre"    => array(
							"type" => "VARCHAR",
							"constraint" => "128"
						),
			"detalle"   => array(
							"type" => "TEXT"
						)
		);
		$this->EE->dbforge->add_field($fields);
		$this->EE->dbforge->add_key('pieza_id', TRUE);
		$this->EE->dbforge->create_table($this->table_name_piezas);
		

		return TRUE;
	}

	
	function update($current = '') {

		if ($current == '' OR version_compare($current, $this->version) === 0)
		{
			return FALSE;
		}

		// -------------------------------------
		//  Upgrade a 0.2
		// -------------------------------------

		if (version_compare($current, '0.2.0', '<'))
		{
			$this->EE->load->dbforge();
			$fields = array(
	                        'date_llegada' => array('type' => 'INT')
			);
			$this->EE->dbforge->add_column( $this->table_name_seguimiento, $fields);
		}
			

		return TRUE;
	}
	
	function uninstall() {
		$this->EE->db->where('module_name', $this->name);
		$this->EE->db->delete('modules');


		$this->EE->load->dbforge();
		$this->EE->dbforge->drop_table($this->table_name_seguimiento);
		$this->EE->dbforge->drop_table($this->table_name_piezas);

		return TRUE;
	}

}
