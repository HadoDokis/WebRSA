<h1><?php echo $this->pageTitle = 'Erreur 401:  Accès au dossier refusé';?></h1>
<p><?php echo sprintf( "Ce dossier a été bloqué en modification par %s jusqu'au %s.", '<strong>'.$params['user'].'</strong>', '<strong>'.strftime( '%d/%m/%Y à %H:%M:%S', $params['time'] ).'</strong>' );?></p>
