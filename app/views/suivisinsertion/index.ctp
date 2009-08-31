<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php  $this->pageTitle = 'Suivi du parcours d\'insertion';?>

<?php echo $this->element( 'dossier_menu', array( 'id' => $details['Dossier']['id'] ) );?>


<?php
    function thead( $pct = 10, $role = null ) {
        return '<thead>
                <tr>
                    <th colspan="4" style="width: '.$pct.'%;">'.$role.'</th>
                </tr>
            </thead>';
    }

    function value( $array, $index ) {
        $keys = array_keys( $array );
        $index = ( ( $index == null ) ? '' : $index );
        if( @in_array( $index, $keys ) && isset( $array[$index] ) ) {
            return $array[$index];
        }
        else {
            return null;
        }
    }
?>


<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

    <div id="resumeDossier">
    <!-- Etape 1 : Affichage des instructions du Dossier (valable pour le Demandeur et le Conjoint) -->
        <h2>Etape 1: Instruction dossier</h2>
        <table>
            <?php echo thead( 100, 'Parcours Demandeur/Conjoint' );?>
            <tbody>
                <tr class="odd">
                    <th>Service instructeur</th>
                    <th>Date de demande</th>
                    <th>Réalisé</th>
                    <th class="action">Action</th>
                </tr>
                <tr>
                    <td><?php echo Set::extract( 'Serviceinstructeur.lib_service', $details );?></td>
                    <td><?php echo date_short( $details['Dossier']['dtdemrsa'] );?></td>
                    <td><?php echo $html->boolean( !empty( $details['Dossier']['dtdemrsa'] ) );?></td>
                    <td><?php echo $html->viewLink(
                        'Voir le dossier',
                        array( 'controller' => 'dossiers', 'action' => 'view', $details['Dossier']['id'] )
                        );?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="suiviInsertion">
        <!-- Etape 2 : Affichage de la dernière orientation du Demandeur et du Conjoint -->
        <h2>Etape 2: Orientation</h2>
        <table>
        <?php echo thead( 100, 'Parcours Demandeur' );?>
            <tbody>
                <tr class="odd">
                    <th style="width:7em;">Structure référente</th>
                    <th style="width:7em;">Date d'orientation</th>
                    <th style="width:7em;">Réalisé</th>
                    <th style="width:2em;" class="action">Action</th>
                </tr>
                <tr>
                    <td><?php echo value( $structuresreferentes, Set::extract( 'DEM.Orientstruct.derniere.structurereferente_id', $details ) );?></td>
                    <td><?php echo date_short( Set::extract( 'DEM.Orientstruct.derniere.date_valid', $details ) );?></td>
                    <td><?php echo $html->boolean( !empty( $details['DEM']['Orientstruct']['derniere']['date_valid'] ) );?></td>
                    <td><?php echo $html->viewLink(
                        'Voir l\'orientation',
                        array( 'controller' => 'orientsstructs', 'action' => 'index', Set::extract( 'DEM.Orientstruct.derniere.personne_id', $details ) )
                        );?>
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="aere">
        <?php echo thead( 100, 'Parcours Conjoint' );?>
            <tbody>
                <tr class="odd">
                    <th style="width:7em;">Structure référente</th>
                    <th style="width:7em;">Date d'orientation</th>
                    <th style="width:7em;">Réalisé</th>
                    <th style="width:2em;" class="action">Action</th>
                </tr>
                <tr>
                    <td><?php echo value( $structuresreferentes, Set::extract( 'CJT.Orientstruct.derniere.structurereferente_id', $details ) );?></td>
                    <td><?php echo date_short( Set::extract( 'CJT.Orientstruct.derniere.date_valid', $details ) );?></td>
                    <td><?php echo $html->boolean( !empty( $details['CJT']['Orientstruct']['derniere']['date_valid'] ) );?></td>
                    <td><?php echo $html->viewLink(
                        'Voir l\'orientation',
                        array( 'controller' => 'orientsstructs', 'action' => 'index', Set::extract( 'CJT.Orientstruct.derniere.personne_id', $details ) )
                        );?>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Etape 3 : Affichage des entretiens avec les structures référentes -->
        <h2>Etape 3: Entretien Structure Référente</h2>
        <table>
        <?php echo thead( 100, 'Parcours Demandeur' );?>
            <tbody>
                <tr class="odd">
                    <th style="width:11em;">Date de l'entretien</th>
                    <th style="width:11em;" colspan="2">Réalisé</th>
                    <th class="action">Action</th>
                </tr>
                <tr>
                    <td><?php echo date_short( Set::extract( 'DEM.Orientstruct.derniere.date_valid', $details ) );?></td>
                    <td colspan="2"><?php echo $html->boolean( !empty( $details['DEM']['Orientstruct']['derniere']['date_valid'] ) );?></td>
                    <td><?php echo $html->viewLink(
                        'Voir l\'orientation',
                        array( 'controller' => 'orientsstructs', 'action' => 'index', Set::extract( 'DEM.Orientstruct.derniere.personne_id', $details ) )
                        );?>
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="aere">
        <?php echo thead( 100, 'Parcours Conjoint' );?>
            <tbody>
                <tr class="odd">
                    <th style="width:11em;">Date de l'entretien</th>
                    <th style="width:11em;" colspan="2" >Réalisé</th>
                    <th class="action">Action</th>
                </tr>
                <tr>
                    <td><?php echo date_short( Set::extract( 'CJT.Orientstruct.derniere.date_valid', $details ) );?></td>
                    <td colspan="2" ><?php echo $html->boolean( !empty( $details['CJT']['Orientstruct']['derniere']['date_valid'] ) );?></td>
                    <td><?php echo $html->viewLink(
                        'Voir l\'orientation',
                        array( 'controller' => 'orientsstructs', 'action' => 'index', Set::extract( 'CJT.Orientstruct.derniere.personne_id', $details ) )
                        );?>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Etape 4 : Affichage des derniers enregistrements des contrats insertion -->
        <h2>Etape 4: Enregistrement Contrat d'Insertion</h2>
        <table>
        <?php echo thead( 100, 'Parcours Demandeur' );?>
            <tbody>
                <tr class="odd">
                    <th style="width:11em;">Date de signature</th>
                    <th style="width:11em;" colspan="2">Réalisé</th>
                    <th class="action">Action</th>
                </tr>
                <tr>
                    <td><?php echo date_short( Set::extract( 'DEM.Contratinsertion.date_saisi_ci', $details ) );?></td>
                    <td colspan="2"><?php echo $html->boolean( !empty( $details['DEM']['Contratinsertion']['date_saisi_ci'] ) );?></td>
                    <td><?php echo $html->viewLink(
                        'Voir le contrat d\'insertion',
                        array( 'controller' => 'contratsinsertion', 'action' => 'index', Set::extract( 'DEM.Personne.id', $details ) )
                        );?>
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="aere">
        <?php echo thead( 100, 'Parcours Conjoint' );?>
            <tbody>
                <tr class="odd">
                    <th style="width:11em;">Date de signature</th>
                    <th style="width:11em;" colspan="2" >Réalisé</th>
                    <th class="action">Action</th>
                </tr>
                <tr>
                    <td><?php echo date_short( Set::extract( 'CJT.Contratinsertion.date_saisi_ci', $details ) );?></td>
                    <td colspan="2" ><?php echo $html->boolean( !empty( $details['CJT']['Contratinsertion']['date_saisi_ci'] ) );?></td>
                    <td><?php echo $html->viewLink(
                        'Voir le contrat d\'insertion',
                        array( 'controller' => 'contratsinsertion', 'action' => 'index', Set::extract( 'CJT.Personne.id', $details ) )
                        );?>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Etape 5 : Affichage des validations des contrats insertion -->
        <h2>Etape 5: Validation Contrat d'Insertion</h2>
        <table>
        <?php echo thead( 100, 'Parcours Demandeur' );?>
            <tbody>
                <tr class="odd">
                    <th style="width:11em;">Date de validation</th>
                    <th style="width:11em;" colspan="2">Réalisé</th>
                    <th class="action">Action</th>
                </tr>
                <tr>
                    <td><?php echo date_short( Set::extract( 'DEM.Contratinsertion.datevalidation_ci', $details ) );?></td>
                    <td colspan="2"><?php echo $html->boolean( !empty( $details['DEM']['Contratinsertion']['datevalidation_ci'] ) );?></td>
                    <td><?php echo $html->viewLink(
                        'Voir le contrat d\'insertion',
                        array( 'controller' => 'contratsinsertion', 'action' => 'index', Set::extract( 'DEM.Personne.id', $details ) )
                        );?>
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="aere">
        <?php echo thead( 100, 'Parcours Conjoint' );?>
            <tbody>
                <tr class="odd">
                    <th style="width:11em;">Date de validation</th>
                    <th style="width:11em;" colspan="2" >Réalisé</th>
                    <th class="action">Action</th>
                </tr>
                <tr>
                    <td><?php echo date_short( Set::extract( 'CJT.Contratinsertion.datevalidation_ci', $details ) );?></td>
                    <td colspan="2" ><?php echo $html->boolean( !empty( $details['CJT']['Contratinsertion']['datevalidation_ci'] ) );?></td>
                    <td><?php echo $html->viewLink(
                        'Voir le contrat d\'insertion',
                        array( 'controller' => 'contratsinsertion', 'action' => 'index', Set::extract( 'CJT.Personne.id', $details ) )
                        );?>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Etape 6 : Affichage des Actions d'insertion engagées -->
        <h2>Etape 6: Actions d'insertion engagées</h2>
        <table>
        <?php echo thead( 100, 'Parcours Demandeur' );?>
            <tbody>
                <tr class="odd">
                    <th style="width:7em;">Actions engagées</th>
                    <th style="width:7em;">Date dernière action</th>
                    <th style="width:7em;">Réalisé</th>
                    <th class="action">Action</th>
                </tr>
                <tr>
                    <td><?php echo count( Set::extract( 'DEM.Actioninsertion.id', $details ) );?></td>
                    <td><?php echo date_short( Set::extract( 'DEM.Actioninsertion.dd_action', $details ) );?></td>
                    <td><?php echo $html->boolean( !empty( $details['DEM']['Contratinsertion']['datevalidation_ci'] ) );?></td>
                    <td><?php echo $html->viewLink(
                        'Voir les actions d\'insertion',
                        array( 'controller' => 'actionsinsertion', 'action' => 'index', Set::extract( 'DEM.Personne.id', $details ) )
                        );?>
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="aere">
        <?php echo thead( 100, 'Parcours Conjoint' );?>
            <tbody>
                <tr class="odd">
                    <th style="width:7em;">Actions engagées</th>
                    <th style="width:7em;">Date dernière action</th>
                    <th style="width:7em;">Réalisé</th>
                    <th class="action">Action</th>
                </tr>
                <tr>
                    <td><?php echo count( Set::extract( 'CJT.Actioninsertion.id', $details ) );?></td>
                    <td><?php echo date_short( Set::extract( 'CJT.Actioninsertion.dd_action', $details ) );?></td>
                    <td><?php echo $html->boolean( !empty( $details['CJT']['Actioninsertion']['dd_action'] ) );?></td>
                    <td><?php echo $html->viewLink(
                        'Voir les actions d\'insertion',
                        array( 'controller' => 'actionsinsertion', 'action' => 'index', Set::extract( 'CJT.Personne.id', $details ) )
                        );?>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Etape 7 : Affichage des bilans de fin de Contrat d'insertion -->
        <h2>Etape 7: Bilan de fin de Contrat</h2>
        <table>
        <?php echo thead( 100, 'Parcours Demandeur' );?>
            <tbody>
                <tr class="odd">
                    <th style="width:7em;">Date bilan</th>
                    <th style="width:7em;">Bilan</th>
                    <th style="width:7em;">Réalisé</th>
                    <th class="action">Action</th>
                </tr>
                <tr>
                    <td><?php echo date_short( Set::extract( 'DEM.Contratinsertion.df_ci', $details ) );?></td>
                    <td><?php echo 'Non défini'?></td>
                    <td><?php echo $html->boolean( !empty( $details['DEM']['Contratinsertion']['df_ci'] ) );?></td>
                    <td><?php echo $html->viewLink(
                        'Voir les actions d\'insertion',
                        array( 'controller' => 'contratsinsertion', 'action' => 'index', Set::extract( 'DEM.Personne.id', $details ) )
                        );?>
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="aere">
        <?php echo thead( 100, 'Parcours Conjoint' );?>
            <tbody>
                <tr class="odd">
                    <th style="width:7em;">Date bilan</th>
                    <th style="width:7em;">Bilan</th>
                    <th style="width:7em;">Réalisé</th>
                    <th class="action">Action</th>
                </tr>
                <tr>
                    <td><?php echo date_short( Set::extract( 'CJT.Contratinsertion.df_ci', $details ) );?></td>
                    <td><?php echo 'Non défini'?></td>
                    <td><?php echo $html->boolean( !empty( $details['CJT']['Contratinsertion']['df_ci'] ) );?></td>
                    <td><?php echo $html->viewLink(
                        'Voir les actions d\'insertion',
                        array( 'controller' => 'contratsinsertion', 'action' => 'index', Set::extract( 'CJT.Personne.id', $details ) )
                        );?>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Etape 8 : Affichage des poursuites pour le droit -->
        <h2>Etape 8: Poursuite du droit</h2>
        <table>
        <?php echo thead( 100, 'Parcours Demandeur' );?>
            <tbody>
                <tr class="odd">
                    <th style="width:6em;">Date commission pluridisciplinaire</th>
                    <th style="width:6em;">Décision</th>
                    <th style="width:6em;">Réalisé</th>
                    <th class="action">Action</th>
                </tr>
                <tr>
                    <td><?php echo Set::extract( 'DEM.Contratinsertion.datevalidation_ci', $details );?></td>
                    <td><?php echo value( $decision_ci, Set::extract( 'DEM.Contratinsertion.decision_ci', $details ) );?></td>
                    <td><?php echo $html->boolean( !empty( $details['DEM']['Contratinsertion']['decision_ci'] )  && ( $details['DEM']['Contratinsertion']['decision_ci'] != 'E' ) );?></td>
                    <td><?php echo $html->viewLink(
                        'Voir les actions d\'insertion',
                        array( 'controller' => 'contratsinsertion', 'action' => 'index', Set::extract( 'DEM.Personne.id', $details ) )
                        );?>
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="aere">
        <?php echo thead( 100, 'Parcours Conjoint' );?>
            <tbody>
                <tr class="odd">
                    <th style="width:6em;">Date commission pluridisciplinaire</th>
                    <th style="width:6em;">Décision</th>
                    <th style="width:6em;">Réalisé</th>
                    <th class="action">Action</th>
                </tr>
                <tr>
                    <td><?php echo Set::extract( 'CJT.Contratinsertion.datevalidation_ci', $details );?></td>
                    <td><?php echo value( $decision_ci, Set::extract( 'CJT.Contratinsertion.decision_ci', $details ) );?></td>
                    <td><?php echo $html->boolean( !empty( $details['CJT']['Contratinsertion']['decision_ci'] ) && ( $details['CJT']['Contratinsertion']['decision_ci'] != 'E' ) );?></td>
                    <td><?php echo $html->viewLink(
                        'Voir les actions d\'insertion',
                        array( 'controller' => 'contratsinsertion', 'action' => 'index', Set::extract( 'CJT.Personne.id', $details ) )
                        );?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="clearer"><hr /></div>
<?php /*debug( $details );*/?>