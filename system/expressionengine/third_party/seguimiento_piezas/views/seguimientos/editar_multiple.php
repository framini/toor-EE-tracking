<script>
$(function() {
	$('form').submit(function() {
		
		var count = 0;
		
	  	$('input.required').each(function(index, elem) {
	  		if( $(elem).val() == '' ) {
	  			count++;
	  			$(elem).addClass('error');
	  			$( elem ).on('keyup', actualizar);
	  		}
	  	});
	  	
	  	if( count > 0 ) {
	  		$('div.error').html( 'Hay ' + count + ' por corregir. Los mismos estan resaltados en el formulario' ).slideDown('slow');
	  	} else {
	  		$('div.error').slideUp('slow');
	  		return true;
	  	}
	  	
	  return false;
	});
	
	function actualizar(event) {
		if( $( this ).val() != '' ) {
			$( this ).off('keyup', actualizar);
			$( this ).removeClass('error');
		}
	}
});
</script>

<div class="error" style="display: none;"><span></span></div>

<?=form_open('C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=seguimiento_piezas'.AMP.'method=editar_seguimiento')?>

<?php foreach ($seguimientos as $seguimiento):?>
	
	<?=form_hidden('seguimiento_id[]', $seguimiento['seguimiento_id'])?>

	<?php $this -> table -> set_heading(array('data' => '&nbsp;', 'style' => 'width:40%;'), lang('valor'));
	
	$data_estado = array(
		'name' => 'estado[' . $seguimiento['seguimiento_id'] . ']',
		'value'=> $seguimiento['estado'],
		'class'=> 'required'
	);
	
	$data_detalle = array(
		'name' => 'detalle',
		'value' => $seguimiento['detalle']
	);
	
	$this -> table -> add_row(array(lang('row_seg_codigo'), 
									$seguimiento['seguimiento_codigo']
									)
							  );
							  
	$this -> table -> add_row(array(lang('row_seg_fecha_creacion'), 
									$seguimiento['date_added']
									)
							  );
	
	$this -> table -> add_row(array(lang('row_seg_estado'), 
									form_input($data_estado) 
									)
							  );

	$this -> table -> add_row( array(lang('row_seg_usuario'), 
									 form_dropdown('usuario[' . $seguimiento['seguimiento_id'] . ']', $usuarios, $seguimiento['usuario_id'])
									 )
							  );
	
	$this -> table -> add_row( array(lang('row_seg_pieza'), 
									 form_dropdown('pieza[' . $seguimiento['seguimiento_id'] . ']', $piezas, $seguimiento['pieza_id'])
									 )
							  );
							  
	$this -> table -> add_row( array(lang('row_seg_detalle'), 
									 form_textarea('detalle[' . $seguimiento['seguimiento_id'] . ']', $seguimiento['detalle'])
									 )
							  );

	echo $this -> table -> generate();
	$this -> table -> clear();
	?>

<hr />

<?php endforeach; ?>

<div><?=form_submit('editar_piezas', lang('update'), 'class="submit"')?></div>


</div>
<?=form_close() ?>
