<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author Francisco Ramini <framini@gmail.com>
 * 
 * Libreria para poder usar datatables en el modulo.
 * Ejemplo de uso:
 *
 * $this->EE->load->library('frr_datatables');
 * 
 * $output = $this->EE->frr_datatables->getTable($this->EE, $tabla, $campos, $radio, $col_id);
 */

class Frr_datatables {

	public function getTable($EE, $tabla, $campos, $radio = FALSE, $col_id = NULL)
    {	
     	/*
	  	* Array de columnas que van a ser devueltas al frontend
	  	*/
        $aColumns = $campos;
        
        // Tabla a ser usada
        $sTable = $tabla;
        //
    
        $iDisplayStart = $EE->input->get_post('iDisplayStart', true);
        $iDisplayLength = $EE->input->get_post('iDisplayLength', true);
        $iSortCol_0 = $EE->input->get_post('iSortCol_0', true);
        $iSortingCols = $EE->input->get_post('iSortingCols', true);
        $sSearch = $EE->input->get_post('sSearch', true);
        $sEcho = $EE->input->get_post('sEcho', true);
    
        // Paging
        if(isset($iDisplayStart) && $iDisplayLength != '-1')
        {
            $EE->db->limit($EE->db->escape_str($iDisplayLength), $EE->db->escape_str($iDisplayStart));
        }
        
        // Ordenamiento
        if(isset($iSortCol_0))
        {
            for($i=0; $i<intval($iSortingCols); $i++)
            {
                $iSortCol = $EE->input->get_post('iSortCol_'.$i, true);
                $bSortable = $EE->input->get_post('bSortable_'.intval($iSortCol), true);
                $sSortDir = $EE->input->get_post('sSortDir_'.$i, true);
    
                if($bSortable == 'true')
                {
                    $EE->db->order_by($aColumns[intval($EE->db->escape_str($iSortCol))], $EE->db->escape_str($sSortDir));
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
                $bSearchable = $EE->input->get_post('bSearchable_'.$i, true);
                
                // Filtro individual de columnas
                if(isset($bSearchable) && $bSearchable == 'true')
                {
                    $EE->db->or_like($aColumns[$i], $EE->db->escape_like_str($sSearch));
                }
            }
        }
        
        // Select de los datos
        $EE->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $aColumns)), false);
        $rResult = $EE->db->get($sTable);
    
        // Data set length despues del filtrado
        $EE->db->select('FOUND_ROWS() AS found_rows');
        $iFilteredTotal = $EE->db->get()->row()->found_rows;
    
        // Total data set length
        $iTotal = $EE->db->count_all($sTable);
    
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
            	if( $col == "usuario_id" ) {
            		$usuario = $EE->member_model->get_member_data( $aRow[$col] )->result_array();
					$row[] = $usuario[0]['username'];
            	} 
            	
				elseif( preg_match('/^date_.+/', $col, $matches) != 0 ) {
					$fecha = unix_to_human($aRow[$col], TRUE, 'eu');
					$row[] = $fecha;
				}
				
            	else {
            		$row[] = $aRow[$col];
            	}
            }
			
			if( $radio ) {
				array_push($row, '<input type="checkbox" name="item_seleccionado[]" value="' . $aRow[ $col_id ] . '" class="item_seleccionado" />');
			}

            $output['aaData'][] = $row;
        }
    
        return $output;
    }

}