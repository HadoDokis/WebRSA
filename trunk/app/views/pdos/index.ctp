<?php $this->pageTitle = 'Paramétrages des PDOs';?>
<h1>Paramétrage des PDOs</h1>

<?php echo $form->create( 'NouvellesPDOs', array( 'url'=> Router::url( null, true ) ) );?>
    <table >
        <thead>
            <tr>
                <th>Nom de Table</th>
                <th colspan="2" class="action">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
                echo $html->tableCells(
                    array(
                        h( 'Décision PDOs' ),
                        $html->viewLink(
                            'Voir la table',
                            array( 'controller' => 'decisionspdos', 'action' => 'index' )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
                echo $html->tableCells(
                    array(
                        h( 'Origine PDOs' ),
                        $html->viewLink(
                            'Voir la table',
                            array( 'controller' => 'originespdos', 'action' => 'index' )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
                echo $html->tableCells(
                    array(
                        h( 'Situation PDOs' ),
                        $html->viewLink(
                            'Voir la table',
                            array( 'controller' => 'situationspdos', 'action' => 'index' )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
                echo $html->tableCells(
                    array(
                        h( 'Statut décisions PDOs' ),
                        $html->viewLink(
                            'Voir la table',
                            array( 'controller' => 'statutsdecisionspdos', 'action' => 'index' )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
                echo $html->tableCells(
                    array(
                        h( 'Statut PDOs' ),
                        $html->viewLink(
                            'Voir la table',
                            array( 'controller' => 'statutspdos', 'action' => 'index' )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
                echo $html->tableCells(
                    array(
                        h( 'Type de notification' ),
                        $html->viewLink(
                            'Voir la table',
                            array( 'controller' => 'typesnotifspdos', 'action' => 'index' )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
                echo $html->tableCells(
                    array(
                        h( 'Type de PDOs' ),
                        $html->viewLink(
                            'Voir la table',
                            array( 'controller' => 'typespdos', 'action' => 'index' )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
            ?>
        </tbody>
    </table>
    <div class="submit">
        <?php
            echo $form->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) );
        ?>
    </div>
<?php echo $form->end();?>