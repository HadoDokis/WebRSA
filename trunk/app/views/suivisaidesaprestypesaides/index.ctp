<?php echo $xform->create( 'Suiviaideapretypeaide' );?>
<div>
<h1><?php echo $this->pageTitle = 'Paramétrage des types d\'aides en fonction des personnes chargés du suivi';?></h1>
    <?php if( empty( $suivisaidesaprestypesaides ) ):?>
    <ul class="actionMenu">
        <?php
            echo '<li>'.$xhtml->addLink(
                'Ajouter',
                array( 'controller' => 'suivisaidesaprestypesaides', 'action' => 'add' )
            ).' </li>';
        ?>
    </ul>

        <p class="notice">Aucune aide présente pour le moment.</p>
    <?php else:?>
        <ul class="actionMenu">
            <?php
                echo '<li>'.$xhtml->editLink(
                    'Modifier',
                    array( 'controller' => 'suivisaidesaprestypesaides', 'action' => 'edit' )
                ).' </li>';
            ?>
        </ul>
    <div>
        <h2>Table des types d'aides en fonction des personnes chargées du suivi</h2>
        <table>
        <thead>
            <tr>
                <th>Type d'aide</th>
                <th>Personne responsable</th>
                <!--<th colspan="2" class="action">Actions</th>-->
            </tr>
        </thead>
        <tbody>
            <?php foreach( $suivisaidesaprestypesaides as $suiviaideapretypeaide ):?>
                <?php echo $xhtml->tableCells(
                    array(
                        h( Set::enum( Set::classicExtract( $suiviaideapretypeaide, 'Suiviaideapretypeaide.typeaide' ), $natureAidesApres ) ),
                        h( Set::enum( Set::classicExtract( $suiviaideapretypeaide, 'Suiviaideapretypeaide.suiviaideapre_id' ), $personnessuivis ) ),
//                         $xhtml->editLink(
//                             'Éditer le suivi d\'aide ',
//                             array( 'controller' => 'suivisaidesaprestypesaides', 'action' => 'edit', $suiviaideapretypeaide['Suiviaideapretypeaide']['id'] )
//                         ),
//                         $xhtml->deleteLink(
//                             'Supprimer le suivi d\'aide ',
//                             array( 'controller' => 'suivisaidesaprestypesaides', 'action' => 'delete', $suiviaideapretypeaide['Suiviaideapretypeaide']['id'] )
//                         )
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