<?php
	$this->pageTitle = sprintf( 'APREs liées à %s', $personne['Personne']['nom_complet'] );
	$this->modelClass = Inflector::classify( $this->request->params['controller'] );
?>
<h1><?php echo $this->pageTitle;?></h1>
<?php echo $this->element( 'ancien_dossier' );?>

		<?php if( empty( $apres ) ):?>
			<p class="notice">Cette personne ne possède pas encore d'APRE.</p>
		<?php endif;?>
		<?php if( $this->Permissions->checkDossier( 'apres'.Configure::read( 'Apre.suffixe' ), 'add', $dossierMenu ) ):?>
			<ul class="actionMenu">
				<?php
					echo '<li>'.$this->Xhtml->addLink(
						'Ajouter APRE',
						array( 'controller' => 'apres'.Configure::read( 'Apre.suffixe' ), 'action' => 'add', $personne_id )
					).' </li>';
				?>
			</ul>
		<?php endif;?>

<?php if( !empty( $apres ) ):?>
<?php
	echo 'Montant accordé à ce jour : '.$apresPourCalculMontant[0][0]['montantaccorde'].' €';
	if( $alerteMontantAides ) {
		echo $this->Xhtml->tag(
			'p',
			$this->Xhtml->image( 'icons/error.png', array( 'alt' => 'Remarque' ) ).' '.sprintf( 'Cette personne risque de bénéficier de plus de %s € d\'aides sur l\'année en cours', Configure::read( 'Apre.montantMaxComplementaires' ) ),
			array( 'class' => 'error' )
		);
	}
?>

