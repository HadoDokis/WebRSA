<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $this->Xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'partenaire', "Partenaires::{$this->action}" )
	)
?>

<?php
	echo $this->Xform->create();

	echo $this->Default2->subform(
		array(
			'Partenaire.id' => array( 'type' => 'hidden'),
			'Partenaire.libstruc' => array( 'required' => true ),
			'Partenaire.codepartenaire',
			'Partenaire.numvoie' => array( 'required' => true ),
			'Partenaire.typevoie' => array( 'options' => $options['Adresse']['typevoie'], 'required' => true ),
			'Partenaire.nomvoie' => array( 'required' => true ),
			'Partenaire.compladr',
			'Partenaire.numtel',
			'Partenaire.numfax',
			'Partenaire.email',
			'Partenaire.codepostal' => array( 'required' => true ),
// 			'Partenaire.canton' => array( 'options' => $cantons, 'empty' => true ),
			'Partenaire.ville' => array( 'required' => true )
		)
	);
	
	/*echo '<br />';

	echo $this->Default2->subform(
		array(
			'Partenaire.secteuractivitepartenaire_id' => array( 'empty' => true, 'options' => $secteursactivites ),
			'Partenaire.statut' => array( 'empty' => true, 'options' => $options['Partenaire']['statut'] ),
			'Partenaire.serviceinstructeur_id' => array( 'empty' => true, 'options' => $options['Partenaire']['serviceinstructeur_id'] ),
			'Partenaire.nomtiturib',
			'Partenaire.codeban',
			'Partenaire.guiban',
			'Partenaire.numcompt',
			'Partenaire.nometaban',
			'Partenaire.clerib',
			'Partenaire.orgrecouvcotis' => array( 'type' => 'radio', 'empty' => false, 'options' => $options['Partenaire']['orgrecouvcotis'] )
		)
	);
		
	echo $this->Default2->subform(
		array(
			'Partenaire.iscui' => array( 'empty' => false, 'type' => 'radio', 'options' => $options['Partenaire']['iscui'], 'required' => true )
		)
	);*/

	
	
	echo $this->Html->tag(
		'div',
		 $this->Xform->button( 'Enregistrer', array( 'type' => 'submit' ) )
		.$this->Xform->button( 'Annuler', array( 'type' => 'submit', 'name' => 'Cancel' ) ),
		array( 'class' => 'submit noprint' )
	);

	echo $this->Xform->end();
?>