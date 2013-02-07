<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// include config file
include(PATH_THIRD.'seguimiento_piezas/config.php');

/**
 * Modulo Init Base Class
 *
 * Clase que tiene funciones y varuables comunes a las distintas clases que usa el modulo
 *
 * @package        seguimiento_piezas
 * @author         Francisco Ramini <framini@gmail.com>
 */
class Modulo_init_base
{
	// --------------------------------------------------------------------
	// PROPERTIES
	// --------------------------------------------------------------------
	
	/**
	 * Nombre del Add-on
	 *
	 * @var        string
	 * @access     public
	 */
	public $name = FRR_NAME;

	/**
	 * Version Add-on
	 *
	 * @var        string
	 * @access     public
	 */
	public $version = FRR_VERSION;


	/**
	 * EE object
	 *
	 * @var        object
	 * @access     protected
	 */
	protected $EE;


	/**
	 * Nombre Package
	 *
	 * @var        string
	 * @access     protected
	 */
	protected $package = FRR_PACKAGE;


	/**
	 * Site id shortcut (Para MSM)
	 *
	 * @var        int
	 * @access     protected
	 */
	protected $site_id;


	/**
	 * url base para el modulo
	 *
	 * @var        string
	 * @access     protected
	 */
	protected $base_url;


	/**
	 * url base para la extension (en caso que haya una)
	 *
	 * @var        string
	 * @access     protected
	 */
	protected $ext_url;


	/**
	 * Data array para las views
	 *
	 * @var        array
	 * @access     protected
	 */
	protected $data = array();

	/**
	 * Assets (CSS, JSS, etc) para el Control Panel
	 *
	 * @var        array
	 * @access     private
	 */
	private $mcp_assets;


	/**
	 * Constructor
	 *
	 * @access     public
	 * @return     void
	 */
	public function __construct()
	{
		// -------------------------------------
		//  Obtenemos el Objeto de EE
		// -------------------------------------

		$this->EE =& get_instance();

		// -------------------------------------
		//  Definimos el path al package
		// -------------------------------------

		$this->EE->load->add_package_path(PATH_THIRD.$this->package);

		// -------------------------------------
		//  Cargamos los helpers
		// -------------------------------------

		$this->EE->load->helper($this->package);

		// -------------------------------------
		//  Obtenemos el site shortcut
		// -------------------------------------

		$this->site_id = $this->EE->config->item('site_id');

		//--------------------------------------
		//  Incializamos los assets a ser usados por el modulo
		//--------------------------------------
		$this->mcp_assets = unserialize(FRR_ASSETS);

	}


	/**
	 * Setea el base url para las views
	 *
	 * @access     protected
	 * @return     void
	 */
	protected function set_base_url()
	{
		$this->data['base_url'] = $this->base_url = base_url() . BASE.AMP.'C=addons_modules&amp;M=show_module_cp&amp;module='.$this->package;
		$this->data['ext_url'] = $this->ext_url = base_url() . BASE.AMP.'C=addons_extensions&amp;M=extension_settings&amp;file='.$this->package;
	}



	/**
	 * Metodo a ser llamado para mostrar una pagina del add-on
	 *
	 * @access     protected
	 * @param      string
	 * @return     string
	 */
	protected function view($file)
	{
		// -------------------------------------
		//  Cargamos el CSS y JS
		// -------------------------------------

		$this->_load_assets();

		// -------------------------------------
		//  Si hay flashdata cargada la cargamos en data
		// -------------------------------------

		if ( $this->EE->session->flashdata('message') )
		{
			$this->data['message'] = $this->EE->session->flashdata('message');
		}

		return $this->EE->load->view($file, $this->data, TRUE);
	}


	/**
	 * Metodo para cargar assets: CSS y JSS
	 *
	 * @access     private
	 * @return     void
	 */
	private function _load_assets()
	{
		$header = array();

		// -------------------------------------
		//  Recorremos el array de assets
		// -------------------------------------

		$asset_url = ((defined('URL_THIRD_THEMES'))
		           ? URL_THIRD_THEMES
		           : $this->EE->config->item('theme_folder_url') . 'third_party/')
		           . $this->package . '/';

		foreach ($this->mcp_assets AS $file)
		{
			// ubicacion en el server
			$file_url = $asset_url.$file;

			if (substr($file, -3) == 'css')
			{
				$header[] = '<link charset="utf-8" type="text/css" href="'.$file_url.'" rel="stylesheet" media="screen" />';
			}
			elseif (substr($file, -2) == 'js')
			{
				$header[] = '<script charset="utf-8" type="text/javascript" src="'.$file_url.'"></script>';
			}
		}

		// -------------------------------------
		//  por ultimo agregamos los assets al header
		// -------------------------------------

		if ($header)
		{
			$this->EE->cp->add_to_head(
				NL."<!-- {$this->package} assets -->".NL.
				implode(NL, $header).
				NL."<!-- / {$this->package} assets -->".NL
			);
		}
	}


}