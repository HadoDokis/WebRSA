<div id="menu1Wrapper">
    <div class="menu1">
        <ul>
        <?php if( $session->check( 'Auth.User' ) ): ?>
            <?php if( $permissions->check( 'cohortes', 'index' ) ) : ?>
                <li id="menu1one" onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
                    <?php echo $html->link( 'Gestion des cohortes', '#' );?>
                    <ul>
                    <?php if( $permissions->check( 'cohortesci', 'index' ) ): ?>
                        <!-- AJOUT POUR LA GESTION DES CONTRATS D'INSERTION (Cohorte) -->
                        <li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
                            <?php echo $html->link( 'Contrat insertion', array( 'controller' => 'cohortesci', 'action' => 'index' ), array( 'title'=>'Gestion des contrats' ) );?>
                        </li>
                    <?php endif;?>
                    <?php if( $permissions->check( 'cohortes', 'nouvelles' ) || $permissions->check( 'cohortes', 'orientees' ) || $permissions->check( 'cohortes', 'enattente' ) || $permissions->check( 'cohortesindus', 'index' ) ): ?>
                        <!-- MODIF POUR LA GESTION DES ORIENTATIONS (Cohorte) -->
                        <li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
                            <?php  echo $html->link( 'Orientation', '#' );?>
                                <ul>
                                    <?php if( $permissions->check( 'cohortes', 'nouvelles' ) ): ?>
                                        <li><?php echo $html->link( 'Nouvelles demandes', array( 'controller' => 'cohortes', 'action' => 'nouvelles' ), array( 'title'=>'Nouvelles demandes' ) );?></li>
                                    <?php endif; ?>
                                    <?php if( $permissions->check( 'cohortes', 'orientees' ) ): ?>
                                        <li><?php echo $html->link( 'Demandes orientées', array( 'controller' => 'cohortes', 'action' => 'orientees' ), array( 'title'=>'Demandes orientées' ) );?></li>
                                    <?php endif; ?>
                                    <?php if( $permissions->check( 'cohortes', 'enattente' ) ): ?>
                                        <li><?php echo $html->link( 'En attente', array( 'controller' => 'cohortes', 'action' => 'enattente' ), array( 'title'=>'Demandes en attente' ) );?></li>
                                    <?php endif; ?>
                                    <!-- <li><?php echo $html->link( 'Fichiers Exportés', array( 'controller' => 'cohortes', 'action' => 'exports_index' ), array( 'title'=>'Fichiers exportés' ) );?></li> -->
                                    <!--<li><?php echo $html->link( 'Liste suivant critères', '#' );?></li>
                                    <li><?php echo $html->link( 'Gestion des éditions', '#' );?></li> -->
                                </ul>
                        </li>
                        <?php if( $permissions->check( 'cohortesindus', 'index' ) ): ?>
                            <!-- AJOUT POUR LA GESTION DES CONTRATS D'INSERTION (Cohorte) -->
                            <li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
                                <?php echo $html->link( 'Indu', array( 'controller' => 'cohortesindus', 'action' => 'index' ), array( 'title'=>'Gestion des indus' ) );?>
                            </li>
                        <?php endif;?>
                    </ul>
                    <?php endif;?>
                </li>
            <?php endif;?>
            <?php if( $permissions->check( 'dossiers', 'index' ) || $permissions->check( 'criteres', 'index' ) || $permissions->check( 'criteresci', 'index' ) ) :?>
                <li id="menu2one" >
                    <?php echo $html->link( 'Recherche multicritères', '#' );?>
                    <ul>
                        <li><?php echo $html->link( 'Par dossier / allocataire', array( 'controller' => 'dossiers', 'action' => 'index' ) );?></li>
                        <li><?php echo $html->link( 'Par Orientation', array( 'controller' => 'criteres', 'action' => 'index' )  );?></li>
                        <li><?php echo $html->link( 'Par Contrat insertion',  array( 'controller' => 'criteresci', 'action' => 'index'  ) );?></li>
                    </ul>
                </li>
            <?php endif;?>
            <?php if( $permissions->check( 'droits', 'edit' ) || $permissions->check( 'parametrages', 'index' ) || $permissions->check( 'infosfinancieres', 'indexdossier' ) || $permissions->check( 'totalisationsacomptes', 'index' ) ): ?>
                    <li id="menu3one">
                        <?php echo $html->link( 'Administration', '#' );?>
                        <ul>
                            <?php if( $permissions->check( 'droits', 'edit' ) ):?>
                                <li><?php echo $html->link( 'Droits', array( 'controller' => 'droits', 'action' => 'edit' )  );?></li>
                            <?php endif;?>
                            <?php if( $permissions->check( 'parametrages', 'index' ) ):?>
                                <li><?php echo $html->link( 'Paramétrage',  array( 'controller' => 'parametrages', 'action' => 'index'  ) );?></li>
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
            <li id="menu4one"><?php echo $html->link( 'Déconnexion '.$session->read( 'Auth.User.username' ), array( 'controller' => 'users', 'action' => 'logout' ) );?></li>
            <?php else: ?>
                <li><?php echo $html->link( 'Connexion', array( 'controller' => 'users', 'action' => 'login' ) );?></li>
            <?php endif; ?>
        </ul>
    </div>
</div>