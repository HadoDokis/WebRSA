<?php
	App::import( 'Sanitize' );

	class Cohorte extends AppModel
	{
		public $name = 'Cohorte';

		public $useTable = false;

		public $actsAs = array( 'Gedooo' );

		/**
		* Dernière version des règles de préorientation:
		*	- prise en compte des informations Pôle Emploi le 04/01/2011, par mail
		* 	- changement règle 4 le 16/04/2010, par mail
		*/

		public function preOrientation( $element ) {
			$propo_algo = null;

			/// Inscription Pôle Emploi ?
			$this->Informationpe = Classregistry::init( 'Informationpe' );
			$informationpe = $this->Informationpe->find(
				'first',
				array(
					'fields' => array(
						'(
							SELECT
									"Historiqueetatpe"."etat"
								FROM "historiqueetatspe" AS "Historiqueetatpe"
								WHERE
									"Historiqueetatpe"."informationpe_id" = "Informationpe"."id"
									ORDER BY "Historiqueetatpe"."date" DESC LIMIT 1
						) AS "Historiqueetatpe__dernieretat"'
					),
					'conditions' => $this->Informationpe->qdConditionsJoinPersonneOnValues( 'Informationpe', $element['Personne'] ),
					'contain' => false
				)
			);

			// La personne se retrouve préorientée en emploi si la dernière information
			// venant de Pôle Emploi la concernant est une inscription
			if( !empty( $informationpe ) ) {
				if( @$informationpe['Historiqueetatpe']['dernieretat'] == 'inscription' ) {
					return 'Emploi';
				}
			}

			// On ne peut pas préorienter à partir des informations Pôle Emploi
			if( is_null( $propo_algo ) ) {
				/// Dsp
				$this->Dsp = Classregistry::init( 'Dsp' );
				$dsp = $this->Dsp->find(
					'first',
					array(
						'fields' => array(
							'Dsp.natlog',
							'Dsp.sitpersdemrsa',
							'Dsp.cessderact',
							'Dsp.hispro',
							'Detaildiflog.diflog',
						),
						'conditions' => array( 'Dsp.personne_id' => $element['Personne']['id'] ),
						'contain' => false,
						'joins' => array(
							array(
								'table'      => 'detailsdiflogs',
								'alias'      => 'Detaildiflog',
								'type'       => 'LEFT OUTER',
								'foreignKey' => false,
								'conditions' => array(
									'Detaildiflog.dsp_id = Dsp.id',
									'Detaildiflog.diflog' => '1006'
								)
							),
						)
					)
				);

				/// Règles de gestion déduites depuis les DSP
				if( !empty( $dsp ) ) {
					// Règle 1 (Prioritaire) : Code XML instruction : « NATLOG ». Nature du logement ?
					// 0904 = Logement d'urgence : CHRS → Orientation vers le Social
					// 0911 = Logement précaire : résidence sociale → Orientation vers le Social
					$natlog = Set::classicExtract( $dsp, 'Dsp.natlog' );
					if( empty( $propo_algo ) && !empty( $natlog ) ) {
						if( in_array( $natlog, array( '0904', '0911' ) ) ) {
							$propo_algo = 'Social';
						}
					}

					// Règle 2 (Prioritaire)  : Code XML instruction : « DIFLOG ». Difficultés logement ?
					// 1006 = Fin de bail, expulsion → Orientation vers le Service Social
					$diflog = Set::classicExtract( $dsp, 'Detaildiflog.diflog' );
					if( empty( $propo_algo ) && !empty( $diflog ) ) {
						if( $diflog == '1006' ) {
							$propo_algo = 'Social';
						}
					}

					// Règle 3 (Prioritaire)  : Code XML instruction : « sitpersdemrsa ». "Quel est le motif de votre demande de rSa ?"
					// 0102 = Fin de droits AAH → Orientation vers le Social
					// 0105 = Attente de pension vieillesse ou invalidité‚ ou d'allocation handicap → Orientation vers le Social
					// 0109 = Fin d'études → Orientation vers le Pôle Emploi
					// 0101 = Fin de droits ASSEDIC → Orientation vers le Pôle Emploi
					$sitpersdemrsa = Set::extract( $dsp, 'Dsp.sitpersdemrsa' );
					if( empty( $propo_algo ) && !empty( $sitpersdemrsa ) ) {
						if( in_array( $sitpersdemrsa, array( '0102', '0105' ) ) ) {
							$propo_algo = 'Social';
						}
						else if( in_array( $sitpersdemrsa, array( '0109', '0101' ) ) ) {
							$propo_algo = 'Emploi';
						}
					}

					// Règle 4 : Code XML instruction : « DTNAI ». Date de Naissance.
					$dtnai = Set::extract( $element, 'Personne.dtnai' );
					/// FIXME: change chaque année ...
					$cessderact = Set::extract( $dsp, 'Dsp.cessderact' );

					// Si le code CESSDERACT n'est pas renseigné : Règle 5
					if( empty( $propo_algo ) && !empty( $cessderact ) ) {
						$age = age( $dtnai );

						// Si - de 57 a :
						// "2701" : Encore en activité ou cessation depuis moins d'un an ->Pôle Emploi
						// "2702" : Cessation d'activité depuis plus d'un an -> PDV
						if( $age < 57 ) {
							if( $cessderact == '2701' ) {
								$propo_algo = 'Emploi';
							}
							else if( $cessderact == '2702' ) {
								$propo_algo = 'Socioprofessionnelle';
							}
						}

						// Si + de 57 a :
						// "2701" : Encore en activité ou cessation depuis moins d'un an -> PDV
						// "2702" : Cessation d'activité depuis plus d'un an ->Service Social
						else if( $age >= 57 ) {
							if( $cessderact == '2701' ) {
								$propo_algo = 'Socioprofessionnelle';
							}
							else if( $cessderact == '2702' ) {
								$propo_algo = 'Social';
							}
						}
					}

					// Règle 5 : Code XML instruction : « HISPRO ». Question : Passé professionnel ?
					// 1901 = Oui → Orientation vers le Pôle Emploi
					// 1902 = Oui → Orientation vers le PDV
					// 1903 = Oui → Orientation vers le PDV
					// 1904 = Oui → Orientation vers le PDV
					$hispro = Set::extract( $dsp, 'Dsp.hispro' );
					if( empty( $propo_algo ) && !empty( $hispro ) ) {
						if( $hispro == '1901' ) {
							$propo_algo = 'Emploi';
						}
						else if( in_array( $hispro, array( '1902', '1903', '1904' ) ) ) {
							$propo_algo = 'Socioprofessionnelle';
						}
					}
				}
			}

			return $propo_algo;
		}

		/**
		*
		*/
		public function statistiques( $mesCodesInsee, $filtre_zone_geo, $criteres ) {
			$Personne = ClassRegistry::init( 'Personne' );
			$Option = ClassRegistry::init( 'Option' );

			$statuts = array( 'Orienté', 'Non orienté', 'En attente' );

			$return = array();

			$typeorient = $criteres['Filtre']['typeorient'];

			foreach( $statuts as $statut ) {
				if( $statut != 'Orienté' ) {
					$criteres['Filtre']['propo_algo'] = $typeorient;
					$criteres['Filtre']['typeorient'] = null;
				}
				else {
					$criteres['Filtre']['propo_algo'] = null;
					$criteres['Filtre']['typeorient'] = $typeorient;
				}

				$querydata = $this->recherche( $statut, $mesCodesInsee, $filtre_zone_geo, $criteres, array() );
				unset( $querydata['fields'] );
				$return[$statut] = $Personne->find( 'count', $querydata );
			}

			return  $return;
		}

		/**
		*
		*/
		public function sqPreorientationName( $allocataireIdAlias, $allocataireDtnaiAlias ) {
			return "(
				SELECT
						(
							CASE
								WHEN ( etat IS NOT NULL AND etat = 'inscription' ) THEN 'Emploi'
								WHEN ( natlog IS NOT NULL AND natlog IN ( '0904', '0911' ) ) THEN 'Social'
								WHEN ( diflog IS NOT NULL AND diflog = '1006' ) THEN 'Social'
								WHEN ( sitpersdemrsa IS NOT NULL AND sitpersdemrsa IN ( '0102', '0105' ) ) THEN 'Social'
								WHEN ( sitpersdemrsa IS NOT NULL AND sitpersdemrsa IN ( '0109', '0101' ) ) THEN 'Emploi'
								WHEN ( AGE( {$allocataireDtnaiAlias} ) < '57 years' AND cessderact IS NOT NULL AND cessderact = '2701' ) THEN 'Emploi'
								WHEN ( AGE( {$allocataireDtnaiAlias} ) < '57 years' AND cessderact IS NOT NULL AND cessderact = '2702' ) THEN 'Socioprofessionnelle'
								WHEN ( AGE( {$allocataireDtnaiAlias} ) >= '57 years' AND cessderact IS NOT NULL AND cessderact = '2701' ) THEN 'Socioprofessionnelle'
								WHEN ( AGE( {$allocataireDtnaiAlias} ) >= '57 years' AND cessderact IS NOT NULL AND cessderact = '2702' ) THEN 'Social'
								WHEN ( hispro IS NOT NULL AND hispro = '1901' ) THEN 'Emploi'
								WHEN ( hispro IS NOT NULL AND hispro IN ( '1902', '1903', '1904' ) ) THEN 'Socioprofessionnelle'
								ELSE NULL
							END
						) AS testpreconisation
					FROM (
						SELECT
									dsps.natlog,
									dsps.sitpersdemrsa,
									dsps.cessderact,
									dsps.hispro,
									detailsdiflogs.diflog,
									( SELECT historiqueetatspe.etat FROM historiqueetatspe WHERE historiqueetatspe.informationpe_id = informationspe.id ORDER BY historiqueetatspe.date DESC LIMIT 1 ) AS etat
								FROM personnes
									LEFT OUTER JOIN dsps ON ( dsps.personne_id = personnes.id )
									LEFT OUTER JOIN detailsdiflogs ON ( detailsdiflogs.dsp_id = dsps.id AND detailsdiflogs.diflog = '1006' )
									LEFT OUTER JOIN informationspe ON (
										(
											informationspe.nir IS NOT NULL
											AND SUBSTRING( informationspe.nir FROM 1 FOR 13 ) = SUBSTRING( TRIM( BOTH ' ' FROM personnes.nir ) FROM 1 FOR 13 )
											AND informationspe.dtnai = personnes.dtnai
										)
										OR (
											personnes.nom IS NOT NULL
											AND personnes.prenom IS NOT NULL
											AND personnes.dtnai IS NOT NULL
											AND TRIM( BOTH ' ' FROM personnes.nom ) <> ''
											AND TRIM( BOTH ' ' FROM personnes.prenom ) <> ''
											AND TRIM( BOTH ' ' FROM informationspe.nom ) = TRIM( BOTH ' ' FROM personnes.nom )
											AND TRIM( BOTH ' ' FROM informationspe.prenom ) = TRIM( BOTH ' ' FROM personnes.prenom )
											AND informationspe.dtnai = personnes.dtnai
										)
									)
								WHERE
									personnes.id = {$allocataireIdAlias}
								LIMIT 1
					) AS infospreconisation
			)";
		}

		/**
		* Retourne une sous-requête permettant de savoir si une préorientation peut être
		* calculée pour l'allocataire dont l'alias est passé en paramètre.
		*
		* @param string $allocataireIdAlias L'alias de l'allocataire (ex.: "Personne"."id")
		* @return string
		*/
		public function sqPreorientationAllocataireCalculable( $allocataireIdAlias ) {
			return "(
				SELECT
						(
							( etat IS NOT NULL AND etat = 'inscription' )
							OR ( natlog IS NOT NULL AND natlog IN ( '0904', '0911' ) )
							OR ( diflog IS NOT NULL AND diflog = '1006' )
							OR ( sitpersdemrsa IS NOT NULL AND sitpersdemrsa IN ( '0102', '0105', '0109', '0101' ) )
							OR ( cessderact IS NOT NULL AND cessderact IN ( '2701', '2702' ) )
							OR ( hispro IS NOT NULL AND hispro IN ( '1901', '1902', '1903', '1904' ) )
						) AS calculable
					FROM (
						SELECT
									dsps.natlog,
									dsps.sitpersdemrsa,
									dsps.cessderact,
									dsps.hispro,
									detailsdiflogs.diflog,
									( SELECT historiqueetatspe.etat FROM historiqueetatspe WHERE historiqueetatspe.informationpe_id = informationspe.id ORDER BY historiqueetatspe.date DESC LIMIT 1 ) AS etat
								FROM personnes
									LEFT OUTER JOIN dsps ON ( dsps.personne_id = personnes.id )
									LEFT OUTER JOIN detailsdiflogs ON ( detailsdiflogs.dsp_id = dsps.id AND detailsdiflogs.diflog = '1006' )
									LEFT OUTER JOIN informationspe ON (
										(
											informationspe.nir IS NOT NULL
											AND SUBSTRING( informationspe.nir FROM 1 FOR 13 ) = SUBSTRING( TRIM( BOTH ' ' FROM personnes.nir ) FROM 1 FOR 13 )
											AND informationspe.dtnai = personnes.dtnai
										)
										OR (
											personnes.nom IS NOT NULL
											AND personnes.prenom IS NOT NULL
											AND personnes.dtnai IS NOT NULL
											AND TRIM( BOTH ' ' FROM personnes.nom ) <> ''
											AND TRIM( BOTH ' ' FROM personnes.prenom ) <> ''
											AND TRIM( BOTH ' ' FROM informationspe.nom ) = TRIM( BOTH ' ' FROM personnes.nom )
											AND TRIM( BOTH ' ' FROM informationspe.prenom ) = TRIM( BOTH ' ' FROM personnes.prenom )
											AND informationspe.dtnai = personnes.dtnai
										)
									)
								WHERE
									personnes.id = {$allocataireIdAlias}
								LIMIT 1
					) AS infosalgorithme
			)";
		}

		/**
		* FIXME: remplacer la méthode search à terme
		*/
		public function recherche( $statutOrientation, $mesCodesInsee, $filtre_zone_geo, $criteres, $lockedDossiers ) {
				$conditions = array();

				/// Requête
				$Situationdossierrsa = ClassRegistry::init( 'Situationdossierrsa' );

				/// Conditions de base
				$conditions = array();

				if( in_array( $statutOrientation, array( 'Calculables', 'Non calculables' ) ) ) {
					$enattente = Set::classicExtract( $criteres, 'Filtre.enattente' );
					if( empty( $enattente ) ) {
						$enattente = array( 'En attente', 'Non orienté' );
					}
					$conditions['Orientstruct.statut_orient'] = $enattente;
				}

				if( $statutOrientation == 'Calculables' ) {
					$conditions[] = $this->sqPreorientationAllocataireCalculable( '"Personne"."id"' );
				}
				else if( $statutOrientation == 'Non calculables' ) {
					$conditions[] = $this->sqPreorientationAllocataireCalculable( '"Personne"."id"' ).' <> TRUE';
				}
				else {
					$conditions[] = 'Orientstruct.statut_orient = \''.Sanitize::clean( $statutOrientation ).'\'';
				}

				if( $statutOrientation == 'Orienté' ) {
					// INFO: nouvelle manière de générer les PDFs
					$conditions[] = 'Orientstruct.id IN ( SELECT pdfs.fk_value FROM pdfs WHERE modele = \'Orientstruct\' )';
				}
				else {
					if( in_array( $statutOrientation, array( 'Calculables', 'Non calculables' ) ) ) {
						$toppersdrodevorsa = Set::classicExtract( $criteres, 'Filtre.toppersdrodevorsa' );
						if( $toppersdrodevorsa != '' ) {
							if( $toppersdrodevorsa == 'NULL' ) {
								$conditions[] = 'Calculdroitrsa.toppersdrodevorsa IS NULL';
							}
							else {
								$conditions['Calculdroitrsa.toppersdrodevorsa'] = $toppersdrodevorsa;
							}
						}
					}
					else {
						$toppersdrodevorsa = Set::classicExtract( $criteres, 'Filtre.toppersdrodevorsa' );
						if( isset( $toppersdrodevorsa ) && !empty( $toppersdrodevorsa ) ) {
							$conditions['Calculdroitrsa.toppersdrodevorsa'] = $toppersdrodevorsa;
						}
						// INFO: ne faire apparaître les personnes à orienter que si la personne est soumise à droits et devoirs
// 						$conditions[] = 'Calculdroitrsa.toppersdrodevorsa = \'1\'';

					}

					// INFO: ne faire apparaître les personnes à orienter que si le dossier se trouve dans un état ouvert
					$etatdossier = Set::extract( $criteres, 'Situationdossierrsa.etatdosrsa' );
					if( isset( $criteres['Situationdossierrsa']['etatdosrsa'] ) && !empty( $criteres['Situationdossierrsa']['etatdosrsa'] ) ) {
						$conditions[] = '( Situationdossierrsa.etatdosrsa IN ( \''.implode( '\', \'', $etatdossier ).'\' ) )';
					}
					else {
						$conditions[] = '( Situationdossierrsa.etatdosrsa IN ( \''.implode( '\', \'', $Situationdossierrsa->etatOuvert() ).'\' ) )';
					}


					// INFO: ne faire apparaître les personnes à orienter que si le dossier se trouve en RSA Socle
					$natpf = Set::extract( $criteres, 'Detailcalculdroitrsa.natpf' );
					$natpfSocle = Configure::read( 'Detailcalculdroitrsa.natpf.socle' );
					if( isset( $criteres['Detailcalculdroitrsa']['natpf'] ) && !empty( $criteres['Detailcalculdroitrsa']['natpf'] ) ) {
						$conditions[] = '( Detailcalculdroitrsa.natpf IN ( \''.implode( '\', \'', $natpf ).'\' ) )';
					}
					else {
						$conditions[] = '( Detailcalculdroitrsa.natpf IN ( \''.implode( '\', \'', $natpfSocle ).'\' ) )';
					}

				}

				/// Filtre zone géographique
				$conditions[] = $this->conditionsZonesGeographiques( $filtre_zone_geo, $mesCodesInsee );

				/// Dossiers lockés
				if( !empty( $lockedDossiers ) ) {
					if( is_array( $lockedDossiers ) ) {
						$lockedDossiers = implode( ', ', $lockedDossiers );
					}
					$conditions[] = "Dossier.id NOT IN ( {$lockedDossiers} )";
				}

				/// Critères
				$oridemrsa = Set::extract( $criteres, 'Filtre.oridemrsa' );
				$locaadr = Set::extract( $criteres, 'Filtre.locaadr' );
				$numcomptt = Set::extract( $criteres, 'Filtre.numcomptt' );
				$codepos = Set::extract( $criteres, 'Filtre.codepos' );
				$dtdemrsa = Set::extract( $criteres, 'Filtre.dtdemrsa' );
				$date_impression = Set::extract( $criteres, 'Filtre.date_impression' );
				$date_print = Set::extract( $criteres, 'Filtre.date_print' );
				$date_valid = Set::extract( $criteres, 'Filtre.date_valid' );
				if ( isset( $criteres['Filtre']['typeorient'] ) && !empty( $criteres['Filtre']['typeorient'] ) ) {
					$typeorient = Set::extract( $criteres, 'Filtre.typeorient' );
				}
				elseif ( isset( $criteres['Filtre']['propo_algo'] ) && !empty( $criteres['Filtre']['propo_algo'] ) ) {
					$preorient = Set::extract( $criteres, 'Filtre.propo_algo' );
				}

				$hasDsp = Set::extract( $criteres, 'Filtre.hasDsp' );
				//-------------------------------------------------------
				$cantons = Set::extract( $criteres, 'Filtre.cantons' );

				/// FIXME: dans le modèle
				if( isset( $typeorient ) && !empty( $typeorient ) ) {
					if( Configure::read( 'with_parentid' ) ) {//FIXME: subquery
						$conditions[] = 'Orientstruct.typeorient_id IN ( SELECT typesorients.id FROM typesorients WHERE typesorients.parentid = \''.Sanitize::clean( $typeorient ).'\' )';
					}
					else {
						$conditions[] = 'Orientstruct.typeorient_id = \''.Sanitize::clean( $typeorient ).'\'';
					}
				}
				elseif( isset( $preorient ) && !empty( $preorient ) ) {
// 					if( in_array( $statutOrientation, array( 'Non orienté', 'En attente', 'Calculables', 'Non calculables' ) ) ) {
// 						$sqPreorientationName = $this->sqPreorientationName( '"Personne"."id"', '"Personne"."dtnai"' );
// 						if( $preorient == 'NULL' ) {
// 							$conditions[] = "( {$sqPreorientationName} ) IS NULL";
// 						}
// 						else {
// 							$typesorients = ClassRegistry::init( 'Typeorient' )->listOptions();
// 							$conditions[] = "( {$sqPreorientationName} ) = '".$typesorients[Sanitize::clean( $preorient )]."'";
// 						}
// 					}
// 					else {
						if ( $preorient == 'NULL' ) {
							$conditions[] = 'Orientstruct.propo_algo IS NULL';
						}
						else if ( $preorient == 'NOTNULL' ) {
							$conditions[] = 'Orientstruct.propo_algo IS NOT NULL';
						}
						else {
							$conditions[] = 'Orientstruct.propo_algo = \''.Sanitize::clean( $preorient ).'\'';
						}
// 					}
				}
				//-------------------------------------------------------

				if( isset( $criteres['Filtre']['origine'] ) && !empty( $criteres['Filtre']['origine'] ) ) {
					$conditions[] = 'Orientstruct.origine = \''.Sanitize::clean( $criteres['Filtre']['origine'] ).'\'';
				}

				// Origine de la demande
				if( !empty( $oridemrsa ) ) {
					$conditions[] = 'Detaildroitrsa.oridemrsa IN ( \''.implode( '\', \'', $oridemrsa ).'\' )';
				}

				// Critères sur une personne du foyer - nom, prénom, nom de jeune fille -> FIXME: seulement demandeur pour l'instant
				$filtersPersonne = array();
				foreach( array( 'nom', 'prenom', 'nomnai' ) as $criterePersonne ) {
					if( isset( $criteres['Filtre'][$criterePersonne] ) && !empty( $criteres['Filtre'][$criterePersonne] ) ) {
						$conditions[] = 'Personne.'.$criterePersonne.' ILIKE \''.$this->wildcard( replace_accents( $criteres['Filtre'][$criterePersonne] ) ).'\'';
					}
				}

				// Localité adresse
				if( !empty( $locaadr ) ) {
					$conditions[] = 'Adresse.locaadr ILIKE \'%'.Sanitize::clean( $locaadr ).'%\'';
				}
				// Commune au sens INSEE
				if( !empty( $numcomptt ) ) {
					$conditions[] = 'Adresse.numcomptt = \''.Sanitize::clean( $numcomptt ).'\'';
				}
				// Code postal adresse
				if( !empty( $codepos ) ) {
					$conditions[] = 'Adresse.codepos = \''.Sanitize::clean( $codepos ).'\'';
				}

				/// Critères sur l'adresse - canton
				if( Configure::read( 'CG.cantons' ) ) {
					if( isset( $criteres['Canton']['canton'] ) && !empty( $criteres['Canton']['canton'] ) ) {
						$this->Canton = ClassRegistry::init( 'Canton' );
						$tmpConditions = $this->Canton->queryConditions( $criteres['Canton']['canton'] );
						$_conditions = array();
						foreach( $tmpConditions['or'] as $tmpCondition ) {
							$_condition = array();
							foreach( $tmpCondition as $field => $value ) {
								if( valid_int( $value ) ) {
									$_condition[] = "$field = '".str_replace( "'", "\\'", $value )."'";
								}
								else {
									$_condition[] = "$field '".str_replace( "'", "\\'", $value )."'";
								}
							}
							if( !empty( $_condition ) ) {
								$_conditions[] = '( '.implode( ') AND (', $_condition ).' )';
							}
						}
						if( !empty( $_conditions ) ) {
							$conditions[] = '( ( '.implode( ') OR (', $_conditions ).' ) )';
						}
					}
				}

				// Date de demande
				if( !empty( $dtdemrsa ) && $dtdemrsa != 0 ) {
					$dtdemrsa_from = Set::extract( $criteres, 'Filtre.dtdemrsa_from' );
					$dtdemrsa_to = Set::extract( $criteres, 'Filtre.dtdemrsa_to' );
					// FIXME: vérifier le bon formatage des dates
					$dtdemrsa_from = $dtdemrsa_from['year'].'-'.$dtdemrsa_from['month'].'-'.$dtdemrsa_from['day'];
					$dtdemrsa_to = $dtdemrsa_to['year'].'-'.$dtdemrsa_to['month'].'-'.$dtdemrsa_to['day'];

					$conditions[] = 'Dossier.dtdemrsa BETWEEN \''.$dtdemrsa_from.'\' AND \''.$dtdemrsa_to.'\'';
				}

				// Statut impression
				if( !empty( $date_impression ) && in_array( $date_impression, array( 'I', 'N' ) ) ) {
					if( $date_impression == 'I' ) {
						$conditions[] = 'Orientstruct.date_impression IS NOT NULL';
					}
					else {
						$conditions[] = 'Orientstruct.date_impression IS NULL';
					}
				}

				// Date d'orientation
				if( !empty( $date_valid ) && $date_valid != 0 ) {
					$date_valid_from = Set::extract( $criteres, 'Filtre.date_valid_from' );
					$date_valid_to = Set::extract( $criteres, 'Filtre.date_valid_to' );
					// FIXME: vérifier le bon formatage des dates
					$date_valid_from = $date_valid_from['year'].'-'.$date_valid_from['month'].'-'.$date_valid_from['day'];
					$date_valid_to = $date_valid_to['year'].'-'.$date_valid_to['month'].'-'.$date_valid_to['day'];

					$conditions[] = 'Orientstruct.date_valid BETWEEN \''.$date_valid_from.'\' AND \''.$date_valid_to.'\'';
				}

				// Date d'impression
				if( !empty( $date_print ) && $date_print != 0 ) {
					$date_impression_from = Set::extract( $criteres, 'Filtre.date_impression_from' );
					$date_impression_to = Set::extract( $criteres, 'Filtre.date_impression_to' );
					// FIXME: vérifier le bon formatage des dates
					$date_impression_from = $date_impression_from['year'].'-'.$date_impression_from['month'].'-'.$date_impression_from['day'];
					$date_impression_to = $date_impression_to['year'].'-'.$date_impression_to['month'].'-'.$date_impression_to['day'];

					$conditions[] = 'Orientstruct.date_impression BETWEEN \''.$date_impression_from.'\' AND \''.$date_impression_to.'\'';
				}

				// Trouver la dernière demande RSA pour chacune des personnes du jeu de résultats
				if( @$criteres['Dossier']['dernier'] ) {
					$conditions[] = 'Dossier.id IN (
						SELECT
								dossiers.id
							FROM personnes
								INNER JOIN prestations ON (
									personnes.id = prestations.personne_id
									AND prestations.natprest = \'RSA\'
								)
								INNER JOIN foyers ON (
									personnes.foyer_id = foyers.id
								)
								INNER JOIN dossiers ON (
									dossiers.id = foyers.dossier_id
								)
							WHERE
								prestations.rolepers IN ( \'DEM\', \'CJT\' )
								AND (
									(
										nir_correct13( Personne.nir )
										AND nir_correct13( personnes.nir )
										AND SUBSTRING( TRIM( BOTH \' \' FROM personnes.nir ) FROM 1 FOR 13 ) = SUBSTRING( TRIM( BOTH \' \' FROM Personne.nir ) FROM 1 FOR 13 )
										AND personnes.dtnai = Personne.dtnai
									)
									OR
									(
										UPPER(personnes.nom) = UPPER(Personne.nom)
										AND UPPER(personnes.prenom) = UPPER(Personne.prenom)
										AND personnes.dtnai = Personne.dtnai
									)
								)
							ORDER BY dossiers.dtdemrsa DESC
							LIMIT 1
					)';
				}




				// INFO: on veut récupérer tout ce qui est orienté (LEFT OUTER) et ne garder que ce qui a du sens pour l'orientation (INNER)
				$joinType = ( ( $statutOrientation == 'Orienté' ) ? 'LEFT OUTER' : 'INNER' );

				// INFO: n'apparaît plus que dans l'export CSV
				$sqDernierContrat = ClassRegistry::init( 'Contratinsertion' )->sq(
					array(
						'alias' => 'contratsinsertion',
						'fields' => array( 'contratsinsertion.dd_ci' ),
						'contain' => false,
						'conditions' => array( 'contratsinsertion.personne_id = Personne.id' ),
						'order' => array( 'contratsinsertion.dd_ci DESC' ),
						'limit' => 1
					)
				);


				// Présence de DSPs

				if( !empty( $hasDsp ) && in_array( $hasDsp, array( 'O', 'N' ) ) ) {
					if( $hasDsp == 'O' ) {
						$conditions[] = 'Dsp.id IS NOT NULL';
					}
					else {
						$conditions[] = 'Dsp.id IS NULL';
					}
				}

				$fields = array(
					'Dossier.id',
					'Dossier.numdemrsa',
					'Dossier.dtdemrsa',
					'Dossier.matricule',
					'( CASE WHEN dtdemrsa >= \'2009-06-01 00:00:00\' THEN \'Nouvelle demande\' ELSE \'Diminution des ressources\' END ) AS "Dossier__statut"',
					'Personne.id',
					'Personne.nom',
					'Personne.prenom',
					'Personne.nir',
					'Personne.dtnai',
					'Foyer.dossier_id',
					'Dsp.id',
					'Adresse.locaadr',
					'Adresse.codepos',
					'Adresse.canton',
					'Adresse.numcomptt',
					'Situationdossierrsa.dtclorsa',
					'Situationdossierrsa.moticlorsa',
					'Suiviinstruction.typeserins',
					'Orientstruct.id',
					'Orientstruct.date_valid',
					'Orientstruct.propo_algo',
					'Orientstruct.date_propo',
					'Orientstruct.typeorient_id',
					'Orientstruct.structurereferente_id',
					'Orientstruct.origine',
					'Typeorient.lib_type_orient',
					'Structurereferente.lib_struc',
					'Orientstruct.statut_orient',
// 					'Contratinsertion.dd_ci',
					'( '.$sqDernierContrat.' ) AS "Contratinsertion__dd_ci"',
					'"Prestation"."rolepers"',
					'"Situationdossierrsa"."etatdosrsa"',
				);

// 				if( in_array( $statutOrientation, array( 'Non orienté', 'En attente', 'Calculables', 'Non calculables' ) ) ) {
// 					$fields[] = $this->sqPreorientationName( '"Personne"."id"', '"Personne"."dtnai"' ).' AS "Preorientation__name"';
// 				}

				$queryData = array(
					'fields' => $fields,
					'joins' => array(
						array(
							'table' => 'prestations',
							'alias' => 'Prestation',
							'type' => $joinType,
							'foreignKey' => false,
							'conditions' => array(
								'Personne.id = Prestation.personne_id',
								'Prestation.natprest' => 'RSA',
								'Prestation.rolepers' => array( 'DEM', 'CJT' ),
							)
						),
						array(
							'table' => 'calculsdroitsrsa',
							'alias' => 'Calculdroitrsa',
							'type' => $joinType,
							'foreignKey' => false,
							'conditions' => array( 'Personne.id = Calculdroitrsa.personne_id' )
						),
						array(
							'table' => 'dsps',
							'alias' => 'Dsp',
							'type' => 'LEFT OUTER',
							'foreignKey' => false,
							'conditions' => array(
								'Personne.id = Dsp.personne_id',
								'Dsp.id IN ('
									.ClassRegistry::init( 'Dsp' )->sqDerniereDsp()
								.')'
							)
						),
						array(
							'table' => 'foyers',
							'alias' => 'Foyer',
							'type' => 'INNER',
							'foreignKey' => false,
							'conditions' => array( 'Personne.foyer_id = Foyer.id' )
						),
						array(
							'table' => 'dossiers',
							'alias' => 'Dossier',
							'type' => 'INNER',
							'foreignKey' => false,
							'conditions' => array( 'Dossier.id = Foyer.dossier_id' )
						),
						array(
							'table' => 'suivisinstruction',
							'alias' => 'Suiviinstruction',
							'type' => 'LEFT OUTER',
							'foreignKey' => false,
							'conditions' => array(
								'Dossier.id = Suiviinstruction.dossier_id',
								'Suiviinstruction.id IN (
									SELECT suivisinstruction.id
										FROM suivisinstruction
										WHERE suivisinstruction.dossier_id = Suiviinstruction.dossier_id
										ORDER BY suivisinstruction.id DESC
										LIMIT 1
								)'
							)
						),
						array(
							'table' => 'adressesfoyers',
							'alias' => 'Adressefoyer',
							'type' => $joinType,
							'foreignKey' => false,
							'conditions' => array(
								'Adressefoyer.foyer_id = Foyer.id',
								'Adressefoyer.rgadr' => '01',
								'Adressefoyer.id IN (
									'.ClassRegistry::init( 'Adressefoyer' )->sqDerniereRgadr01( 'Adressefoyer.foyer_id' ).'
								)'
							),
						),
						array(
							'table' => 'adresses',
							'alias' => 'Adresse',
							'type' => $joinType,
							'foreignKey' => false,
							'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
						),
						array(
							'table' => 'orientsstructs',
							'alias' => 'Orientstruct',
							'type' => 'INNER',
							'foreignKey' => false,
							'conditions' => array( 'Orientstruct.personne_id = Personne.id', )
						),
						array(
							'table' => 'typesorients',
							'alias' => 'Typeorient',
							'type' => 'LEFT OUTER', // FIXME: compléter une variable joins
							'foreignKey' => false,
							'conditions' => array( 'Typeorient.id = Orientstruct.typeorient_id' )
						),
						array(
							'table' => 'structuresreferentes',
							'alias' => 'Structurereferente',
							'type' => 'LEFT OUTER', // FIXME: compléter une variable joins
							'foreignKey' => false,
							'conditions' => array( 'Structurereferente.id = Orientstruct.structurereferente_id' )
						),
						/*array(
							'table' => 'contratsinsertion',
							'alias' => 'Contratinsertion',
							'type' => 'LEFT OUTER',
							'foreignKey' => false,
							'conditions' => array(
								'Contratinsertion.personne_id = Personne.id',
								'Contratinsertion.id IN (
									'.ClassRegistry::init( 'Contratinsertion' )->sqDernierContrat().'
								)'
							)
						),*/
						array(
							'table' => 'detailsdroitsrsa',
							'alias' => 'Detaildroitrsa',
							'type' => $joinType,
							'foreignKey' => false,
							'conditions' => array( 'Detaildroitrsa.dossier_id = Dossier.id', )
						),
						array(
							'table' => 'detailscalculsdroitsrsa',
							'alias' => 'Detailcalculdroitrsa',
							'type' => $joinType,
							'foreignKey' => false,
							'conditions' => array( 'Detailcalculdroitrsa.detaildroitrsa_id = Detaildroitrsa.id', )
						),
						array(
							'table' => 'situationsdossiersrsa',
							'alias' => 'Situationdossierrsa',
							'type' => $joinType,
							'foreignKey' => false,
							'conditions' => array( 'Situationdossierrsa.dossier_id = Dossier.id', )
						),
					),
					'conditions' => $conditions,
					'contain' => false,
					'recursive' => -1,
				);

			return $queryData;
		}

		/**
		*
		*/

		function search( $statutOrientation, $mesCodesInsee, $filtre_zone_geo, $criteres, $lockedDossiers, $limit = PHP_INT_MAX ) {
			/// Requête
			$Situationdossierrsa = ClassRegistry::init( 'Situationdossierrsa' );

			/// Conditions de base
			$conditions = array(
				'orientsstructs.statut_orient = \''.Sanitize::clean( $statutOrientation ).'\''
			);

			if( $statutOrientation == 'Orienté' ) {
				// INFO: nouvelle manière de générer les PDFs
				$conditions[] = 'orientsstructs.id IN ( SELECT pdfs.fk_value FROM pdfs WHERE modele = \'Orientstruct\' )';
			}
			else {
				// INFO: ne faire apparaître les personnes à orienter que si la personne est soumise à droits et devoirs
				$conditions[] = 'calculsdroitsrsa.toppersdrodevorsa = \'1\'';
				// INFO: ne faire apparaître les personnes à orienter que si le dossier se trouve dans un état ouvert
				$conditions[] = '( situationsdossiersrsa.etatdosrsa IN ( \''.implode( '\', \'', $Situationdossierrsa->etatOuvert() ).'\' ) )';
			}

			/// Filtre zone géographique
			if( $filtre_zone_geo ) {
				$mesCodesInsee = ( !empty( $mesCodesInsee ) ? $mesCodesInsee : '0' );
				$conditions[] = 'Adresse.numcomptt IN ( \''.implode( '\', \'', $mesCodesInsee ).'\' )';
			}

			/// Dossiers lockés
			if( !empty( $lockedDossiers ) ) {
				$conditions[] = 'dossiers.id NOT IN ( '.implode( ', ', $lockedDossiers ).' )';
			}

			/// Critères
			$oridemrsa = Set::extract( $criteres, 'Filtre.oridemrsa' );
			$locaadr = Set::extract( $criteres, 'Filtre.locaadr' );
			$numcomptt = Set::extract( $criteres, 'Filtre.numcomptt' );
			$codepos = Set::extract( $criteres, 'Filtre.codepos' );
			$dtdemrsa = Set::extract( $criteres, 'Filtre.dtdemrsa' );
			$date_impression = Set::extract( $criteres, 'Filtre.date_impression' );
			$date_print = Set::extract( $criteres, 'Filtre.date_print' );
			$modeles = Set::extract( $criteres, 'Filtre.typeorient' );
			//-------------------------------------------------------
			$cantons = Set::extract( $criteres, 'Filtre.cantons' );

			/// FIXME: dans le modèle
			$typeorient = Set::classicExtract( $criteres, 'Filtre.typeorient' );
			if( !empty( $typeorient ) ) {
				if( Configure::read( 'with_parentid' ) ) {
					$conditions[] = 'orientsstructs.typeorient_id IN ( SELECT typesorients.id FROM typesorients WHERE typesorients.parentid = \''.Sanitize::clean( $typeorient ).'\' )';
				}
				else {
					$conditions[] = 'orientsstructs.typeorient_id = \''.Sanitize::clean( $typeorient ).'\'';
				}
			}

			// Origine de la demande
			if( !empty( $oridemrsa ) ) {
				$conditions[] = 'detailsdroitsrsa.oridemrsa IN ( \''.implode( '\', \'', $oridemrsa ).'\' )';
			}

			// Critères sur une personne du foyer - nom, prénom, nom de jeune fille -> FIXME: seulement demandeur pour l'instant
			$filtersPersonne = array();
			foreach( array( 'nom', 'prenom', 'nomnai' ) as $criterePersonne ) {
				if( isset( $criteres['Filtre'][$criterePersonne] ) && !empty( $criteres['Filtre'][$criterePersonne] ) ) {
					$conditions[] = 'personnes.'.$criterePersonne.' ILIKE \''.$this->wildcard( replace_accents( $criteres['Filtre'][$criterePersonne] ) ).'\'';
				}
			}

			// Localité adresse
			if( !empty( $locaadr ) ) {
				$conditions[] = 'Adresse.locaadr ILIKE \'%'.Sanitize::clean( $locaadr ).'%\'';
			}
			// Commune au sens INSEE
			if( !empty( $numcomptt ) ) {
				$conditions[] = 'Adresse.numcomptt = \''.Sanitize::clean( $numcomptt ).'\'';
			}
			// Code postal adresse
			if( !empty( $codepos ) ) {
				$conditions[] = 'Adresse.codepos = \''.Sanitize::clean( $codepos ).'\'';
			}

			/// Critères sur l'adresse - canton
			if( Configure::read( 'CG.cantons' ) ) {
				if( isset( $criteres['Canton']['canton'] ) && !empty( $criteres['Canton']['canton'] ) ) {
					$this->Canton = ClassRegistry::init( 'Canton' );
					$tmpConditions = $this->Canton->queryConditions( $criteres['Canton']['canton'] );
					$_conditions = array();
					foreach( $tmpConditions['or'] as $tmpCondition ) {
						$_condition = array();
						foreach( $tmpCondition as $field => $value ) {
							if( valid_int( $value ) ) {
								$_condition[] = "$field = '".str_replace( "'", "\\'", $value )."'";
							}
							else {
								$_condition[] = "$field '".str_replace( "'", "\\'", $value )."'";
							}
						}
						if( !empty( $_condition ) ) {
							$_conditions[] = '( '.implode( ') AND (', $_condition ).' )';
						}
					}
					if( !empty( $_conditions ) ) {
						$conditions[] = '( ( '.implode( ') OR (', $_conditions ).' ) )';
					}
				}
			}

			// Date de demande
			if( !empty( $dtdemrsa ) && $dtdemrsa != 0 ) {
				$dtdemrsa_from = Set::extract( $criteres, 'Filtre.dtdemrsa_from' );
				$dtdemrsa_to = Set::extract( $criteres, 'Filtre.dtdemrsa_to' );
				// FIXME: vérifier le bon formatage des dates
				$dtdemrsa_from = $dtdemrsa_from['year'].'-'.$dtdemrsa_from['month'].'-'.$dtdemrsa_from['day'];
				$dtdemrsa_to = $dtdemrsa_to['year'].'-'.$dtdemrsa_to['month'].'-'.$dtdemrsa_to['day'];

				$conditions[] = 'dossiers.dtdemrsa BETWEEN \''.$dtdemrsa_from.'\' AND \''.$dtdemrsa_to.'\'';
			}

			// Statut impression
			if( !empty( $date_impression ) && in_array( $date_impression, array( 'I', 'N' ) ) ) {
				if( $date_impression == 'I' ) {
					$conditions[] = 'orientsstructs.date_impression IS NOT NULL';
				}
				else {
					$conditions[] = 'orientsstructs.date_impression IS NULL';
				}
			}

			// Date d'impression
			if( !empty( $date_print ) && $date_print != 0 ) {
				$date_impression_from = Set::extract( $criteres, 'Filtre.date_impression_from' );
				$date_impression_to = Set::extract( $criteres, 'Filtre.date_impression_to' );
				// FIXME: vérifier le bon formatage des dates
				$date_impression_from = $date_impression_from['year'].'-'.$date_impression_from['month'].'-'.$date_impression_from['day'];
				$date_impression_to = $date_impression_to['year'].'-'.$date_impression_to['month'].'-'.$date_impression_to['day'];

				$conditions[] = 'orientsstructs.date_impression BETWEEN \''.$date_impression_from.'\' AND \''.$date_impression_to.'\'';
			}

			$this->Dossier = ClassRegistry::init( 'Dossier' );
			$sql = 'SELECT orientsstructs.id
					FROM personnes
						INNER JOIN prestations ON ( prestations.personne_id = personnes.id AND prestations.natprest = \'RSA\' AND ( prestations.rolepers = \'DEM\' OR prestations.rolepers = \'CJT\' ) )
						INNER JOIN calculsdroitsrsa ON ( calculsdroitsrsa.personne_id = personnes.id )
						'.( ( $statutOrientation == 'Non orienté' ) ? 'INNER JOIN  dsps ON ( dsps.personne_id = personnes.id )' : '' ).'
						INNER JOIN foyers ON ( personnes.foyer_id = foyers.id )
						INNER JOIN dossiers ON ( foyers.dossier_id = dossiers.id )
						INNER JOIN adressesfoyers ON (
							adressesfoyers.foyer_id = foyers.id
							AND adressesfoyers.rgadr = \'01\'
							AND adressesfoyers.id IN (
								'.ClassRegistry::init( 'Adressefoyer' )->sqDerniereRgadr01( 'foyers.id' ).'
							)
						)
						INNER JOIN adresses as Adresse ON ( adressesfoyers.adresse_id = Adresse.id)
						INNER JOIN orientsstructs ON ( orientsstructs.personne_id = personnes.id )
						INNER JOIN detailsdroitsrsa ON ( detailsdroitsrsa.dossier_id = dossiers.id )
						INNER JOIN situationsdossiersrsa ON ( situationsdossiersrsa.dossier_id = dossiers.id )
					WHERE '.implode( ' AND ', $conditions ).'
					LIMIT '.$limit;

			$cohorte = $this->Dossier->query( $sql );

			return Set::extract( $cohorte, '{n}.0.id' );
		}

		/**
		*
		*/

		/* public function search2( $statutOrientation, $mesCodesInsee, $filtre_zone_geo, $criteres, $lockedSubquery = null, $limit = PHP_INT_MAX ) {
			/// Requête
			$Situationdossierrsa = ClassRegistry::init( 'Situationdossierrsa' );

			/// Conditions de base
			$conditions = array(
				'Orientstruct.statut_orient = \''.Sanitize::clean( $statutOrientation ).'\''
			);

			/// Sous_condition sur les jetons / les dossiers lockés ?
			if( !empty( $lockedSubquery ) ) {
				$dbo = $this->getDataSource( $this->useDbConfig );
				$conditions[] = $dbo->expression( "{$dbo->startQuote}Dossier{$dbo->endQuote}.{$dbo->startQuote}id{$dbo->endQuote} NOT IN ( {$lockedSubquery} )" );
			}

			if( $statutOrientation == 'Orienté' ) {
				// INFO: nouvelle manière de générer les PDFs
				$conditions[] = 'Orientstruct.id IN ( SELECT pdfs.fk_value FROM pdfs WHERE modele = \'Orientstruct\' )';
			}
			else {
				// INFO: ne faire apparaître les personnes à orienter que si la personne est soumise à droits et devoirs
				$conditions[] = 'Calculdroitrsa.toppersdrodevorsa = \'1\'';
				// INFO: ne faire apparaître les personnes à orienter que si le dossier se trouve dans un état ouvert
				$conditions[] = '( Situationdossierrsa.etatdosrsa IN ( \''.implode( '\', \'', $Situationdossierrsa->etatOuvert() ).'\' ) )';
			}

			/// Filtre zone géographique
			if( $filtre_zone_geo ) {
				$mesCodesInsee = ( !empty( $mesCodesInsee ) ? $mesCodesInsee : '0' );
				$conditions[] = 'Adresse.numcomptt IN ( \''.implode( '\', \'', $mesCodesInsee ).'\' )';
			}

			/// Critères
			$oridemrsa = Set::extract( $criteres, 'Filtre.oridemrsa' );
			$locaadr = Set::extract( $criteres, 'Filtre.locaadr' );
			$numcomptt = Set::extract( $criteres, 'Filtre.numcomptt' );
			$codepos = Set::extract( $criteres, 'Filtre.codepos' );
			$dtdemrsa = Set::extract( $criteres, 'Filtre.dtdemrsa' );
			$date_impression = Set::extract( $criteres, 'Filtre.date_impression' );
			$date_print = Set::extract( $criteres, 'Filtre.date_print' );
			$modeles = Set::extract( $criteres, 'Filtre.typeorient' );
			//-------------------------------------------------------
			$cantons = Set::extract( $criteres, 'Filtre.cantons' );

			/// FIXME: dans le modèle
			$typeorient = Set::classicExtract( $criteres, 'Filtre.typeorient' );
			if( !empty( $typeorient ) ) {
				if( Configure::read( 'with_parentid' ) ) {
					$conditions[] = 'Orientstruct.typeorient_id IN ( SELECT typesorients.id FROM typesorients WHERE typesorients.parentid = \''.Sanitize::clean( $typeorient ).'\' )';
				}
				else {
					$conditions[] = 'Orientstruct.typeorient_id = \''.Sanitize::clean( $typeorient ).'\'';
				}
			}

			// Origine de la demande
			if( !empty( $oridemrsa ) ) {
				$conditions[] = 'Detaildroitrsa.oridemrsa IN ( \''.implode( '\', \'', $oridemrsa ).'\' )';
			}

			// Critères sur une personne du foyer - nom, prénom, nom de jeune fille -> FIXME: seulement demandeur pour l'instant
			$filtersPersonne = array();
			foreach( array( 'nom', 'prenom', 'nomnai' ) as $criterePersonne ) {
				if( isset( $criteres['Filtre'][$criterePersonne] ) && !empty( $criteres['Filtre'][$criterePersonne] ) ) {
					$conditions[] = 'Personne.'.$criterePersonne.' ILIKE \''.$this->wildcard( replace_accents( $criteres['Filtre'][$criterePersonne] ) ).'\'';
				}
			}

			// Localité adresse
			if( !empty( $locaadr ) ) {
				$conditions[] = 'Adresse.locaadr ILIKE \'%'.Sanitize::clean( $locaadr ).'%\'';
			}
			// Commune au sens INSEE
			if( !empty( $numcomptt ) ) {
				$conditions[] = 'Adresse.numcomptt = \''.Sanitize::clean( $numcomptt ).'\'';
			}
			// Code postal adresse
			if( !empty( $codepos ) ) {
				$conditions[] = 'Adresse.codepos = \''.Sanitize::clean( $codepos ).'\'';
			}

			/// Critères sur l'adresse - canton
			if( Configure::read( 'CG.cantons' ) ) {
				if( isset( $criteres['Canton']['canton'] ) && !empty( $criteres['Canton']['canton'] ) ) {
					$this->Canton = ClassRegistry::init( 'Canton' );
					$tmpConditions = $this->Canton->queryConditions( $criteres['Canton']['canton'] );
					$_conditions = array();
					foreach( $tmpConditions['or'] as $tmpCondition ) {
						$_condition = array();
						foreach( $tmpCondition as $field => $value ) {
							if( valid_int( $value ) ) {
								$_condition[] = "$field = '".str_replace( "'", "\\'", $value )."'";
							}
							else {
								$_condition[] = "$field '".str_replace( "'", "\\'", $value )."'";
							}
						}
						if( !empty( $_condition ) ) {
							$_conditions[] = '( '.implode( ') AND (', $_condition ).' )';
						}
					}
					if( !empty( $_conditions ) ) {
						$conditions[] = '( ( '.implode( ') OR (', $_conditions ).' ) )';
					}
				}
			}

			// Date de demande
			if( !empty( $dtdemrsa ) && $dtdemrsa != 0 ) {
				$dtdemrsa_from = Set::extract( $criteres, 'Filtre.dtdemrsa_from' );
				$dtdemrsa_to = Set::extract( $criteres, 'Filtre.dtdemrsa_to' );
				// FIXME: vérifier le bon formatage des dates
				$dtdemrsa_from = $dtdemrsa_from['year'].'-'.$dtdemrsa_from['month'].'-'.$dtdemrsa_from['day'];
				$dtdemrsa_to = $dtdemrsa_to['year'].'-'.$dtdemrsa_to['month'].'-'.$dtdemrsa_to['day'];

				$conditions[] = 'Dossier.dtdemrsa BETWEEN \''.$dtdemrsa_from.'\' AND \''.$dtdemrsa_to.'\'';
			}

			// Statut impression
			if( !empty( $date_impression ) && in_array( $date_impression, array( 'I', 'N' ) ) ) {
				if( $date_impression == 'I' ) {
					$conditions[] = 'Orientstruct.date_impression IS NOT NULL';
				}
				else {
					$conditions[] = 'Orientstruct.date_impression IS NULL';
				}
			}

			// Date d'impression
			if( !empty( $date_print ) && $date_print != 0 ) {
				$date_impression_from = Set::extract( $criteres, 'Filtre.date_impression_from' );
				$date_impression_to = Set::extract( $criteres, 'Filtre.date_impression_to' );
				// FIXME: vérifier le bon formatage des dates
				$date_impression_from = $date_impression_from['year'].'-'.$date_impression_from['month'].'-'.$date_impression_from['day'];
				$date_impression_to = $date_impression_to['year'].'-'.$date_impression_to['month'].'-'.$date_impression_to['day'];

				$conditions[] = 'Orientstruct.date_impression BETWEEN \''.$date_impression_from.'\' AND \''.$date_impression_to.'\'';
			}

			// FIXME: prefixes pour les jointures
			return array(
				'fields' => array(
					'Adresse.canton',
					'Adresse.codepos',
					'Adresse.locaadr',
					'Adresse.numcomptt',
					'Dossier.dtdemrsa',
					'Dossier.matricule',
					'Dossier.numdemrsa',
					/// FIXME: dépendant du SGBD
					'( CASE WHEN dtdemrsa >= \'2009-06-01 00:00:00\' THEN \'Nouvelle demande\' ELSE \'Diminution des ressources\' END ) AS "Dossier__statut"',
					'Foyer.dossier_id',
					'Orientstruct.id',
					'Orientstruct.propo_algo',
					'Orientstruct.structurereferente_id',
					'Orientstruct.date_propo',
					'Orientstruct.statut_orient',
					'Orientstruct.typeorient_id',
					'Personne.dtnai',
					'Personne.id',
					'Personne.nom',
					'Personne.nir',
					'Personne.prenom',
					'Situationdossierrsa.dtclorsa',
					'Situationdossierrsa.moticlorsa',
					'Dsp.id',
					'Typeorient.lib_type_orient',
					/// FIXME: dépendant du SGBD
					'Preorientation.lib_type_orient',
					'Structurereferente.lib_struc',
					'Contratinsertion.dd_ci',
					'Suiviinstruction.numagrins',
					'Suiviinstruction.numcomins',
					'Suiviinstruction.numdepins',
					'Suiviinstruction.typeserins'
				),
				'joins' => array(
					array(
						'table'      => 'personnes',
						'alias'      => 'Personne',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Personne.id = Orientstruct.personne_id' )
					),
					array(
						'table'      => 'foyers',
						'alias'      => 'Foyer',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Foyer.id = Personne.foyer_id' )
					),
					array(
						'table'      => 'adressesfoyers',
						'alias'      => 'Adressefoyer',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Foyer.id = Adressefoyer.foyer_id',
							// Dernière adresse
							'Adressefoyer.id IN (
								SELECT adressesfoyers.id
									FROM adressesfoyers
									WHERE
										adressesfoyers.foyer_id = Personne.foyer_id
										AND adressesfoyers.rgadr = \'01\'
									ORDER BY adressesfoyers.dtemm DESC
									LIMIT 1
							)'
						)
					),
					array(
						'table'      => 'adresses',
						'alias'      => 'Adresse',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
					),
					array(
						'table'      => 'dossiers',
						'alias'      => 'Dossier',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Dossier.id = Foyer.dossier_id'
						)
					),
					array(
						'table'      => 'situationsdossiersrsa',
						'alias'      => 'Situationdossierrsa',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Dossier.id = Situationdossierrsa.dossier_id' )
					),
					array(
						'table'      => 'typesorients',
						'alias'      => 'Typeorient',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Typeorient.id = Orientstruct.typeorient_id' )
					),
					array(
						'table'      => 'typesorients',
						'alias'      => 'Preorientation',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Preorientation.id = Orientstruct.propo_algo' )
					),
					array(
						'table'      => 'structuresreferentes',
						'alias'      => 'Structurereferente',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Structurereferente.id = Orientstruct.structurereferente_id' )
					),
					/// Une seule
					array(
						'table'      => 'dsps',
						'alias'      => 'Dsp',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Personne.id = Dsp.personne_id' )
					),
					array(
						'table'      => 'calculsdroitsrsa',
						'alias'      => 'Calculdroitrsa',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Personne.id = Calculdroitrsa.personne_id' )
					),
					array( // FIXME: seulement visualisation
						'table'      => 'contratsinsertion',
						'alias'      => 'Contratinsertion',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Personne.id = Contratinsertion.personne_id',
							'Contratinsertion.id IN (
								SELECT contratsinsertion.id
									FROM contratsinsertion
									WHERE
										contratsinsertion.personne_id = Personne.id
									ORDER BY contratsinsertion.dd_ci DESC
									LIMIT 1
							)'
						)
					),
					array(
						'table'      => 'detailsdroitsrsa',
						'alias'      => 'Detaildroitrsa',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Dossier.id = Detaildroitrsa.dossier_id' )
					),
					array(
						'table'      => 'suivisinstruction',
						'alias'      => 'Suiviinstruction',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Dossier.id = Suiviinstruction.dossier_id' )
					),
				),
				'recursive' => -1,
				'limit' => 10,
				'conditions' => $conditions
			);
		} */

		/**
		*
		*/

		public function structuresAutomatiques() {
			$this->Structurereferente = ClassRegistry::init( 'Structurereferente' );
			$this->Typeorient = ClassRegistry::init( 'Typeorient' );

			// FIXME: valeurs magiques
			$typesPermis = $this->Typeorient->find(
				'list',
				array(
					'conditions' => array(
						'Typeorient.lib_type_orient' => array( 'Emploi', 'Social', 'Socioprofessionnelle' )
					),
					'recursive' => -1
				)
			);
			$typesPermis = array_keys( $typesPermis );

			$this->Structurereferente->unbindModelAll();
			$this->Structurereferente->bindModel( array( 'hasAndBelongsToMany' => array( 'Zonegeographique' ) ) );
			$this->Structurereferente->Zonegeographique->unbindModelAll();
			$structures = $this->Structurereferente->find(
				'all',
				array(
					'conditions' => array(
						'Structurereferente.typeorient_id' => $typesPermis
					),
					'recursive' => 2
				)
			);

			$return = array();
			foreach( $structures as $structure ) {
				if( !empty( $structure['Zonegeographique'] ) ) {
					foreach( $structure['Zonegeographique'] as $zonegeographique ) {
						$return[$structure['Structurereferente']['typeorient_id']][$zonegeographique['codeinsee']] = $structure['Structurereferente']['typeorient_id'].'_'.$structure['Structurereferente']['id'];
					}
				}
			}

			return $return;
		}
	}
?>