<script>
	$(function() {
        $( "#datepicker" ).datepicker();
    });
</script>

<div class="pageContents">

	<?=form_open('C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=seguimiento_piezas'.AMP.'method=crear_editar_seguimiento')?>
					<?php //echo validation_errors(); ?>
					<?php
						// without the div above, the slide effect breaks the table widths

						$this->table->set_template($cp_table_template);
						$this->table->set_heading(
												'',
												''
												);
						$data_detalle = array(
			              'name'        => 'detalle',
			              'id'          => 'detalle',
			            );

						$this->table->add_row(
							form_label('Usuario'),
							form_dropdown('usuario_id', $usuarios)
						);
						
						$this->table->add_row(
							form_label('Pieza'),
							form_dropdown('pieza_id', $piezas)
						);
						
						$this->table->add_row(
							form_label('Fecha de llegada'),
							form_input(array('name' => 'date_llegada', 'id' => 'datepicker'))
						);
						
						$this->table->add_row(
							form_label('Detalle'),
							form_textarea($data_detalle).form_error( $data_detalle['name'] )										
						);
						
						echo $this->table->generate();
						$this->table->clear();
					?>
					</div>

			<input type="submit" class="submit" value="<?=lang('crear_seguimiento')?>" />
			
			<?=form_close()?>


</div>
</div><!-- contents -->
