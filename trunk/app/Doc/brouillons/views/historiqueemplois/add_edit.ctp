<?php
	echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	$this->pageTitle = ( $this->action == 'add' ? 'Ajout d\'un métier exercé' : 'Modification d\'un métier exercé' );
	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>
<div class="with_treemenu">
	<h1><?php echo $this->pageTitle;?></h1>

	<?php
		echo $xform->create();
		echo '<div>'.$xform->input( 'Historiqueemploi.id' ).'</div>';
		echo '<div>'.$xform->input( 'Historiqueemploi.personne_id', array( 'type' => 'hidden', 'value' => $personne_id ) ).'</div>';
		echo $xform->input( 'Historiqueemploi.datedebut', array( 'empty' => true, 'domain' => 'historiqueemploi', 'dateFormat' => 'DMY', 'maxYear' => date('Y')+1, 'minYear' => date('Y')-20 ) );
		echo $xform->input( 'Historiqueemploi.datefin', array( 'empty' => true, 'domain' => 'historiqueemploi', 'dateFormat' => 'DMY', 'maxYear' => date('Y')+1, 'minYear' => date('Y')-20 ) );
		echo $xform->input( 'Historiqueemploi.secteuractivite', array( 'domain' => 'historiqueemploi', 'type' => 'select', 'empty' => true, 'options' => $options['Historiqueemploi']['secteuractivite'] ) );
		echo $xform->input( 'Historiqueemploi.emploi', array( 'domain' => 'historiqueemploi', 'type' => 'select', 'empty' => true, 'options' => $options['Historiqueemploi']['emploi'] ) );
		echo $xform->input( 'Historiqueemploi.dureehebdomadaire', array( 'domain' => 'historiqueemploi', 'type' => 'select', 'empty' => true, 'options' => $options['Historiqueemploi']['dureehebdomadaire'] ) );
		echo $xform->input( 'Historiqueemploi.naturecontrat', array( 'domain' => 'historiqueemploi', 'type' => 'select', 'empty' => true, 'options' => $options['Historiqueemploi']['naturecontrat'] ) );
		echo $xform->input( 'Historiqueemploi.dureecdd', array( 'domain' => 'historiqueemploi', 'type' => 'select', 'empty' => true, 'options' => $options['Historiqueemploi']['dureecdd'] ) );
		echo $xform->submit( 'Enregistrer' );
		echo $xform->end();
	?>
</div>
<div class="clearer"><hr /></div>

<script type="text/javascript">
	document.observe( "dom:loaded", function() {
		// Mise à vide de la durée du CDD si la nature du contrat de travail n'est pas un CDD
		observeDisableFieldsOnValue(
			'HistoriqueemploiNaturecontrat',
			[ 'HistoriqueemploiDureecdd' ],
			'TCT3',
			false
		);
	} );
</script>