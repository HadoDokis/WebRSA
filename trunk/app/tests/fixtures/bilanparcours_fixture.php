<?php

	class BilanparcoursFixture extends CakeTestFixture {
		var $name = 'Bilanparcours';
		var $table = 'bilanparcours';
		var $import = array( 'table' => 'bilanparcours', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'personne_id' => '1',
				'referent_id' => '1',
				'structurereferente_id' => '1',
				'objinit' => null,
				'objatteint' => null,
				'objnew' => null,
				'proposition' => null,
				'rendezvous_id' => '1',
				'datebilan' => null,
				'maintienorientsansep' => null,
				'changementrefsansep' => null,
				'datedebreconduction' => null,
				'datefinreconduction' => null,
				'nvsansep_referent_id' => '1',
				'accordprojet' => null,
				'choixparcours' => null,
				'maintienorientparcours' => null,
				'changementrefparcours' => null,
				'nvparcours_referent_id' => '1',
				'reorientation' => null,
				'examenaudition' => null,
				'infoscomplementaires' => null,
				'observbenef' => null,
				'dateaviseplocale' => null,
				'maintienorientavisep' => null,
				'changementrefeplocale' => null,
				'reorientationeplocale' => null,
				'avisparcours' => null,
				'aviscoordonnateur' => null,
				'aviscga' => null,
				'typeeplocale' => null,
				'dateavisaudition' => null,
				'decisioncommission' => null,
				'autreaviscommission' => null,
				'infoscompleplocale' => null,
				'dateaviscoordonnateur' => null,
				'decisioncoordonnateur' => null,
				'motivationavis' => null,
				'dateaviscga' => null,
				'decisioncga' => null,
				'motivationaviscga' => null,					
			),
		);
	}

?>
