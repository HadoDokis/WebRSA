<?php
	$this->pageTitle =  __d( 'decisionpersonnepcg66', "Decisionspersonnespcgs66::{$this->action}", true );

	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>

<div class="with_treemenu">
	<?php
		echo $xhtml->tag( 'h1', $this->pageTitle );
		echo $form->create( 'Decisionpersonnepcg66', array( 'type' => 'post', 'id' => 'decisionpersonnepcg66form', 'url' => Router::url( null, true ) ) );

		$motif = Set::enum( Set::classicExtract( $decisionpersonnepcg66, 'Decisionpersonnepcg66.personnepcg66_situationpdo_id' ), $personnespcgs66Situationspdos );

		echo $default2->view(
			$decisionpersonnepcg66,
			array(
				'Decisionpersonnepcg66.personnepcg66_situationpdo_id' => array( 'label' => 'Motif en question', 'value' => $motif ),
				'Decisionpersonnepcg66.datepropositions',
				'Decisionpdo.libelle',
				'Decisionpersonnepcg66.commentaire',
			),
			array(
				'class' => 'aere'
			)
		);

		echo '<div class="aere">';
		echo $default->button(
			'backpdo',
			array(
				'controller' => 'decisionspersonnespcgs66',
				'action'     => 'index',
				$personnepcg66_id
			),
			array(
				'id' => 'Back',
				'label' => 'Retour au dossier'
			)
		);
		echo '</div>';

	?>
</div>

<div class="clearer"></div>