<table class="tooltips default2">
	<thead>
		<tr>
			<th>Date demande APRE</th>
			<th>Etat du dossier</th>
			<th>Thème de l'aide</th>
			<th>Type d'aides</th>
			<th>Montant proposé</th>
			<th>Montant accordé</th>
			<th>Décision</th>
			<th colspan="7" class="action">Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php
			foreach( $apres as $index => $apre ) {

				$nbFichiersLies = 0;
				$nbFichiersLies = ( isset( $apre['Fichiermodule'] ) ? count( $apre['Fichiermodule'] ) : 0 );

				$statutApre = Set::classicExtract( $apre, "{$this->modelClass}.statutapre" );

				$etat = Set::classicExtract( $apre, "{$this->modelClass}.etatdossierapre" );

				$mtforfait = Set::classicExtract( $apre, 'Aideapre66.montantpropose' );
				$mtattribue = Set::classicExtract( $apre, 'Aideapre66.montantaccorde' );

				$buttonEnabled = true;
				$editButton = true;

				$buttonEnabledInc = ( ( $etat != 'INC' ) ? true : false );
				$editButton = ( ( $etat == 'VAL' || $etat == 'TRA' ) ? false : true );
				$buttonEnabledNotif = ( ( $apre['Apre66']['isdecision']=='N' ) ? false :  true );

				$etat = Set::enum( $etat, $options['etatdossierapre'] );



				$innerTable = '<table id="innerTable'.$index.'" class="innerTable">
					<tbody>
						<tr>
							<th>N° APRE</th>
							<td>'.h( Set::classicExtract( $apre, "{$this->modelClass}.numeroapre" ) ).'</td>
						</tr>
						<tr>
							<th>Nom/Prénom Allocataire</th>
							<td>'.h( $apre['Personne']['nom'].' '.$apre['Personne']['prenom'] ).'</td>
						</tr>
						<tr>
							<th>Référent APRE</th>
							<td>'.h( Set::enum( Set::classicExtract( $apre, "{$this->modelClass}.referent_id" ), $referents ) ).'</td>
						</tr>
						<tr>
							<th>Natures de la demande</th>
							<td>'.( empty( $aidesApre ) ? null :'<ul><li>'.implode( '</li><li>', $aidesApre ).'</li></ul>' ).'</td>
						</tr>
						<tr>
							<th>Raison annulation</th>
							<td>'.h( $apre['Apre66']['motifannulation'] ).'</td>
						</tr>
					</tbody>
				</table>';

				echo $this->Xhtml->tableCells(
					array(
						h( date_short( Set::classicExtract( $apre, 'Aideapre66.datedemande' ) ) ),
						h( $etat ),
						h( Set::enum( Set::classicExtract( $apre, 'Aideapre66.themeapre66_id' ), $themes  ) ),
						h( Set::enum( Set::classicExtract( $apre, 'Aideapre66.typeaideapre66_id' ), $nomsTypeaide  ) ),
						h( $this->Locale->money( $mtforfait ) ),
						h( $this->Locale->money( $mtattribue ) ),
						h(  Set::enum( Set::classicExtract( $apre, 'Aideapre66.decisionapre' ), $options['decisionapre'] ) ),
						$this->Xhtml->viewLink(
							'Voir la demande APRE',
							array( 'controller' => 'apres'.Configure::read( 'Apre.suffixe' ), 'action' => 'view'.Configure::read( 'Cg.departement' ), $apre[$this->modelClass]['id'] ),
							$this->Permissions->checkDossier( 'apres'.Configure::read( 'Apre.suffixe' ), 'view', $dossierMenu )
						),
						$this->Xhtml->editLink(
							'Editer la demande APRE',
							array( 'controller' => 'apres'.Configure::read( 'Apre.suffixe' ), 'action' => 'edit', $apre[$this->modelClass]['id'] ),
							$editButton
							&& $this->Permissions->checkDossier( 'apres'.Configure::read( 'Apre.suffixe' ), 'edit', $dossierMenu )
							&& ( Set::classicExtract( $apre, 'Apre66.etatdossierapre' ) != 'ANN' )
						),
						$this->Xhtml->printLink(
							'Imprimer la demande APRE',
							array( 'controller' => 'apres66', 'action' => 'impression', $apre[$this->modelClass]['id'] ),
							$buttonEnabledInc
							&& $this->Permissions->checkDossier( 'apres66', 'impression', $dossierMenu )
							&& ( Set::classicExtract( $apre, 'Apre66.etatdossierapre' ) != 'ANN' )
						),
						$this->Default2->button(
							'email',
							array( 'controller' => 'apres'.Configure::read( 'Apre.suffixe' ), 'action' => 'maillink', $apre[$this->modelClass]['id'] ),
							array(
								'label' => 'Envoi mail référent',
								'title' => 'Envoi Mail',
								'enabled' => (
									$buttonEnabled
									&& $this->Permissions->checkDossier( 'apres'.Configure::read( 'Apre.suffixe' ), 'maillink', $dossierMenu )
									&& ( Set::classicExtract( $apre, 'Apre66.etatdossierapre' ) != 'ANN' )
								)
							)
						),
						$this->Xhtml->fileLink(
							'Fichiers liés',
							array( 'controller' => 'apres'.Configure::read( 'Apre.suffixe' ), 'action' => 'filelink', $apre[$this->modelClass]['id'] ),
							$buttonEnabled
							&& $this->Permissions->checkDossier( 'apres'.Configure::read( 'Apre.suffixe' ), 'filelink', $dossierMenu )
//                                && ( Set::classicExtract( $apre, 'Apre66.etatdossierapre' ) != 'ANN' )
						),
						h( '('.$nbFichiersLies.')' ),
						$this->Default2->button(
							'cancel',
							array( 'controller' => 'apres66', 'action' => 'cancel', $apre[$this->modelClass]['id'] ),
							array(
								'enabled' => (
									$this->Permissions->checkDossier( 'apres'.Configure::read( 'Apre.suffixe' ), 'cancel', $dossierMenu )
									&& ( Set::classicExtract( $apre, 'Apre66.etatdossierapre' ) != 'ANN' )
//                                        && ( Set::classicExtract( $apre, 'Aideapre66.decisionapre' ) != 'ACC' )
								)
							)
						),
						array( $innerTable, array( 'class' => 'innerTableCell' ) )
					),
					array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
					array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
				);
			}
		?>
	</tbody>
</table>
<?php endif;?>
