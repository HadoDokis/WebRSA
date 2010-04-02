<h1><?php echo $this->pageTitle = 'Paramétrages pour les fiches de candidature';?></h1>

<?php echo $form->create( 'NouvellesFiches', array( 'url'=> Router::url( null, true ) ) );?>
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
                        h( 'Actions d\'insertion pour fiches de candidature' ),
                        $html->viewLink(
                            'Voir la table',
                            array( 'controller' => 'actionscandidats', 'action' => 'index' )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
                echo $html->tableCells(
                    array(
                        h( 'Contacts des partenaires' ),
                        $html->viewLink(
                            'Voir la table',
                            array( 'controller' => 'contactspartenaires', 'action' => 'index' )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
                echo $html->tableCells(
                    array(
                        h( 'Partenaires pour fiches candidature' ),
                        $html->viewLink(
                            'Voir la table',
                            array( 'controller' => 'partenaires', 'action' => 'index' )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
                echo $html->tableCells(
                    array(
                        h( 'Types d\'actions par partenaires' ),
                        $html->viewLink(
                            'Voir la table',
                            array( 'controller' => 'actionscandidats_partenaires', 'action' => 'index' )
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