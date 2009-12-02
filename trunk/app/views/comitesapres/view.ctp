<?php $this->pageTitle = 'Comité d\'examen pour l\'APRE';?>
<h1>Détails Comité d'examen</h1>
<?php if( $permissions->check( 'comitesapres', 'add' ) ):?>
    <ul class="actionMenu">
        <?php
            echo '<li>'.$html->editLink(
                'Modifier Comité',
                array( 'controller' => 'comitesapres', 'action' => 'edit', Set::classicExtract( $comiteapre, 'Comiteapre.id' ) )
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

<?php if( isset( $comiteapre['Participantcomite'] ) ):?>
    <h1>Liste des participants</h1>
    <?php if( is_array( $comiteapre['Participantcomite'] ) && count( $comiteapre['Participantcomite'] ) > 0  ):?>
        <ul class="actionMenu">
            <?php
                echo '<li>'.$html->editLink(
                    'Modifier Liste des participants',
                    array( 'controller' => 'comitesapres_participantscomites', 'action' => 'edit', Set::classicExtract( $comiteapre, 'Comiteapre.id' ) )
                ).' </li>';
            ?>
        </ul>
    <div>
        <table class="tooltips_oupas">
            <thead>
                <tr>
                    <th>Nom/Prénom</th>
                    <th>Fonction</th>
                    <th>Organisme</th>
                    <th>N° Téléphone</th>
                    <th class="action">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach( $comiteapre['Participantcomite'] as $participant ) {

                        echo $html->tableCells(
                            array(
                                h( Set::classicExtract( $participant, 'qual' ).' '.Set::classicExtract( $participant, 'nom' ).' '.Set::classicExtract( $participant, 'prenom' ) ),
                                h( Set::classicExtract( $participant, 'fonction' ) ),
                                h( Set::classicExtract( $participant, 'organisme' ) ),
                                h( Set::classicExtract( $participant, 'numtel' ) ),
                                $html->viewLink(
                                    'Voir les participants',
                                    array( 'controller' => 'participantscomites', 'action' => 'index', Set::classicExtract( $participant, 'id' ) ),
                                    $permissions->check( 'comitesapres', 'index' )
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
                    echo '<li>'.$html->editLink(
                        'Modifier Participant',
                        array( 'controller' => 'comitesapres_participantscomites', 'action' => 'add', Set::classicExtract( $comiteapre, 'Comiteapre.id' ) )
                    ).' </li>';
                ?>
            </ul>
        <?php endif;?>

<?php endif;?>

<br />

<?php if( isset( $comiteapre['Apre'] ) ):?>
    <h1>Liste des APREs</h1>
    <?php if( is_array( $comiteapre['Apre'] ) && count( $comiteapre['Apre'] ) > 0  ):?>
        <ul class="actionMenu">
            <?php
                echo '<li>'.$html->editLink(
                    'Modifier Liste APRES',
                    array( 'controller' => 'apres_comitesapres', 'action' => 'edit', Set::classicExtract( $comiteapre, 'Comiteapre.id' ) )
                ).' </li>';
            ?>
        </ul>
    <div>
        <table id="searchResults" class="tooltips_oupas">
            <thead>
                <tr>
                    <th>N° demande APRE</th>
                    <th>NIR</th>
                    <th>Nom/Prénom</th>
                    <th>Localité</th>
                    <th>Préscripteur/Préinscripteur</th>
                    <th>Date demande APRE</th>
                    <th>Quota</th>
                    <th class="action">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach( $comiteapre['Apre'] as $apre ) {
                        $isRecours = Set::classicExtract( $apre, 'ApreComiteapre.comite_pcd_id' );
                        $isRecours = !empty( $isRecours );

                        if( !$isRecours ) {
                            echo $html->tableCells(
                                array(
                                    h( Set::classicExtract( $apre, 'numeroapre' ) ),
                                    h( Set::classicExtract( $apre, 'Personne.nir' ) ),
                                    h( Set::classicExtract( $apre, 'Personne.qual' ).' '.Set::classicExtract( $apre, 'Personne.nom' ).' '.Set::classicExtract( $apre, 'Personne.prenom' ) ),
                                    h( Set::classicExtract( $apre, 'Adresse.locaadr' ) ),
                                    h( Set::enum( Set::classicExtract( $apre, 'referentapre_id' ), $referentapre ) ),
                                    h( date_short( Set::classicExtract( $apre, 'datedemandeapre' ) ) ),
                                    h( Set::classicExtract( $apre, 'quota' ) ),
                                    $html->viewLink(
                                        'Voir les apres',
                                        array( 'controller' => 'apres', 'action' => 'index', Set::classicExtract( $apre, 'personne_id' ) ),
                                        $permissions->check( 'comitesapres', 'index' )
                                    )
                                ),
                                array( 'class' => 'odd' ),
                                array( 'class' => 'even' )
                            );
                        }
                    }
                ?>
            </tbody>
        </table>
    </div>
        <?php else:?>
            <ul class="actionMenu">
                <?php
                    echo '<li>'.$html->editLink(
                        'Modifier Liste APRE',
                        array( 'controller' => 'apres_comitesapres', 'action' => 'add', Set::classicExtract( $comiteapre, 'Comiteapre.id' ) )
                    ).' </li>';
                ?>
            </ul>
        <?php endif;?>

<?php endif;?>

<br />

<?php if( isset( $comiteapre['Apre'] ) ):?>
    <h1>Liste des APREs en Recours</h1>
    <?php if( is_array( $comiteapre['Apre'] ) && count( $comiteapre['Apre'] ) > 0  ):?>
        <ul class="actionMenu">
            <?php
                echo '<li>'.$html->editLink(
                    'Modifier Liste APRES en Recours',
                    array( 'controller' => 'apres_comitesapres', 'action' => 'edit', Set::classicExtract( $comiteapre, 'Comiteapre.id' ), 'recours' => 1 )
                ).' </li>';
            ?>
        </ul>
    <div>
        <table id="searchResults" class="tooltips_oupas">
            <thead>
                <tr>
                    <th>N° demande APRE</th>
                    <th>NIR</th>
                    <th>Nom/Prénom</th>
                    <th>Localité</th>
                    <th>Préscripteur/Préinscripteur</th>
                    <th>Date demande APRE</th>
                    <th>Recours</th>
                    <th class="action">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach( $comiteapre['Apre'] as $apre ) {
                        $isRecours = Set::classicExtract( $apre, 'ApreComiteapre.comite_pcd_id' );
                        $isRecours = !empty( $isRecours );

                        if( $isRecours ) {
                            echo $html->tableCells(
                                array(
                                    h( Set::classicExtract( $apre, 'numeroapre' ) ),
                                    h( Set::classicExtract( $apre, 'Personne.nir' ) ),
                                    h( Set::classicExtract( $apre, 'Personne.qual' ).' '.Set::classicExtract( $apre, 'Personne.nom' ).' '.Set::classicExtract( $apre, 'Personne.prenom' ) ),
                                    h( Set::classicExtract( $apre, 'Adresse.locaadr' ) ),
                                    h( Set::enum( Set::classicExtract( $apre, 'referentapre_id' ), $referentapre ) ),
                                    h( date_short( Set::classicExtract( $apre, 'datedemandeapre' ) ) ),
                                    h( Set::enum( Set::classicExtract( $apre, 'ApreComiteapre.recoursapre' ), $options['recoursapre'] ) ),
                                    $html->viewLink(
                                        'Voir les apres',
                                        array( 'controller' => 'apres', 'action' => 'index', Set::classicExtract( $apre, 'personne_id' ) ),
                                        $permissions->check( 'comitesapres', 'index' )
                                    )
                                ),
                                array( 'class' => 'odd' ),
                                array( 'class' => 'even' )
                            );
                        }
                    }
                ?>
            </tbody>
        </table>
    </div>
        <?php else:?>
            <ul class="actionMenu">
                <?php
                    echo '<li>'.$html->editLink(
                        'Modifier Liste APRES en Recours',
                        array( 'controller' => 'apres_comitesapres', 'action' => 'add', Set::classicExtract( $comiteapre, 'Comiteapre.id' ), 'recours' => 1 )
                    ).' </li>';
                ?>
            </ul>
        <?php endif;?>

<?php endif;?>
<div class="clearer"><hr /></div>