<?php $this->pageTitle = __d( 'ajoutdossier', "Ajoutdossiers::{$this->action}", true );?>
<?php echo $form->create('Ajoutdossiers',array('id'=>'SignupForm','url'=>str_replace( '/webrsa/', '/', $this->here )));?>
	<div class="submit">
		<?php echo $form->submit('Continue', array('div'=>false));?>
		<?php echo $form->submit('Cancel', array('name'=>'Cancel','div'=>false));?>
	</div>
<?php echo $form->end();?>