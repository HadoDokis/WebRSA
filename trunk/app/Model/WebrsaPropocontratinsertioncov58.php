<?php
	/**
	 * Code source de la classe WebrsaPropocontratinsertioncov58.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'ConfigurableQueryFields', 'ConfigurableQuery.Utility' );

	/**
	 * La classe WebrsaPropocontratinsertioncov58 contient de la logique
	 * métier pour la thématique de COV "Proposition de CER".
	 *
	 * @package app.Model
	 */
	class WebrsaPropocontratinsertioncov58 extends AppModel
	{

		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'WebrsaPropocontratinsertioncov58';

		/**
		 * Ce modèle n'utilise pas directement de table.
		 *
		 * @var integer
		 */
		public $useTable = false;

		/**
		 * Récursivité par défaut du modèle.
		 *
		 * @var integer
		 */
		public $recursive = -1;

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
			'Propocontratinsertioncov58'
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
				'Structurereferente' => 'VxStructurereferente',
				'Referent' => 'VxReferent',
			);

			$query['fields'] = array_merge(
				$query['fields'],
				array_words_replace(
					ConfigurableQueryFields::getModelsFields(
						array(
							$this->Propocontratinsertioncov58,
							$this->Propocontratinsertioncov58->Structurereferente,
							$this->Propocontratinsertioncov58->Referent
						)
					),
					$replacements
				)
			);

			$query['joins'][] = array_words_replace( $this->Propocontratinsertioncov58->join( 'Structurereferente', array( 'type' => 'INNER' ) ), $replacements );
			$query['joins'][] = array_words_replace( $this->Propocontratinsertioncov58->join( 'Referent', array( 'type' => 'LEFT OUTER' ) ), $replacements );

			return $query;
		}
	}
?>