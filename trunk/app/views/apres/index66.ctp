<?php
	$this->pageTitle = sprintf( 'APREs liées à %s', $personne['Personne']['nom_complet'] );
	$this->modelClass = $this->params['models'][0];
?>

<?php  echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id) );?>

<div class="with_treemenu">
	<h1><?php echo $this->pageTitle;?></h1>

			<?php if( empty( $apres ) ):?>
				<p class="notice">Cette personne ne possède pas encore d'APRE.</p>
			<?php endif;?>
			<?php if( $permissions->check( 'apres'.Configure::read( 'Apre.suffixe' ), 'add' ) ):?>
				<ul class="actionMenu">
					<?php
						echo '<li>'.$xhtml->addLink(
							'Ajouter APRE',
							array( 'controller' => 'apres'.Configure::read( 'Apre.suffixe' ), 'action' => 'add', $personne_id )
						).' </li>';
					?>
				</ul>
			<?php endif;?>

	<?php if( !empty( $apres ) ):?>
	<?php
		echo 'Montant accordé à ce jour : '.$montantComplementaires.' €';
		if( $alerteMontantAides ) {
			echo $xhtml->tag(
				'p',
				$xhtml->image( 'icons/error.png', array( 'alt' => 'Remarque' ) ).' '.sprintf( 'Cette personne risque de bénéficier de plus de %s € d\'aides sur l\'année en cours', Configure::read( 'Apre.montantMaxComplementaires' ) ),
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

					echo $xhtml->tableCells(
						array(
							h( date_short( Set::classicExtract( $apre, 'Aideapre66.datedemande' ) ) ),
							h( $etat ),
							h( Set::enum( Set::classicExtract( $apre, 'Aideapre66.themeapre66_id' ), $themes  ) ),
							h( Set::enum( Set::classicExtract( $apre, 'Aideapre66.typeaideapre66_id' ), $nomsTypeaide  ) ),
							h( $locale->money( $mtforfait ) ),
							h( $locale->money( $mtattribue ) ),
							h(  Set::enum( Set::classicExtract( $apre, 'Aideapre66.decisionapre' ), $options['decisionapre'] ) ),
							$xhtml->viewLink(
								'Voir la demande APRE',
								array( 'controller' => 'apres'.Configure::read( 'Apre.suffixe' ), 'action' => 'view'.Configure::read( 'Cg.departement' ), $apre[$this->modelClass]['id'] ),
                                $permissions->check( 'apres'.Configure::read( 'Apre.suffixe' ), 'view' )
							),
							$xhtml->editLink(
								'Editer la demande APRE',
								array( 'controller' => 'apres'.Configure::read( 'Apre.suffixe' ), 'action' => 'edit', $apre[$this->modelClass]['id'] ),
                                $editButton
                                && $permissions->check( 'apres'.Configure::read( 'Apre.suffixe' ), 'edit' )
                                && ( Set::classicExtract( $apre, 'Apre66.etatdossierapre' ) != 'ANN' )
							),
							$xhtml->printLink(
								'Imprimer la demande APRE',
								array( 'controller' => 'apres66', 'action' => 'impression', $apre[$this->modelClass]['id'] ),
								$buttonEnabledInc
                                && $permissions->check( 'apres66', 'impression' )
                                && ( Set::classicExtract( $apre, 'Apre66.etatdossierapre' ) != 'ANN' )
							),
							$default2->button(
								'email',
								array( 'controller' => 'apres'.Configure::read( 'Apre.suffixe' ), 'action' => 'maillink', $apre[$this->modelClass]['id'] ),
								array(
									'label' => 'Envoi mail référent',
									'title' => 'Envoi Mail',
									'enabled' => (
                                        $buttonEnabled
                                        && $permissions->check( 'apres'.Configure::read( 'Apre.suffixe' ), 'maillink' )
                                        && ( Set::classicExtract( $apre, 'Apre66.etatdossierapre' ) != 'ANN' )
                                    )
								)
							),
							$xhtml->fileLink(
								'Fichiers liés',
								array( 'controller' => 'apres'.Configure::read( 'Apre.suffixe' ), 'action' => 'filelink', $apre[$this->modelClass]['id'] ),
								$buttonEnabled
                                && $permissions->check( 'apres'.Configure::read( 'Apre.suffixe' ), 'filelink' )
//                                && ( Set::classicExtract( $apre, 'Apre66.etatdossierapre' ) != 'ANN' )
							),
							h( '('.$nbFichiersLies.')' ),
                            $default2->button(
								'cancel',
								array( 'controller' => 'apres66', 'action' => 'cancel', $apre[$this->modelClass]['id'] ),
								array(
                                    'enabled' => (
                                        $permissions->check( 'apres'.Configure::read( 'Apre.suffixe' ), 'cancel' )
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

</div>
<div class="clearer"><hr /></div>
