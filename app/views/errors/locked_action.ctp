<h1><?php echo $this->pageTitle = 'Erreur 401:  Accès à l\'action refusé';?></h1>

<p><?php echo sprintf( "Cette action est en train d'être effectuée par %s jusqu'au %s.", '<strong>'.$params['user'].'</strong>', '<strong>'.strftime( '%d/%m/%Y à %H:%M:%S', $params['time'] ).'</strong>' );?></p>
