<?php
	/*
	* INFO: Parfois la variable a le nom personne_id, parfois personneId
	* 	On met tout le monde d'accord (en camelcase)
	*/

	if( isset( ${Inflector::variable( 'personne_id' )} ) ) {
		$personne_id = ${Inflector::variable( 'personne_id' )};
	}

	if( isset( ${Inflector::variable( 'foyer_id' )} ) ) {
		$foyer_id = ${Inflector::variable( 'foyer_id' )};
	}

	/*
	* Recherche du dossier à afficher
	*/

	if( isset( $personne_id ) ) {
		$dossier = $this->requestAction( array( 'controller' => 'dossiers', 'action' => 'menu' ), array( 'personne_id' => $personne_id ) );
	}
	else if( isset( $foyer_id ) ) {
		$dossier = $this->requestAction( array( 'controller' => 'dossiers', 'action' => 'menu' ), array( 'foyer_id' => $foyer_id ) );
	}
	else if( isset( $id ) ) {
		$dossier = $this->requestAction( array( 'controller' => 'dossiers', 'action' => 'menu' ), array( 'id' => $id ) );
	}
?>

<div class="treemenu">

		<h2 >
			<?php if( Configure::read( 'UI.menu.large' ) ):?>
			<?php
				echo $this->Xhtml->link(
					$this->Xhtml->image( 'icons/bullet_toggle_plus2.png', array( 'alt' => '', 'title' => 'Étendre le menu ', 'width' => '12px' ) ),
					'#',
					array( 'onclick' => 'treeMenuExpandsAll( \''.Router::url( '/', true ).'\' ); return false;', 'id' => 'treemenuToggleLink' ),
					false,
					false
				);
			?>
			<?php endif;?>

			<?php
				echo $this->Xhtml->link( 'Dossier RSA '.$dossier['Dossier']['numdemrsa'], array( 'controller' => 'dossiers', 'action' => 'view', $dossier['Dossier']['id'] ) ).
				( $dossier['Dossier']['locked'] ? $this->Xhtml->image( 'icons/lock.png', array( 'alt' => '', 'title' => 'Dossier verrouillé' ) ) : null ).
				$this->Gestionanomaliebdd->foyerErreursPrestationsAllocataires( $dossier ).
				$this->Gestionanomaliebdd->foyerPersonnesSansPrestation( $dossier );
			?>
		</h2>

<?php $etatdosrsaValue = Set::classicExtract( $dossier, 'Situationdossierrsa.etatdosrsa' );?>

<?php
	if( isset( $personne_id ) ) {
		$personneDossier = Set::extract( $dossier, '/Foyer/Personne' );
		foreach( $personneDossier as $i => $personne ) {
			if( $personne_id == Set::classicExtract( $personne, 'Personne.id' ) ) {
				$personneDossier = Set::classicExtract( $personne, 'Personne.qual' ).' '.Set::classicExtract( $personne, 'Personne.nom' ).' '.Set::classicExtract( $personne, 'Personne.prenom' );
			}
		}

		if( Configure::read( 'UI.menu.lienDemandeur' ) ) {
			echo $this->Xhtml->tag(
				'p',
				$this->Xhtml->link( $personneDossier, sprintf( Configure::read( 'UI.menu.lienDemandeur' ), $dossier['Dossier']['matricule'] ), array(  'class' => 'external' ) ),
				array( 'class' => 'etatDossier' ),
				false,
				false
			);
		}
		else {
			echo $this->Xhtml->tag( 'p', $personneDossier, array( 'class' => 'etatDossier' ) );
		}
	}
?>


<p class="etatDossier">
<?php
    $etatdosrsa = ClassRegistry::init( 'Option' )->etatdosrsa();
//     debug($this->viewVars);
    echo ( isset( $etatdosrsa[$etatdosrsaValue] ) ? $etatdosrsa[$etatdosrsaValue] : 'Non défini' );?>
