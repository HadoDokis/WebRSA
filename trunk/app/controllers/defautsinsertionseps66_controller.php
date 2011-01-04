<?php
	class Defautsinsertionseps66Controller extends AppController
	{
		public $helpers = array( 'Default2' );

		/**
		* FIXME: pour les dossiers qui ne sont pas encore en séance.
		*	1°) personnes non cochées que l'on sélectionne
		*	2°) personnes précédemment sélectionnées, que l'on désélectionne
		*	3°) personnes précédemment sélectionnées, que l'on garde sélectionnées
		* FIXME: initialiser les cases à cocher du tableau
		*/

		public function selectionnoninscrits() {
			if( !empty( $this->data ) ) {
				$success = true;
				$this->Defautinsertionep66->begin();

				foreach( $this->data['Orientstruct'] as $item ) {
					if( !empty( $item['chosen'] ) ) {
						$dossierep = array(
							'Dossierep' => array(
								'themeep' => 'defautsinsertionseps66',
								'personne_id' => $item['personne_id']
							)
						);
						$this->Defautinsertionep66->Dossierep->create( $dossierep );
						$success = $this->Defautinsertionep66->Dossierep->save() && $success;

						$defautinsertionep66 = array(
							'Defautinsertionep66' => array(
								'dossierep_id' => $this->Defautinsertionep66->Dossierep->id,
								'orientstruct_id' => $item['id'],
								'origine' => 'noninscriptionpe'
							)
						);
						$this->Defautinsertionep66->create( $defautinsertionep66 );
						$success = $this->Defautinsertionep66->save() && $success;
					}
				}

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->Defautinsertionep66->rollback(); // FIXME
				}
				else {
					$this->Defautinsertionep66->rollback();
				}
			}

			$queryData = $this->Defautinsertionep66->qdNonInscrits();
			$queryData['limit'] = 10;

			$this->paginate = array( 'Personne' => $queryData );
			$personnes = $this->paginate( $this->Defautinsertionep66->Dossierep->Personne );

			$this->set( compact( 'personnes' ) );
		}

		/**
		*
		*/

		public function selectionradies() {
			$queryData = $this->Defautinsertionep66->qdRadies();
			debug( $queryData );
		}
	}
?>