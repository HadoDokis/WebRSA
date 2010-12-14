<?php
	class Relancesnonrespectssanctionseps93Controller extends AppController
	{
		public $helpers = array( 'Default2' );

		public $uses = array( 'Relancenonrespectsanctionep93', 'Orientstruct', 'Contratinsertion' );

		/**
		*
		*/

		public function beforeFilter() {
			$this->Auth->allow( '*' ); // FIXME
		}

		/**
		*
		*/

		public function index() {
			if( !empty( $this->data ) ) {
				if( isset( $this->data['Relancenonrespectsanctionep93'] ) ) {
					$data = array( 'Relancenonrespectsanctionep93' => $this->data['Relancenonrespectsanctionep93'] );
					debug( $data );
				}

				$search = $this->data;
				unset( $search['Relancenonrespectsanctionep93'] );
				$search = Set::flatten( $search );
				$search = Set::filter( $search );


				$conditions = array();

				foreach( $search as $field => $condition ) {
					if( in_array( $field, array( 'Personne.nom', 'Personne.prenom' ) ) ) {
						$conditions["UPPER({$field}) LIKE"] = str_replace( '*', '%', $condition );
					}
					else if( $field != 'Relance.numrelance' ) {
						$conditions[$field] = $condition;
					}
				}

				// Personne orientée sans contrat
				// FIXME: dernière orientation
				// FIXME: et qui ne se trouve pas dans les EPs en cours de traitement

				switch( $search['Relance.numrelance'] ) {
					case 1:
						$conditions[] = 'Orientstruct.id NOT IN (
							SELECT nonrespectssanctionseps93.orientstruct_id
								FROM nonrespectssanctionseps93
								WHERE
									nonrespectssanctionseps93.active = \'1\'
									AND nonrespectssanctionseps93.orientstruct_id = Orientstruct.id
						)';
						break;
				}
				$conditions['Orientstruct.statut_orient'] = 'Orienté';
				$conditions[] = 'Orientstruct.personne_id NOT IN (
									SELECT contratsinsertion.personne_id
										FROM contratsinsertion
										WHERE
											contratsinsertion.personne_id = Orientstruct.personne_id
											AND date_trunc( \'day\', contratsinsertion.datevalidation_ci ) >= Orientstruct.date_valid
										)';
				// AND date_trunc( \'day\', contratsinsertion.datevalidation_ci ) <= ( Orientstruct.date_valid + INTERVAL \'2 mons\' )

				$queryData = array(
					'conditions' => $conditions,
					'contain' => array(
						'Personne' => array(
							'fields' => array(
								'Personne.nom',
								'Personne.prenom',
								'Personne.nir',
							),
							'Foyer' => array(
								'Dossier' => array(
									'fields' => array(
										'Dossier.matricule',
									)
								),
								'Adressefoyer' => array(
									'conditions' => array(
										'Adressefoyer.rgadr' => '01'
									),
									'Adresse' => array(
										'fields' => array(
											'Adresse.locaadr',
										)
									)
								)
							)
						)
					),
					'limit' => 10,
					'order' => array( 'Orientstruct.date_valid ASC' ),
				);

				$results = $this->Orientstruct->find( 'all', $queryData );

				foreach( $results as $i => $result ) {
					$results[$i]['Orientstruct']['nbjours'] = round(
						( mktime() - strtotime( $result['Orientstruct']['date_valid'] ) ) / ( 60 * 60 * 24 )
					);
				}

				$this->set( compact( 'results' ) );
			}
		}
	}
?>