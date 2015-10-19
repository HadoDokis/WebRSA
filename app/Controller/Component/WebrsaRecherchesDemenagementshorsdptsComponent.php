<?php
	/**
	 * Code source de la classe WebrsaRecherchesDemenagementshorsdptsComponent.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'WebrsaAbstractRecherchesComponent', 'Controller/Component' );

	/**
	 * La classe WebrsaRecherchesDemenagementshorsdptsComponent ...
	 *
	 * @deprecated since 3.0.00
	 *
	 * @package app.Controller.Component
	 */
	class WebrsaRecherchesDemenagementshorsdptsComponent extends WebrsaAbstractRecherchesComponent
	{
		/**
		 * Retourne le querydata complété par les conditions du moteur de recherche,
		 * ainsi que des conditions liées à l'utilisateur connecté.
		 *
		 * @param array $query
		 * @param array $params
		 * @return array
		 */
		public function getQueryConditions( array $query, array $params = array()  ) {
			$Controller = $this->_Collection->getController();
			$departement = (int)Configure::read( 'Cg.departement' );
			$query = parent::getQueryConditions($query, $params);

			// Conditions sur les dates d'emménagement pour les externes
			if( $departement === 93 && ( strpos( $Controller->Session->read( 'Auth.User.type' ), 'externe_' ) === 0 ) ) {
				$query['conditions'][] = array(
					'OR' => array(
						// L'allocataire a quitté le CG en rang 01 et l'adresse de rang 2 ...
						array(
							'Adresse2.numcom LIKE' => "{$departement}%",
							"CAST( DATE_PART( 'year', \"Adressefoyer\".\"dtemm\" ) + 1 || '-03-31' AS DATE ) >= NOW()",
						),
						// L'allocataire a quitté l'adresse de rang 3 ...
						array(
							'Adresse3.numcom LIKE' => "{$departement}%",
							"CAST( DATE_PART( 'year', \"Adressefoyer2\".\"dtemm\" ) + 1 || '-03-31' AS DATE ) >= NOW()",
						),
					)
				);
			}

			return $query;
		}

		/**
		 * Options pour le moteur de recherche
		 *
		 * @param array $params
		 * @return array
		 */
		public function options( array $params = array() ) {
			$options = parent::options( $params );
			$this->Option = ClassRegistry::init( 'Option' );

			$options = Hash::merge(
				$options,
				array(
					'Adresse2' => array(
						'pays' => $this->Option->pays(),
						'typeres' => $this->Option->typeres()
					),
					'Adressefoyer2' => array(
						'rgadr' => $this->Option->rgadr(),
						'typeadr' => $this->Option->typeadr(),
					),
					'Adresse3' => array(
						'pays' => $this->Option->pays(),
						'typeres' => $this->Option->typeres()
					),
					'Adressefoyer3' => array(
						'rgadr' => $this->Option->rgadr(),
						'typeadr' => $this->Option->typeadr(),
					),
				)
			);

			return $options;
		}
	}
?>