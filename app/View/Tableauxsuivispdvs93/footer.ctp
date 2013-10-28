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

		// On permet l'export CSV des tableaux D1 et D2
		if( in_array( $this->request->params['pass'][0], array( 'tableaud1', 'tableaud2' ) ) ) {
			$url = array( 'action' => 'exportcsv', $this->request->params['pass'][0], $id );

			$actions[DefaultUrl::toString( $url )] = array(
				'enabled' => $this->Permissions->check( $this->request->params['controller'], 'exportcsv' ),
			);
		}
	}

	echo $this->DefaultDefault->actions( $actions );
?>