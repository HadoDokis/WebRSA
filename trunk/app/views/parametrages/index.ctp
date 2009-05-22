<h1>Paramétrage des tables</h1>

<?php echo $form->create( 'NouvellesDemandes', array( 'url'=> Router::url( null, true ) ) );?>
    <table class="tooltips_oupas">
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
                        h( 'Utilisateurs' ),
                        $html->viewLink(
                            'Voir la table',
                            array( 'controller' => 'users', 'action' => 'index' )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
                echo $html->tableCells(
                    array(
                        h( 'Zones géographiques' ),
                        $html->viewLink(
                            'Voir la table',
                            array( 'controller' => 'zonesgeographiques', 'action' => 'index' )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
//                 echo $html->tableCells(
//                     array(
//                         h( 'Types orientations' ),
//                         $html->viewLink(
//                             'Voir la table',
//                             array( 'controller' => 'typesorients', 'action' => 'index' )
//                         )
//                     ),
//                     array( 'class' => 'odd' ),
//                     array( 'class' => 'even' )
//                 );
//                 echo $html->tableCells(
//                     array(
//                         h( 'Structures référentes' ),
//                         $html->viewLink(
//                             'Voir la table',
//                             array( 'controller' => 'structuresreferentes', 'action' => 'index' )
//                         )
//                     ),
//                     array( 'class' => 'odd' ),
//                     array( 'class' => 'even' )
//                 );
            ?>
        </tbody>
    </table>
<?php echo $form->end();?>