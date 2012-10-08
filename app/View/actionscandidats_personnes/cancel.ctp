<?php
	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );

	$modelClassName = 'ActioncandidatPersonne';
	$domain = "actioncandidat_personne_".Configure::read( 'ActioncandidatPersonne.suffixe' );
?>
<div class="with_treemenu">
	<h1> <?php
			echo $xhtml->tag(
				'h1',
				$this->pageTitle = __d( $domain, "ActionscandidatsPersonnes::{$this->action}", true )
			);
		?> 
	</h1>

	<?php
		if( Configure::read( 'debug' ) > 0 ) {
			echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
		}
	?>

	<?php
		echo $xform->create();


		echo $default->subform(
			array(
				"{$modelClassName}.id" => array( 'type' => 'hidden' ),
				"{$modelClassName}.personne_id" => array( 'type' => 'hidden' ),
				"{$modelClassName}.referent_id" => array( 'type' => 'hidden' ),
				"{$modelClassName}.actioncandidat_id" => array( 'type' => 'hidden' ),
				"{$modelClassName}.motifannulation" => array( 'type' => 'textarea' ),
			),
			array(
				'domain' => $domain
			)
		);

		echo '<div class="submit">';
		echo $xform->submit( 'Enregistrer', array( 'div' => false ) );
		echo $xform->submit( 'Annuler', array( 'name' => 'Cancel', 'div' => false ) );
		echo '</div>';
		echo $xform->end();
	?>
</div>
<div class="clearer"><hr /></div>