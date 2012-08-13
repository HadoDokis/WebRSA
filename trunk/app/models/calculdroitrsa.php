<?php
	class Calculdroitrsa extends AppModel
	{
		public $name = 'Calculdroitrsa';

		protected $_modules = array( 'caf' );

		public $actsAs = array(
			'Formattable'
		);

		public $validate = array(
			'mtpersressmenrsa' => array(
				array(
					// FIXME INFO ailleurs aussi => 123,25 ne passe pas
					'rule' => 'numeric',
					'message' => 'Veuillez entrer une valeur numérique.'
				),
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
			)
		);

		public $belongsTo = array(
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		/**
		*
		*/

		public function dossierId( $ressource_id ) {
			$this->unbindModelAll();
			$this->bindModel(
				array(
					'hasOne' => array(
						'Personne' => array(
							'foreignKey' => false,
							'conditions' => array( 'Personne.id = Calculdroitrsa.personne_id' )
						),
						'Foyer' => array(
							'foreignKey' => false,
							'conditions' => array( 'Foyer.id = Personne.foyer_id' )
						)
					)
				)
			);
			$qd_ressource = array(
				'conditions' => array(
					'Ressource.id' => $ressource_id
				),
				'fields' => null,
				'order' => null,
				'recursive' => 1
			);
			$ressource = $this->find('first', $qd_ressource);

			if( !empty( $ressource ) ) {
				return $ressource['Foyer']['dossier_id'];
			}
			else {
				return null;
			}
		}

		/**
		*	Fonction retournant un booléen précisant si la personne est soumise à drit et devoir ou non
		*/

		public function isSoumisAdroitEtDevoir( $personne_id ) {
			return (
				$this->find(
					'count',
					array(
						'conditions' => array(
							'Calculdroitrsa.personne_id' => $personne_id,
							'Calculdroitrsa.toppersdrodevorsa' => 1
						),
						'contain' => false
					)
				) > 0
			);
		}
	}
?>
