<?php
	if( $this->request->action != 'view' ) {
		$url = Hash::merge(
			array( 'action' => 'historiser', $this->action ),
			Hash::flatten( $this->request->data )
		);

		$actions = array(
			DefaultUrl::toString( $url ) => array(
				'enabled' => $this->Permissions->check( $this->request->params['controller'], 'historiser' ),
			)
		);
	}
	else {
		$actions = array(
			DefaultUrl::toString( $this->request->referer( true ) ) => array(
				'text' => 'Retour',
				'msgid' => 'Retour à la page précédente',
				'enabled' => $this->Permissions->check( $this->request->params['controller'], 'index' ),
				'class' => 'back'
			)
		);
	}

	echo $this->DefaultDefault->actions( $actions );
?>