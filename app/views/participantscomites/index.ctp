<?php $this->pageTitle = 'Paramétrage des participants au Comité de l\'APRE';?>
<?php echo $form->create( 'Paramsparticipants', array( 'url'=> Router::url( null, true ) ) );?>
<div>
    <h1><?php echo 'Visualisation de la table participant au comité APRE ';?></h1>

    <ul class="actionMenu">
        <?php
            echo '<li>'.$xhtml->addLink(
                'Ajouter',
                array( 'controller' => 'participantscomites', 'action' => 'add' )
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
                <?php echo $xhtml->tableCells(
                            array(
                                h( Set::classicExtract( $qual, Set::classicExtract( $participant, 'Participantcomite.qual' ) ) ),
                                h( Set::classicExtract( $participant, 'Participantcomite.nom' ) ),
                                h( Set::classicExtract( $participant, 'Participantcomite.prenom' ) ),
                                h( Set::classicExtract( $participant, 'Participantcomite.fonction' ) ),
                                h( Set::classicExtract( $participant, 'Participantcomite.organisme' ) ),
                                h( Set::classicExtract( $participant, 'Participantcomite.numtel' ) ),
                                h( Set::classicExtract( $participant, 'Participantcomite.mail' ) ),
                                $xhtml->editLink(
                                    'Éditer le participant ',
                                    array( 'controller' => 'participantscomites', 'action' => 'edit', $participant['Participantcomite']['id'] )
                                ),
                                $xhtml->deleteLink(
                                    'Supprimer le participant ',
                                    array( 'controller' => 'participantscomites', 'action' => 'delete', $participant['Participantcomite']['id'] )
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
    <div class="submit">
        <?php
            echo $form->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) );
        ?>
    </div>
</div>
<div class="clearer"><hr /></div>
<?php echo $form->end();?>