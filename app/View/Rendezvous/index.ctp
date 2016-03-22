<?php
	$this->pageTitle = 'Rendez-vous de la personne';

	$departement = Configure::read( 'Cg.departement' );
?>

<h1>Rendez-vous</h1>
<?php echo $this->element( 'ancien_dossier' );?>

	<?php if( empty( $rdvs ) ):?>
		<p class="notice">Cette personne ne possède pas encore de rendez-vous.</p>
	<?php endif;?>
            <ul class="actionMenu">
                <?php
                    echo '<li>'.$this->Xhtml->addLink(
                        'Ajouter un Rendez-vous',
                        array( 'controller' => 'rendezvous', 'action' => 'add', $personne_id ),
                        ( $this->Permissions->checkDossier( 'rendezvous', 'add', $dossierMenu ) && ( $ajoutPossible ) )

                    ).' </li>';
                ?>
            </ul>


<?php if( !empty( $rdvs ) ):?>
	<?php
		if( isset( $dossierep ) && !empty( $dossierep ) ) {
			echo '<p class="error">Ce dossier est en cours de passage en EP : '.$dossierep['StatutrdvTyperdv']['motifpassageep'].'.</p>';
		}
		if( isset( $dossiercov ) && !empty( $dossiercov ) ) {
			echo '<p class="error">Ce dossier est en cours de passage en COV: '.$dossiercov['StatutrdvTyperdv']['motifpassageep'].'.</p>';
		}
		if ( !isset( $dossiercommissionLie ) ) {
			$dossiercommissionLie = 0;
		}
	?>
<table id="listeRendezvous" class="tooltips">
	<thead>
		<tr>
			<th>Nom de la personne</th>
			<th><?php echo $departement == 93 ? 'Structure proposant le RDV' : 'Type de structure';?></th>
			<th><?php echo $departement == 93 ? 'Personne proposant le RDV' : 'Nom du prescripteur';?></th>
			<th>Permanence liée</th>
			<th>Objet du RDV</th>
			<th>Statut du RDV</th>
			<th>Date de RDV</th>
			<th>Heure de RDV</th>
			<th colspan="6" class="action">Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php
			foreach( $rdvs as $index => $rdv ) {
				$lastrdv = true;
				if( $departement != 93 ) {
					$lastrdv = ( Set::classicExtract( $rdv, 'Rendezvous.id' ) == $lastrdv_id );
				}

				// TODO: code en commun avec Criteresrdv/index.ctp
				$thematiques = Hash::extract( $rdv, 'Thematiquerdv.{n}.name' );
				$row = null;
				if( !empty( $thematiques ) ) {
					$row = '<tr>
						<th>'.__d( 'rendezvous', 'Thematiquerdv.name' ).'</th>
						<td><ul><li>'.implode( '</li><li>', $thematiques ).'</li></ul></td>
					</tr>';
				}

				$innerTable = '<table id="innerTablelisteRendezvous'.$index.'" class="innerTable">
					<tbody>
						<tr>
							<th>Objectif du RDV</th>
							<td>'.h( Set::classicExtract( $rdv, 'Rendezvous.objetrdv' ) ).'</td>
						</tr>
						<tr>
							<th>Commentaires suite au RDV</th>
							<td>'.h( Set::classicExtract( $rdv, 'Rendezvous.commentairerdv' ) ).'</td>
						</tr>
						'.$row.'
					</tbody>
				</table>';

				echo $this->Xhtml->tableCells(
					array(
						h( Set::classicExtract( $rdv, 'Personne.nom_complet' ) ),
						h( Set::classicExtract( $rdv, 'Structurereferente.lib_struc' ) ),
						h( Set::classicExtract( $rdv, 'Referent.nom_complet' ) ),
						h( Set::classicExtract( $rdv, 'Permanence.libpermanence' ) ),
						h( Set::classicExtract( $rdv, 'Typerdv.libelle' ) ),
						h( Set::classicExtract( $rdv, 'Statutrdv.libelle' ) ),
						h( date_short( Set::classicExtract( $rdv, 'Rendezvous.daterdv' ) ) ),
						h( $this->Locale->date( 'Time::short', Set::classicExtract( $rdv, 'Rendezvous.heurerdv' ) ) ),
						$this->Xhtml->viewLink(
							'Voir le Rendez-vous',
							array( 'controller' => 'rendezvous', 'action' => 'view',
							$rdv['Rendezvous']['id'] ),
							( $this->Permissions->checkDossier( 'rendezvous', 'view', $dossierMenu ) == 1 )
						),
						$this->Xhtml->editLink(
							'Editer le référent',
							array( 'controller' => 'rendezvous', 'action' => 'edit',
							$rdv['Rendezvous']['id'] ),
							( $lastrdv && ( $dossiercommissionLie== 0 ) && ( $this->Permissions->checkDossier( 'rendezvous', 'edit', $dossierMenu ) == 1 ) )
						),
						$this->Xhtml->printLink(
							'Imprimer le Rendez-vous',
							array( 'controller' => 'rendezvous', 'action' => 'impression',
							$rdv['Rendezvous']['id'] ),
							( $this->Permissions->check( 'rendezvous', 'impression', $dossierMenu ) == 1 )
						),
						$this->Xhtml->deleteLink(
							( $departement == 93 ) && Hash::get( $rdv, 'Rendezvous.has_questionnaired1pdv93' )
								? 'Attention ce RDV est lié à un questionnaire D1, si vous le supprimez, vous supprimez également le D1'
								: 'Supprimer le rendez-vous',
							array( 'controller' => 'rendezvous', 'action' => 'delete',
							$rdv['Rendezvous']['id'] ),
							(
								$lastrdv
								&& ( $dossiercommissionLie== 0 )
								&& ( $this->Permissions->checkDossier( 'rendezvous', 'delete', $dossierMenu ) == 1 )
							)
						),
						$this->Xhtml->fileLink(
							'Lier des fichiers',
							array( 'controller' => 'rendezvous', 'action' => 'filelink',
							$rdv['Rendezvous']['id'] ),
							( $this->Permissions->checkDossier( 'rendezvous', 'filelink', $dossierMenu )  )
						),
						h( '('.Set::classicExtract( $rdv, 'Fichiermodule.nb_fichiers_lies' ).')' ),
						array( $innerTable, array( 'class' => 'innerTableCell noprint' ) ),
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
			}
		?>
	</tbody>
</table>
<?php  endif;?>