<?php
	// INFO: http://n2.nabble.com/named-params-and-prefix-routing-td1642832.html
?>
<p>
	<?php
		$paginator->options( array( 'url' => $this->passedArgs ) );
		$params = array( // FIXME: pluriels
			'format' => 'Résultats %start% - %end% sur un total de %count%.'
		);
		echo $paginator->counter( $params );
	?>
</p>
<p>
	<?php echo $paginator->first( '<<' ); ?>
	<?php echo $paginator->prev( '<' ); ?>
	<?php echo $paginator->numbers(); ?>
	<?php echo $paginator->next( '>' ); ?>
	<?php echo $paginator->last( '>>' ); ?>
</p>