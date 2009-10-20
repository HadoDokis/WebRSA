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
                        h( 'Type de notification' ),
                        $html->viewLink(
                            'Voir la table',
                            array( 'controller' => 'typesnotifs', 'action' => 'index' )
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
<?php echo $form->end();?>