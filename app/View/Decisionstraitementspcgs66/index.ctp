<?php
	$this->pageTitle = 'Propositions de décision';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $this->element( 'dossier_menu', array( 'personne_id' => $traitementpcg66['Personnepcg66']['personne_id'] ) );
?>

<div class="with_treemenu">
	<?php
		echo $this->Xhtml->tag(
			'h1',
			$this->pageTitle = __d( 'decisiontraitementpcg66', "Decisionstraitementspcgs66::{$this->action}" ).' '.$traitementpcg66['Descriptionpdo']['name']
		);

		echo $this->Default2->index(
			$listeDecisions,
			array(
				'Decisiontraitementpcg66.valide',
				'Decisiontraitementpcg66.commentaire',
				'Decisiontraitementpcg66.created'
			),
			array(
				'add' => array(
					'Decisiontraitementpcg66.add' => array( 'controller'=>'decisionstraitementspcgs66', 'action'=>'add', $traitementpcg66['Traitementpcg66']['id'] ),
				),
				'options' => $options
			)
		);

		echo '<div class="aere">';
		echo $this->Default->button(
			'backpdo',
			array(
				'controller' => 'traitementspcgs66',
				'action'     => 'index',
				$traitementpcg66['Personnepcg66']['personne_id'],
				$traitementpcg66['Personnepcg66']['dossierpcg66_id']
			),
			array(
				'id' => 'Back',
				'label' => 'Retour au dossier'
			)
		);
		echo '</div>';

	?>
</div>
<div class="clearer"><hr /></div>