<?php
	require_once( dirname( __FILE__ ).DS.'search.ctp' );
	$index = 0;
	$annee = Hash::get( $this->request->data, 'Search.annee' );
?>
<?php if( isset( $results ) ): ?>
	<?php debug( $results );?>
	<?php require_once( dirname( __FILE__ ).DS.'footer.ctp' );?>
<?php endif;?>