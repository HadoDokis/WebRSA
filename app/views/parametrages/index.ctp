<h1>Paramétrage des tables</h1>

<?php debug( $session->read( 'Auth' ) );?>

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
                        ),
                        $html->editLink(
                            'Editer la table',
                            array( 'controller' => 'parametrages', 'action' => 'edit', 'users')
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
                            array( 'controller' => 'parametrages', 'action' => 'view', 'zones' )
                        ),
                        $html->editLink(
                            'Editer la table',
                            array( 'controller' => 'parametrages', 'action' => 'edit', 'zones' )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
                echo $html->tableCells(
                    array(
                        h( 'Types orientations' ),
                        $html->viewLink(
                            'Voir la table',
                            array( 'controller' => 'parametrages', 'action' => 'view', 'orients' )
                        ),
                        $html->editLink(
                            'Editer la table',
                            array( 'controller' => 'parametrages', 'action' => 'edit', 'orients' )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
                echo $html->tableCells(
                    array(
                        h( 'Structures référentes' ),
                        $html->viewLink(
                            'Voir la table',
                            array( 'controller' => 'parametrages', 'action' => 'view', 'structs' )
                        ),
                        $html->editLink(
                            'Editer la table',
                            array( 'controller' => 'parametrages', 'action' => 'edit', 'structs' )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
            ?>
        </tbody>
    </table>
<?php echo $form->end();?>