<?php $this->pageTitle = 'Erreur 404: page non trouvée';?>

<h1>Erreur 404: page non trouvée</h1>
<p><?php echo sprintf( "La page %s n'existe pas.", "<strong>'{$message}'</strong>" );?></p>
<!--<p>Page possible: <a class="parent" href="/">Blog</a></p>-->
<p>Rendez-vous à l'<?php echo $xhtml->link( 'accueil', '/' );?><!-- ou au <a href="/sitemap.php">plan du site-->.</a>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus augue nunc, laoreet eu, semper a, molestie eu, odio. Nunc pretium. In nulla lacus, facilisis at, lobortis sed, volutpat quis, lorem. Duis libero ipsum, commodo et, feugiat vel, tincidunt non, nisi. Maecenas consequat gravida felis. Mauris diam felis, congue ornare, dignissim sed, lobortis et, massa.</p>