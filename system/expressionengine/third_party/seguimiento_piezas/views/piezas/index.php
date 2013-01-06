<script>
	$(document).ready(function() {

			$('.mainTable').dataTable({
				"bProcessing": true,
					"bJQueryUI": true,
			        "bServerSide": true,
			        "sServerMethod": "GET",
			        "sAjaxSource": "<?= $urlDataSource ?>",
			        "iDisplayLength": 2,
			        "sPaginationType": "full_numbers" ,
			        "aLengthMenu": [[2, 25, 50, -1], [2, 25, 50, "Todos"]],
			        "aaSorting": [[0, 'asc']],
			        "oLanguage" : {
				    	"oPaginate" : {
					    	sFirst: "Primera",
							sLast: "&Uacute;ltima",
							sNext: "Siguiente",
							sPrevious: "Anterior"
					    },
				    	sInfo: "Total de piezas: <strong>_TOTAL_ </strong>",
						sInfoEmpty: "Resultados Encontrados: <strong>0 </strong>",
						sInfoFiltered: "",
						sLengthMenu: "Mostrar _MENU_ registros por p&aacute;gina",
						sLoadingRecords: "Cargando...",
						sProcessing: "Procesando...",
						sSearch: "Buscar:",
						sZeroRecords: "No se han encontrado registros"
				    }
			});
			
			$('.dataTables_length select').chosen();
			
			$('form').submit(function() {
				if( $('input:checkbox:checked.item_seleccionado').size() > 0 ) {
					return true;
				}
				return false;
			});

			
	});
</script>

<?=form_open($action_url, '')?>

<?php if( isset($message) ) { ?>
	<p class="mensage" style="display: none;"> <?= $message ?> </p>
<?php } ?>

<?php
	$this->table->set_template($cp_table_template);
	$this->table->set_heading(
							array('data' => lang('columna_1'), 'class' => 'primera_columna_tabla'),
							lang('columna_2_piezas'),
							lang('columna_3_piezas'),
							''
							);
	

	if (is_null($piezas)) {
		//Si no hay seguimientos cargados en el sistema mostramos un mensaje default
		$this->table->add_row(array('data' => lang('sin_seguimientos'), 'colspan' => 3, 'class' => 'no_files_warning'));
	
	} else {
		foreach($piezas as $pieza)
		{
			$this->table->add_row(
					$pieza['id'],
					$pieza['nombre'],
					$pieza['detalle'],
					form_checkbox( array( 'name' => 'item_seleccionado[]', 'value' => $pieza['id'], 'class' => 'item_seleccionado' ) )
				);
		}
	}

	echo $this->table->generate();
	$this->table->clear();
?>

<div class="tableFooter">
	<div class="tableSubmit">
		<?=form_submit(array('name' => 'submit', 'value' => lang('submit'), 'class' => 'submit')).NBS.NBS.form_dropdown('action', $options)?>
	</div>

	<span class="js_hide"><?=$pagination?></span>	
	<span class="pagination" id="filter_pagination"></span>
</div>

<?=form_close()?>