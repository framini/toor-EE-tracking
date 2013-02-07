<?php

/**
 * Config file
 *
 * @package        seguimiento_piezas
 * @author         Francisco Ramini <framini@gmail.com>
 */

if ( ! defined('FRR_NAME'))
{
	define('FRR_NAME',         'Seguimientos de Piezas');
	define('FRR_PACKAGE',      'seguimiento_piezas');
	define('FRR_VERSION',      '0.5.0');
	define('FRR_ASSETS',  
						serialize( 
							array(
							'css/jquery.dataTables_themeroller.css',
							'css/dataTable.custom.css',
							'css/chosen.css',
							'css/general.css',
							'js/lib/jquery-ui-1.9.2.custom.min.js',
							'js/lib/jquery.dataTables.js',
							'js/lib/chosen.jquery.js',
							'js/varios/mensajes.js',
							'js/lib/jquery.validate.js',
							'js/lib/jquery.form.js'
							)
						) // serialize
			); // define
}

$config['name']    = FRR_NAME;
$config['version'] = FRR_VERSION;
