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
		if( in_array( $tableausuivipdv93['Tableausuivipdv93']['name'], array( 'tableaud1', 'tableaud2' ) ) ) {
			// Export CSv du tableau de résultats
			$url = array( 'action' => 'exportcsvdonnees', $tableausuivipdv93['Tableausuivipdv93']['name'], $id );
			$actions[DefaultUrl::toString( $url )] = array(
				'enabled' => $this->Permissions->check( $this->request->params['controller'], 'exportcsvdonnees' ),
			);

			// Export CSV du corpus
			$url = array( 'action' => 'exportcsvcorpus', $tableausuivipdv93['Tableausuivipdv93']['name'], $id );
			$actions[DefaultUrl::toString( $url )] = array(
				'enabled' => $this->Permissions->check( $this->request->params['controller'], 'exportcsvcorpus' ),
			);
		}
	}

	echo $this->DefaultDefault->actions( $actions );
?>