<?php
	$this->pageTitle = 'Rendez-vous de la personne';
	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id) );
?>

<div class="with_treemenu">
	<h1>Rendez-vous</h1>
		<?php if( empty( $rdvs ) ):?>
			<p class="notice">Cette personne ne possède pas encore de rendez-vous.</p>
		<?php endif;?>
		<ul class="actionMenu">
			<?php
				echo '<li>'.$xhtml->addLink(
					'Ajouter un Rendez-vous',
					array( 'controller' => 'rendezvous', 'action' => 'add', $personne_id )
				).' </li>';
			?>
		</ul>

	<?php if( !empty( $rdvs ) ):?>
		<?php
			if( isset( $dossierep ) && !empty( $dossierep ) ) {
			echo '<p class="error">Ce dossier est en cours de passage en EP : '.$dossierep['StatutrdvTyperdv']['motifpassageep'].'.</p>';
			}
			if ( !isset( $dossierepLie ) ) {
				$dossierepLie = 0;
			}
		?>
	<table class="tooltips">
		<thead>
			<tr>
				<th>Nom de la personne</th>
				<th>Type de structure</th>
				<th>Nom du prescripteur</th>
				<th>Permanence liée</th>
				<th>Objet du RDV</th>
				<th>Statut du RDV</th>
				<th>Date de RDV</th>
				<th>Heure de RDV</th>
				<th>Objectif du RDV</th>
				<th>Commentaires suite au RDV</th>
				<th colspan="6" class="action">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php

				foreach( $rdvs as $rdv ) {
					echo $xhtml->tableCells(
						array(
							h( Set::classicExtract( $rdv, 'Personne.nom_complet' ) ),
							h( Set::classicExtract( $rdv, 'Structurereferente.lib_struc' ) ),
							h( Set::classicExtract( $rdv, 'Referent.nom_complet' ) ),
							h( Set::classicExtract( $rdv, 'Permanence.libpermanence' ) ),
							h( Set::classicExtract( $rdv, 'Typerdv.libelle' ) ),
							h( Set::classicExtract( $rdv, 'Statutrdv.libelle' ) ),
							h( date_short( Set::classicExtract( $rdv, 'Rendezvous.daterdv' ) ) ),
							h( $locale->date( 'Time::short', Set::classicExtract( $rdv, 'Rendezvous.heurerdv' ) ) ),
							h( Set::classicExtract( $rdv, 'Rendezvous.objetrdv' ) ),
							h( Set::classicExtract( $rdv, 'Rendezvous.commentairerdv' ) ),
							$xhtml->viewLink(
								'Voir le Rendez-vous',
								array( 'controller' => 'rendezvous', 'action' => 'view',
								$rdv['Rendezvous']['id'] ),
								( $permissions->check( 'rendezvous', 'view' ) == 1 )
							),
							$xhtml->editLink(
								'Editer le référent',
								array( 'controller' => 'rendezvous', 'action' => 'edit',
								$rdv['Rendezvous']['id'] ),
								( ( Set::classicExtract( $rdv, 'Rendezvous.id' ) == $lastrdv_id ) && ( $dossierepLie == 0 ) && ( $permissions->check( 'rendezvous', 'edit' ) == 1 ) )
							),
							$xhtml->printLink(
								'Imprimer le Rendez-vous',
								array( 'controller' => 'rendezvous', 'action' => 'impression',
								$rdv['Rendezvous']['id'] ),
								( $permissions->check( 'rendezvous', 'impression' ) == 1 )
							),
							$xhtml->deleteLink(
								'Supprimer le Rendez-vous',
								array( 'controller' => 'rendezvous', 'action' => 'delete',
								$rdv['Rendezvous']['id'] ),
								( ( Set::classicExtract( $rdv, 'Rendezvous.id' ) == $lastrdv_id ) && ( $dossierepLie == 0 ) && ( $permissions->check( 'rendezvous', 'delete' ) == 1 ) )
							),
							$xhtml->fileLink(
								'Lier des fichiers',
								array( 'controller' => 'rendezvous', 'action' => 'filelink',
								$rdv['Rendezvous']['id'] ),
								( $permissions->check( 'rendezvous', 'filelink' )  )
							),
							h( '('.Set::classicExtract( $rdv, 'Fichiermodule.nb_fichiers_lies' ).')' )
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