<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id) );
?>

<div class="with_treemenu">
	<h1><?php echo $this->pageTitle = 'Ajout d\'une relance';?></h1>

	<?php
		echo $xform->create();

		echo $xform->input( 'Nonrespectsanctionep93.id', array( 'type' => 'hidden' ) );
		echo $xform->input( 'Nonrespectsanctionep93.origine', array( 'type' => 'radio', 'options' => array( 'orientstruct' => 'Orientation non contractualisée', 'contratinsertion' => 'Non renouvellement du CER' ) ) );

		echo $xform->input( 'Relancenonrespectsanctionep93.id', array( 'type' => 'hidden' ) );
		echo $xform->input( 'Relancenonrespectsanctionep93.numrelance', array( 'type' => 'radio', 'options' => array( 1 => 'Première relance', 2 => 'Seconde relance', 3 => 'Troisième relance' ) ) );
		echo $xform->input( 'Relancenonrespectsanctionep93.daterelance', array( 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 1, 'minYear' => date( 'Y' ) - 1, 'empty' => true ) );

		echo $xform->end( 'Enregistrer' );
	?>
</div>
<div class="clearer"><hr /></div>