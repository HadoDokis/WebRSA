<h1><?php
echo $this->pageTitle = 'Dossiers PCGs concernant le '.Hash::get( $rolepers, Hash::get( $personneDem, 'Prestation.rolepers' ) ).' : '.Hash::get( $qual, Hash::get( $personneDem, 'Personne.qual' ) ).' '.Hash::get( $personneDem, 'Personne.nom' ).' '.Hash::get( $personneDem, 'Personne.prenom' ); ?></h1>
<?php if( $this->Permissions->checkDossier( 'dossierspcgs66', 'edit', $dossierMenu ) ):?>
	<ul class="actionMenu">
		<?php
			echo '<li>'.$this->Xhtml->addLink(
				'Ajouter un dossier',
				array( 'controller' => 'dossierspcgs66', 'action' => 'add', $foyer_id )
			).' </li>';
		?>
	</ul>
<?php endif;?>
<?php if( empty( $dossierspcgs66 ) ):?>
	<p class="notice">Ce foyer ne possède pas encore de dossier PCG.</p>
<?php endif;?>

<?php if( !empty( $dossierspcgs66 ) ):?>
<table class="tooltips default2" id="searchResults">
	<thead>
		<tr>
			<th>Type de PDO</th>
			<th>Date de réception de la PDO</th>
			<th>Gestionnaire du dossier</th>
			<th>Etat du dossier PDO</th>
			<th>Motifs de la personne</th>
<!-- 					<th>Dossier complet ?</th> -->
			<th>Décision</th>
			<th colspan="4" class="action">Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach( $dossierspcgs66 as $index => $dossierpcg66 ):?>
			<?php
                // Récupération des dates de transmission des dossiers tranmis à l'OP
                $dates =  array_values( Hash::filter( Hash::extract( $dossierpcg66, 'Decisiondossierpcg66.{n}.datetransmissionop' ) ) );
                $datetransmissionfinale = !empty( $dates ) ?  $dates[0] : null;

                // Liste des organismes auxquels on transmet le dossier
                $orgs =  array_values( Hash::filter( Hash::extract( $dossierpcg66, 'Decisiondossierpcg66.{n}.Orgtransmisdossierpcg66.{n}.name' ) ) );

				$etat = null;
				if( Hash::get( $dossierpcg66, 'Dossierpcg66.etatdossierpcg' ) == 'transmisop' ){
					$etat = Set::enum( Hash::get( $dossierpcg66, 'Dossierpcg66.etatdossierpcg' ), $options['Dossierpcg66']['etatdossierpcg'] )
                            .' à '.implode( ', ', $orgs )
                            .' le '.date_short( $datetransmissionfinale );
				}
                else if( Hash::get( $dossierpcg66, 'Dossierpcg66.etatdossierpcg' ) == 'atttransmisop' ){
					$etat = Set::enum( Hash::get( $dossierpcg66, 'Dossierpcg66.etatdossierpcg' ), $options['Dossierpcg66']['etatdossierpcg'] )
                            .' à '.implode( ', ', $orgs );
				}
				else{
					$etat = Set::enum( Hash::get( $dossierpcg66, 'Dossierpcg66.etatdossierpcg' ), $options['Dossierpcg66']['etatdossierpcg'] );
				}
				
				$differentsStatuts = '';
				foreach( $dossierpcg66['Personnepcg66']['listemotifs'] as $key => $statut ) {
					if( !empty( $statut ) ) {
						$differentsStatuts .= $this->Xhtml->tag( 'h3', '' ).'<ul><li>'.$statut.'</li></ul>';
					}
				}

				
				$innerTable = '<table id="innerTablesearchResults'.$index.'" class="innerTable">
					<tbody>
						<tr>
							<th>Raison annulation</th>
							<td>'.$dossierpcg66['Dossierpcg66']['motifannulation'].'</td>
						</tr>
					</tbody>
				</table>';

                $pole = isset( $dossierpcg66['Dossierpcg66']['poledossierpcg66_id'] ) ? ( Set::enum( Hash::get( $dossierpcg66, 'Dossierpcg66.poledossierpcg66_id' ), $polesdossierspcgs66 ).' / ' ) : null;
                
//                $gestionnaires = Set::enum( Hash::get( $dossierpcg66, 'Dossierpcg66.user_id' ), $gestionnaire );
                $gestionnaires = Hash::get( $dossierpcg66, 'User.nom_complet' );
				echo $this->Xhtml->tableCells(
					array(
						h( Set::enum( Hash::get( $dossierpcg66, 'Dossierpcg66.typepdo_id' ), $typepdo ) ),
						h( date_short( Hash::get( $dossierpcg66, 'Dossierpcg66.datereceptionpdo' ) ) ),
						h( $pole.$gestionnaires  ),
						h( $etat ),
						$differentsStatuts,
						h( Hash::get( $dossierpcg66, 'Decisiondossierpcg66.0.Decisionpdo.libelle' ) ),
						$this->Xhtml->viewLink(
							'Voir le dossier',
							array( 'controller' => 'dossierspcgs66', 'action' => 'view', $dossierpcg66['Dossierpcg66']['id']),
							$this->Permissions->checkDossier( 'dossierspcgs66', 'view', $dossierMenu )
						),
						$this->Xhtml->editLink(
							'Éditer le dossier',
							array( 'controller' => 'dossierspcgs66', 'action' => 'edit', $dossierpcg66['Dossierpcg66']['id'] ),
							( $this->Permissions->checkDossier( 'dossierspcgs66', 'edit', $dossierMenu )  && Hash::get( $dossierpcg66, 'Dossierpcg66.etatdossierpcg' ) != 'annule' )
						),
						$this->Xhtml->cancelLink(
							'Annuler',
							array( 'controller' => 'dossierspcgs66', 'action' => 'cancel', $dossierpcg66['Dossierpcg66']['id'] ),
							( $this->Permissions->checkDossier( 'dossierspcgs66', 'cancel', $dossierMenu ) && Hash::get( $dossierpcg66, 'Dossierpcg66.etatdossierpcg' ) != 'annule' )
						),
						$this->Xhtml->deleteLink(
							'Etes-vous sûr de vouloir supprimer le dossier',
							array( 'controller' => 'dossierspcgs66', 'action' => 'delete', $dossierpcg66['Dossierpcg66']['id'] ),
							( $this->Permissions->checkDossier( 'dossierspcgs66', 'delete', $dossierMenu ) )
						),
						array( $innerTable, array( 'class' => 'innerTableCell noprint' ) )
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
			?>
		<?php endforeach;?>
	</tbody>
</table>
<?php  endif;?>