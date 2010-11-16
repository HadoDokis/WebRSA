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
                echo $xhtml->tableCells(
                    array(
                        h( 'Décision PDOs' ),
                        $xhtml->viewLink(
                            'Voir la table',
                            array( 'controller' => 'decisionspdos', 'action' => 'index' )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
                echo $xhtml->tableCells(
                    array(
                        h( 'Description pour traitements PDOs' ),
                        $xhtml->viewLink(
                            'Voir la table',
                            array( 'controller' => 'descriptionspdos', 'action' => 'index' )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
                echo $xhtml->tableCells(
                    array(
                        h( 'Origine PDOs' ),
                        $xhtml->viewLink(
                            'Voir la table',
                            array( 'controller' => 'originespdos', 'action' => 'index' )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
                echo $xhtml->tableCells(
                    array(
                        h( 'Situation PDOs' ),
                        $xhtml->viewLink(
                            'Voir la table',
                            array( 'controller' => 'situationspdos', 'action' => 'index' )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
//                 echo $xhtml->tableCells(
//                     array(
//                         h( 'Statut décisions PDOs' ),
//                         $xhtml->viewLink(
//                             'Voir la table',
//                             array( 'controller' => 'statutsdecisionspdos', 'action' => 'index' )
//                         )
//                     ),
//                     array( 'class' => 'odd' ),
//                     array( 'class' => 'even' )
//                 );
                echo $xhtml->tableCells(
                    array(
                        h( 'Statut PDOs' ),
                        $xhtml->viewLink(
                            'Voir la table',
                            array( 'controller' => 'statutspdos', 'action' => 'index' )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
                echo $xhtml->tableCells(
                    array(
                        h( 'Type de notification' ),
                        $xhtml->viewLink(
                            'Voir la table',
                            array( 'controller' => 'typesnotifspdos', 'action' => 'index' )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
                echo $xhtml->tableCells(
                    array(
                        h( 'Types de traitements PDOs' ),
                        $xhtml->viewLink(
                            'Voir la table',
                            array( 'controller' => 'traitementstypespdos', 'action' => 'index' )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
                echo $xhtml->tableCells(
                    array(
                        h( 'Type de PDOs' ),
                        $xhtml->viewLink(
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