<?php
	class Actioncandidat extends AppModel
	{
		public $name = 'Actioncandidat';

		public $displayField = 'name';

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Enumerable' => array(
				'fields' => array(
					'contractualisation', 'correspondantaction', 'hasfichecandidature'
				)
			)
		);

//		public $validate = array(
//			'intitule' => array(
//				array(
//					'rule' => array('notEmpty'),
//				),
//			),
//			'code' => array(
//				array(
//					'rule' => array('notEmpty'),
//				),
//			),
//		);

		public $hasAndBelongsToMany = array(
			'Partenaire' => array(
				'className' => 'Partenaire',
				'joinTable' => 'actionscandidats_partenaires',
				'foreignKey' => 'actioncandidat_id',
				'associationForeignKey' => 'partenaire_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'ActioncandidatPartenaire'
			),
			'Personne' => array(
				'className' => 'Personne',
				'joinTable' => 'actionscandidats_personnes',
				'foreignKey' => 'actioncandidat_id',
				'associationForeignKey' => 'personne_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'ActioncandidatPersonne'
			),
			'Zonegeographique' => array(
				'className' => 'Zonegeographique',
				'joinTable' => 'actionscandidats_zonesgeographiques',
				'foreignKey' => 'actioncandidat_id',
				'associationForeignKey' => 'zonegeographique_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'ActioncandidatZonegeographique' // TODO
			)			
		);
		

		function afterFind($results,$primary = false)
		{
			$resultset = parent::afterFind( $results, $primary );

			if( !empty( $resultset ) ) 
			{
				foreach( $resultset as $i => $results )
				{
					if( isset( $results['Actioncandidat']['id'] ) && isset( $results['Actioncandidat']['themecode'] ) )
					{
						$codeaction = $results['Actioncandidat']['themecode'].$results['Actioncandidat']['codefamille'].$results['Actioncandidat']['numcodefamille'];
						$results['Actioncandidat']['codeaction'] = $codeaction;
					}
					$resultset[$i] = $results;
				}
			}
			return $resultset;
		}		
		

		
	}
?>
