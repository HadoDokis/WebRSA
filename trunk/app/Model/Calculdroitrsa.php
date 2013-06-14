<?php	
	/**
	 * Code source de la classe Calculdroitrsa.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Calculdroitrsa ...
	 *
	 * @package app.Model
	 */
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

		public function dossierId( $calculdroitrsa_id ) {
			$qd_calculdroitrsa = array(
				'fields' => array( 'Foyer.dossier_id' ),
				'joins' => array(
					$this->join( 'Personne', array( 'type' => 'INNER' ) ),
					$this->Personne->join( 'Foyer', array( 'type' => 'INNER' ) )
				),
				'conditions' => array(
					'Calculdroitrsa.id' => $calculdroitrsa_id
				),
				'recursive' => -1
			);
			$calculdroitrsa = $this->find('first', $qd_calculdroitrsa);

			if( !empty( $calculdroitrsa ) ) {
				return $calculdroitrsa['Foyer']['dossier_id'];
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
							'Calculdroitrsa.toppersdrodevorsa' => '1'
						),
						'contain' => false
					)
				) > 0
			);
		}
	}
?>