</p>

	<p class="etatDossier">
	<?php
		$numcaf = $dossier['Dossier']['matricule'];
		$fonorg = $dossier['Dossier']['fonorg'];

		if( !empty( $numcaf ) && !empty( $fonorg ) ) {
			echo 'N°'.( isset( $fonorg ) ? $fonorg : '' ).' : '.( isset( $numcaf ) ? $numcaf : '' );
		}
		else {
			echo '';
		}
	?>
	</p>

	<?php
		$itemsAllocataires = array();
		foreach( $dossier['Foyer']['Personne'] as $personne ) {
			$subAllocataire = array( 'url' => array( 'controller' => 'personnes', 'action' => 'view', $personne['id'] ) );

			if( $personne['Prestation']['rolepers'] == 'DEM' || $personne['Prestation']['rolepers'] == 'CJT' ) {
				if( Configure::read( 'Cg.departement' ) == '66' ) {
					$subAllocataire['Mémos'] = array( 'url' => array( 'controller' => 'memos', 'action' => 'index', $personne['id'] ) );
				}

				// Droit
				$subAllocataire['Droit'] = array(
					'DSP d\'origine' => array( 'url' => array( 'controller' => 'dsps', 'action' => 'view', $personne['id'] ) ),
					( Configure::read( 'Cg.departement' ) == 66 ? 'DSPs mises à jour' : 'DSPs CG' ) => array( 'url' => array( 'controller' => 'dsps', 'action' => 'histo', $personne['id'] ) ),
				);

				if (Configure::read( 'nom_form_ci_cg' ) == 'cg58' ) {
					$subAllocataire['Droit']['Consultation dossier PDO'] = array( 'url' => array( 'controller' => 'propospdos', 'action' => 'index', $personne['id'] ) );
					$subAllocataire['Droit']['Orientation'] = array( 'url' => array( 'controller' => 'orientsstructs', 'action' => 'index', $personne['id'] ) );
				}
				else if (Configure::read( 'nom_form_ci_cg' ) == 'cg66' ) {
					$subAllocataire['Droit']['Orientation'] = array( 'url' => array( 'controller' => 'orientsstructs', 'action' => 'index', $personne['id'] ) );
					$subAllocataire['Droit']['Traitements PCG'] = array( 'url' => array( 'controller' => 'traitementspcgs66', 'action' => 'index', $personne['id'] ) );
				}
				else {
					$subAllocataire['Droit']['Orientation'] = array( 'url' => array( 'controller' => 'orientsstructs', 'action' => 'index', $personne['id'] ) );
					$subAllocataire['Droit']['Consultation dossier PDO'] = array( 'url' => array( 'controller' => 'propospdos', 'action' => 'index', $personne['id'] ) );
				}

				// Accompagnement du parcours
				$subAllocataire['Accompagnement du parcours'] = array(
					'Chronologie parcours' => array( 'url' => '#' ),
					'Référent du parcours' => array( 'url' => array( 'controller' => 'personnes_referents', 'action' => 'index', $personne['id'] ) ),
					'Gestion RDV' => array( 'url' => array( 'controller' => 'rendezvous', 'action' => 'index', $personne['id'] ) ),
				);

				if( Configure::read( 'nom_form_bilan_cg' ) == 'cg66' ) {
					$subAllocataire['Accompagnement du parcours']['Bilan du parcours'] = array( 'url' => array( 'controller' => 'bilansparcours66', 'action' => 'index', $personne['id'] ) );
				}

				$contratcontroller = 'contratsinsertion';
				if( Configure::read( 'Cg.departement' ) == 93 ) {
					$contratcontroller = 'cers93';
				}
				$subAllocataire['Accompagnement du parcours']['Contrats'] = array(
					'url' => '#',
					'CER' => array( 'url' => array( 'controller' => $contratcontroller, 'action' => 'index', $personne['id'] ) ),
					'CUI' => array( 'url' => array( 'controller' => 'cuis', 'action' => 'index', $personne['id'] ) ),
				);
				$subAllocataire['Accompagnement du parcours']['Actualisation suivi'] = array(
					'url' => '#',
					'Entretiens' => array( 'url' => array( 'controller' => 'entretiens', 'action' => 'index', $personne['id'] ) ),
				);

				if( Configure::read( 'Cg.departement' ) == 93 ) {
					$subAllocataire['Accompagnement du parcours']['Actualisation suivi']['Relances'] = array( 'url' => array( 'controller' => 'relancesnonrespectssanctionseps93', 'action' => 'index', $personne['id'] ) );
				}

				$subAllocataire['Accompagnement du parcours']['Historique des EPs'] = array(
					'url' => array( 'controller' => 'historiqueseps', 'action' => 'index', $personne['id'] ),
				);

				$subAllocataire['Accompagnement du parcours']['Offre d\'insertion'] = array(
					'url' => '#',
					( Configure::read( 'ActioncandidatPersonne.suffixe' ) == 'cg93' ? 'Fiche de liaison' : 'Fiche de candidature' ) => array(
						'url' => array( 'controller' => 'actionscandidats_personnes', 'action' => 'index', $personne['id'] )
					)
				);

				$subAllocataire['Accompagnement du parcours']['Aides financières'] = array(
					'url' => '#',
					'Aides / APRE' => array(
						'url' => array( 'controller' => 'apres'.Configure::read( 'Apre.suffixe' ), 'action' => 'index', $personne['id'] )
					)
				);

				if( Configure::read( 'Cg.departement' ) != 66 ) {
					$subAllocataire['Accompagnement du parcours']['Mémos'] = array(
						'url' => array( 'controller' => 'memos', 'action' => 'index', $personne['id'] ),
					);
				}

				if( Configure::read( 'Cg.departement' ) == 93 ) {
					$subAllocataire['Accompagnement du parcours'][__d( 'historiqueemploi', 'Historiqueemplois::index' )] = array(
						'url' => array( 'controller' => 'historiqueemplois', 'action' => 'index', $personne['id'] ),
					);
				}

				// Situation financière
				$subAllocataire['Situation financière'] = array(
					'url' => '#',
					'Ressources' => array( 'url' => array( 'controller' => 'ressources', 'action' => 'index', $personne['id'] ) ),
				);
			}

			$itemsAllocataires[implode( ' ', array( '(', $personne['Prestation']['rolepers'], ')', $personne['qual'], $personne['nom'], $personne['prenom'] ) )] = $subAllocataire;
		}

		$items = array(
			'Composition du foyer' => array(
				'url' => array( 'controller' => 'personnes', 'action' => 'index', $dossier['Foyer']['id'] ),
			)
			+ $itemsAllocataires,
			'Informations foyer' => array(
				'Historique du droit' => array( 'url' => array( 'controller' => 'situationsdossiersrsa', 'action' => 'index', $dossier['Dossier']['id'] ) ),
				'Détails du droit RSA' => array( 'url' => array( 'controller' => 'detailsdroitsrsa', 'action' => 'index', $dossier['Dossier']['id'] ) ),
				'Adresses' => array( 'url' => array( 'controller' => 'adressesfoyers', 'action' => 'index', $dossier['Foyer']['id'] ) ),
				'Evénements' => array( 'url' => array( 'controller' => 'evenements', 'action' => 'index', $dossier['Foyer']['id'] ) ),
				'Modes de contact' => array( 'url' => array( 'controller' => 'modescontact', 'action' => 'index', $dossier['Foyer']['id'] ) ),
				'Avis PCG droit rsa' => array( 'url' => array( 'controller' => 'avispcgdroitrsa', 'action' => 'index', $dossier['Dossier']['id'] ) ),
				'Informations financières' => array( 'url' => array( 'controller' => 'infosfinancieres', 'action' => 'index', $dossier['Dossier']['id'] ) ),
				'Liste des Indus' => array( 'url' => array( 'controller' => 'indus', 'action' => 'index', $dossier['Dossier']['id'] ) ),
				'Suivi instruction du dossier' => array( 'url' => array( 'controller' => 'suivisinstruction', 'action' => 'index', $dossier['Dossier']['id'] ) ),
			)
		);

		// Dossier PCG (CG 66)
		if( Configure::read( 'Cg.departement' ) == 66 ) {
			$items['Dossier PCG'] = array( 'url' => array( 'controller' => 'dossierspcgs66', 'action' => 'index', $dossier['Foyer']['id'] ) );
		}

		$items['Synthèse du parcours d\'insertion'] = array( 'url' => array( 'controller' => 'suivisinsertion', 'action' => 'index', $dossier['Dossier']['id'] ) );
		$items['Modification Dossier RSA'] = array( 'url' => array( 'controller' => 'dossiers', 'action' => 'edit', $dossier['Dossier']['id'] ) );

		// Préconisation d'orientation
		if( Configure::read( 'Cg.departement' ) != 58 ) {
			$itemsPreconisations = array();

			if( !empty( $dossier['Foyer']['Personne'] ) ) {
				foreach( $dossier['Foyer']['Personne'] as $personnes ) {
					if( in_array( $personnes['Prestation']['rolepers'], array( 'DEM', 'CJT' ) ) ) {
						$itemsPreconisations[$personnes['qual'].' '.$personnes['nom'].' '.$personnes['prenom']] = array( 'url' => array( 'controller' => 'dossierssimplifies', 'action' => 'edit', $personnes['id'] ) );
					}
				}
			}

			$items['Préconisation d\'orientation'] = $itemsPreconisations;
		}

		echo $this->Menu->make( $items );
	?>
</div>