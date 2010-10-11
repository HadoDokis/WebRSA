<?php $this->pageTitle = 'Paramétrage des Structures référentes';?>
<?php echo $xform->create( 'Structurereferente' );?>
<div>
    <h1><?php echo 'Visualisation de la table  ';?></h1>

    <?php if( $permissions->check( 'structuresreferentes', 'add' ) ):?>
        <ul class="actionMenu">
            <?php
                echo '<li>'.$html->addLink(
                    'Ajouter',
                    array( 'controller' => 'structuresreferentes', 'action' => 'add' )
                ).' </li>';
            ?>
        </ul>
    <?php endif;?>
    <div>
        <h2>Table Structures référentes</h2>
        <table>
        <thead>
            <tr>
                <th>Nom de la structure</th>
                <th>N° de voie</th>
                <th>Type de voie</th>
                <th>Nom de voie</th>
                <th>Code postal</th>
                <th>Ville</th>
                <th>Code insee</th>
                <th>Type d'orientation</th>
                <th colspan="2" class="action">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach( $structuresreferentes as $structurereferente ):?>
                <?php

                    echo $html->tableCells(
                        array(
                            h( $structurereferente['Structurereferente']['lib_struc'] ),
                            h( $structurereferente['Structurereferente']['num_voie'] ),
                            h( isset( $typevoie[$structurereferente['Structurereferente']['type_voie']] ) ? $typevoie[$structurereferente['Structurereferente']['type_voie']] : null ),
                            h( $structurereferente['Structurereferente']['nom_voie'] ),
                            h( $structurereferente['Structurereferente']['code_postal'] ),
                            h( $structurereferente['Structurereferente']['ville'] ),
                            h( $structurereferente['Structurereferente']['code_insee'] ),
                            h( $typeorient[$structurereferente['Structurereferente']['typeorient_id']] ),
                            $html->editLink(
                                'Éditer la structure référente ',
                                array( 'controller' => 'structuresreferentes', 'action' => 'edit', $structurereferente['Structurereferente']['id'] )
                            ),
                            $html->deleteLink(
                                'Supprimer la structure référente ',
                                array( 'controller' => 'structuresreferentes', 'action' => 'delete', $structurereferente['Structurereferente']['id'] )
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
</div>
    <div class="submit">
        <?php
            echo $xform->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) );
        ?>
    </div>

<div class="clearer"><hr /></div>
<?php echo $xform->end();?>