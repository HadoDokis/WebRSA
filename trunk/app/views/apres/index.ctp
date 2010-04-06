<?php $this->pageTitle = sprintf( 'APREs liées à %s', $personne['Personne']['nom_complet'] );?>

<?php  echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id) );?>

<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

    <!-- Données concernant le CG93 -->
        <?php if( Configure::read( 'nom_form_apre_cg' ) == 'cg93' ):?>
            <?php if( empty( $apres ) ):?>
                <p class="error">Cette personne ne possède pas encore d'APRE forfaitaire, il n'est donc pas possible de créer une APRE Complémentaire.</p>
            <?php endif;?>

            <?php if( $permissions->check( 'apres', 'add' ) && ( $apre_forfait > 0 ) ):?>
                <ul class="actionMenu">
                    <?php
                        echo '<li>'.$html->addLink(
                            'Ajouter APRE',
                            array( 'controller' => 'apres', 'action' => 'add', $personne_id )
                        ).' </li>';
                    ?>
                </ul>
            <?php endif;?>
        <?php endif;?>
    <!-- Fin des Données concernant le CG93 -->
<!-- .................................................................................... -->
    <!-- Données concernant le CG66 -->
        <?php if( Configure::read( 'nom_form_apre_cg' ) == 'cg66' ):?>
            <?php if( empty( $apres ) ):?>
                <p class="notice">Cette personne ne possède pas encore d'APRE.</p>
            <?php endif;?>
            <?php if( $permissions->check( 'apres', 'add' ) ):?>
                <ul class="actionMenu">
                    <?php
                        echo '<li>'.$html->addLink(
                            'Ajouter APRE',
                            array( 'controller' => 'apres', 'action' => 'add', $personne_id )
                        ).' </li>';
                    ?>
                </ul>
            <?php endif;?>
        <?php endif;?>
    <!-- Fin des Données concernant le CG66 -->

    <?php if( !empty( $apres ) ):?>
	<?php
		if( $alerteMontantAides ) {
			echo $html->tag(
				'p',
				$html->image( 'icons/error.png', array( 'alt' => 'Remarque' ) ).' '.sprintf( 'Cette personne risque de bénéficier de plus de %s € d\'aides complémentaires au cours des %s derniers mois', Configure::read( 'Apre.montantMaxComplementaires' ), Configure::read( 'Apre.periodeMontantMaxComplementaires' ) ),
				array( 'class' => 'error' )
			);
		}
	?>
    <table class="tooltips">
        <thead>
            <tr>
                <th>Statut de l'APRE</th>
                <th>Date demande APRE</th>
                <th>Etat du dossier</th>
                <th>Montant demandé</th>
                <th>Montant attribué</th>
                <th colspan="4" class="action">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach( $apres as $index => $apre ) {
                    $aidesApre = array();
                    $buttonEnabled = null;
                    $mtforfait = null;
                    $mtattribue = null;
                    $naturesaide = Set::classicExtract( $apre, 'Apre.Natureaide' );

                    foreach( $naturesaide as $natureaide => $nombre ) {
                        if( $nombre > 0 ) {
                            $aidesApre[] = h( Set::classicExtract( $natureAidesApres, $natureaide ) );
                            ///Calcul des montants versés pour les aides d'une APRE complémentaire
                            $montantaide = Set::classicExtract( $apre, "$natureaide.montantaide" );
                            $mtforfait += $montantaide;
                        }
                    }

                    $piecesManquantes = Set::extract( $apre, '/Relanceapre/Piecemanquante/libelle' );

                    /**
                    **  Mise en place de l'impossibilité de modifier/relancer/imprimer les APREs forfaitaires
                    **  +
                    **  Conditionnement des éléments à afficher selon le statut de l'APRE
                    **/
                    $statutApre = Set::classicExtract( $apre, 'Apre.statutapre' );
                    if( $statutApre == 'C' ) {
                        $etat = Set::enum( Set::classicExtract( $apre, 'Apre.etatdossierapre' ), $options['etatdossierapre'] );
                        $mtforfait = $mtforfait;
                        $buttonEnabled = true;
                        if( $etat == 'Complet' ){
                            $buttonEnabledInc = false;
                        }
                        else{
                            $buttonEnabledInc = true;
                        }

						// Calcul des montants attribués
						$montantsAttribues = Set::extract( $apre, '/Comiteapre/ApreComiteapre[decisioncomite=ACC]/montantattribue' );
						$mtattribue = ( ( is_array( $montantsAttribues ) && !empty( $montantsAttribues ) ) ? array_sum( $montantsAttribues ) : null );
                    }
                    else if( $statutApre == 'F' ) {
                        $etat = null;
                        $mtforfait = Set::classicExtract( $apre, 'Apre.mtforfait' );
                        $buttonEnabled = false;
                        $buttonEnabledInc = false;

						// Calcul des montants attribués
						$mtattribue = $mtforfait;
                    }

					$innerTable = '<table id="innerTable'.$index.'" class="innerTable">
						<tbody>
							<tr>
								<th>N° APRE</th>
								<td>'.h( Set::classicExtract( $apre, 'Apre.numeroapre' ) ).'</td>
							</tr>
							<tr>
								<th>Nom/Prénom Allocataire</th>
								<td>'.h( $apre['Personne']['nom'].' '.$apre['Personne']['prenom'] ).'</td>
							</tr>
							<tr>
								<th>Référent APRE</th>
								<td>'.h( Set::enum( Set::classicExtract( $apre, 'Apre.referent_id' ), $referents ) ).'</td>
							</tr>
							<tr>
								<th>Natures de la demande</th>
								<td>'.( empty( $aidesApre ) ? null :'<ul><li>'.implode( '</li><li>', $aidesApre ).'</li></ul>' ).'</td>
							</tr>
						</tbody>
					</table>';

                    echo $html->tableCells(
                        array(
                            h( Set::enum( $statutApre, $options['statutapre'] ) ),
                            h( date_short( Set::classicExtract( $apre, 'Apre.datedemandeapre' ) ) ),
                            h( $etat ),
                            h( $locale->money( $mtforfait ) ),
                            h( $locale->money( $mtattribue ) ),
                            $html->viewLink(
                                'Voir la demande APRE',
                                array( 'controller' => 'apres', 'action' => 'view', $apre['Apre']['id'] ),
                                $permissions->check( 'apres', 'view' )
                            ),
                            $html->editLink(
                                'Editer la demande APRE',
                                array( 'controller' => 'apres', 'action' => 'edit', $apre['Apre']['id'] ),
                                $buttonEnabled,
                                $permissions->check( 'apres', 'edit' )
                            ),
                            $html->relanceLink(
                                'Relancer la demande APRE',
                                array( 'controller' => 'relancesapres', 'action' => 'add', $apre['Apre']['id'] ),
                                $buttonEnabledInc,
                                $permissions->check( 'relancesapres', 'add' ) && ( $apre['Apre']['etatdossierapre'] == 'INC' )
                            ),
                            $html->printLink(
                                'Imprimer la demande APRE',
                                array( 'controller' => 'gedooos', 'action' => 'apre', $apre['Apre']['id'] ),
                                $buttonEnabled,
                                $permissions->check( 'gedooos', 'apre' )
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

<br />

    <?php if( !empty( $apres ) ):?>

        <h2>Liste des relances</h2>
        <?php if( empty( $relancesapres ) ):?>
            <p class="notice">Cette personne ne possède pas encore de relances.</p>
        <?php endif;?>

        <?php if( !empty( $apres ) && !empty( $relancesapres ) ):?>
        <table class="tooltips">
            <thead>
                <tr>
                    <th>N° Apre</th>
                    <th>Date de relance</th>
                    <th>Liste des pièces manquantes</th>
                    <th>Commentaire</th>
                    <th colspan="3" class="action">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach( $relancesapres as $relanceapre ) {
                        $piecesAbsentes = Set::extract( $relanceapre, '/Relanceapre/Piecemanquante/libelle' );
                        $piecesManquantesAides = Set::classicExtract( $relanceapre, "Apre.Piece.Manquante" );

                        $textePiecesManquantes = '';
                        foreach( $piecesManquantesAides as $model => $pieces ) {
                            if( !empty( $pieces ) ) {
                                $textePiecesManquantes .= $html->tag( 'h3', __d( 'apre', $model, true ) ).'<ul><li>'.implode( '</li><li>', $pieces ).'</li></ul>';
                            }
                        }

                        echo $html->tableCells(
                            array(
                                h( Set::classicExtract( $relanceapre, 'Apre.numeroapre' ) ),
                                h( date_short( Set::classicExtract( $relanceapre, 'Relanceapre.daterelance' ) ) ),
                                $textePiecesManquantes,
                                h( Set::classicExtract( $relanceapre, 'Relanceapre.commentairerelance' ) ),
//                                 $html->viewLink(
//                                     'Voir la relance',
//                                     array( 'controller' => 'relancesapres', 'action' => 'view', Set::classicExtract( $relanceapre, 'Relanceapre.id' ) ),
//                                     $permissions->check( 'relancesapres', 'view' )
//                                 ),
                                $html->editLink(
                                    'Editer la relance',
                                    array( 'controller' => 'relancesapres', 'action' => 'edit', Set::classicExtract( $relanceapre, 'Relanceapre.id' ) ),
                                    $permissions->check( 'relancesapres', 'edit' )
                                ),
                                $html->printLink(
                                    'Imprimer la notification de relance',
                                    array( 'controller' => 'gedooos', 'action' => 'relanceapre', Set::classicExtract( $relanceapre, 'Relanceapre.id' ) ),
                                    $permissions->check( 'gedooos', 'relanceapre' )
                                )
                            ),
                            array( 'class' => 'odd' ),
                            array( 'class' => 'even' )
                        );
                    }
                ?>
            </tbody>
        </table>
        <?php  endif;?>
    <?php  endif;?>
</div>
<div class="clearer"><hr /></div>