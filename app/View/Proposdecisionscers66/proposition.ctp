<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>

<div class="with_treemenu">
	<?php
		echo $this->Xhtml->tag(
			'h1',
			$this->pageTitle = __d( 'propodecisioncer66', "Proposdecisionscers66::{$this->action}" )
		);

		echo $this->Xform->create( 'Propodecisioncer66', array( 'id' => 'propodecisioncer66form' ) );
		if( Set::check( $this->request->data, 'Propodecisioncer66.id' ) ){
			echo $this->Xform->input( 'Propodecisioncer66.id', array( 'type' => 'hidden' ) );
		}
		echo $this->Xform->input( 'Propodecisioncer66.contratinsertion_id', array( 'type' => 'hidden', 'value' => $contratinsertion_id ) );
	?>

	<fieldset>

		<?php
			$formeci = Set::enum( Set::classicExtract( $contratinsertion, 'Contratinsertion.forme_ci' ), $forme_ci );
			$ddci = date_short( $contratinsertion['Contratinsertion']['dd_ci'] );
			$dfci = date_short( $contratinsertion['Contratinsertion']['df_ci'] );
			$duree = Set::enum( Set::classicExtract( $contratinsertion, 'Contratinsertion.duree_engag' ), $duree_engag_cg66 );
			$referent = $contratinsertion['Referent']['nom_complet'];

			echo $this->Xform->fieldValue( 'Contratinsertion.forme_ci', $formeci );
			echo $this->Xform->fieldValue( 'Contratinsertion.dd_ci', $ddci );
			echo $this->Xform->fieldValue( 'Contratinsertion.df_ci', $dfci );
			echo $this->Xform->fieldValue( 'Contratinsertion.duree_engag', $duree );
			echo $this->Xform->fieldValue( 'Referent.nom_complet', $referent );

			echo $this->Form->input( 'Propodecisioncer66.isvalidcer', array( 'legend' => __d( 'propodecisioncer66', 'Propodecisioncer66.isvalidcer' ), 'type' => 'radio', 'options' => $options['isvalidcer'] ) );
			echo $this->Form->input( 'Propodecisioncer66.datevalidcer', array( 'label' => __d( 'propodecisioncer66', 'Propodecisioncer66.datevalidcer' ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+2, 'minYear'=>date('Y')-2 ) );

		?>
		<fieldset id="motifcer" class="invisible">
			<?php
				echo $this->Default2->subform(
					array(
						'Motifcernonvalid66.Motifcernonvalid66' => array( 'type' => 'select', 'label' => 'Motif de non validation', 'multiple' => 'checkbox', 'empty' => false, 'options' => $listMotifs ),
						'Propodecisioncer66.motifficheliaison' => array( 'type' => 'textarea' ),
						'Propodecisioncer66.motifnotifnonvalid' => array( 'type' => 'textarea' ),
						'Propodecisioncer66.nonvalidationparticulier' => array( 'type' => 'radio', 'options' => $options['nonvalidationparticulier'] )
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
<div class="clearer"><hr /></div>
<script type="text/javascript">
	document.observe("dom:loaded", function() {
		observeDisableFieldsetOnRadioValue(
			'propodecisioncer66form',
			'data[Propodecisioncer66][isvalidcer]',
			$( 'motifcer' ),
			['N'],
			false,
			true
		);
	});
</script>