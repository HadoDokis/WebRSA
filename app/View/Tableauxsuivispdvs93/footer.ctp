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
		$actions = $this->Default3->DefaultAction->back();
	}

	echo $this->DefaultDefault->actions( $actions );
?>