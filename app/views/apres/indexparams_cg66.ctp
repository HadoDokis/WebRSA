<h1><?php echo $this->pageTitle = 'Paramétrages des APREs';?></h1>

<?php echo $form->create( 'NouvellesAPREs', array( 'url'=> Router::url( null, true ) ) );?>
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
                        h( 'Liste des aides de l\'APRE' ),
                        $html->viewLink(
                            'Voir la table',
                            array( 'controller' => 'typesaidesapres66', 'action' => 'index' ),
							( ( $compteurs['Pieceaide66'] > 0 ) && ( $compteurs['Themeapre66'] > 0 ) )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
                echo $html->tableCells(
                    array(
                        h( 'Liste des pièces à fournir' ),
                        $html->viewLink(
                            'Voir la table',
                            array( 'controller' => 'piecesaides66', 'action' => 'index' )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );

                echo $html->tableCells(
                    array(
                        h( 'Thèmes de la demande d\'aide APRE' ),
                        $html->viewLink(
                            'Voir la table',
                            array( 'controller' => 'themesapres66', 'action' => 'index' )
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