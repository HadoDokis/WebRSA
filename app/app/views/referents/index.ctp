<?php $this->pageTitle = 'Paramétrage des référents';?>
<?php echo $xform->create( 'Referent' );?>
<div>
    <h1><?php echo 'Visualisation de la table référents ';?></h1>

    <ul class="actionMenu">
        <?php
            echo '<li>'.$html->addLink(
                'Ajouter',
                array( 'controller' => 'referents', 'action' => 'add' )
            ).' </li>';
        ?>
    </ul>

    <?php if( empty( $referents ) ):?>
        <p class="notice">Aucun référent présent pour le moment.</p>

    <?php else:?>
    <div>
        <h2>Table Référents</h2>
        <table>
        <thead>
            <tr>
                <th>Civilité</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Fonction</th>
                <th>N° téléphone</th>
                <th>Email</th>
                <th>Structure référente liée</th>
                <th colspan="2" class="action">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach( $referents as $referent ):?>
                <?php echo $html->tableCells(
                            array(
                                h( $qual[$referent['Referent']['qual']] ),
                                h( $referent['Referent']['nom'] ),
                                h( $referent['Referent']['prenom'] ),
                                h( $referent['Referent']['fonction'] ),
                                h( $referent['Referent']['numero_poste'] ),
                                h( $referent['Referent']['email'] ),
                                h( $sr[$referent['Referent']['structurereferente_id']] ),
                                $html->editLink(
                                    'Éditer le référent',
                                    array( 'controller' => 'referents', 'action' => 'edit', $referent['Referent']['id'] )
                                ),
                                $html->deleteLink(
                                    'Supprimer le référent',
                                    array( 'controller' => 'referents', 'action' => 'delete', $referent['Referent']['id'] )
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
<?php endif?>
</div>
    <div class="submit">
        <?php
            echo $xform->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) );
        ?>
    </div>

<div class="clearer"><hr /></div>
<?php echo $xform->end();?>