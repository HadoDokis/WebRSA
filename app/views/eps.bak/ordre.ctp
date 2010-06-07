<?php
    echo $html->tag(
        'h1',
        $this->pageTitle = __d( 'ep', "Eps::{$this->action}", true ).': '.Set::classicExtract( $ep, 'Ep.name' )
    )
?>
<?php if( $permissions->check( 'eps', 'ordre' ) ):?>
    <ul class="actionMenu">
        <?php
            echo '<li>'.$html->editLink(
                'Modifier l\'équipe pluridisciplinaire',
                array( 'controller' => 'eps', 'action' => 'edit', Set::classicExtract( $ep, 'Ep.id' ) )
            ).' </li>';
        ?>
    </ul>
<?php endif;?>
 <?php echo $xform->create( 'EpOJ', array( 'type' => 'post', 'url' => Router::url( null, true ) ) ); ?> 
<div id="ficheCI" class="aere">
        <table>
            <tbody>
                <tr class="even">
                    <th><?php __( 'Intitulé de l\'équipe');?></th>
                    <td><?php echo Set::classicExtract( $ep, 'Ep.name' );?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'Date de l\'équipe' );?></th>
                    <td><?php echo  $locale->date( 'Locale->datetime', Set::classicExtract( $ep, 'Ep.date' ) );?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'Lieu de l\'équipe' );?></th>
                    <td><?php echo Set::classicExtract( $ep, 'Ep.localisation' );?></td>
                </tr>
            </tbody>
        </table>
</div>


<div id="tabbedWrapper" class="tabs">
    <?php if( isset( $ep['Partep'] ) ):?>

        <div id="participants">
            <h2 class="title">Liste des participants</h2>
            <?php if( is_array( $ep['Partep'] ) && count( $ep['Partep'] ) > 0  ):?>
                <ul class="actionMenu">
                    <?php
                        echo '<li>'.$html->editLink(
                            'Modifier Liste des participants',
                            array( 'controller' => 'eps_partseps', 'action' => 'edit', Set::classicExtract( $ep, 'Ep.id' ) )
                        ).' </li>';
                    ?>
                </ul>
                <div>
                    <table class="tooltips">
                        <thead>
                            <tr>
                                <th>Nom/Prénom</th>
                                <th>Rôle</th>
                                <th>Téléphone</th>
                                <th>Email</th>
                                <th>Présence prévue</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach( $ep['Partep'] as $participant ) {
// debug($participant);
                                    echo $html->tableCells(
                                        array(
                                            h( Set::classicExtract( $participant, 'qual' ).' '.Set::classicExtract( $participant, 'nom' ).' '.Set::classicExtract( $participant, 'prenom' ) ),
                                            h( Set::classicExtract( $participant, 'Rolepartep.0.name' ) ),
                                            h( Set::classicExtract( $participant, 'tel' ) ),
                                            h( Set::classicExtract( $participant, 'email' ) ),
                                            h( $html->boolean( Set::classicExtract( $participant, 'EpPartep.presencepre' ), false ) )/*,
                                            $html->viewLink(
                                                'Voir le participant',
                                                array( 'controller' => 'partep', 'action' => 'index', Set::classicExtract( $participant, 'id' ) )
                                            )*/
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
                        echo '<li>'.$html->editLink(
                            'Modifier Participant',
                            array( 'controller' => 'eps_partseps', 'action' => 'add', Set::classicExtract( $ep, 'Ep.id' ) )
                        ).' </li>';
                    ?>
                </ul>
                <p class="notice">Aucun participant présent pour cette équipe.</p>
            <?php endif;?>
        </div>
    <?php endif;?>


    <?php if( isset( $ep['Demandereorient'] ) ):?>
        <div id="themesatraiter">
            <ul class="actionMenu">
                <?php
                    echo '<li>'.$html->editLink(
                        'Modifier les thèmes à traiter',
                        array( 'controller' => 'eps_themes', 'action' => 'edit', Set::classicExtract( $ep, 'Ep.id' ) )
                    ).' </li>';
                ?>
            </ul>

            <h2 class="title">Thèmes à traiter</h2>
            <?php if( is_array( $ep['Demandereorient'] ) && count( $ep['Demandereorient'] ) > 0 ):?>
                <div>
                    <?php echo $html->tag( 'h3', 'Thème 1: demandes de réorientation    ' );?>
                    <table id="searchResults" class="tooltips aere">
                        <thead>
                            <tr>
                                <th>Nom/Prénom Personne</th>
                                <th>Motif demande</th>
                                <th>Commentaire</th>
                                <th>Urgent</th>
                                <th>Accord bénéficiaire</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach( $ep['Demandereorient'] as $demandereorient ) {
                                    echo $html->tableCells(
                                        array(
                                            h( Set::classicExtract( $demandereorient, 'Personne.qual' ).' '.Set::classicExtract( $demandereorient, 'Personne.nom' ).' '.Set::classicExtract( $demandereorient, 'Personne.prenom' ) ),
                                            h( Set::enum( Set::classicExtract( $demandereorient, 'motifdemreorient_id' ), $motifdemreorient ) ),
                                            h( Set::classicExtract( $demandereorient, 'commentaire' ) ),
                                            h( $html->boolean( Set::classicExtract( $demandereorient, 'urgent' ), false ) ),
                                            h( $html->boolean( Set::classicExtract( $demandereorient, 'accordbenef' ), false ) )
                                        ),
                                        array( 'class' => 'odd' ),
                                        array( 'class' => 'even' )
                                    );
                                }
                            ?>
                        </tbody>
                    </table>

                    <?php
//                         echo $html->tag( 'h3', 'Thème 2: parcours détectés' );
// 
//                         echo $default->index(
//                             $this->viewVars['ep']['Parcoursdetecte'],
//                             array(
//                                 'Orientstruct.Personne.nom_complet',
//                                 'Parcoursdetecte.signale' => array( 'type' => 'boolean' ),
//                                 'Parcoursdetecte.commentaire'
//                             )
//                         );
                    ?>
                </div>
            <?php else:?>
                <!--<ul class="actionMenu">
                    <?php
                        echo '<li>'.$html->editLink(
                            'Modifier les thèmes à traiter',
                            array( 'controller' => 'eps_themes', 'action' => 'add', Set::classicExtract( $ep, 'Ep.id' ) )
                        ).' </li>';
                    ?>
                </ul>-->
                <p class="notice">Aucun thème présent pour cette équipe.</p>
            <?php endif;?>
        </div>
    <?php endif;?>

</div>

<div class="submit">
    <?php if( ( is_array( $ep['Demandereorient'] ) && count( $ep['Demandereorient'] ) > 0 ) && ( is_array( $ep['Partep'] ) && count( $ep['Partep'] ) > 0 ) ):?>
        <?php
            echo $form->submit( 'Finaliser', array( 'name' => 'Valid', 'div' => false ) );
        ?>
    <?php endif;?>
</div>
<?php echo $xform->end();?>
<!-- *********************************************************************** -->

<?php
    echo $javascript->link( 'prototype.livepipe.js' );
    echo $javascript->link( 'prototype.tabs.js' );
?>

<script type="text/javascript">
    makeTabbed( 'tabbedWrapper', 2 );
</script>