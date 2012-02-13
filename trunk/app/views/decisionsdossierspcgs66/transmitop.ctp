<?php
	$this->pageTitle =  __d( 'decisiondossierpcg66', "Decisionsdossierspcgs66::{$this->action}", true );
echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	
?>
<?php echo $this->element( 'dossier_menu', array( 'foyer_id' => $foyer_id ) ); ?>
<?php  echo $form->create( 'Decisiondossierpcg66',array(  'id' => 'transmissionopdossierpcg66form', 'url' => Router::url( null, true ) ) ); ?>


<div class="with_treemenu">
	<h1><?php echo $this->pageTitle;?></h1>

		<fieldset>
				<?php echo $form->input( 'Decisiondossierpcg66.id', array( 'type' => 'hidden' ) );?>
				<?php echo $form->input( 'Decisiondossierpcg66.dossierpcg66_id', array( 'type' => 'hidden', 'value' => $dossierpcg66_id ) );?>

				<?php echo $form->input( 'Decisiondossierpcg66.etatop', array( 'label' => __d( 'decisiondossierpcg66', 'Decisiondossierpcg66.etatop', true ), 'legend' => false, 'type' => 'radio', 'options' => $options['Decisiondossierpcg66']['etatop'] )  ); ?>

				<feildset id="etattransmission" class="noborder" >
					<?php echo $form->input( 'Decisiondossierpcg66.datetransmissionop', array( 'label' => __d( 'decisiondossierpcg66', 'Decisiondossierpcg66.datetransmissionop', true ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1 , 'empty' => true)  ); ?>
				</fieldset>
		</fieldset>

	<div class="submit">
		<?php echo $form->submit( 'Enregistrer', array( 'div' => false ) );?>
		<?php echo $form->submit('Annuler', array( 'name' => 'Cancel', 'div' => false ) );?>
	</div>
	<?php echo $form->end();?>
</div>

<div class="clearer"><hr /></div>

<script type="text/javascript">
	document.observe( "dom:loaded", function() {
		observeDisableFieldsetOnRadioValue(
			'transmissionopdossierpcg66form',
			'data[Decisiondossierpcg66][etatop]',
			$( 'etattransmission' ),
			'transmis',
			false,
			true
		);
	} );
</script>