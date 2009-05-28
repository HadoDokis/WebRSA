<div id="pageMenu">
    <ul>
        <?php if( $session->check( 'Auth.User' ) ): ?>
            <!--<li><?php echo $html->link( 'Accueil', '/' );?></li>-->
            <li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
                <?php echo $html->link( 'Gestion des cohortes', '#' );?>
                <ul>
                    <li><?php echo $html->link( 'Nouvelles demandes', array( 'controller' => 'cohortes', 'action' => 'nouvelles' ) );?></li>
                    <li><?php echo $html->link( 'Demandes orientées', array( 'controller' => 'cohortes', 'action' => 'orientees' ) );?></li>
                    <!--<li><?php echo $html->link( 'Gestion des PDO', '#' );?></li>
                    <li><?php echo $html->link( 'Liste suivant critères', '#' );?></li>
                    <li><?php echo $html->link( 'Gestion des éditions', '#' );?></li>-->
                </ul>
            </li>
            <li>
                <?php echo $html->link( 'Recherche dossier / allocataire', array( 'controller' => 'dossiers', 'action' => 'index' ) );?>
            </li>
            <li><?php echo $html->link( 'Recherche multi-critères', array( 'controller' => 'criteres', 'action' => 'index' )  );?></li>

            <!--<li><?php echo $html->link( 'Préorientation / orientation', '#' );?></li>-->
            <li class="selected">
                <?php echo $html->link( 'Administration', '#' );?>
                <ul>
                    <li><?php echo $html->link( 'Droits', array( 'controller' => 'droits', 'action' => 'edit' )  );?></li>
                    <li><?php echo $html->link( 'Paramétrage',  array( 'controller' => 'parametrages', 'action' => 'index'  ) );?></li>
                  <!--  <li><?php echo $html->link( 'Intégration flux', '#' );?></li>
                    <li><?php echo $html->link( 'Gestion des logs', '#' );?></li>
                    <li><?php echo $html->link( 'Gestion des éditions', '#' );?></li>-->
                </ul>
            </li>
            <li class="selected">
                <?php echo $html->link( '  Test    party   ', '#' );?>
                <ul>
                    <li><a href="../webrsa/app/webroot/files/demotestparty.ppt"> Demo </a></li>
                    <li><a href="../webrsa/app/webroot/files/rapport_anomalies.xls"> Rapport </a></li>
                </ul>
            </li>
           <!-- <li>
                <?php echo $html->link( 'Organismes / partenaires', '#' );?>
                <ul>
                    <li><?php echo $html->link( 'Paramétrage', '#' );?></li>
                    <li><?php echo $html->link( 'Gestion des conventions', '#' );?></li>
                </ul>
            </li>-->

            <li><?php echo $html->link( 'Déconnexion', array( 'controller' => 'users', 'action' => 'logout' ) );?></li>
        <?php else: ?>
            <li><?php echo $html->link( 'Connexion', array( 'controller' => 'users', 'action' => 'login' ) );?></li>
        <?php endif; ?>
    </ul>
</div>