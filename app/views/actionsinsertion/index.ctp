<?php $this->pageTitle = 'Actions d\'insertion pour le contrat';?>
<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>


<?php
    if( $this->action == 'add' ) {
        $this->pageTitle = 'Ajout d\'une action d\'insertion';
    }
    else {
        $this->pageTitle = 'Actions d\'insertion ';
        $foyer_id = $this->data['Personne']['foyer_id'];
    }
?>


<div class="with_treemenu">
    <h1><?php echo 'Actions d\'insertion pour le contrat ';?></h1>

    <?php if( empty( $actionsinsertion ) ):?>
        <p><b>Aides</b></p>
            <?php 
                echo $html->addLink(
                                'Ajouter une aide',
                                array( 'controller' => 'aidesdirectes', 'action' => 'add', $contratinsertion_id )
                            );
            ?>
        <p class="notice">Ce contrat ne possède pas d'aides d'insertion.</p>

        <p><b>Prestations</b></p>
            <?php       echo $html->addLink(
                                'Ajouter une prestation',
                                array( 'controller' => 'prestsform', 'action' => 'add', $contratinsertion_id )
                            );
            ?>
        <p class="notice">Ce contrat ne possède pas de prestations d'insertion.</p>

    <?php else: ?>
        <table class="tooltips">
            <thead>
                <tr>
                    <p><b>Aides</b></p>
                    <?php 
                        echo $html->addLink(
                                        'Ajouter une aide',
                                        array( 'controller' => 'aidesdirectes', 'action' => 'add', $contratinsertion_id )
                                    );
                    ?>
                    <th width="220">Type d'aide</th>
                    <th width="220">Libellé de l'aide</th>
                    <th width="220">Date de l'aide</th>

                    <th colspan="2" class="action">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach( $actionsinsertion as $actioninsertion ):?>
                    <?php foreach( $actioninsertion['Aidedirecte'] as $aidedirecte ):?>
                    <?php

                        echo $html->tableCells(
                            array(
                                h( $typo_aide[$aidedirecte['typo_aide']] ),
                                h( $actions[$aidedirecte['lib_aide']] ),
                                h( $aidedirecte['date_aide'] ),
                                $html->editLink(
                                    'Éditer l\'aide ',
                                    array( 'controller' => 'aidesdirectes', 'action' => 'edit', $aidedirecte['id'] )
                                )
                            )
                        );
                    ?>
                    <?php endforeach;?>
                <?php endforeach;?>
            </tbody>
        </table>
        <table class="tooltips" title="Prestations">
            <thead>
                <tr>
                    <p><b>Prestations</b></p>
                     <?php       echo $html->addLink(
                                        'Ajouter une prestation',
                                        array( 'controller' => 'prestsform', 'action' => 'add', $contratinsertion_id)
                                    );
                    ?>
                    <th width="220">Libellé de la prestation</th>
                    <th width="220">Référent de la prestation</th>
                    <th width="220">Date de la prestation</th>

                    <th colspan="2" class="action">Actions</th>

                </tr>
            </thead>
            <tbody>
                <?php foreach( $actionsinsertion as $actioninsertion ):?>
                    <?php foreach( $actioninsertion['Prestform'] as $prestform ):?>
                        <?php
                            //foreach( $prestform['Refpresta'] as $refpresta ):
                            echo $html->tableCells(
                                array(
                                    h( $actions[$prestform['lib_presta']] ),
                                    h( $prestform['Refpresta']['nomrefpresta'].' '.$prestform['Refpresta']['prenomrefpresta']),
                                    h( $prestform['date_presta']),
                                    //h( $actioninsertion['Refpresta']['nom']),
//                                     $html->viewLink(
//                                         'Voir la prestation',
//                                         array( 'controller' => 'prestsform', 'action' => 'view', $actioninsertion['id'])
//                                     ),
                                    $html->editLink(
                                        'Éditer la prestation ',
                                        array( 'controller' => 'prestsform', 'action' => 'edit', $prestform['id'] )
                                    )
                                )
                            );
                        ?>
                    <?php endforeach;?>
                <?php endforeach;?>
            </tbody>
        </table>

    <?php endif;?>

</div>
<div class="clearer"><hr /></div>