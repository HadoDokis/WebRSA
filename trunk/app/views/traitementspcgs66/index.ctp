<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Traitements des PDOs';?>

<?php echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>

<div class="with_treemenu">
	<?php
		echo $xhtml->tag(
			'h1',
			$this->pageTitle = __d( 'traitementpcg66', "Traitementspcgs66::{$this->action}", true ).' '.$nompersonne
		);
// debug( $searchOptions );
		echo $default2->search(
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
			echo '<p class="notice"> Aucun traitement présent pour ce dossier</p>';
			echo '<li>'.$xhtml->addLink(
				__d('Traitementpcg66','Traitementpcg66.add',true),
				array( 'controller' => 'traitementspcgs66', 'action' => 'add', $personnepcg66_id )
			).' </li>';
		}
		else{
			$pagination = $xpaginator2->paginationBlock( 'Traitementpcg66', Set::merge( $this->params['pass'], $this->params['named'] ) );

			echo $default2->index(
				$listeTraitements,
				array(
					'Situationpdo.libelle' => array( 'type'=>'string' ),
					'Traitementpcg66.descriptionpdo_id' => array( 'type'=>'string' ),
					'Traitementpcg66.datedepart',
					'Traitementpcg66.datereception',
					'Traitementpcg66.daterevision',
					'Traitementpcg66.dateecheance',
					'Traitementpcg66.typetraitement'
				),
				array(
					'actions' => array(
						'Traitementspcgs66::view',
						
						'Traitementspcgs66::edit' => array( 'disabled' => '\'#Traitementpcg66.annule#\' == \'O\' || \''.$permissions->check( 'traitementspcgs66', 'edit' ).'\' != \'1\'' ),
						
						'Traitementspcgs66::print' => array( 'label' => 'Fiche de calcul', 'url' => array( 'controller' => 'traitementspcgs66', 'action'=>'printFicheCalcul' ), 'disabled' => '\'#Traitementpcg66.annule#\' == \'O\' || \'#Traitementpcg66.typetraitement#\' != \'revenu\' || \''.$permissions->check( 'traitementspcgs66', 'printfichecalcul' ).'\' != \'1\'' ),

						'Traitementspcgs66::printModeleCourrier' => array( 'label' => 'Imprimer courrier', 'url' => array( 'controller' => 'traitementspcgs66', 'action'=>'printModeleCourrier' ), 'disabled' => '\'#Traitementpcg66.annule#\' == \'O\' || \'#Traitementpcg66.typetraitement#\' != \'courrier\' || \''.$permissions->check( 'traitementspcgs66', 'printModeleCourrier' ).'\' != \'1\'' ),
						
						'Traitementspcgs66::reverseDO' => array( 'label' => 'Reverser dans DO', 'condition' => '(\'#Traitementpcg66.reversedo#\' != 1)', 'disabled' => '\'#Traitementpcg66.annule#\' == \'O\' || \'#Traitementpcg66.typetraitement#\' != \'revenu\'', 'confirm' => 'Confirmer la répercussion de la fiche de calcul ?'  ),
						'Traitementspcgs66::deverseDO' => array( 'label' => 'Déverser de la DO', 'condition' => '(\'#Traitementpcg66.reversedo#\' == 1)', 'disabled' => '\'#Traitementpcg66.annule#\' == \'O\' || \'#Traitementpcg66.typetraitement#\' != \'revenu\'', 'confirm' => 'Confirmer la non répercussion de la fiche de calcul ?'  ),

						'Traitementspcgs66::clore' => array( 'disabled' => '\'#Traitementpcg66.clos#\' == \'O\' || \'#Traitementpcg66.annule#\' == \'O\' || \''.$permissions->check( 'traitementspcgs66', 'clore' ).'\' != \'1\'', 'confirm' => 'Confirmer la clôture de ce traitement ?' ),
						
						'Traitementspcgs66::cancel' => array( 'disabled' => '\'#Traitementpcg66.annule#\' == \'O\' || \''.$permissions->check( 'traitementspcgs66', 'cancel' ).'\' != \'1\'', 'confirm' => 'Confirmer l\'annulation de ce traitement ?' ),
						
						'Traitementspcgs66::delete' => array( 'disabled' => '\'#Traitementpcg66.annule#\' == \'O\' || \''.$permissions->check( 'traitementspcgs66', 'delete' ).'\' != \'1\'', 'confirm' => 'Confirmer la suppression de ce traitement ?' ),
						///FIXME: à remettre quand on aura la fonction et qu'on saura quoi imprimer
					),
					'add' => array( 'Traitementpdo.add' => array( 'controller'=>'traitementspcgs66', 'action'=>'add', $personnepcg66_id ) ),
					'options' => $options,
					'class' => 'default2'
				)
			);
debug($listeTraitements);
		}
		if( !empty( $listeTraitements ) ){
			echo '<div class="aere">';
			echo $default->button(
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
</div>
<div class="clearer"><hr /></div>