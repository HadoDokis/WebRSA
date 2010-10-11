<div class="titressejour form">
<?php echo $form->create('Titresejour');?>
	<fieldset>
 		<legend><?php __('Add Titresejour');?></legend>
	<?php
		echo $form->input('personne_id');
		echo $form->input('dtentfra');
		echo $form->input('nattitsej');
		echo $form->input('menttitsej');
		echo $form->input('ddtitsej');
		echo $form->input('dftitsej');
		echo $form->input('numtitsej');
		echo $form->input('numduptitsej');
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('List Titressejour', true), array('action' => 'index'));?></li>
	</ul>
</div>
