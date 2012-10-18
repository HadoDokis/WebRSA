<?php
	/**
	 * Code source de la classe Cer93.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Cer93 ...
	 *
	 * @package app.Model
	 */
	class Cer93 extends AppModel
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'Cer93';

		/**
		 * Récursivité.
		 *
		 * @var integer
		 */
		public $recursive = -1;

		/**
		 * Behaviors utilisés.
		 *
		 * @var array
		 */
		public $actsAs = array(
			'Validation.Autovalidate',
			'Enumerable',
			'Formattable',
		);

		/**
		 * Liaisons "belongsTo" avec d'autres modèles.
		 *
		 * @var array
		 */
		public $belongsTo = array(
			'Contratinsertion' => array(
				'className' => 'Contratinsertion',
				'foreignKey' => 'contratinsertion_id',
				'conditions' => null,
				'type' => 'INNER',
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
		);

		/**
		 * Liaisons "hasOne" avec d'autres modèles.
		 *
		 * @var array
		 */
// 		public $hasOne = array(
// 			'Compofoyercer93' => array(
// 				'className' => 'Compofoyercer93',
// 				'foreignKey' => 'cer93_id',
// 				'dependent' => true,
// 				'conditions' => '',
// 				'fields' => '',
// 				'order' => '',
// 				'limit' => '',
// 				'offset' => '',
// 				'exclusive' => '',
// 				'finderQuery' => '',
// 				'counterQuery' => ''
// 			),
// 		);

		/**
		 * Liaisons "hasMany" avec d'autres modèles.
		 *
		 * @var array
		 */
		public $hasMany = array(
			'Compofoyercer93' => array(
				'className' => 'Compofoyercer93',
				'foreignKey' => 'cer93_id',
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
			'Diplomecer93' => array(
				'className' => 'Diplomecer93',
				'foreignKey' => 'cer93_id',
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
		
		/**
		*	Fonction permettant la sauvegarde du CER 93
		*	Une règle de validation est supprimée en amont
		*	Les valeurs de la table Compofoyercer93 sont mises à jour à chaque modifciation
		*	@param $data
		*	@return boolean
		*/
		
		public function saveFormulaire( $data ){
			$success = true;
			// Sinon, ça pose des problèmes lors du add car la valeur n'existe pas encore
			$this->unsetValidationRule( 'contratinsertion_id', 'notEmpty' );

			if( isset( $data['Cer93']['id'] ) && !empty( $data['Cer93']['id'] ) ) {
				$success = $this->Compofoyercer93->deleteAll(
					array( 'Compofoyercer93.cer93_id' => $data['Cer93']['id'] )
				);
			}
				
			$success = $this->saveResultAsBool(
				$this->saveAssociated( $data, array( 'validate' => 'first', 'atomic' => false, 'deep' => true ) )
			) && $success;

			return $success;
		}

	}
?>