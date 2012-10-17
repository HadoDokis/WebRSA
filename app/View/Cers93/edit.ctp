<?php
	$title_for_layout = ( ( $this->action == 'add' ) ? 'Ajout d\'un CER' : 'Modification d\'un CER' );
	$this->set( 'title_for_layout', $title_for_layout );

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>

<div class="with_treemenu">
<?php
	echo $this->Xhtml->tag( 'h1', $title_for_layout );

	echo $this->Xform->create();
	echo $this->Xform->inputs(
		array(
			'fieldset' => false,
			'legend' => false,
			'Contratinsertion.id' => array( 'type' => 'hidden' ),
			'Contratinsertion.personne_id' => array( 'type' => 'hidden', 'value' => $personne_id ),
			'Contratinsertion.structurereferente_id' => array( 'type' => 'select', 'options' => $options['Contratinsertion']['structurereferente_id'], 'empty' => true ),
			'Contratinsertion.dd_ci' => array( 'type' => 'date', 'empty' => true, 'dateFormat' => 'DMY' ),
			'Contratinsertion.df_ci' => array( 'type' => 'date', 'empty' => true, 'dateFormat' => 'DMY' ),
			'Contratinsertion.date_saisi_ci' => array( 'type' => 'date', 'empty' => true, 'dateFormat' => 'DMY' ),
			'Cer93.id' => array( 'type' => 'hidden' ),
			'Cer93.contratinsertion_id' => array( 'type' => 'hidden' ),
			// Bloc 2: état cvil
			'Cer93.dtdemrsa' => array( 'type' => 'hidden', 'value' => $personne['Dossier']['dtdemrsa'] ),
			'Cer93.nom' => array( 'type' => 'hidden', 'value' => $personne['Personne']['nom'] ),
			'Cer93.nomnai' => array( 'type' => 'hidden', 'value' => $personne['Personne']['nomnai'] ),
			'Cer93.prenom' => array( 'type' => 'hidden', 'value' => $personne['Personne']['prenom'] ),
			'Cer93.dtnai' => array( 'type' => 'hidden', 'value' => $personne['Personne']['dtnai'] ),
			'Cer93.sitfam' => array( 'type' => 'hidden', 'value' => $personne['Foyer']['sitfam'] ),
			'Cer93.incoherencesetatcivil' => array( 'type' => 'textarea' ),
		)
	);

	echo $this->Xhtml->tag(
		'div',
		 $this->Xform->button( 'Enregistrer', array( 'type' => 'submit' ) )
		.$this->Xform->button( 'Annuler', array( 'type' => 'submit', 'name' => 'Cancel' ) ),
		array(
			'class' => 'submit noprint'
		)
	);
	echo $this->Xform->end();
?>
</div>
<div class="clearer"><hr /></div>