<h1>Paramétrage pour les équipes pluridisciplinaires</h1>

<?php echo $form->create( 'EquipesPluridisciplinaires', array( 'url'=> Router::url( null, true ) ) );?>
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
                        h( 'Intitulé des équipes pluridisciplinaires' ),
                        $html->viewLink(
                            'Voir la table',
                            array( 'controller' => 'eps', 'action' => 'index' )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
                echo $html->tableCells(
                    array(
                        h( 'Fonction des participants' ),
                        $html->viewLink(
                            'Voir la table',
                            array( 'controller' => 'fonctionspartseps', 'action' => 'index' )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );

                echo $html->tableCells(
                    array(
                        h( 'Motifs des demandes de réorientation' ),
                        $html->viewLink(
                            'Voir la table',
                            array( 'controller' => 'motifsdemsreorients', 'action' => 'index' )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
                echo $html->tableCells(
                    array(
                        h( 'Participants aux Equipes pluridisciplinaires' ),
                        $html->viewLink(
                            'Voir la table',
                            array( 'controller' => 'partseps', 'action' => 'index' ),
                            ( ( $compteurs['Fonctionpartep'] > 0 ) && ( $compteurs['Ep'] > 0 ) )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
                echo $html->tableCells(
                    array(
                        h( 'Présence aux séances' ),
                        $html->viewLink(
                            'Voir la table',
                            array( 'controller' => 'partseps_seanceseps', 'action' => 'index' ),
                            ( ( $compteurs['Partep'] > 0 ) )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
//                 echo $html->tableCells(
//                     array(
//                         h( 'Séances d\'équipes pluridisciplinaires' ),
//                         $html->viewLink(
//                             'Voir la table',
//                             array( 'controller' => 'seanceseps', 'action' => 'indexparams' )
//                         )
//                     ),
//                     array( 'class' => 'odd' ),
//                     array( 'class' => 'even' )
//                 );
            ?>
        </tbody>
    </table>
        <div class="submit">
        <?php
            echo $form->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) );
        ?>
    </div>
<?php echo $form->end();?>