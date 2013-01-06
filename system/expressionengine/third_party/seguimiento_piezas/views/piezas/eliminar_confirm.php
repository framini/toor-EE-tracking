<?=form_open( $action_url )?>
	
<?php foreach($damned as $entry_id):?>
	<?=form_hidden('delete[]', $entry_id)?>
<?php endforeach;?>

<p><strong><?=$message?></strong></p>

<p class="notice"><?=lang('action_can_not_be_undone')?></p>

<p><?=form_submit('delete_members', lang('delete'), 'class="submit"')?></p>

<?=form_close()?>