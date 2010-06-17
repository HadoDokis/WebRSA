<div id="menu1Wrapper">
    <div class="menu1">
        <ul>
        <?php if( $session->check( 'Auth.User' ) ): ?>
            <?php if( $permissions->check( 'cohortes', 'index' ) ) : ?>
                <li id="menu1one" onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
                    <?php echo $html->link( 'Cohortes', '#' );?>
                    <ul>
                        <?php if( $permissions->check( 'cohortesci', 'nouveaux' ) || $permissions->check( 'cohortesci', 'valides' ) || $permissions->check( 'cohortesci', 'enattente' ) ):?>
                            <!-- AJOUT POUR LA GESTION DES CONTRATS D'ENGAGEMENT RECIPROQUE (Cohorte) -->
                            <li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
                                <?php  echo $html->link( 'CER ', '#' );?>
                                    <ul>
                                        <?php if( $permissions->check( 'cohortesci', 'nouveaux' ) ): ?>
                                            <li><?php echo $html->link( 'Contrats à valider', array( 'controller' => 'cohortesci', 'action' => 'nouveaux' ), array( 'title' => 'Contrats à valider' ) );?></li>
                                        <?php endif; ?>
                                        <?php if( $permissions->check( 'cohortesci', 'enattente' ) ): ?>
                                            <li><?php echo $html->link( 'En attente', array( 'controller' => 'cohortesci', 'action' => 'enattente' ), array( 'title' => 'Contrats en attente' ) );?></li>
                                        <?php endif; ?>
                                        <?php if( $permissions->check( 'cohortesci', 'valides' ) ): ?>
                                            <li><?php echo $html->link( 'Contrats validés', array( 'controller' => 'cohortesci', 'action' => 'valides' ), array( 'title' => 'Contrats validés' ) );?></li>
                                        <?php endif; ?>
                                    </ul>
                            </li>
                        <?php endif;?>
                        <?php if( $permissions->check( 'cohortes', 'nouvelles' ) || $permissions->check( 'cohortes', 'orientees' ) || $permissions->check( 'cohortes', 'enattente' ) ): ?>
                            <!-- MODIF POUR LA GESTION DES ORIENTATIONS (Cohorte) -->
                            <li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
                                <?php  echo $html->link( 'Orientation', '#' );?>
                                    <ul>
                                        <?php if( $permissions->check( 'cohortes', 'nouvelles' ) ): ?>
                                            <li><?php echo $html->link( 'Nouvelles demandes', array( 'controller' => 'cohortes', 'action' => 'nouvelles' ), array( 'title'=>'Nouvelles demandes' ) );?></li>
                                        <?php endif; ?>
                                        <?php if( $permissions->check( 'cohortes', 'enattente' ) ): ?>
                                            <li><?php echo $html->link( 'En attente', array( 'controller' => 'cohortes', 'action' => 'enattente' ), array( 'title'=>'Demandes en attente' ) );?></li>
                                        <?php endif; ?>
                                        <?php if( $permissions->check( 'cohortes', 'orientees' ) ): ?>
                                            <li><?php echo $html->link( 'Demandes orientées', array( 'controller' => 'cohortes', 'action' => 'orientees' ), array( 'title'=>'Demandes orientées' ) );?></li>
                                        <?php endif; ?>
                                        <!--<li><?php echo $html->link( 'Liste suivant critères', '#' );?></li>
                                        <li><?php echo $html->link( 'Gestion des éditions', '#' );?></li> -->
                                    </ul>
                            </li>
                        <?php endif;?>
                        <?php if( $permissions->check( 'cohortespdos', 'avisdemande' ) || $permissions->check( 'cohortespdos', 'valide' ) || $permissions->check( 'cohortespdos', 'enattente' ) ): ?>
                            <!-- AJOUT POUR LA GESTION DES PDOs (Cohorte) -->
                            <li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
                                <?php echo $html->link( 'PDOs', '#' );?>
                                <ul>
                                    <?php if( $permissions->check( 'cohortespdos', 'avisdemande' ) ): ?>
                                        <li><?php echo $html->link( 'Nouvelles demandes', array( 'controller' => 'cohortespdos', 'action' => 'avisdemande' ), array( 'title' => 'Avis CG demandé' ) );?></li>
                                    <?php endif; ?>
                                    <!-- <?php /*if( $permissions->check( 'cohortespdos', 'enattente' ) ): ?>
                                        <li><?php echo $html->link( 'PDOs en attente', array( 'controller' => 'cohortespdos', 'action' => 'enattente' ), array( 'title' => 'PDOs en attente' ) );?></li>
                                    <?php endif;*/ ?> -->
                                    <?php if( $permissions->check( 'cohortespdos', 'valide' ) ): ?>
                                        <li><?php echo $html->link( 'Liste PDOs', array( 'controller' => 'cohortespdos', 'action' => 'valide' ), array( 'title' => 'PDOs validés' ) );?></li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        <?php endif;?>
                        <?php if( $permissions->check( 'relances', 'relance' ) || $permissions->check( 'relances', 'arelancer' )): ?>
                            <li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
                                <?php echo $html->link( 'Relances','#' );?>
                                <ul>
                                    <?php if( $permissions->check( 'relances', 'arelancer' ) ): ?>
                                        <li><?php echo $html->link( 'Dossiers à relancer', array( 'controller' => 'relances', 'action' => 'arelancer' ), array( 'title' => 'Dossiers à relancer' ) );?></li>
                                    <?php endif;?>
                                    <?php if( $permissions->check( 'relances', 'relance' ) ): ?>
                                        <li><?php echo $html->link( 'Dossiers relancés', array( 'controller' => 'relances', 'action' => 'relance' ), array( 'title' => 'Dossiers relancés' ) );?></li>
                                    <?php endif;?>
                                </ul>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif;?>
            <?php if( $permissions->check( 'dossiers', 'index' ) || $permissions->check( 'criteres', 'index' ) || $permissions->check( 'criteresci', 'index' ) ) :?>
                <li id="menu2one" >
                    <?php echo $html->link( 'Recherches', '#' );?>
                    <ul>
                        <?php if( $permissions->check( 'dossiers', 'index' ) ):?>
                            <li><?php echo $html->link( 'Par dossier / allocataire', array( 'controller' => 'dossiers', 'action' => 'index' ) );?></li>
                        <?php endif;?>
                        <?php if( $permissions->check( 'criteres', 'index' ) ):?>
                            <li><?php echo $html->link( 'Par Orientation', array( 'controller' => 'criteres', 'action' => 'index' )  );?></li>
                        <?php endif;?>
                        <?php if( $permissions->check( 'criteresci', 'index' ) || $permissions->check( 'criterescuis', 'index' ) ):?>
                            <li>
                                <?php echo $html->link( 'Par Contrats', '#' );?>
                                <ul>
                                    <li>
                                        <?php echo $html->link( 'Par CER',  array( 'controller' => 'criteresci', 'action' => 'index'  ) );?>
                                    </li>

                                    <li>
                                        <?php echo $html->link( 'Par CUI',  array( 'controller' => 'criterescuis', 'action' => 'index'  ) );?>
                                    </li>
                                </ul>
                            </li>
                        <?php endif;?>
                        <?php if( $permissions->check( 'cohortesindus', 'index' ) ): ?>
                            <li><?php echo $html->link( 'Par Indus', array( 'controller' => 'cohortesindus', 'action' => 'index' ) );?>
                            </li>
                        <?php endif;?>
                        <?php if( $permissions->check( 'criteresrdv', 'index' ) ):?>
                            <li><?php echo $html->link( 'Par Rendez-vous',  array( 'controller' => 'criteresrdv', 'action' => 'index'  ) );?></li>
                        <?php endif;?>
                        <?php if( $permissions->check( 'criterespdos', 'index' ) ):?>
                            <li>
                                <?php echo $html->link( 'Par PDOs', '#' );?>
                                <ul>
                                    <li>
                                        <?php echo $html->link( 'Nouvelles PDOs',  array( 'controller' => 'criterespdos', 'action' => 'nouvelles'  ) );?>
                                    </li>

                                    <li>
                                        <?php echo $html->link( 'Liste des PDOs',  array( 'controller' => 'criterespdos', 'action' => 'index'  ) );?>
                                    </li>
                                </ul>
                            </li>

                        <?php endif;?>
                    </ul>
                </li>
            <?php endif;?>
            <?php if( $permissions->check( 'criteresapres', 'index' ) || $permissions->check( 'repsddtefp', 'index' ) || $permissions->check( 'comitesapres', 'index' ) || $permissions->check( 'recoursapres', 'index' ) ) :?>
                <li id="menu3one" >
                    <?php echo $html->link( 'APRE', '#' );?>
                    <ul>
                        <?php if( $permissions->check( 'criteresapres', 'index' ) ):?>
                            <li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
                                <?php echo $html->link( 'Liste des demandes d\'APRE', '#');?>
                                    <ul>
                                        <?php if( $permissions->check( 'criteresapres', 'index' ) ): ?>
                                            <li><?php echo $html->link( 'Toutes les APREs', array( 'controller' => 'criteresapres', 'action' => 'all' ) );?></li>
                                        <?php endif;?>
                                        <?php if( $permissions->check( 'criteresapres', 'index' ) ): ?>
                                            <li><?php echo $html->link( 'Eligibilité des APREs', array( 'controller' => 'criteresapres', 'action' => 'eligible' ) );?></li>
                                        <?php endif;?>
                                        <?php if( $permissions->check( 'recoursapres', 'index' ) ): ?>
                                            <li><?php echo $html->link( 'Demande de recours', array( 'controller' => 'recoursapres', 'action' => 'demande' ) );?></li>
                                        <?php endif;?>
                                        <?php if( $permissions->check( 'recoursapres', 'index' ) ): ?>
                                            <li><?php echo $html->link( 'Visualisation des recours', array( 'controller' => 'recoursapres', 'action' => 'visualisation' ) );?></li>
                                        <?php endif;?>
                                    </ul>
                                </li>
                        <?php endif;?>

                <?php if( Configure::read( 'nom_form_apre_cg' ) == 'cg93' ):?> <!-- Début de l'affichage en fonction du CG-->
                        <?php if( $permissions->check( 'comitesapres', 'index' ) || $permissions->check( 'cohortescomitesapres', 'index' ) ):?>
                            <li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
                                <?php echo $html->link( 'Comité d\'examen', '#');?>
                                <ul>
                                    <?php if( $permissions->check( 'comitesapres', 'index' ) ): ?>
                                        <li><?php echo $html->link( 'Recherche de Comité', array( 'controller' => 'comitesapres', 'action' => 'index' ) );?></li>
                                    <?php endif;?>
                                    <?php if( $permissions->check( 'cohortescomitesapres', 'index' ) ): ?>
                                        <li><?php echo $html->link( 'Gestion des décisions Comité', array( 'controller' => 'cohortescomitesapres', 'action' => 'aviscomite' ) );?></li>
                                    <?php endif;?>
                                    <?php if( $permissions->check( 'cohortescomitesapres', 'index' ) ): ?>
                                        <li><?php echo $html->link( 'Notifications décisions Comité', array( 'controller' => 'cohortescomitesapres', 'action' => 'notificationscomite' ) );?></li>
                                    <?php endif;?>
                                    <?php if( $permissions->check( 'comitesapres', 'liste' ) ): ?>
                                        <li><?php echo $html->link( 'Liste des Comités', array( 'controller' => 'comitesapres', 'action' => 'liste' ) );?></li>
                                    <?php endif;?>
                                </ul>
                            </li>
                        <?php endif;?>
                        <?php if( $permissions->check( 'repsddtefp', 'index' ) || $permissions->check( 'repsddtefp', 'suivicontrole' ) ):?>
                            <li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
                                <?php echo $html->link( 'Reporting bi-mensuel', '#' );?>
                                <ul>
                                    <?php if( $permissions->check( 'repsddtefp', 'index' ) ):?>
                                        <li><?php echo $html->link( 'Reporting bi-mensuel DDTEFP', array( 'controller' => 'repsddtefp', 'action' => 'index' ) );?></li>
                                    <?php endif;?>
                                    <?php if( $permissions->check( 'repsddtefp', 'suivicontrole' ) ):?>
                                        <li><?php echo $html->link( 'Suivi et contrôle de l\'enveloppe APRE', array( 'controller' => 'repsddtefp', 'action' => 'suivicontrole' ) );?></li>
                                    <?php endif;?>
                                </ul>
                            </li>
                        <?php endif;?>
                        <?php if( $permissions->check( 'integrationfichiersapre', 'index' ) ):?>
                            <li><?php echo $html->link( 'Journal d\'intégration des fichiers CSV', array( 'controller' => 'integrationfichiersapre', 'action' => 'index' ) );?></li>
                        <?php endif;?>
                        <?php if( $permissions->check( 'etatsliquidatifs', 'index' ) ):?>
                            <li><?php echo $html->link( 'États liquidatifs APRE', array( 'controller' => 'etatsliquidatifs', 'action' => 'index' ) );?></li>
                        <?php endif;?>
                        <?php if( $permissions->check( 'budgetsapres', 'index' ) ):?>
                            <li><?php echo $html->link( 'Budgets APRE', array( 'controller' => 'budgetsapres', 'action' => 'index' ) );?></li>
                        <?php endif;?>

                <?php endif;?> <!-- Fin de l'affichage en fonction du CG-->

                    </ul>
                </li>
            <?php endif;?>


            <!-- Début du Nouveau menu pour les Equipes pluridisciplinaires -->

            <?php if( $permissions->check( 'eps', 'liste' ) /*|| $permissions->check( 'referents', 'demandes_reorient' ) || $permissions->check( 'demandesreorient', 'index' ) || $permissions->check( 'parcoursdetectes', 'index' ) || $permissions->check( 'precosreorients', 'index' ) || $permissions->check( 'parcoursdetectes', 'equipe' ) || $permissions->check( 'precosreorients', 'conseil' ) || $permissions->check( 'parcoursdetectes', 'conseil' ) || $permissions->check( 'partseps', 'index' ) || $permissions->check( 'rolespartseps', 'index' ) */ ) :?>
            <li id="menu4one">
                <?php echo $html->link( 'Eq. Pluri.', '#' );?>
                <ul>
                    <!-- <li>
                        <?php /*echo $html->link( 'Parcours', '#' );?>
                        <ul>
                            <li>
                                <?php echo $html->link( 'Détection de parcours', array( 'controller' => 'parcoursdetectes', 'action' => 'detecte' ) );?>
                            </li>
                            <li>
                                <?php echo $html->link( 'Parcours détectés', array( 'controller' => 'parcoursdetectes', 'action' => 'index' ) );*/?>
                            </li>
                        </ul>
                    </li> -->
                    <?php if( $permissions->check( 'seanceseps', 'index' ) ):?>
                        <li>
                            <?php echo $html->link( 'Liste des séances', array( 'controller' => 'seanceseps', 'action' => 'index' ) );?>
                        </li>
                    <?php endif;?>
                   <!--  <?php /*if( $permissions->check( 'eps', 'liste' ) ):?>
                       <li>
                            <?php echo $html->link( 'Liste des équipes', array( 'controller' => 'eps', 'action' => 'liste' ) );?>
                            <ul>
                                <li>
                                    <?php /*echo $html->link( 'Gestion des demandes de réorientation', array( 'controller' => 'eps', 'action' => 'liste' ) );?>
                                </li>
                                <li>
                                    <?php echo $html->link( 'Gestion des parcours détectés', array( 'controller' => 'eps', 'action' => 'detection' ) );?>
                                </li>
                            </ul> 
                        </li>

                        <li>
                            <?php echo $html->link( 'Demandes de réorientation par référents', array( 'controller' => 'referents', 'action' => 'liste_demande_reorient' ) );?>
                        </li>
                    <?php endif;*/?> -->
                   <!-- <?php if( $permissions->check( 'precosreorients', 'conseil' ) || $permissions->check( 'parcoursdetectes', 'conseil' ) ) :?>
                        <li>
                            <?php echo $html->link( 'Conseil général', '#' );?>
                            <ul>
                                <li><?php echo $html->link( 'Cohorte demandes de réorientation', array( 'controller' => 'precosreorients', 'action' => 'conseil' ) );?></li>
                                <li><?php echo $html->link( 'Cohorte parcours', array( 'controller' => 'parcoursdetectes', 'action' => 'conseil' ) );?></li>
                            </ul>
                        </li>
                    <?php endif;?>
                    <?php if( $permissions->check( 'referents', 'demandes_reorient' ) ) :?>
                        <li>
                            <?php echo $html->link( 'Référents', '#' );?>
                            <ul>
                                <li><?php echo $html->link( 'Synthèse référent 1', array( 'controller' => 'referents', 'action' => 'demandes_reorient' ) );?></li>
                                <li><?php echo $html->link( 'Synthèse référent 2', array( 'controller' => 'referents', 'action' => 'demandes_reorient' ) );?></li>
                            </ul>
                        </li>
                    <?php endif;?>
                    <?php if( $permissions->check( 'demandesreorient', 'index' ) || $permissions->check( 'parcoursdetectes', 'index' )) :?>
                        <li>
                            <?php echo $html->link( 'Listes', '#' );?>
                            <ul>
                                <li><?php echo $html->link( 'Demandes de réorientation', array( 'controller' => 'demandesreorient', 'action' => 'indexparams' ) );?></li>
                                <li><?php echo $html->link( 'Parcours', array( 'controller' => 'parcoursdetectes', 'action' => 'index' ) );?></li>
                            </ul>
                        </li>
                    <?php endif;?> -->
                </ul>
            </li>
            <?php endif;?>
            <!-- Fin du Nouveau menu pour les Equipes pluridisciplinaires -->

            <?php if( $permissions->check( 'indicateursmensuels', 'index' ) ) :?>
                <li id="menu5one" >
                    <?php echo $html->link( 'Tableaux de bord', '#' );?>
                    <ul>
                        <?php if( $permissions->check( 'indicateursmensuels', 'index' ) ):?>
                            <li><?php echo $html->link( 'Indicateurs mensuels', array( 'controller' => 'indicateursmensuels', 'action' => 'index' ) );?></li>
                        <?php endif;?>
                    </ul>
                </li>
            <?php endif;?>
            <?php if(/* $permissions->check( 'droits', 'edit' ) || */$permissions->check( 'parametrages', 'index' ) || $permissions->check( 'infosfinancieres', 'indexdossier' ) || $permissions->check( 'totalisationsacomptes', 'index' ) ): ?>
                    <li id="menu6one">
                        <?php echo $html->link( 'Administration', '#' );?>
                        <ul>
                        	<!-- Lien caché afin de ne pas concurencer la nouvelle gestion des droits -->
                            <!--<?php if( $permissions->check( 'droits', 'edit' ) ):?>
                                <li><?php echo $html->link( 'Droits', array( 'controller' => 'droits', 'action' => 'edit' )  );?></li>
                            <?php endif;?>-->
                            <?php if( $permissions->check( 'parametrages', 'index' ) ):?>
                                <li><?php echo $html->link( 'Paramétrages',  array( 'controller' => 'parametrages', 'action' => 'index'  ) );?></li>
                            <?php endif;?>
                            <?php if( $permissions->check( 'infosfinancieres', 'indexdossier' ) || $permissions->check( 'totalisationsacomptes', 'index' ) ):?>
                                <li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
                                <?php  echo $html->link( 'Paiement allocation', '#' );?>
                                    <ul>
                                        <?php if( $permissions->check( 'infosfinancieres', 'indexdossier' ) ):?>
                                            <li><?php echo $html->link( 'Listes nominatives', array( 'controller' => 'infosfinancieres', 'action' => 'indexdossier' ), array( 'title' => 'Listes nominatives' ) );?></li>
                                        <?php endif;?>
                                        <?php if( $permissions->check( 'totalisationsacomptes', 'index' ) ):?>
                                            <li><?php echo $html->link( 'Mandats mensuels', array( 'controller' => 'totalisationsacomptes', 'action' => 'index' ), array( 'title' => 'Mandats mensuels' ) );?></li>
                                        <?php endif;?>
                                    </ul>
                                </li>
                            <?php endif;?>
                        </ul>
                    </li>
            <?php endif;?>
            <li id="menu7one"><?php echo $html->link( 'Déconnexion '.$session->read( 'Auth.User.username' ), array( 'controller' => 'users', 'action' => 'logout' ) );?></li>
            <?php else: ?>
                <li><?php echo $html->link( 'Connexion', array( 'controller' => 'users', 'action' => 'login' ) );?></li>
            <?php endif; ?>
        </ul>
    </div>
</div>
