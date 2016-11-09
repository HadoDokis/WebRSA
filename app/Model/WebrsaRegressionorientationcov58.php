<?php
	/**
	 * Code source de la classe WebrsaRegressionorientationcov58.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AppModel', 'Model' );
	App::uses( 'ConfigurableQueryFields', 'ConfigurableQuery.Utility' );

	/**
	 * La classe WebrsaRegressionorientationcov58 contient de la logique métier pour
	 * la thématique de COV "Orientation du professionnel vers le social".
	 *
	 * @package app.Model
	 */
	class WebrsaRegressionorientationcov58 extends AppModel
	{

		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'WebrsaRegressionorientationcov58';

		/**
		 * Ce modèle n'utilise pas directement de table.
		 *
		 * @var integer
		 */
		public $useTable = false;

		/**
		 * Behaviors utilisés par le modèle.
		 *
		 * @var array
		 */
		public $actsAs = array();

		/**
		 * Modèles utilisés par le modèle.
		 *
		 * @var array
		 */
		public $uses = array(
			'Regressionorientationcov58'
		);

		/**
		 * Complète un querydata avec les données de la thématique.
		 *
		 * @param array $query
		 * @return array
		 */
		public function completeQuery( array $query ) {
			$query += array(
				'fields' => array(),
				'conditions' => array(),
				'joins' => array()
			);

			$replacements = array(
				'Orientstruct' => 'VxOrientstruct',
				'Typeorient' => 'VxTypeorient',
				'Structurereferente' => 'VxStructurereferente',
				'Referent' => 'VxReferent',
			);

			$query['fields'] = array_merge(
				$query['fields'],
				array_words_replace(
					ConfigurableQueryFields::getModelsFields(
						array(
							$this->Regressionorientationcov58,
							$this->Regressionorientationcov58->Orientstruct,
							$this->Regressionorientationcov58->Orientstruct->Typeorient,
							$this->Regressionorientationcov58->Orientstruct->Structurereferente,
							$this->Regressionorientationcov58->Orientstruct->Referent
						)
					),
					$replacements
				)
			);

			$query['joins'][] = array_words_replace( $this->Regressionorientationcov58->join( 'Orientstruct', array( 'type' => 'INNER' ) ), $replacements );
			$query['joins'][] = array_words_replace( $this->Regressionorientationcov58->Orientstruct->join( 'Typeorient', array( 'type' => 'INNER' ) ), $replacements );
			$query['joins'][] = array_words_replace( $this->Regressionorientationcov58->Orientstruct->join( 'Structurereferente', array( 'type' => 'INNER' ) ), $replacements );
			$query['joins'][] = array_words_replace( $this->Regressionorientationcov58->Orientstruct->join( 'Referent', array( 'type' => 'LEFT OUTER' ) ), $replacements );

			return $query;
		}
	}
?>