<?php
    $domain = "actioncandidat_personne_".Configure::read( 'ActioncandidatPersonne.suffixe' );
    echo $this->element( 'dossier_menu', array( 'id' => $dossierId, 'personne_id' => $personne_id ) );

	$typeFiche = '';
	if( Configure::read( 'Cg.departement' ) == 66 ){
		$typeFiche = 'de candidature';
	}
	else if( Configure::read( 'Cg.departement' ) != 66 ){
		$typeFiche = 'de liaison';
	}
?>

<div class="with_treemenu">

    <?php

        echo $xhtml->tag(
            'h1',
            $this->pageTitle = __d( $domain, "ActionscandidatsPersonnes::{$this->action}", true )
        );
    ?>

    <?php
        if( ( $orientationLiee == 0 ) ) {
            echo '<p class="error">Cette personne ne possède pas d\'orientation. Impossible de créer une fiche '.$typeFiche.'</p>';
        }
        else if( ( $referentLie == 0 ) ) {
            echo '<p class="error">Cette personne ne possède pas de référent lié. Impossible de créer une fiche '.$typeFiche.'</p>';
        }
	?>
		<?php if( empty( $actionscandidats_personnes ) ):?>
			<p class="notice">Cette personne ne possède pas de fiche <?php echo $typeFiche;?></p>
		<?php endif;?>
		
		<?php if( $orientationLiee != 0 && $referentLie != 0 ):?>
			<ul class="actionMenu">
				<?php
					echo '<li>'.$xhtml->addLink(
						'Ajouter une fiche de candidature',
						array( 'controller' => 'actionscandidats_personnes', 'action' => 'add', $personne_id )
					).' </li>';
				?>
			</ul>
		<?php endif;?>
		<?php if( !empty( $actionscandidats_personnes ) ):?>
				<table class="tooltips">
					<thead>
						<tr>
							<th>Intitulé de l'action</th>
							<th>Nom du prescripteur</th>
							<th>Libellé du partenaire</th>
							<th>Date de la signature</th>
							<th>Position de la fiche</th>
							<th>Sortie le</th>
							<th>Motif de la sortie</th>
							<th colspan="6" class="action">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach( $actionscandidats_personnes as $actioncandidat_personne ){
								$nbFichiersLies = 0;
								$nbFichiersLies = ( isset( $actioncandidat_personne['Fichiermodule'] ) ? count( $actioncandidat_personne['Fichiermodule'] ) : 0 );

								echo $xhtml->tableCells(
									array(
										h( Set::classicExtract( $actioncandidat_personne, 'Actioncandidat.name' ) ),
										h( Set::classicExtract( $actioncandidat_personne, 'Referent.nom_complet' ) ),
										h( Set::classicExtract( $actioncandidat_personne, 'Actioncandidat.Contactpartenaire.Partenaire.libstruc' ) ),
										h( date_short( Set::classicExtract( $actioncandidat_personne, 'ActioncandidatPersonne.datesignature' ) ) ),
										h( Set::classicExtract( $options['ActioncandidatPersonne']['positionfiche'], Set::classicExtract( $actioncandidat_personne, 'ActioncandidatPersonne.positionfiche' ) ) ),
										h( date_short( Set::classicExtract( $actioncandidat_personne, 'ActioncandidatPersonne.sortiele' ) ) ),
										h( Set::classicExtract( $actioncandidat_personne, 'Motifsortie.name' ) ),

										$xhtml->viewLink(
											'Voir la fiche de candidature',
											array( 'controller' => 'actionscandidats_personnes', 'action' => 'view',
											$actioncandidat_personne['ActioncandidatPersonne']['id'] ),
											( $permissions->check( 'actionscandidats_personnes', 'view' ) == 1 )
										),
										$xhtml->editLink(
											'Editer la fiche de candidature',
											array( 'controller' => 'actionscandidats_personnes', 'action' => 'edit',
											$actioncandidat_personne['ActioncandidatPersonne']['id'] ),
											(
												( $permissions->check( 'actionscandidats_personnes', 'edit' ) == 1 )
												&& ( Set::classicExtract( $actioncandidat_personne, 'ActioncandidatPersonne.positionfiche' ) != 'annule' )
											)
										),
										$xhtml->cancelLink(
											'Annuler la fiche de candidature',
											array( 'controller' => 'actionscandidats_personnes', 'action' => 'cancel',
											$actioncandidat_personne['ActioncandidatPersonne']['id'] ),
											(
												( $permissions->check( 'actionscandidats_personnes', 'cancel' ) == 1 )
												&& ( Set::classicExtract( $actioncandidat_personne, 'ActioncandidatPersonne.positionfiche' ) != 'annule' )
											)
										),
										$xhtml->printLink(
											'Imprimer la fiche de candidature',
											array( 'controller' => 'actionscandidats_personnes', 'action' => 'printFiche',
											$actioncandidat_personne['ActioncandidatPersonne']['id'] ),
											(
												( $permissions->check( 'actionscandidats_personnes', 'printFiche' ) == 1 )
												&& ( Set::classicExtract( $actioncandidat_personne, 'ActioncandidatPersonne.positionfiche' ) != 'annule' )
											)
										),
										$xhtml->fileLink(
											'Lier des fichiers',
											array( 'controller' => 'actionscandidats_personnes', 'action' => 'filelink',
											$actioncandidat_personne['ActioncandidatPersonne']['id'] ),
											(
												( $permissions->check( 'actionscandidats_personnes', 'filelink' ) == 1 )
												&& ( Set::classicExtract( $actioncandidat_personne, 'ActioncandidatPersonne.positionfiche' ) != 'annule' )
											)
										),
										h( '('.$nbFichiersLies.')' )
									),
									array( 'class' => 'odd' ),
									array( 'class' => 'even' )
								);
							}

						?>
					</tbody>
				</table>
	<?php  endif;?>
</div>
<div class="clearer"><hr /></div>