<div id="pageMenu">
    <ul>
        <?php if( $session->check( 'Auth.User' ) ): ?>

            <?php if( $permissions->check( 'cohortes', 'index' ) ) : ?>
                <li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
                    <?php echo $html->link( 'Gestion des cohortes', '#' );?>
                    <ul>
                        <li><?php echo $html->link( 'Nouvelles demandes', array( 'controller' => 'cohortes', 'action' => 'nouvelles' ), array('title'=>'Nouvelles demandes') );?></li>
                        <li><?php echo $html->link( 'Demandes orientées', array( 'controller' => 'cohortes', 'action' => 'orientees' ), array('title'=>'Demandes orientées') );?></li>
                        <li><?php echo $html->link( 'En attente', array( 'controller' => 'cohortes', 'action' => 'enattente' ), array('title'=>'Demandes en attente') );?></li>
                        <!--<li><?php echo $html->link( 'Gestion des PDO', '#' );?></li>
                        <li><?php echo $html->link( 'Liste suivant critères', '#' );?></li>
                        <li><?php echo $html->link( 'Gestion des éditions', '#' );?></li>-->
                    </ul>
                </li>
            <?php endif;?>

            <?php if( $permissions->check( 'dossiers', 'index' ) || true ) : //FIXME ?>
                <li>
                    <?php echo $html->link( 'Recherche dossier / allocataire', array( 'controller' => 'dossiers', 'action' => 'index' ) );?>
                </li>
            <?php endif;?>

            <?php if( $permissions->check( 'criteres', 'index' ) || $permissions->check( 'criteresci', 'index' ) ) :?> <!-- FIXME: ajout arnaud -->
                <!-- <li><?php echo $html->link( 'Recherche multi-critères', array( 'controller' => 'criteres', 'action' => 'index' )  );?></li> -->
		 <li>
                    <?php echo $html->link( 'Recherche multi-critères', '#' );?>
                    <ul>
                        <li><?php echo $html->link( 'Par Orientations', array( 'controller' => 'criteres', 'action' => 'index' )  );?></li>
                        <li><?php echo $html->link( 'Par Contrat insertion',  array( 'controller' => 'criteresci', 'action' => 'index'  ) );?></li>
		    </ul>
		</li>
            <?php endif;?>
<!-- FIXME: n'apparaît pas avec IE 6 -->
            <?php if( $permissions->check( 'droits', 'edit' ) || $permissions->check( 'parametrages', 'index' ) ) : ?>
                <li>
                    <?php echo $html->link( 'Administration', '#' );?>
                    <ul>
                        <li><?php echo $html->link( 'Droits', array( 'controller' => 'droits', 'action' => 'edit' )  );?></li>
                        <li><?php echo $html->link( 'Paramétrage',  array( 'controller' => 'parametrages', 'action' => 'index'  ) );?></li>
                    <!--  <li><?php echo $html->link( 'Intégration flux', '#' );?></li>
                        <li><?php echo $html->link( 'Gestion des logs', '#' );?></li>
                        <li><?php echo $html->link( 'Gestion des éditions', '#' );?></li>-->
                    </ul>
                </li>
            <?php endif;?>
<!-- FIXME: n'apparaît pas avec IE 6 -->
<!--            <li>
                <?php echo $html->link( '  Test    party   ', '#' );?>
                <ul>
                    <li><a target="_blank" href="../webrsa/app/webroot/files/demotestparty.pdf"> Demo </a></li>
                    <li><a target="_blank" href="../webrsa/app/webroot/files/rapport_anomalies.xls"> Rapport </a></li>
                </ul>
            </li>-->

            <li><?php echo $html->link( 'Déconnexion '.$session->read( 'Auth.User.username' ), array( 'controller' => 'users', 'action' => 'logout' ) );?></li>
        <?php else: ?>
            <li><?php echo $html->link( 'Connexion', array( 'controller' => 'users', 'action' => 'login' ) );?></li>
        <?php endif; ?>
    </ul>
</div>