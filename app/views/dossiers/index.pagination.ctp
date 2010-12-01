<?php
	$paginator->options( array( 'url' => $this->passedArgs ) );

	// INFO: http://n2.nabble.com/named-params-and-prefix-routing-td1642832.html
	if( Configure::read( 'Optimisations.progressivePaginate' ) ) {
		$params = array( 'format' => 'Résultats %start% - %end% sur au moins %count% résultats.' );
	}
	else {
		$params = array( 'format' => 'Résultats %start% - %end% sur un total de %count%.' );
	}

	echo $paginator->counter( $params );
?>
<p>
	<?php echo $paginator->first( '<<' ); ?>
	<?php echo $paginator->prev( '<' ); ?>
	<?php echo $paginator->numbers(); ?>
	<?php echo $paginator->next( '>' ); ?>
	<?php
		if( !Configure::read( 'Optimisations.progressivePaginate' ) ) {
			echo $paginator->last( '>>' );
		}
	?>
</p>
