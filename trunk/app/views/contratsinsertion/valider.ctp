<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php
	if( Configure::read( 'nom_form_ci_cg' ) == 'cg66' ){
		$this->pageTitle = 'Décision du CER '.Set::enum( Set::classicExtract( $contratinsertion, 'Contratinsertion.forme_ci'), $forme_ci);
	}
	else{
		$this->pageTitle = 'Décision du CER';
	}
?>
<?php  echo $form->create( 'Contratinsertion',array( 'url' => Router::url( null, true ) ) ); ?>

<?php echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>

<div class="with_treemenu">
	<h1><?php echo $this->pageTitle;?></h1>

		<fieldset>
			<legend> PARTIE RESERVEE AU DEPARTEMENT</legend>
				<?php
					echo $form->input( 'Contratinsertion.id', array( 'type' => 'hidden' ) );
					echo $form->input( 'Contratinsertion.personne_id', array( 'type' => 'hidden', 'value' => $personne_id ) );

					if( Configure::read( 'Cg.departement' ) == 66 ) {
						$formeci = Set::enum( Set::classicExtract( $contratinsertion, 'Contratinsertion.forme_ci' ), $forme_ci );
						$ddci = date_short( $contratinsertion['Contratinsertion']['dd_ci'] );
						$dfci = date_short( $contratinsertion['Contratinsertion']['df_ci'] );
						$duree = Set::enum( Set::classicExtract( $contratinsertion, 'Contratinsertion.duree_engag' ), $duree_engag_cg66 );
						$referent = $contratinsertion['Referent']['nom_complet'];
						$propodecision = Set::enum( $contratinsertion['Propodecisioncer66']['isvalidcer'], $options['Propodecisioncer66']['isvalidcer'] );
						$datevalidcer = date_short( $contratinsertion['Propodecisioncer66']['datevalidcer'] );

						echo $xform->fieldValue( 'Contratinsertion.forme_ci', $formeci );
						echo $xform->fieldValue( 'Contratinsertion.dd_ci', $ddci );
						echo $xform->fieldValue( 'Contratinsertion.df_ci', $dfci );
						echo $xform->fieldValue( 'Contratinsertion.duree_engag', $duree );
						echo $xform->fieldValue( 'Referent.nom_complet', $referent );
// 						echo $xform->fieldValue( 'Propodecisioncer66.isvalidcer', $propodecision );
// 						echo $xform->fieldValue( 'Propodecisioncer66.datevalidcer', $datevalidcer );
						
						echo $form->input( 'Contratinsertion.decision_ci', array( 'label' => 'Décision finale', 'type' => 'select', 'options' => $decision_ci ) );
						echo $form->input( 'Contratinsertion.datedecision', array( 'label' => '', 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+2, 'minYear'=>date('Y')-3 , 'empty' => true)  );
						echo $form->input( 'Contratinsertion.observ_ci', array( 'label' => __d( 'contratinsertion', 'Contratinsertion.observ_ci', true ), 'type' => 'textarea', 'rows' => 6, 'class' => 'aere')  );
					}
					else{
						echo 'CER '.Set::enum( Set::classicExtract( $contratinsertion, 'Contratinsertion.forme_ci' ), $forme_ci ).' du '.date_short( Set::classicExtract( $contratinsertion, 'Contratinsertion.dd_ci' ) ).' au '.date_short( Set::classicExtract( $contratinsertion, 'Contratinsertion.df_ci' ) );

						echo $form->input( 'Contratinsertion.observ_ci', array( 'label' => __d( 'contratinsertion', 'Contratinsertion.observ_ci', true ), 'type' => 'textarea', 'rows' => 6, 'class' => 'aere')  );

						echo $form->input( 'Contratinsertion.positioncer', array( 'type' => 'hidden' ) );
						
						echo $form->input( 'Contratinsertion.decision_ci', array( 'label' => __d( 'contratinsertion', 'Contratinsertion.decision_ci', true ), 'type' => 'select', 'options' => $decision_ci ) );
						echo $form->input( 'Contratinsertion.datevalidation_ci', array( 'label' => '', 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+2, 'minYear'=>date('Y')-3 , 'empty' => true)  );
					}
				?>
		</fieldset>

	<div class="submit">
		<?php echo $form->submit( 'Enregistrer', array( 'div' => false ) );?>
		<?php echo $form->submit('Annuler', array( 'name' => 'Cancel', 'div' => false ) );?>
	</div>
	<?php echo $form->end();?>
</div>

<div class="clearer"><hr /></div>
<script>
	document.observe("dom:loaded", function() {
	
		observeDisableFieldsOnValue(
			'ContratinsertionDecisionCi',
			[
				'ContratinsertionDatedecisionDay',
				'ContratinsertionDatedecisionMonth',
				'ContratinsertionDatedecisionYear'
			],
			'E',
			true
		);
	
		observeDisableFieldsOnValue(
			'ContratinsertionDecisionCi',
			[
				'ContratinsertionDatevalidationCiDay',
				'ContratinsertionDatevalidationCiMonth',
				'ContratinsertionDatevalidationCiYear'
			],
			'V',
			false
		);
	});
</script>