<div id="menu1Wrapper">
    <div class="menu1">
        <ul>
        <?php if( $session->check( 'Auth.User' ) ): ?>
            <?php if( $permissions->check( 'cohortes', 'index' ) ) : ?>
                <li id="menu1one" onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
                    <?php echo $html->link( 'Gestion des cohortes', '#' );?>
                    <ul>
                        <?php if( $permissions->check( 'cohortesci', 'nouveaux' ) || $permissions->check( 'cohortesci', 'valides' ) || $permissions->check( 'cohortesci', 'enattente' ) ):?>
                            <!-- AJOUT POUR LA GESTION DES CONTRATS D'INSERTION (Cohorte) -->
                            <li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
                                <?php  echo $html->link( 'Contrat insertion', '#' );?>
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
                                    <?php if( $permissions->check( 'cohortespdos', 'enattente' ) ): ?>
                                        <li><?php echo $html->link( 'PDOs en attente', array( 'controller' => 'cohortespdos', 'action' => 'enattente' ), array( 'title' => 'PDOs en attente' ) );?></li>
                                    <?php endif; ?>
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
                    <?php echo $html->link( 'Recherche multicritères', '#' );?>
                    <ul>
                        <?php if( $permissions->check( 'dossiers', 'index' ) ):?>
                            <li><?php echo $html->link( 'Par dossier / allocataire', array( 'controller' => 'dossiers', 'action' => 'index' ) );?></li>
                        <?php endif;?>
                        <?php if( $permissions->check( 'criteres', 'index' ) ):?>
                            <li><?php echo $html->link( 'Par Orientation', array( 'controller' => 'criteres', 'action' => 'index' )  );?></li>
                        <?php endif;?>
                        <?php if( $permissions->check( 'criteresci', 'index' ) ):?>
                            <li><?php echo $html->link( 'Par Contrat insertion',  array( 'controller' => 'criteresci', 'action' => 'index'  ) );?></li>
                        <?php endif;?>
                        <?php if( $permissions->check( 'cohortesindus', 'index' ) ): ?>
                            <li><?php echo $html->link( 'Par Indus', array( 'controller' => 'cohortesindus', 'action' => 'index' ) );?>
                            </li>
                        <?php endif;?>
                        <?php if( $permissions->check( 'criteresrdv', 'index' ) ):?>
                            <li><?php echo $html->link( 'Par Rendez-vous',  array( 'controller' => 'criteresrdv', 'action' => 'index'  ) );?></li>
                        <?php endif;?>
                    </ul>
                </li>
            <?php endif;?>
            <?php if( $permissions->check( 'criteresapres', 'index' ) || $permissions->check( 'repsddtefp', 'index' ) ) :?>
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
                                            <li><?php echo $html->link( 'APREs incomplètes', array( 'controller' => 'criteresapres', 'action' => 'incomplete' ) );?></li>
                                        <?php endif;?>
                                    </ul>
                                </li>
                        <?php endif;?>
                        <?php if( $permissions->check( 'repsddtefp', 'index' ) ):?>
                            <li><?php echo $html->link( 'Reporting bi-mensuel DDTEFP', array( 'controller' => 'repsddtefp', 'action' => 'index' ) );?></li>
                        <?php endif;?>
                        <?php if( $permissions->check( 'comitesexamenapres', 'index' ) ):?>
                            <li><?php echo $html->link( 'Comité d\'examen', array( 'controller' => 'comitesexamenapres', 'action' => 'index' ) );?></li>
                        <?php endif;?>
                        <!-- <?php if( $permissions->check( 'commissionsapre', 'nouvelles' ) || $permissions->check( 'commissionsapre', 'enattente' ) || $permissions->check( 'commissionsapre', 'valide' )):?>
                            <li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
                                <?php echo $html->link( 'Avis commission d\'attribution','#' );?>
                                <ul>
                                    <?php if( $permissions->check( 'commissionsapre', 'nouvelles' ) ): ?>
                                        <li><?php echo $html->link( 'Nouvelles demandes', array( 'controller' => 'commissionsapre', 'action' => 'nouvelles' ), array( 'title' => 'Nouvelles demandes' )  );?></li>
                                    <?php endif;?>
                                    <?php if( $permissions->check( 'commissionsapre', 'enattente' ) ): ?>
                                        <li><?php echo $html->link( 'En attente', array( 'controller' => 'commissionsapre', 'action' => 'enattente' ), array( 'title' => 'Demandes en attente' ) );?></li>
                                    <?php endif;?>
                                    <?php if( $permissions->check( 'commissionsapre', 'valide' ) ): ?>
                                        <li><?php echo $html->link( 'Demandes validées', array( 'controller' => 'commissionsapre', 'action' => 'valide' ), array( 'title' => 'Demandes validées' ) );?></li>
                                    <?php endif;?>
                                </ul>
                            </li>
                        <?php endif;?> -->
                    </ul>
                </li>
            <?php endif;?>
            <?php if( $permissions->check( 'indicateursmensuels', 'index' ) ) :?>
                <li id="menu4one" >
                    <?php echo $html->link( 'Tableaux de bord', '#' );?>
                    <ul>
                        <?php if( $permissions->check( 'indicateursmensuels', 'index' ) ):?>
                            <li><?php echo $html->link( 'Indicateurs mensuels', array( 'controller' => 'indicateursmensuels', 'action' => 'index' ) );?></li>
                        <?php endif;?>
                    </ul>
                </li>
            <?php endif;?>
            <?php if(/* $permissions->check( 'droits', 'edit' ) || */$permissions->check( 'parametrages', 'index' ) || $permissions->check( 'infosfinancieres', 'indexdossier' ) || $permissions->check( 'totalisationsacomptes', 'index' ) ): ?>
                    <li id="menu5one">
                        <?php echo $html->link( 'Administration', '#' );?>
                        <ul>
                            <?php if( $permissions->check( 'droits', 'edit' ) ):?>
                                <li><?php echo $html->link( 'Droits', array( 'controller' => 'droits', 'action' => 'edit' )  );?></li>
                            <?php endif;?>
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
            <li id="menu6one"><?php echo $html->link( 'Déconnexion '.$session->read( 'Auth.User.username' ), array( 'controller' => 'users', 'action' => 'logout' ) );?></li>
            <?php else: ?>
                <li><?php echo $html->link( 'Connexion', array( 'controller' => 'users', 'action' => 'login' ) );?></li>
            <?php endif; ?>
        </ul>
    </div>
</div>