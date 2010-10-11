<?php $this->pageTitle = 'Paramétrages';?>
<h1>Paramétrage des tables</h1>

<?php echo $form->create( 'NouvellesDemandes', array( 'url'=> Router::url( null, true ) ) );?>
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
                        h( 'Actions d\'insertion' ),
                        $html->viewLink(
                            'Voir la table',
                            array( 'controller' => 'actions', 'action' => 'index' )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
                echo $html->tableCells(
                    array(
                        h( 'APREs' ),
                        $html->viewLink(
                            'Voir la table',
                            array( 'controller' => 'apres'.Configure::read( 'Apre.suffixe' ), 'action' => 'indexparams' )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
                if( Configure::read( 'ActioncandidatPersonne.suffixe' ) == 'cg66' ){
                    echo $html->tableCells(
                        array(
                            h( 'Fiches de Candidature' ),
                            $html->viewLink(
                                'Voir la table',
                                array( 'controller' => 'actionscandidats_personnes', 'action' => 'indexparams' )
                            )
                        ),
                        array( 'class' => 'odd' ),
                        array( 'class' => 'even' )
                    );
                }
                else if( Configure::read( 'ActioncandidatPersonne.suffixe' ) == 'cg93' ){
                    echo $html->tableCells(
                        array(
                            h( 'Fiches de Liaison' ),
                            $html->viewLink(
                                'Voir la table',
                                array( 'controller' => 'actionscandidats_personnes', 'action' => 'indexparams' )
                            )
                        ),
                        array( 'class' => 'odd' ),
                        array( 'class' => 'even' )
                    );
                }
                echo $html->tableCells(
                    array(
                        h( 'Cantons' ),
                        $html->viewLink(
                            'Voir la table',
                            array( 'controller' => 'cantons', 'action' => 'index' )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
                echo $html->tableCells(
                    array(
                        h( 'Groupes d\'utilisateurs' ),
                        $html->viewLink(
                            'Voir la table',
                            array( 'controller' => 'groups', 'action' => 'index' )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
                echo $html->tableCells(
                    array(
                        h( 'Objets du rendez-vous' ),
                        $html->viewLink(
                            'Voir la table',
                            array( 'controller' => 'typesrdv', 'action' => 'index' )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
                echo $html->tableCells(
                    array(
                        h( 'PDOs' ),
                        $html->viewLink(
                            'Voir la table',
                            array( 'controller' => 'pdos', 'action' => 'index' )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
                echo $html->tableCells(
                    array(
                        h( 'Permanences' ),
                        $html->viewLink(
                            'Voir la table',
                            array( 'controller' => 'permanences', 'action' => 'index' )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
                echo $html->tableCells(
                    array(
                        h( 'Référents pour les structures' ),
                        $html->viewLink(
                            'Voir la table',
                            array( 'controller' => 'referents', 'action' => 'index' )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
                echo $html->tableCells(
                    array(
                        h( 'Services instructeurs' ),
                        $html->viewLink(
                            'Voir la table',
                            array( 'controller' => 'servicesinstructeurs', 'action' => 'index' )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
                echo $html->tableCells(
                    array(
                        h( 'Statut des RDVs' ),
                        $html->viewLink(
                            'Voir la table',
                            array( 'controller' => 'statutsrdvs', 'action' => 'index' )
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
                            array( 'controller' => 'structuresreferentes', 'action' => 'index' )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
                echo $html->tableCells(
                    array(
                        h( 'Types d\'actions' ),
                        $html->viewLink(
                            'Voir la table',
                            array( 'controller' => 'typesactions', 'action' => 'index' )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
//                 echo $html->tableCells(
//                     array(
//                         h( 'Types de contrats d\'insertion' ),
//                         $html->viewLink(
//                             'Voir la table',
//                             array( 'controller' => 'typoscontrats', 'action' => 'index' )
//                         )
//                     ),
//                     array( 'class' => 'odd' ),
//                     array( 'class' => 'even' )
//                 );

                echo $html->tableCells(
                    array(
                        h( 'Types d\'orientations' ),
                        $html->viewLink(
                            'Voir la table',
                            array( 'controller' => 'typesorients', 'action' => 'index' )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
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
                        h( 'Vérification de l\'application' ),
                        $html->viewLink(
                            'Voir la table',
                            array( 'controller' => 'checks', 'action' => 'index' )
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
            ?>
        </tbody>
    </table>
<?php echo $form->end();?>
