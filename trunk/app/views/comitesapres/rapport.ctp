<?php $this->pageTitle = 'Comité d\'examen pour l\'APRE';?>
<h1>Détails Comité d'examen</h1>
<?php if( $permissions->check( 'comitesapres', 'add' ) ):?>
    <ul class="actionMenu">
        <?php
            echo '<li>'.$xhtml->editLink(
                'Modifier Comité',
                array( 'controller' => 'comitesapres', 'action' => 'edit', Set::classicExtract( $comiteapre, 'Comiteapre.id' ), 'rapport' => 1 )
            ).' </li>';
        ?>
    </ul>
<?php endif;?>


<div id="ficheCI">
        <table>
            <tbody>
                <tr class="even">
                    <th><?php __( 'Date du comité');?></th>
                    <td><?php echo date_short( Set::classicExtract( $comiteapre, 'Comiteapre.datecomite' ) );?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'Heure du comité' );?></th>
                    <td><?php echo $locale->date( 'Time::short', Set::classicExtract( $comiteapre, 'Comiteapre.heurecomite' ) );?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'Lieu du comité' );?></th>
                    <td><?php echo Set::classicExtract( $comiteapre, 'Comiteapre.lieucomite' );?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'Intitulé du comité' );?></th>
                    <td><?php echo Set::classicExtract( $comiteapre, 'Comiteapre.intitulecomite' );?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'Observations du comité' );?></th>
                    <td><?php echo Set::classicExtract( $comiteapre, 'Comiteapre.observationcomite' );?></td>
                </tr>
            </tbody>
        </table>
</div>

<br />

<div id="tabbedWrapper" class="tabs">
    <?php if( isset( $comiteapre['Participantcomite'] ) ):?>
        <div id="participants">
            <h2 class="title">Présence des participants</h2>
            <?php if( is_array( $comiteapre['Participantcomite'] ) && count( $comiteapre['Participantcomite'] ) > 0  ):?>
                <ul class="actionMenu">
                    <?php
                        echo '<li>'.$xhtml->editLink(
                            'Modifier Liste des participants',
                            array( 'controller' => 'comitesapres_participantscomites', 'action' => 'rapport', Set::classicExtract( $comiteapre, 'Comiteapre.id' ), 'rapport' => 1 )
                        ).' </li>';
                    ?>
                </ul>
            <div>
                <table class="tooltips">
                    <thead>
                        <tr>
                            <th>Nom/Prénom</th>
                            <th>Fonction</th>
                            <th>Organisme</th>
                            <th>N° Téléphone</th>
                            <th>Présence</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach( $comiteapre['Participantcomite'] as $participant ) {
        // debug($participant);
                                echo $xhtml->tableCells(
                                    array(
                                        h( Set::classicExtract( $participant, 'qual' ).' '.Set::classicExtract( $participant, 'nom' ).' '.Set::classicExtract( $participant, 'prenom' ) ),
                                        h( Set::classicExtract( $participant, 'fonction' ) ),
                                        h( Set::classicExtract( $participant, 'organisme' ) ),
                                        h( Set::classicExtract( $participant, 'numtel' ) ),
                                        h( Set::enum( Set::classicExtract( $participant, 'ComiteapreParticipantcomite.presence' ), $options['presence'] ) ),
                                    ),
                                    array( 'class' => 'odd' ),
                                    array( 'class' => 'even' )
                                );
                            }
                        ?>
                    </tbody>
                </table>
            </div>
            <?php else:?>
                <ul class="actionMenu">
                    <?php
                        echo '<li>'.$xhtml->editLink(
                            'Modifier Participant',
                            array( 'controller' => 'comitesapres_participantscomites', 'action' => 'rapport', Set::classicExtract( $comiteapre, 'Comiteapre.id' ), 'rapport' => 1 )
                        ).' </li>';
                    ?>
                </ul>
            <?php endif;?>
        </div>
    <?php endif;?>


    <?php
        /**
            $apresSansRecours = Set::extract( $comiteapre, '/Apre/ApreComiteapre[id=/[^(159|160)]/]' );
            debug( $apresSansRecours );
        */

        $apresAvecRecours = array();
        $apresSansRecours = array();

        foreach( $comiteapre['Apre'] as $apre ) {
            $comite_pcd_id = Set::classicExtract( $apre, 'ApreComiteapre.comite_pcd_id' );
            if( !empty( $comite_pcd_id ) ) {
                $apresAvecRecours[] = array( 'Apre' => $apre );
            }
            else {
                $apresSansRecours[] = array( 'Apre' => $apre );
            }
        }

    ?>

    <?php if( isset( $comiteapre['Apre'] ) ):?>
        <div id="apres">
            <h2 class="title">Décision des APREs</h2>
            <?php if( is_array( $comiteapre['Apre'] ) && count( $comiteapre['Apre'] ) > 0  ):?>
