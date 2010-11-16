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
                echo $xhtml->tableCells(
                    array(
                        h( 'Paramètres financiers pour la gestion de l\'APRE' ),
                        $xhtml->viewLink(
                            'Voir la table',
                            array( 'controller' => 'parametresfinanciers', 'action' => 'index' )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
                echo $xhtml->tableCells(
                    array(
                        h( 'Participants comités APRE' ),
                        $xhtml->viewLink(
                            'Voir la table',
                            array( 'controller' => 'participantscomites', 'action' => 'index' )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
                echo $xhtml->tableCells(
                    array(
                        h( 'Personnes chargées du suivi des Aides APREs' ),
                        $xhtml->viewLink(
                            'Voir la table',
                            array( 'controller' => 'suivisaidesapres', 'action' => 'index' )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
                echo $xhtml->tableCells(
                    array(
                        h( 'Tiers prestataires de l\'APRE' ),
                        $xhtml->viewLink(
                            'Voir la table',
                            array( 'controller' => 'tiersprestatairesapres', 'action' => 'index' )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
                echo $xhtml->tableCells(
                    array(
                        h( 'Types d\'aides liées au personne chargée du suivi de l\'APRE' ),
                        $xhtml->viewLink(
                            'Voir la table',
                            array( 'controller' => 'suivisaidesaprestypesaides', 'action' => 'index' )
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