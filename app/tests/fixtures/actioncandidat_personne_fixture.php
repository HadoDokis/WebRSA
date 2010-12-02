<?php

	class ActioncandidatPersonneFixture extends CakeTestFixture {
		var $name = 'ActioncandidatPersonne';
		var $table = 'actionscandidats_personnes';
		var $import = array( 'table' => 'actionscandidats_personnes', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'personne_id' => '1',
				'actioncandidat_id' => '1',
				'referent_id' => '1',
				'ddaction' => null,
				'dfaction' => null,
				'motifdemande' => null,
				'enattente' => null,
				'datesignature' => null,
				'bilanvenu' => null,
				'bilanretenu' => null,
				'infocomplementaire' => null,
				'datebilan' => null,
				'rendezvouspartenaire' => null,
				'daterdvpartenaire' => null,
				'mobile' => null,
				'naturemobile' => null,
				'typemobile' => null,
				'bilanrecu' => null,
				'daterecu' => null,
				'personnerecu' => null,
				'pieceallocataire' => null,
				'autrepiece' => null,
				'precisionmotif' => null,
				'presencecontrat' => null,
				'integrationaction' => null,
				'rendezvous_id' => '1'
			)
		);
	}

?>
