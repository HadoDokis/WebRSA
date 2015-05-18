<?php
	require_once( dirname( __FILE__ ).DS.'search.ctp' );

	if( isset( $tableauxsuivispdvs93 ) ) {
		$this->Default3->DefaultPaginator->options( array( 'url' => $this->request->params['named'] ) );

		echo $this->Default3->index(
			$tableauxsuivispdvs93,
			array(
				'Tableausuivipdv93.annee' => array( 'type' => 'text', 'class' => 'integer number' ),
				'Pdv.lib_struc',
				'Referent.nom_complet',
				'Tableausuivipdv93.name',
				'Tableausuivipdv93.version',
				'Photographe.nom_complet',
				'Tableausuivipdv93.created',
				'Tableausuivipdv93.modified',
				'/Tableauxsuivispdvs93/view/#Tableausuivipdv93.id#',
				'/Tableauxsuivispdvs93/delete/#Tableausuivipdv93.id#' => array( 'confirm' => true ),
			),
			array(
				'options' => $options,
			)
		);
	}
?>