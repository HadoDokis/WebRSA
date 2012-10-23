<?php
	$title_for_layout = '1ère lecture';
	$this->set( 'title_for_layout', $title_for_layout );

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>

<div class="with_treemenu">
<?php
	echo $this->Html->tag( 'h1', $title_for_layout );

	echo $this->Xform->create( null, array( 'inputDefaults' => array( 'domain' => 'histochoixcer93' ) ) );

	// FIXME: affichage du CER et des étapes précédentes de l'historique

	echo $this->Xform->inputs(
		array(
			'fieldset' => false,
			'legend' => false,
			'Histochoixcer93.id' => array( 'type' => 'hidden' ),
			'Histochoixcer93.cer93_id' => array( 'type' => 'hidden' ),
			'Histochoixcer93.user_id' => array( 'type' => 'hidden' ),
			'Histochoixcer93.formeci' => array( 'type' => 'radio', 'options' => $options['Cer93']['formeci'] ),
			'Histochoixcer93.commentaire' => array( 'type' => 'textarea' ),
			'Histochoixcer93.datechoix' => array( 'type' => 'date', 'dateFormat' => 'DMY' ),
			'Histochoixcer93.prevalide' => array( 'type' => 'radio', 'options' => $options['Histochoixcer93']['prevalide'] ),
			'Histochoixcer93.etape' => array( 'type' => 'hidden' )
		)
	);
?>

<?php
	echo $this->Html->tag(
		'div',
		 $this->Xform->button( 'Enregistrer', array( 'type' => 'submit' ) )
		.$this->Xform->button( 'Annuler', array( 'type' => 'submit', 'name' => 'Cancel' ) ),
		array( 'class' => 'submit noprint' )
	);

	echo $this->Xform->end();
?>
</div>
<div class="clearer"><hr /></div>