<script>
$(function() {
	$("form").validate({
		invalidHandler: function(e, validator) {
			var errors = validator.numberOfInvalids();
			if (errors) {
				var message = errors == 1
					? 'Hay 1 campo incorrecto. Fue resaltado en rojo debajo'
					: 'Hay ' + errors + ' campos incorrectos.  Los mismos fueron resaltados debajo';
				$("div.error").html(message);
				$("div.error").show();
			} else {
				$("div.error").hide();
			}
		},
		submitHandler: function( form ) {
			if ($(form).valid()) 
               form.submit(); 
           return false; 
		},
		messages: {
			nombre: {
				required: " "
			}
		}
	});
});
</script>

<div class="error" style="display: none;"><span></span></div>

	<?=form_open('C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=seguimiento_piezas'.AMP.'method=crear_pieza', array('id' => 'formulario'))?>
					<?php //echo validation_errors(); ?>
					<?php
						$this->table->set_template($cp_table_template);
						$this->table->set_heading(
												'',
												lang("valor")
												);
						$data_pieza = array(
			              'name'        => 'nombre',
			              'id'          => 'nombre',
			              'class'		=> 'required'
			            );
						
						$data_detalle = array(
			              'name'        => 'detalle',
			              'id'          => 'detalle'
			            );
						
						$this->table->add_row(
							form_label('Nombre Pieza'),
							form_input( $data_pieza ) . form_error( $data_pieza['name'] )										
						);
						
						
						$this->table->add_row(
							form_label('Detalle Pieza'),
							form_textarea( $data_detalle ) . form_error( $data_detalle['name'] )
						);
						
						echo $this->table->generate();
						$this->table->clear();
					?>
					
					<hr />
					
			<div><?=form_submit('editar_piezas', lang('crear_pieza'), 'class="submit"')?></div>
			
			<?=form_close()?>
