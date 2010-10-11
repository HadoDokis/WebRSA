<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php  $this->pageTitle = 'Synthèse du parcours d\'insertion';?>

<?php echo $this->element( 'dossier_menu', array( 'id' => $dossier_id ) );?>


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
            <thead>
                <tr class="odd">
                    <th colspan="5">Parcours Demandeur</th>
                    <th colspan="5">Parcours Conjoint</th>
                </tr>
            </thead>
            <tbody>
                <tr class="odd">
                    <th style="width=200px">Structure référente</th>
                    <th style="width=200px">Date d'orientation</th>
                    <th style="width=200px">Date de relance</th>
                    <th style="width=200px">Réalisé</th>
                    <th class="action">Action</th>

                    <th style="width=200px">Structure référente</th>
                    <th style="width=200px">Date d'orientation</th>
                    <th style="width=200px">Date de relance</th>
                    <th style="width=200px">Réalisé</th>
                    <th class="action">Action</th>
                </tr>
                <tr>
                    <td><?php echo value( $structuresreferentes, Set::extract( 'DEM.Orientstruct.derniere.structurereferente_id', $details ) );?></td>
                    <td><?php echo date_short( Set::extract( 'DEM.Orientstruct.derniere.date_valid', $details ) );?></td>
                    <td><?php echo date_short( Set::extract( 'DEM.Orientstruct.derniere.daterelance', $details ) );?></td>
                    <td><?php echo $html->boolean( !empty( $details['DEM']['Orientstruct']['derniere']['date_valid'] ) );?></td>
                    <td><?php
                        if( !empty( $details['DEM']['Orientstruct']['derniere']['structurereferente_id'] ) ){
                            echo $html->viewLink(
                                'Voir l\'orientation',
                                array( 'controller' => 'orientsstructs', 'action' => 'index', Set::extract( 'DEM.Orientstruct.derniere.personne_id', $details ) )
                            );
                        }
                        ?>
                    </td>

                    <td><?php echo value( $structuresreferentes, Set::extract( 'CJT.Orientstruct.derniere.structurereferente_id', $details ) );?></td>
                    <td><?php echo date_short( Set::extract( 'CJT.Orientstruct.derniere.date_valid', $details ) );?></td>
                    <td><?php echo date_short( Set::extract( 'CJT.Orientstruct.derniere.daterelance', $details ) );?></td>
                    <td><?php echo $html->boolean( !empty( $details['CJT']['Orientstruct']['derniere']['date_valid'] ) );?></td>
                    <td><?php
                        if( !empty( $details['CJT']['Orientstruct']['derniere']['structurereferente_id'] ) ){
                            echo $html->viewLink(
                                'Voir l\'orientation',
                                array( 'controller' => 'orientsstructs', 'action' => 'index', Set::extract( 'CJT.Orientstruct.derniere.personne_id', $details ) )
                            );
                        }
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Etape 3 : Affichage des entretiens avec les structures référentes -->
        <h2>Etape 3: Rendez-vous référent</h2>
        <table>
            <thead>
                <tr class="odd">
                    <th colspan="4">Parcours Demandeur</th>
                    <th colspan="4">Parcours Conjoint</th>
                </tr>
            </thead>
            <tbody>
                <tr class="odd">
                    <th>Date du dernier RDV</th>
                    <th colspan="2">Réalisé</th>
                    <th class="action">Action</th>

                    <th>Date du dernier RDV</th>
                    <th colspan="2">Réalisé</th>
                    <th class="action">Action</th>
                </tr>
                <tr>
                    <td><?php echo date_short( Set::extract( 'DEM.Rendezvous.dernier.daterdv', $details ) );?></td>
                    <td colspan="2"><?php echo $html->boolean( !empty( $details['DEM']['Rendezvous']['dernier']['daterdv'] ) );?></td>
                    <td><?php
                        if( !empty( $details['DEM']['Rendezvous']['dernier']['daterdv'] ) ){
                            echo $html->viewLink(
                                'Voir l\'entretien',
                                array( 'controller' => 'rendezvous', 'action' => 'index', Set::extract( 'DEM.Rendezvous.dernier.personne_id', $details ) )
                            );
                        }
                        ?>
                    </td>

                    <td><?php echo date_short( Set::extract( 'CJT.Rendezvous.dernier.daterdv', $details ) );?></td>
                    <td colspan="2" ><?php echo $html->boolean( !empty( $details['CJT']['Rendezvous']['dernier']['daterdv'] ) );?></td>
                    <td><?php
                        if( !empty( $details['CJT']['Rendezvous']['dernier']['daterdv'] ) ){
                            echo $html->viewLink(
                                'Voir l\'entretien',
                                array( 'controller' => 'rendezvous', 'action' => 'index', Set::extract( 'CJT.Rendezvous.dernier.personne_id', $details ) )
                            );
                        }
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Etape 4 : Affichage des derniers enregistrements des contrats engagements -->
        <h2>Etape 4: Enregistrement Contrats ( CER / CUI )</h2>
        <table>
            <thead>
                <tr>
                    <th  class="entete" colspan="8">CER</th>
                    <th></th>
                    <th  class="entete" colspan="8">CUI</th>
                </tr>
                <tr class="odd">
                    <th colspan="4">Parcours Demandeur</th>
                    <th colspan="4">Parcours Conjoint</th>

                    <th></th>

                    <th colspan="4">Parcours Demandeur</th>
                    <th colspan="4">Parcours Conjoint</th>
                </tr>
            </thead>
            <tbody>
                <tr class="odd">
                    <th>Date de signature</th>
                    <th colspan="2">Réalisé</th>
                    <th class="action">Action</th>

                    <th>Date de signature</th>
                    <th colspan="2">Réalisé</th>
                    <th class="action">Action</th>

                    <th></th>

                    <th>Date de signature</th>
                    <th colspan="2">Réalisé</th>
                    <th class="action">Action</th>

                    <th>Date de signature</th>
                    <th colspan="2">Réalisé</th>
                    <th class="action">Action</th>
                </tr>
                <tr>
                    <td><?php echo date_short( Set::extract( 'DEM.Contratinsertion.date_saisi_ci', $details ) );?></td>
                    <td colspan="2"><?php echo $html->boolean( !empty( $details['DEM']['Contratinsertion']['date_saisi_ci'] ) );?></td>
                    <td><?php
                        if( !empty( $details['DEM']['Contratinsertion']['date_saisi_ci'] ) ){
                            echo $html->viewLink(
                                'Voir le contrat',
                                array( 'controller' => 'contratsinsertion', 'action' => 'index', Set::extract( 'DEM.Personne.id', $details ) )
                            );
                        }
                        ?>
                    </td>

                    <td><?php echo date_short( Set::extract( 'CJT.Contratinsertion.date_saisi_ci', $details ) );?></td>
                    <td colspan="2" ><?php echo $html->boolean( !empty( $details['CJT']['Contratinsertion']['date_saisi_ci'] ) );?></td>
                    <td><?php
                        if( !empty( $details['CJT']['Contratinsertion']['date_saisi_ci'] ) ){
                            echo $html->viewLink(
                                'Voir le contrat',
                                array( 'controller' => 'contratsinsertion', 'action' => 'index', Set::extract( 'CJT.Personne.id', $details ) )
                            );
                        }
                        ?>
                    </td>

                    <td></td>

                    <!-- CUI -->
                    <td><?php echo date_short( Set::extract( 'DEM.Cui.datecontrat', $details ) );?></td>
                    <td colspan="2"><?php echo $html->boolean( !empty( $details['DEM']['Cui']['datecontrat'] ) );?></td>
                    <td><?php
                        if( !empty( $details['DEM']['Cui']['datecontrat'] ) ){
                            echo $html->viewLink(
                                'Voir le contrat',
                                array( 'controller' => 'cuis', 'action' => 'index', Set::extract( 'DEM.Personne.id', $details ) )
                            );
                        }
                        ?>
                    </td>

                    <td><?php echo date_short( Set::extract( 'CJT.Cui.datecontrat', $details ) );?></td>
                    <td colspan="2" ><?php echo $html->boolean( !empty( $details['CJT']['Cui']['datecontrat'] ) );?></td>
                    <td><?php
                        if( !empty( $details['CJT']['Cui']['datecontrat'] ) ){
                            echo $html->viewLink(
                                'Voir le contrat',
                                array( 'controller' => 'cuis', 'action' => 'index', Set::extract( 'CJT.Personne.id', $details ) )
                            );
                        }
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Etape 5 : Affichage des validations des contrats d'd'Engagement -->
        <h2>Etape 5: Validation Contrats ( CER / CUI )</h2>
        <table>
            <thead>
                <tr>
                    <th  class="entete" colspan="8">CER</th>
                    <th></th>
                    <th  class="entete" colspan="8">CUI</th>
                </tr>
                <tr class="odd">
                    <th colspan="4">Parcours Demandeur</th>
                    <th colspan="4">Parcours Conjoint</th>
                    <th></th>
                    <th colspan="4">Parcours Demandeur</th>
                    <th colspan="4">Parcours Conjoint</th>
                </tr>
            </thead>
            <tbody>
                <tr class="odd">
                    <th>Date de validation</th>
                    <th colspan="2">Réalisé</th>
                    <th class="action">Action</th>

                    <th>Date de validation</th>
                    <th colspan="2">Réalisé</th>
                    <th class="action">Action</th>
                    <th></th>
                    <th>Date de validation</th>
                    <th colspan="2">Réalisé</th>
                    <th class="action">Action</th>

                    <th>Date de validation</th>
                    <th colspan="2">Réalisé</th>
                    <th class="action">Action</th>
                </tr>
                <tr>
                    <td><?php echo date_short( Set::extract( 'DEM.Contratinsertion.datevalidation_ci', $details ) );?></td>
                    <td colspan="2"><?php echo $html->boolean( !empty( $details['DEM']['Contratinsertion']['datevalidation_ci'] ) );?></td>
                    <td><?php
                        if( !empty( $details['DEM']['Contratinsertion']['datevalidation_ci'] ) ){
                            echo $html->viewLink(
                                'Voir le contrat',
                                array( 'controller' => 'contratsinsertion', 'action' => 'index', Set::extract( 'DEM.Personne.id', $details ) )
                            );
                        }
                        ?>
                    </td>

                    <td><?php echo date_short( Set::extract( 'CJT.Contratinsertion.datevalidation_ci', $details ) );?></td>
                    <td colspan="2" ><?php echo $html->boolean( !empty( $details['CJT']['Contratinsertion']['datevalidation_ci'] ) );?></td>
                    <td><?php
                        if( !empty( $details['CJT']['Contratinsertion']['datevalidation_ci'] ) ){
                            echo $html->viewLink(
                                'Voir le contrat',
                                array( 'controller' => 'contratsinsertion', 'action' => 'index', Set::extract( 'CJT.Personne.id', $details ) )
                            );
                        }
                        ?>
                    </td>
                    <td></td>
                    <td><?php echo date_short( Set::extract( 'DEM.Cui.datevalidation_ci', $details ) );?></td>
                    <td colspan="2"><?php echo $html->boolean( !empty( $details['DEM']['Cui']['datevalidation_ci'] ) );?></td>
                    <td><?php
                        if( !empty( $details['DEM']['Cui']['datevalidation_ci'] ) ){
                            echo $html->viewLink(
                                'Voir le contrat',
                                array( 'controller' => 'cuis', 'action' => 'index', Set::extract( 'DEM.Personne.id', $details ) )
                            );
                        }
                        ?>
                    </td>

                    <td><?php echo date_short( Set::extract( 'CJT.Cui.datevalidation_ci', $details ) );?></td>
                    <td colspan="2" ><?php echo $html->boolean( !empty( $details['CJT']['Cui']['datevalidation_ci'] ) );?></td>
                    <td><?php
                        if( !empty( $details['CJT']['Cui']['datevalidation_ci'] ) ){
                            echo $html->viewLink(
                                'Voir le contrat',
                                array( 'controller' => 'cuis', 'action' => 'index', Set::extract( 'CJT.Personne.id', $details ) )
                            );
                        }
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <!-- Etape 6 : Affichage des Actions d'insertion engagées -->
        <h2>Etape 6: Actions d'insertion engagées</h2>
        <table>
            <thead>
                <tr class="odd">
                    <th colspan="4">Parcours Demandeur</th>
                    <th colspan="4">Parcours Conjoint</th>
                </tr>
            </thead>
            <tbody>
                <tr class="odd">
                    <th>Actions engagées</th>
                    <th>Date dernière action</th>
                    <th>Réalisé</th>
                    <th class="action">Action</th>

                    <th>Actions engagées</th>
                    <th>Date dernière action</th>
                    <th>Réalisé</th>
                    <th class="action">Action</th>
                </tr>
                <tr>
                    <td><?php echo count( Set::extract( 'DEM.Actioninsertion', $details ) );?></td>
                    <td><?php echo date_short( Set::extract( 'DEM.Actioninsertion.0.Actioninsertion.dd_action', $details ) );?></td>
                    <td><?php echo $html->boolean( !empty( $details['DEM']['Contratinsertion']['datevalidation_ci'] ) );?></td>
                    <td><?php
                        if( !empty( $details['DEM']['Contratinsertion'] ) ){
                            echo $html->viewLink(
                                'Voir les actions d\'insertion',
                                array( 'controller' => 'actionsinsertion', 'action' => 'index', Set::extract( 'DEM.Contratinsertion.id', $details ) )
                            );
                        }
                        ?>
                    </td>

                    <td><?php echo count( Set::extract( 'CJT.Actioninsertion', $details ) );?></td>
                    <td><?php echo date_short( Set::extract( 'CJT.Actioninsertion.0.Actioninsertion.dd_action', $details ) );?></td>
                    <td><?php echo $html->boolean( !empty( $details['CJT']['Actioninsertion']['dd_action'] ) );?></td>
                    <td><?php
                        if( !empty( $details['CJT']['Contratinsertion'] ) ){
                            echo $html->viewLink(
                                'Voir les actions d\'insertion',
                                array( 'controller' => 'actionsinsertion', 'action' => 'index', Set::extract( 'CJT.Contratinsertion.id', $details ) )
                            );
                        }
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Etape 7 : Affichage des bilans de fin de Contrat d'insertion -->
        <h2>Etape 7: Bilan de fin de Contrats ( CER / CUI )</h2>
        <table>
            <thead>
                <tr>
                    <th  class="entete" colspan="8">CER</th>
                    <th></th>
                    <th  class="entete" colspan="8">CUI</th>
                </tr>
                <tr class="odd">
                    <th colspan="4">Parcours Demandeur</th>
                    <th colspan="4">Parcours Conjoint</th>
                    <th></th>
                    <th colspan="4">Parcours Demandeur</th>
                    <th colspan="4">Parcours Conjoint</th>
                </tr>
            </thead>
            <tbody>
                <tr class="odd">
                    <th>Date bilan</th>
                    <th>Bilan</th>
                    <th>Réalisé</th>
                    <th class="action">Action</th>

                    <th>Date bilan</th>
                    <th>Bilan</th>
                    <th>Réalisé</th>
                    <th class="action">Action</th>

                    <th></th>

                    <th>Date bilan</th>
                    <th>Bilan</th>
                    <th>Réalisé</th>
                    <th class="action">Action</th>

                    <th>Date bilan</th>
                    <th>Bilan</th>
                    <th>Réalisé</th>
                    <th class="action">Action</th>
                </tr>
                <tr>
                    <td><?php echo date_short( Set::extract( 'DEM.Contratinsertion.df_ci', $details ) );?></td>
                    <td><?php echo 'Non défini'?></td>
                    <td><?php echo $html->boolean( !empty( $details['DEM']['Contratinsertion']['df_ci'] ) );?></td>
                    <td><?php
                        if( !empty( $details['DEM']['Contratinsertion']['df_ci'] ) ){
                            echo $html->viewLink(
                                'Voir le contrat',
                                array( 'controller' => 'contratsinsertion', 'action' => 'index', Set::extract( 'DEM.Personne.id', $details ) )
                            );
                        }
                        ?>
                    </td>

                    <td><?php echo date_short( Set::extract( 'CJT.Contratinsertion.df_ci', $details ) );?></td>
                    <td><?php echo 'Non défini'?></td>
                    <td><?php echo $html->boolean( !empty( $details['CJT']['Contratinsertion']['df_ci'] ) );?></td>
                    <td><?php
                        if( !empty( $details['CJT']['Contratinsertion']['df_ci'] ) ){
                            echo $html->viewLink(
                                'Voir le contrat',
                                array( 'controller' => 'contratsinsertion', 'action' => 'index', Set::extract( 'CJT.Personne.id', $details ) )
                            );
                        }
                        ?>
                    </td>
                    
                    <td></td>
                    
                    <td><?php echo date_short( Set::extract( 'DEM.Cui.df_ci', $details ) );?></td>
                    <td><?php echo 'Non défini'?></td>
                    <td><?php echo $html->boolean( !empty( $details['DEM']['Cui']['df_ci'] ) );?></td>
                    <td><?php
                        if( !empty( $details['DEM']['Cui']['df_ci'] ) ){
                            echo $html->viewLink(
                                'Voir le contrat',
                                array( 'controller' => 'cuis', 'action' => 'index', Set::extract( 'DEM.Personne.id', $details ) )
                            );
                        }
                        ?>
                    </td>

                    <td><?php echo date_short( Set::extract( 'CJT.Cui.df_ci', $details ) );?></td>
                    <td><?php echo 'Non défini'?></td>
                    <td><?php echo $html->boolean( !empty( $details['CJT']['Cui']['df_ci'] ) );?></td>
                    <td><?php
                        if( !empty( $details['CJT']['Cui']['df_ci'] ) ){
                            echo $html->viewLink(
                                'Voir le contrat',
                                array( 'controller' => 'cuis', 'action' => 'index', Set::extract( 'CJT.Personne.id', $details ) )
                            );
                        }
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Etape 8 : Affichage des poursuites pour le droit -->
        <h2>Etape 8: Poursuite du droit</h2>
        <table>
            <thead>
                <tr class="odd">
                    <th colspan="4">Parcours Demandeur</th>
                    <th colspan="4">Parcours Conjoint</th>
                </tr>
            </thead>
            <tbody>
                <tr class="odd">
                    <th>Décision</th>
                    <th>Date commission pluridisciplinaire</th>
                    <th>Réalisé</th>
                    <th class="action">Action</th>

                    <th>Décision</th>
                    <th>Date commission pluridisciplinaire</th>
                    <th>Réalisé</th>
                    <th class="action">Action</th>
                </tr>
                <tr>
                    <td><?php echo value( $decision_ci, Set::extract( 'DEM.Contratinsertion.decision_ci', $details ) );?></td>
                    <td><?php echo date_short( Set::extract( 'DEM.Contratinsertion.datevalidation_ci', $details ) );?></td>
                    <td><?php echo $html->boolean( !empty( $details['DEM']['Contratinsertion']['decision_ci'] )  && ( $details['DEM']['Contratinsertion']['decision_ci'] != 'E' ) );?></td>
                    <td><?php
                        if( !empty( $details['DEM']['Contratinsertion']['datevalidation_ci'] ) ){
                            echo $html->viewLink(
                                'Voir le contrat',
                                array( 'controller' => 'contratsinsertion', 'action' => 'index', Set::extract( 'DEM.Personne.id', $details ) )
                            );
                        }
                        ?>
                    </td>

                    <td><?php echo value( $decision_ci, Set::extract( 'CJT.Contratinsertion.decision_ci', $details ) );?></td>
                    <td><?php echo date_short( Set::extract( 'CJT.Contratinsertion.datevalidation_ci', $details ) );?></td>
                    <td><?php echo $html->boolean( !empty( $details['CJT']['Contratinsertion']['decision_ci'] ) && ( $details['CJT']['Contratinsertion']['decision_ci'] != 'E' ) );?></td>
                    <td><?php
                        if( !empty( $details['CJT']['Contratinsertion']['datevalidation_ci'] ) ){
                            echo $html->viewLink(
                                'Voir le contrat',
                                array( 'controller' => 'contratsinsertion', 'action' => 'index', Set::extract( 'CJT.Personne.id', $details ) )
                            );
                        }
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="clearer"><hr /></div>
<?php /*debug( $details );*/?>