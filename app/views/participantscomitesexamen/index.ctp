<?php $this->pageTitle = 'Paramétrage des participants au Comité de l\'APRE';?>

<div>
    <h1><?php echo 'Visualisation de la table participant au comité APRE ';?></h1>

    <ul class="actionMenu">
        <?php
            echo '<li>'.$html->addLink(
                'Ajouter',
                array( 'controller' => 'participantscomitesexamen', 'action' => 'add' )
            ).' </li>';
        ?>
    </ul>
    <?php if( empty( $participants ) ):?>
        <p class="notice">Aucun participant présent pour le moment.</p>
    <?php else:?>
    <div>
        <h2>Table des Participants APRE</h2>
        <table>
        <thead>
            <tr>
                <th>Civilité</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Fonction</th>
                <th>Organisme</th>
                <th>N° de téléphone</th>
                <th>Email</th>
                <th colspan="2" class="action">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach( $participants as $participant ):?>
                <?php echo $html->tableCells(
                            array(
                                h( Set::classicExtract( $qual, Set::classicExtract( $participant, 'Participantcomiteexamen.qual' ) ) ),
                                h( Set::classicExtract( $participant, 'Participantcomiteexamen.nom' ) ),
                                h( Set::classicExtract( $participant, 'Participantcomiteexamen.prenom' ) ),
                                h( Set::classicExtract( $participant, 'Participantcomiteexamen.fonction' ) ),
                                h( Set::classicExtract( $participant, 'Participantcomiteexamen.organisme' ) ),
                                h( Set::classicExtract( $participant, 'Participantcomiteexamen.numtel' ) ),
                                h( Set::classicExtract( $participant, 'Participantcomiteexamen.mail' ) ),
                                $html->editLink(
                                    'Éditer le participant ',
                                    array( 'controller' => 'participantscomitesexamen', 'action' => 'edit', $participant['Participantcomiteexamen']['id'] )
                                ),
                                $html->deleteLink(
                                    'Supprimer le participant ',
                                    array( 'controller' => 'participantscomitesexamen', 'action' => 'delete', $participant['Participantcomiteexamen']['id'] )
                                )
                            ),
                            array( 'class' => 'odd' ),
                            array( 'class' => 'even' )
                        );
                ?>
            <?php endforeach;?>
            </tbody>
        </table>
    </div>
    <?php endif;?>
</div>
<div class="clearer"><hr /></div>