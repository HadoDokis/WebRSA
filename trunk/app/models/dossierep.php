<?php
	class Dossierep extends AppModel
	{
		public $name = 'Dossierep';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Formattable',
			'Enumerable' => array(
				'fields' => array(
					'etapedossierep',
					'themeep',
				)
			)
		);

		public $belongsTo = array(
			'Seanceep' => array(
				'className' => 'Seanceep',
				'foreignKey' => 'seanceep_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);

		public $hasOne = array(
			'Saisineepreorientsr93' => array(
				'className' => 'Saisineepreorientsr93',
				'foreignKey' => 'dossierep_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Saisineepbilanparcours66' => array(
				'className' => 'Saisineepbilanparcours66',
				'foreignKey' => 'dossierep_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Saisineepdpdo66' => array(
				'className' => 'Saisineepdpdo66',
				'foreignKey' => 'dossierep_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
		);

		public function themeTraite( $id ) {
			$ep = $this->find(
				'first',
				array(
					'conditions' => array(
						"{$this->alias}.{$this->primaryKey}" => $id
					),
					'contain' => array(
						'Seanceep' => array(
							'Ep'
						)
					)
				)
			);

			$themes = $this->Seanceep->Ep->themes();
			$themesTraites = array();

			foreach( $themes as $theme ) {
				if( in_array( $ep['Seanceep']['Ep'][$theme], array( 'ep', 'cg' ) ) ) {
					$themesTraites[$theme] = $ep['Seanceep']['Ep'][$theme];
				}
			}
			return $themesTraites;
		}

		public function prepareFormDataUnique( $dossierep_id, $dossier, $niveauDecision ) {
			$data = array();

			foreach( $this->themeTraite( $dossierep_id ) as $theme => $decision ) {
				$model = Inflector::classify( $theme );

				$data = Set::merge(
					$data,
					$this->{$model}->prepareFormDataUnique(
						$dossierep_id,
						$dossier,
						$niveauDecision
					)
				);
			}

			return $data;
		}
		
		public function sauvegardeUnique( $dossierep_id, $data, $niveauDecision ) {
			$success = true;

			foreach( $this->themeTraite( $dossierep_id ) as $theme => $decision ) {
				$model = Inflector::classify( $theme );
				$success = $this->{$model}->saveDecisionUnique( $data, $niveauDecision ) && $success;
			}

			return $success;
		}
	}
?>
