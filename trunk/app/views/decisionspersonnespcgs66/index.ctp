<?php
	$this->pageTitle = 'Propositions de décision';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>

<?php echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>

<div class="with_treemenu">
	<?php
		echo $xhtml->tag(
			'h1',
			$this->pageTitle = __d( 'decisionpersonnepcg66', "Decisionspersonnespcgs66::{$this->action}", true ).' '.$nompersonne
		);

		echo $default2->index(
			$listeDecisions,
			array(
				'Personnepcg66Situationpdo.Situationpdo.libelle',
				'Decisionpdo.libelle',
				'Decisionpersonnepcg66.datepropositions',
				'Decisionpersonnepcg66.commentaire'
			),
			array(
				'actions' => array(
					'Decisionspersonnespcgs66::view',
					'Decisionspersonnespcgs66::edit' /*=> array( 'disabled' => '\''.$etatdossierpcg.'\' == \'attval\' || \''.$etatdossierpcg.'\' == \'attpj\' || \''.$etatdossierpcg.'\' == \'dossiertraite\' ' )*/,
					'Decisionspersonnespcgs66::print' => array( 'controller' => 'decisionspersonnespcgs66', 'action' => 'gedooo' ),
					'Decisionspersonnespcgs66::delete'
				),
				'add' => array(
					'Decisionpersonnepcg66.add' => array( 'controller'=>'decisionspersonnespcgs66', 'action'=>'add', $personnepcg66_id )/*,
					'disabled' => in_array( $etatdossierpcg, array( 'attval', 'attpj', 'dossiertraite' ) )*/
				),
				'options' => $options
			)
		);

		echo '<div class="aere">';
		echo $default->button(
			'backpdo',
			array(
				'controller' => 'dossierspcgs66',
				'action'     => 'edit',
				$dossierpcg66_id
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