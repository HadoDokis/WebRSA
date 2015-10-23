<?php
	/**
	 * Code source de la classe WebrsaRecherchesContratsinsertionNewComponent.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'WebrsaAbstractRecherchesNewComponent', 'Controller/Component' );

	/**
	 * La classe WebrsaRecherchesContratsinsertionNewComponent ...
	 *
	 * @package app.Controller.Component
	 */
	class WebrsaRecherchesContratsinsertionNewComponent extends WebrsaAbstractRecherchesNewComponent
	{
		/**
		 * Pour le CG 93, certaines jointures sont présentes uniquement lorsque
		 * certains filtres sont activés.
		 *
		 * @param array $params
		 * @param array $search
		 * @return array
		 */
		public function checkConfiguredFields( array $params = array(), array $search = array() ) {
			$departement = (int)Configure::read( 'Cg.departement' );
			if( $departement === 93 ) {
				$search = array(
					'Expprocer93' => array(
						'cer93_id' => 1
					),
					'Cer93Sujetcer93' => array(
						'sujetcer93_id' => 1
					)
				);
			}
			else {
				$search = array();
			}

			return parent::checkConfiguredFields( $params, $search );
		}

		/**
		 * Retourne les options de type "enum", c'est à dire liées aux schémas des
		 * tables de la base de données.
		 *
		 * @return array
		 */
		protected function _optionsEnums( array $params = array() ) {
			$Controller = $this->_Collection->getController();
			$departement = (int)Configure::read( 'Cg.departement' );

			$exists = array( '1' => 'Oui', '0' => 'Non' );

			$options = Hash::merge(
				parent::_optionsEnums( $params ),
				array(
					'Personne' => array(
						'trancheage' => array(
							'0_24' => '- 25 ans',
							'25_34' => '25 - 34 ans',
							'35_44' => '35 - 44 ans',
							'45_54' => '45 - 54 ans',
							'55_999' => '+ 55 ans'
						),
					)
				)
			);

			if( $departement === 58 ) {
				$options['Personne']['etat_dossier_orientation'] = $Controller->Contratinsertion->Personne->enum( 'etat_dossier_orientation' );
			}
			else if( $departement === 93 ) {
				$options = Hash::merge(
					$options,
					$Controller->Contratinsertion->Cer93->enums()
				);
			}

			return $options;
		}

		/**
		 * Retourne les options stockées liées à des enregistrements en base de
		 * données, ne dépendant pas de l'utilisateur connecté.
		 *
		 * @return array
		 */
		protected function _optionsRecords( array $params = array() ) {
			$Controller = $this->_Collection->getController();
			$departement = (int)Configure::read( 'Cg.departement' );

			$options = Hash::merge(
				parent::_optionsRecords( $params ),
				// TODO: dans la session, plutôt ?
				array(
					'Contratinsertion' => array(
						'structurereferente_id' => $Controller->Contratinsertion->Structurereferente->listOptions( array( 'orientation' => 'O' ) ),
						'referent_id' => $Controller->Contratinsertion->Structurereferente->Referent->listOptions()
					)
				)
			);

			if( $departement === 93 ) {
				$Controller->loadModel( 'Catalogueromev3' );

				$options = Hash::merge(
					$options,
					$Controller->Contratinsertion->Cer93->options( array( 'autre' => true, 'find' => true ) ),
					$Controller->Catalogueromev3->dependantSelects(),
					array(
						'Expprocer93' => array(
							'metierexerce_id' => $Controller->Contratinsertion->Cer93->Expprocer93->Metierexerce->find( 'list' ),
							'secteuracti_id' => $Controller->Contratinsertion->Cer93->Expprocer93->Secteuracti->find( 'list' )
						)
					)
				);
			}

			return $options;
		}

		/**
		 * Retourne les options stockées en session, liées à l'utilisateur connecté.
		 *
		 * @return array
		 */
		protected function _optionsSession( array $params ) {
			$Controller = $this->_Collection->getController();
			$departement = (int)Configure::read( 'Cg.departement' );

			$options = Hash::merge(
				parent::_optionsSession( $params ),
				array(
					'Orientstruct' => array(
						'typeorient_id' => $Controller->InsertionsAllocataires->typesorients( array( 'conditions' => array( 'Typeorient.actif' => 'O' ), 'empty' => ( $departement !== 58 ) ) )
					)
				)
			);

			if( $departement === 66 ) {
				$options['Orientstruct']['not_typeorient_id'] = $Controller->InsertionsAllocataires->typesorients( array( 'conditions' => array( 'Typeorient.actif' => 'O', 'Typeorient.parentid IS NULL' ) ) );
			}

			return $options;
		}

		/**
		 * Retourne les noms des modèles dont des enregistrements seront mis en
		 * cache après l'appel à la méthode _optionsRecords() afin que la clé de
		 * cache générée par la méthode _options() se trouve associée dans
		 * ModelCache.
		 *
		 * @see _optionsRecords(), _options()
		 *
		 * @return array
		 */
		protected function _optionsRecordsModels( array $params ) {
			$result = array_merge(
				parent::_optionsRecordsModels( $params ),
				array( 'Typeorient', 'Structurereferente', 'Referent' )
			);

			$departement = (int)Configure::read( 'Cg.departement' );
			if( $departement === 93 ) {
				$result = array_merge(
					$result,
					array(
						'Sujetcer93',
						'Soussujetcer93',
						'Valeurparsoussujetcer93',
						'Familleromev3',
						'Domaineromev3',
						'Metierromev3',
						'Appellationromev3',
						'Metierexerce',
						'Secteuracti'
					)
				);
			}

			return $result;
		}
	}
?>