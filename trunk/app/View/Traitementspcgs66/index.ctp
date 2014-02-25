<?php
	$this->pageTitle = 'Traitements des PDOs';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
	}
?>
<?php
	echo $this->Xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'traitementpcg66', "Traitementspcgs66::{$this->action}" ).' '.$nompersonne
	);
// debug( $searchOptions );
	echo $this->Default2->search(
		array(
			'Personnepcg66.dossierpcg66_id' => array( 'domain' => 'traitementpcg66', 'value' => $dossierpcgId )
		),
		array(
			'options' => $searchOptions
		)
	);

	if( empty( $dossierpcg66_id ) ){
		echo '<p class="notice"> Veuillez sélectionner un dossier afin d\'afficher les traitements</p>';
	}
	else if( empty( $listeTraitements ) ) {
		echo '<ul class="actionMenu"><li>'.$this->Xhtml->addLink(
			__d('Traitementpcg66','Traitementpcg66.add',true),
			array( 'controller' => 'traitementspcgs66', 'action' => 'add', $personnepcg66_id )
		).' </li></ul>';
		echo '<p class="notice"> Aucun traitement présent pour ce dossier</p>';
	}
	else{
		$pagination = $this->Xpaginator2->paginationBlock( 'Traitementpcg66', Set::merge( $this->request->params['pass'], $this->request->params['named'] ) );

		echo $this->Default2->index(
			$listeTraitements,
			array(
				'Situationpdo.libelle' => array( 'type'=>'string' ),
				'Traitementpcg66.descriptionpdo_id' => array( 'type'=>'string' ),
				'Traitementpcg66.datedepart',
				'Traitementpcg66.datereception',
				'Traitementpcg66.daterevision',
				'Traitementpcg66.dateecheance',
				'Traitementpcg66.typetraitement',
				'Traitementpcg66.dateenvoicourrier'
			),
			array(
				'actions' => array(
					'Traitementspcgs66::view',

					'Traitementspcgs66::edit' => array( 'disabled' => '\'#Traitementpcg66.annule#\' == \'O\' || \''.$this->Permissions->checkDossier( 'traitementspcgs66', 'edit', $dossierMenu ).'\' != \'1\'' ),

					'Traitementspcgs66::print' => array( 'label' => 'Fiche de calcul', 'url' => array( 'controller' => 'traitementspcgs66', 'action'=>'printFicheCalcul' ), 'disabled' => '\'#Traitementpcg66.annule#\' == \'O\' || \'#Traitementpcg66.typetraitement#\' != \'revenu\' || \''.$this->Permissions->checkDossier( 'traitementspcgs66', 'printfichecalcul', $dossierMenu ).'\' != \'1\'' ),

					'Traitementspcgs66::printModeleCourrier' => array( 'label' => 'Imprimer courrier', 'url' => array( 'controller' => 'traitementspcgs66', 'action'=>'printModeleCourrier' ), 'disabled' => '\'#Traitementpcg66.annule#\' == \'O\' || \'#Traitementpcg66.typetraitement#\' != \'courrier\' || \''.$this->Permissions->checkDossier( 'traitementspcgs66', 'printModeleCourrier', $dossierMenu ).'\' != \'1\'' ),
						
					'Traitementspcgs66::envoiCourrier' => array( 'label' => 'Envoi courrier', 'disabled' => 'trim(\'#Traitementpcg66.dateenvoicourrier#\') != \'\' || \'#Traitementpcg66.annule#\' == \'O\' || \'#Traitementpcg66.typetraitement#\' != \'courrier\' || \''.$this->Permissions->checkDossier( 'traitementspcgs66', 'envoiCourrier', $dossierMenu ).'\' != \'1\''  ),

					'Traitementspcgs66::reverseDO' => array( 'label' => 'Reverser dans DO', 'condition' => '(\'#Traitementpcg66.reversedo#\' != 1)', 'disabled' => '\'#Traitementpcg66.annule#\' == \'O\' || \'#Traitementpcg66.typetraitement#\' != \'revenu\'', 'confirm' => 'Confirmer la répercussion de la fiche de calcul ?'  ),
					'Traitementspcgs66::deverseDO' => array( 'label' => 'Déverser de la DO', 'condition' => '(\'#Traitementpcg66.reversedo#\' == 1)', 'disabled' => '\'#Traitementpcg66.annule#\' == \'O\' || \'#Traitementpcg66.typetraitement#\' != \'revenu\'', 'confirm' => 'Confirmer la non répercussion de la fiche de calcul ?'  ),

					'Traitementspcgs66::clore' => array( 'disabled' => '\'#Traitementpcg66.clos#\' == \'O\' || \'#Traitementpcg66.annule#\' == \'O\' || \''.$this->Permissions->checkDossier( 'traitementspcgs66', 'clore', $dossierMenu ).'\' != \'1\'', 'confirm' => 'Confirmer la clôture de ce traitement ?' ),

					'Traitementspcgs66::cancel' => array( 'label' => 'Annuler', 'condition' => '(\'#Traitementpcg66.annule#\' != \'O\')', 'disabled' => '\'#Traitementpcg66.annule#\' == \'O\' || \''.$this->Permissions->checkDossier( 'traitementspcgs66', 'cancel', $dossierMenu ).'\' != \'1\'' ),
					'Traitementspcgs66::canceled' => array( 'label' => 'Annulé', 'condition' => '(\'#Traitementpcg66.annule#\' == \'O\')', 'disabled' => '\'#Traitementpcg66.annule#\' == \'O\'' ),

					'Traitementspcgs66::delete' => array( 'disabled' => '\'#Traitementpcg66.annule#\' == \'O\' || \''.$this->Permissions->checkDossier( 'traitementspcgs66', 'delete', $dossierMenu ).'\' != \'1\'', 'confirm' => 'Confirmer la suppression de ce traitement ?' ),
					///FIXME: à remettre quand on aura la fonction et qu'on saura quoi imprimer
				),
				'add' => array( 'Traitementpdo.add' => array( 'controller'=>'traitementspcgs66', 'action'=>'add', $personnepcg66_id ) ),
				'options' => $options,
				'class' => 'default2',
				'tooltip' => array( 'Traitementpcg66.motifannulation', 'Traitementpcg66.dtdebutperiode', 'Traitementpcg66.datefinperiode' )
			)
		);

	}

	if( !empty( $personnepcg66 ) ){
		echo '<div class="aere">';
		echo $this->Default->button(
			'backpdo',
			array(
				'controller' => 'dossierspcgs66',
				'action'     => 'edit',
				$personnepcg66['Personnepcg66']['dossierpcg66_id']
			),
			array(
				'id' => 'Back',
				'label' => 'Retour au dossier'
			)
		);
		echo '</div>';
	}


?>