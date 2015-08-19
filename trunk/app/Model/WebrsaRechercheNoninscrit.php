<?php
	/**
	 * Code source de la classe WebrsaRechercheNoninscrit.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AbstractWebrsaRechercheDefautinsertionep66', 'Model/Abstractclass' );
	
	/**
	 * La classe WebrsaRechercheNoninscrit ...
	 *
	 * @package app.Model
	 */
	class WebrsaRechercheNoninscrit extends AbstractWebrsaRechercheDefautinsertionep66
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'WebrsaRechercheNoninscrit';

		/**
		 * Complète les conditions du querydata avec le contenu des filtres de
		 * recherche.
		 *
		 * @param array $query
		 * @param array $search
		 * @return array
		 */
		public function searchConditions( array $query, array $search ) {
			$qdNonInscrits = $this->Historiqueetatpe->Informationpe->qdNonInscrits();
			$query = parent::searchConditions( $query, $search );
			$query['conditions'][] = $qdNonInscrits['conditions'];
			
			return $query;
		}
	}
?>