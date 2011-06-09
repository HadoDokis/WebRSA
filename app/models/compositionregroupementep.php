<?php
	class Compositionregroupementep extends AppModel
	{
		public $name = 'Compositionregroupementep';

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Formattable',
			'Enumerable' => array(
				'fields' => array(
					'prioritaire',
					'obligatoire'
				)
			)
		);

		public $belongsTo = array(
			'Fonctionmembreep' => array(
				'className' => 'Fonctionmembreep',
				'foreignKey' => 'fonctionmembreep_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Regroupementep' => array(
				'className' => 'Regroupementep',
				'foreignKey' => 'regroupementep_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);

		public function compositionValide( $regroupementep_id, $membreseps ) {
			$return = true;
			$error = null;

			$compositionregroupementep = $this->Regroupementep->find(
				'first',
				array(
					'conditions' => array(
						'Regroupementep.id' => $regroupementep_id
					),
					'contain' => array(
						'Compositionregroupementep'
					)
				)
			);

			$membreChoisi = false;
			$compoCree = false;
			$nbPrioritaire = 0;
			foreach( $compositionregroupementep['Compositionregroupementep'] as $compo ) {
				$compoCree = true;

				if( $compo['obligatoire'] == 1 || $compo['prioritaire'] == 1 ) {
					$membresFonction = $this->Fonctionmembreep->find(
						'first',
						array(
							'conditions' => array(
								'Fonctionmembreep.id' => $compo['fonctionmembreep_id']
							),
							'contain' => array(
								'Membreep'
							)
						)
					);

					foreach ( $membresFonction['Membreep'] as $membre ) {
						if ( !empty( $membreseps ) && in_array( $membre['id'], $membreseps ) ) {
							if( $compo['obligatoire'] == 1 ) {
								$membreChoisi = true;
							}
							if( $compo['prioritaire'] == 1 ) {
								$nbPrioritaire++;
							}
						}
					}
				}
			}

			if ( !$membreChoisi && $compoCree ) {
				$return = false;
				$error = "obligatoire";
			}
			elseif ( $compositionregroupementep['Regroupementep']['nbminmembre'] > 0 && $nbPrioritaire < $compositionregroupementep['Regroupementep']['nbminmembre'] ) {
				$return = false;
				$error = 'nbminmembre';
			}
			elseif ( $compositionregroupementep['Regroupementep']['nbmaxmembre'] > 0 && count( $membreseps ) > $compositionregroupementep['Regroupementep']['nbmaxmembre'] ) {
				$return = false;
				$error = 'nbmaxmembre';
			}

			return array( 'check' => $return, 'error' => $error );
		}

		public function listeFonctionsObligatoires( $regroupementep_id ) {
			$compositionregroupementep = $this->find(
				'all',
				array(
					'conditions' => array(
						'Compositionregroupementep.regroupementep_id' => $regroupementep_id
					),
					'contain' => array(
						'Fonctionmembreep'
					)
				)
			);

			$fonctionsObligatoires = array();
			foreach( $compositionregroupementep as $compo ) {
				if ( $compo['Compositionregroupementep']['obligatoire'] == 1 ) {
					$fonctionsObligatoires[] = $compo['Fonctionmembreep']['name'];
				}
			}

			return $fonctionsObligatoires;
		}
	
	}
?>