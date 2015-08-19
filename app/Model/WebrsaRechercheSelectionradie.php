<?php
	/**
	 * Code source de la classe WebrsaRechercheSelectionradie.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AbstractWebrsaRechercheDefautinsertionep66', 'Model/Abstractclass' );
	
	/**
	 * La classe WebrsaRechercheSelectionradie ...
	 *
	 * @package app.Model
	 */
	class WebrsaRechercheSelectionradie extends AbstractWebrsaRechercheDefautinsertionep66
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'WebrsaRechercheSelectionradie';
		
		/**
		 * Retourne le querydata de base, en fonction du département, à utiliser
		 * dans le moteur de recherche.
		 *
		 * @param array $types Les types de jointure alias => type
		 * @return array
		 */
		public function searchQuery(array $types = array()) {
			$query = parent::searchQuery($types);
			
			$qdRadie = $this->Historiqueetatpe->Informationpe->qdRadies();
			
			$query['joins'] = array_merge( $query['joins'], $qdRadie['joins'] );
			
			return $query;
		}

		/**
		 * Complète les conditions du querydata avec le contenu des filtres de
		 * recherche.
		 *
		 * @param array $query
		 * @param array $search
		 * @return array
		 */
		public function searchConditions( array $query, array $search ) {
			$qdRadies = $this->Historiqueetatpe->Informationpe->qdRadies();
			$query = parent::searchConditions( $query, $search );
			$query['conditions'][] = $qdRadies['conditions'];
			
			$identifiantpe = Hash::get($search, 'Historiqueetatpe.identifiantpe');
			if ( $identifiantpe ) {
				$query['conditions']['SUBSTRING(UPPER(Historiqueetatpe.identifiantpe) FROM 4 FOR 8) = '] = $identifiantpe;
			}
			
			return $query;
		}
	}
?>