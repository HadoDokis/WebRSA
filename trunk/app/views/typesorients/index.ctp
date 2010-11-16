<?php $this->pageTitle = 'Paramétrage des Types d\'orientation';?>
<?php echo $xform->create( 'Typeorient' );?>
<div>
    <h1><?php echo 'Visualisation de la table  ';?></h1>

    <ul class="actionMenu">
        <?php
            echo '<li>'.$xhtml->addLink(
                'Ajouter',
                array( 'controller' => 'typesorients', 'action' => 'add' )
            ).' </li>';
        ?>
    </ul>
    <div>
        <h2>Table Types d'orientation</h2>
        <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Type d'orientation</th>
                <th>Parent</th>
                <th>Modèle de notification</th>
                <th>Modèle de notification pour cohorte</th>
                <th colspan="2" class="action">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach( $typesorients as $typeorient ):?>
				<?php
					$nbOccurences = Set::enum( $typeorient['Typeorient']['id'], $occurences );
					$nbOccurences = ( is_numeric( $nbOccurences ) ? $nbOccurences : 0 );
					echo $xhtml->tableCells(
                            array(
                                h( $typeorient['Typeorient']['id'] ),
                                h( $typeorient['Typeorient']['lib_type_orient'] ),
                                h( $typeorient['Typeorient']['parentid'] ),
                                h( $typeorient['Typeorient']['modele_notif'] ),
                                h( $typeorient['Typeorient']['modele_notif_cohorte'] ),
                                $xhtml->editLink(
                                    'Éditer le type d\'orientation',
                                    array( 'controller' => 'typesorients', 'action' => 'edit', $typeorient['Typeorient']['id'] )
                                ),
                                $xhtml->deleteLink(
                                    'Supprimer le type d\'orientation',
                                    array( 'controller' => 'typesorients', 'action' => 'delete', $typeorient['Typeorient']['id'] ),
                                    ( $permissions->check( 'typesorients', 'delete' ) && ( $nbOccurences == 0 ) )
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