<!--
            <ul class="actionMenu">
                <?php
                    /*echo '<li>'.$xhtml->editLink(
                        'Modifier Liste APRES',
                        array( 'controller' => 'cohortescomitesapres', 'action' => 'aviscomite', 'Cohortecomiteapre__id' => Set::classicExtract( $comiteapre, 'Comiteapre.id' ), 'rapport' => 1 )
                    ).' </li>';*/
                ?>
            </ul>-->

        <div>
            <table id="searchResults" class="tooltips">
                <thead>
                    <tr>
                        <th>N° demande APRE</th>
                        <th>Nom/Prénom</th>
                        <th>Localité</th>
                        <th>Préscripteur/Préinscripteur</th>
                        <th>Date demande APRE</th>
                        <th>Demande de recours</th>
                        <th>Décision comité</th>
                        <th>Montant attribué</th>
                        <th colspan="3" class="action">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach( $comiteapre['Apre'] as $apre ) {

//     debug($apre);
                            echo $xhtml->tableCells(
                                array(
                                    h( Set::classicExtract( $apre, 'numeroapre' ) ),
                                    h( Set::classicExtract( $apre, 'Personne.qual' ).' '.Set::classicExtract( $apre, 'Personne.nom' ).' '.Set::classicExtract( $apre, 'Personne.prenom' ) ),
                                    h( Set::classicExtract( $apre, 'Personne.Foyer.Adressefoyer.0.Adresse.locaadr' ) ),
                                    h( Set::enum( Set::classicExtract( $apre, 'referent_id' ), $referent ) ),
                                    h( date_short( Set::classicExtract( $apre, 'datedemandeapre' ) ) ),
                                    h( Set::enum( Set::classicExtract( $apre, 'ApreComiteapre.recoursapre' ), $options['recoursapre'] ) ),
                                    h( Set::enum( Set::classicExtract( $apre, 'ApreComiteapre.decisioncomite' ), $options['decisioncomite'] ) ),
                                    h( Set::classicExtract( $apre, 'ApreComiteapre.montantattribue' ) ),
//                                     h( Set::classicExtract( $apre, 'ApreComiteapre.observationcomite') ),
                                    $xhtml->viewLink(
                                        'Voir les apres',
                                        array( 'controller' => 'apres', 'action' => 'index', Set::classicExtract( $apre, 'personne_id' ) ),
                                        $permissions->check( 'comitesapres', 'index' )
                                    ),
                                    $xhtml->editLink(
                                        'Modifier la décision',
                                        array( 'controller' => 'cohortescomitesapres', 'action' => 'editdecision', Set::classicExtract( $apre, 'id' ) ),
                                        false && $permissions->check( 'comitesapres', 'index' )
                                    ),
                                    $xhtml->notificationsApreLink(
                                        'Notifier la décision',
                                        array( 'controller' => 'cohortescomitesapres', 'action' => 'notificationscomite', 'Cohortecomiteapre__id' => Set::classicExtract( $comiteapre, 'Comiteapre.id' ) ),
					true,
                                        $permissions->check( 'cohortescomitesapres', 'notificationscomite' )
                                    )
                                ),
                                array( 'class' => 'odd' ),
                                array( 'class' => 'even' )
                            );
                        }
                    ?>
                </tbody>
            </table>
        </div>
            <?php else:?>
                <ul class="actionMenu">
                    <?php
                        echo '<li>'.$xhtml->editLink(
                            'Modifier Liste APRE',
                            array( 'controller' => 'apres_comitesapres', 'action' => 'add', Set::classicExtract( $comiteapre, 'Comiteapre.id' ), 'rapport' => 1 )
                        ).' </li>';
                    ?>
                </ul>
                <p class="notice">Aucune demande d'APRE présente.</p>
            <?php endif;?>
        </div>
    <?php endif;?>
</div>
<div class="clearer"><hr /></div>

<!-- *********************************************************************** -->

<?php
    echo $javascript->link( 'prototype.livepipe.js' );
    echo $javascript->link( 'prototype.tabs.js' );
?>

<script type="text/javascript">
    makeTabbed( 'tabbedWrapper', 2 );
</script>
