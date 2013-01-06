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

<?=form_open('C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=seguimiento_piezas'.AMP.'method=editar_pieza')?>

<?php foreach ($piezas as $pieza):?>
	
	<?=form_hidden('pieza_id[]', $pieza['pieza_id'])?>

	<?php $this -> table -> set_heading(array('data' => '&nbsp;', 'style' => 'width:40%;'), lang('valor'));
	
	$data_nombre = array(
		'name' => 'nombre[' . $pieza['pieza_id'] . ']',
		'value'=> $pieza['nombre'],
		'class'=> 'required'
	);
	
	$this -> table -> add_row(array(lang('row_pieza_nombre'), 
									form_input($data_nombre) 
									)
							  );

	$this -> table -> add_row( array(lang('row_pieza_detalle'), 
									 form_textarea('detalle[' . $pieza['pieza_id'] . ']', $pieza['detalle'])
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
