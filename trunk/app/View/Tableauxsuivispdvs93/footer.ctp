<?php
	if( $this->request->action != 'view' ) {
		$url = Hash::merge(
			array( 'action' => 'historiciser', $this->action ),
			Hash::flatten( $this->request->data )
		);

		$actions = array(
			DefaultUtility::toString( $url ) => array(
				'enabled' => $this->Permissions->check( $this->request->params['controller'], 'historiciser' ),
			)
		);
	}
	else {
		$actions = array(
			DefaultUtility::toString( $this->request->referer( true ) ) => array(
				'text' => 'Retour',
				'msgid' => 'Retour à la page précédente',
				'enabled' => $this->Permissions->check( $this->request->params['controller'], 'index' ),
				'class' => 'back'
			)
		);
	}

	echo $this->DefaultDefault->actions( $actions );
?>