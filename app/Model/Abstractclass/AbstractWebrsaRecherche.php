<?php
	/**
	 * Code source de la classe AbstractWebrsaRecherche.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'WebrsaRechercheInterface', 'Model/Interface' );

	/**
	 * La classe AbstractWebrsaRecherche ...
	 *
	 * @package app.Model
	 */
	abstract class AbstractWebrsaRecherche extends AppModel implements WebrsaRechercheInterface
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'AbstractWebrsaRecherche';

		/**
		 * On n'utilise pas de table.
		 *
		 * @var mixed
		 */
		public $useTable = false;

		/**
		 * Behaviors utilisés par le modèle.
		 *
		 * @var array
		 */
		public $actsAs = array( 'Conditionnable' );

		/**
		 * Liste des clés de configuration utilisées par le moteur de recherche,
		 * pour vérification du paramétrage.
		 *
		 * @see checkParametrage()
		 *
		 * @var array
		 */
		public $keysRecherche = array();

		/**
		 * Retourne un querydata, en fonction du département, prenant en compte
		 * les différents filtres du moteur de recherche.
		 *
		 * @todo à utiliser de manière dissociée (avec préparation des fields dans le contrôleur, ou les passer en paramètre ?)
		 * @param array $params
		 * @return array
		 */
		public function search( array $search ) {
			$query = $this->searchQuery();
			$query = $this->searchConditions( $query, $search );

			return $query;
		}

		/**
		 * Préchargement de la méthode searchQuery().
		 *
		 * @return boolean true en cas de succès, false en cas d'erreur
		 */
		public function prechargement() {
			$success = true;

			$query = $this->searchQuery();
			$success = !empty( $query ) && $success;

			return $success;
		}

		/**
		 * Vérification que les champs spécifiés dans le paramétrage par les clés
		 * XXXX.fields, XXXX.innerTable et XXXX.exportcsv dans le webrsa.inc existent
		 * bien dans la requête de recherche renvoyée par la méthode searchQuery().
		 *
		 * @see $keysRecherche
		 *
		 * @return array
		 */
		public function checkParametrage() {
			$query = $this->search( array() );

			$return = ConfigurableQueryFields::getErrors( $this->keysRecherche, $query );

			// TODO: export des champs disponibles
//			$fileName = TMP.DS.'logs'.DS.__CLASS__.'__searchQuery__cg'.Configure::read( 'Cg.departement' ).'.csv';
//			ConfigurableQueryFields::exportQueryFields( $query, Inflector::tableize( $this->name ), $fileName );

			return $return;
		}
	}
?>