<?php
	/**
	 * Code source de la classe WebrsaDossiercov58.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe WebrsaDossiercov58 contient la logique métier concernant les
	 * dossiers d'EP.
	 *
	 * @package app.Model
	 */
	class WebrsaDossiercov58 extends AppModel
	{

		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'WebrsaDossiercov58';

		/**
		 * Récursivité par défaut du modèle.
		 *
		 * @var integer
		 */
		public $useTable = false;

		/**
		 * Behaviors utilisés par ce modèle.
		 *
		 * @var array
		 */
		public $actsAs = array();

		/**
		 * Modèles utilisés par ce modèle.
		 *
		 * @var array
		 */
		public $uses = array(
			'Dossiercov58'
		);

		/**
		 * Retourne la liste des dossiers de COV en cours ne débouchant pas sur
		 * une orientation pour un allocataire donné.
		 *
		 * @param integer $personne_id
		 * @return array
		 */
		public function getNonReorientationsEnCours( $personne_id ) {
			// 1. Récupération des conditions concernant les dossiers d'EP ouverts pour le bénéficiaire
			$query = $this->Dossiercov58->qdDossiersepsOuverts( $personne_id );
			$conditions = $query['conditions'];

			// 2. Récupération du query permettant de récupérer les dossiers d'EP
			// liés à leur dernier passage en commission
			$query = $this->Dossiercov58->getDossiersQuery();
			$query['fields'] = array(
				'Personne.id',
				'Personne.qual',
				'Personne.nom',
				'Personne.prenom',
				'Dossiercov58.id',
				'Dossiercov58.created',
				'Dossiercov58.themecov58',
				'Passagecov58.id',
				'Passagecov58.etatdossiercov',
				'Cov58.id',
				'Cov58.datecommission',
				'Cov58.etatcov'
			);

			$query['conditions'][] = $conditions;

			// et qui ne conduisent pas à une réorientation (ils se trouvent déjà dans $reorientationscovs)
			$query['conditions'][] = array(
				'NOT' => array(
					'Dossiercov58.themecov58' => $this->Dossiercov58->getThematiquesReorientations()
				)
			);

			// Pour la personne
			$query['conditions'][] = array( 'Dossiercov58.personne_id' => $personne_id );


			return $this->Dossiercov58->find( 'all', $query );
		}
	}
?>