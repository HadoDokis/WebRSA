<h1>
	<?php
		$title = 'Erreur 401:  Accès au dossier refusé';
		echo $this->set( 'title_for_layout', $title );
		?>
</h1>
<p>
	<?php
		echo $this->Html->tag('h1', $title);
		echo $this->Html->tag('br');
		echo sprintf( "Ce dossier a été bloqué en modification par %s jusqu'au %s.", '<strong>'.$error->params['user'].'</strong>', '<strong>'.strftime( '%d/%m/%Y à %H:%M:%S', $error->params['time'] ).'</strong>' );
		?>
</p>
<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->element( 'exception_stack_trace' );
	}
?>