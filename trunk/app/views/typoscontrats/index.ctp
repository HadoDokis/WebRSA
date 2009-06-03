<?php $this->pageTitle = 'Paramétrage des Types de contrat';?>

<div>
    <h1><?php echo 'Visualisation de la table  ';?></h1>

    <ul class="actionMenu">
        <?php
            echo '<li>'.$html->addLink(
                'Ajouter',
                array( 'controller' => 'typoscontrats', 'action' => 'add' )
            ).' </li>';
        ?>
    </ul>
    <div>
        <h2>Table types de Contrat d'insertion</h2>
        <table>
        <thead>
            <tr>
                <th>Libellé du type de contrat d'insertion</th>
                <th>Rang du contrat d'insertion</th>
                <th colspan="1" class="action">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach( $typoscontrats as $typocontrat ):?>
                <?php echo $html->tableCells(
                            array(
                                h( $typocontrat['Typocontrat']['lib_typo'] ),
                                h( $typocontrat['Typocontrat']['rang'] ),
                                $html->editLink(
                                    'Éditer le contrat d\'insertion ',
                                    array( 'controller' => 'typoscontrats', 'action' => 'edit', $typocontrat['Typocontrat']['id'] )
                                ),
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
<div class="clearer"><hr /></div>