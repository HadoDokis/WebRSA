<?php
	$this->pageTitle =  __d( 'decisiondossierpcg66', "Decisionsdossierspcgs66::{$this->action}" );

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>

<h1><?php echo $this->pageTitle;?></h1>
	<?php echo $this->Form->create( 'Decisiondossierpcg66',array(  'id' => 'transmissionopdossierpcg66form' ) ); ?>

	<fieldset>
			<?php echo $this->Form->input( 'Decisiondossierpcg66.id', array( 'type' => 'hidden' ) );?>
			<?php echo $this->Form->input( 'Decisiondossierpcg66.dossierpcg66_id', array( 'type' => 'hidden', 'value' => $dossierpcg66_id ) );?>

			<?php echo $this->Form->input( 'Decisiondossierpcg66.etatop', array( 'label' => __d( 'decisiondossierpcg66', 'Decisiondossierpcg66.etatop' ), 'legend' => false, 'type' => 'radio', 'options' => $options['Decisiondossierpcg66']['etatop'] )  ); ?>

			<feildset id="etattransmission" class="noborder" >
				<?php
					echo $this->Default2->subform(
						array(
							'Orgtransmisdossierpcg66.Orgtransmisdossierpcg66' => array( 'type' => 'select', 'label' => __d( 'orgtransmisdossierpcg66', 'Orgtransmisdossierpcg66.name' ), 'multiple' => 'checkbox', 'empty' => false, 'options' => $orgstransmisdossierspcgs66 ),
							'Decisiondossierpcg66.datetransmissionop' => array( 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1 )
						),
						array(
							'options' => $options
						)
					);
				?>
			</fieldset>
	</fieldset>

<div class="submit">
	<?php echo $this->Form->submit( 'Enregistrer', array( 'div' => false ) );?>
	<?php echo $this->Form->submit('Annuler', array( 'name' => 'Cancel', 'div' => false ) );?>
</div>
<?php echo $this->Form->end();?>

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