<?php

    App::import('Sanitize');

    class GedooosController extends AppController
    {
        var $name = 'Gedooos';
        var $uses = array( 'Cohorte', 'Contratinsertion', 'Typocontrat', 'Adressefoyer', 'Orientstruct', 'Structurereferente', 'Dossier', 'Option', 'Dsp', 'Detaildroitrsa', 'Identificationflux', 'Totalisationacompte', 'Relance', 'Rendezvous', 'Referent', 'Activite', 'Action', 'Permanence', 'Prestation', 'Infofinanciere', 'Modecontact', 'Apre', 'Relanceapre', 'PersonneReferent', 'Formqualif', 'Permisb', 'Comiteapre', 'Referent', 'Suspensiondroit', 'Personne', 'Foyer', 'Situationdossierrsa', 'Zonegeographique' );
        var $components = array( 'Jetons', 'Gedooo' );
        var $helpers = array( 'Locale' );

		function beforeFilter() {
			ini_set('max_execution_time', 0);
			ini_set('memory_limit', '1024M');
            App::import( 'Helper', 'Locale' );
            $this->Locale = new LocaleHelper();
		}

        function _value( $array, $index ) {
            $keys = array_keys( $array );
            $index = ( ( $index == null ) ? '' : $index );
            if( @in_array( $index, $keys ) && isset( $array[$index] ) ) {
                return $array[$index];
            }
            else {
                return null;
            }
        }

        function _ged( $datas, $model ) {
            $this->Gedooo->generate( $datas, $model );
        }

        function notification_structure( $personne_id = null ) {
            $qual = $this->Option->qual();
            $this->set( 'qual', $qual );
            $typevoie = $this->Option->typevoie();
            $this->set( 'typevoie', $typevoie );

            $typeorient = $this->Structurereferente->Typeorient->find(
                'first',
                array(
                    'conditions' => array(
                        'Typeorient.id' => $orientstruct['Orientstruct']['typeorient_id'] // FIXME structurereferente_id
                    )
                )
            );
            $modele = $typeorient['Typeorient']['modele_notif'];

            // TODO: error404/error500 si on ne trouve pas les données
            $personne = $this->Personne->find(
                'first',
                array(
                    'conditions' => array(
                        'Personne.id' => $personne_id
                    ),
                    'recursive' => -1
                )
            );

            // Récupération de l'adresse lié à la personne
            $this->Adressefoyer->bindModel(
                array(
                    'belongsTo' => array(
                        'Adresse' => array(
                            'className'     => 'Adresse',
                            'foreignKey'    => 'adresse_id'
                        )
                    )
                )
            );
            $adresse = $this->Adressefoyer->find(
                'first',
                array(
                    'conditions' => array(
                        'Adressefoyer.foyer_id' => $personne['Personne']['foyer_id'],
                        'Adressefoyer.rgadr' => '01',
                    )
                )
            );
            $personne['Adresse'] = $adresse['Adresse'];

            // Récupération de l'utilisateur
            $user = $this->User->find(
                'first',
                array(
                    'conditions' => array(
                        'User.id' => $this->Session->read( 'Auth.User.id' )
                    )
                )
            );
            $personne['User'] = $user['User'];

            // Récupération de la structure referente liée à la personne
            $orientstruct = $this->Orientstruct->find(
                'first',
                array(
                    'conditions' => array(
                        'Orientstruct.personne_id' => $personne_id
                    )
                )
            );
            $personne['Orientstruct'] = $orientstruct['Orientstruct'];
            $personne['Structurereferente'] = $orientstruct['Structurereferente'];
            $personne['Personne']['qual'] = ( isset( $qual[$personne['Personne']['qual']] ) ? $qual[$personne['Personne']['qual']] : null );
            $personne['Structurereferente']['type_voie'] = ( isset( $typevoie[$personne['Structurereferente']['type_voie']] ) ? $typevoie[$personne['Structurereferente']['type_voie']] : null );
            $personne['Adresse']['typevoie'] = ( isset( $typevoie[$personne['Adresse']['typevoie']] ) ? $typevoie[$personne['Adresse']['typevoie']] : null );

            if( empty( $personne['Orientstruct']['date_impression'] ) ){
                $orientstruct['Orientstruct']['date_impression'] = strftime( '%Y-%m-%d', mktime() );
                $this->Orientstruct->create();
                $this->Orientstruct->set( $orientstruct['Orientstruct'] );
                $this->Orientstruct->save( $orientstruct['Orientstruct'] );
            }

            unset( $personne['Contratinsertion'] ); // FIXME: faire un unbindModel

            $this->_ged( $personne, 'Orientation/'.$modele.'.odt' );
        }

		/**
		*
		*/

        function contratinsertion( $contratinsertion_id = null ) {
            // TODO: error404/error500 si on ne trouve pas les données
            $tc = $this->Typocontrat->find(
                'list',
                array(
                    'fields' => array(
                        'Typocontrat.lib_typo'
                    ),
                )
            );
            $this->set( 'tc', $tc );

            $sect_acti_emp = $this->Option->sect_acti_emp();
            $this->set( 'sect_acti_emp', $sect_acti_emp );
            $emp_occupe = $this->Option->emp_occupe();
            $this->set( 'emp_occupe', $emp_occupe );
            $duree_hebdo_emp = $this->Option->duree_hebdo_emp();
            $this->set( 'duree_hebdo_emp', $duree_hebdo_emp );
            $nat_cont_trav = $this->Option->nat_cont_trav();
            $this->set( 'nat_cont_trav', $nat_cont_trav );
            $duree_cdd = $this->Option->duree_cdd();
            $this->set( 'duree_cdd', $duree_cdd );
            $duree_engag_cg93 = $this->Option->duree_engag_cg93();
            $this->set( 'duree_engag_cg93', $duree_engag_cg93 );
            $decision_ci = $this->Option->decision_ci();
            $this->set( 'decision_ci', $decision_ci );

            $qual = $this->Option->qual();
            $this->set( 'qual', $qual );
            $typevoie = $this->Option->typevoie();
            $this->set( 'typevoie', $typevoie );

            $rolepers = $this->Option->rolepers();
            $this->set( 'rolepers', $rolepers );

            $forme_ci = array( 'S' => 'Simple', 'C' => 'Complexe' );

            $act = $this->Option->act();
            $this->set( 'act', $act );
            $soclmaj = $this->Option->natpfcre( 'soclmaj' );
            $this->set( 'soclmaj', $soclmaj );
            $sitfam = $this->Option->sitfam();
            $this->set( 'sitfam', $sitfam );
            $typeocclog = $this->Option->typeocclog();
            $this->set( 'typeocclog', $typeocclog );
            $oridemrsa = $this->Option->oridemrsa();
            $this->set( 'oridemrsa', $oridemrsa );
            $avisraison_ci = $this->Option->avisraison_ci();
            $this->set( 'avisraison_ci', $avisraison_ci );
            $options = $this->Contratinsertion->allEnumLists();
            $this->set( 'options', $options );
// debug($options);


            $contratinsertion = $this->Contratinsertion->find(
                'first',
                array(
                    'conditions' => array(
                        'Contratinsertion.id' => $contratinsertion_id
                    )
                )
            );
            ///Ajout pour distinguer un CER simple (particulier) d'un CER complexe
            $modele = Set::enum( Set::classicExtract( $contratinsertion, 'Contratinsertion.forme_ci' ), $forme_ci  );
            ///Fin ajout


            //////////////////////////////////////////////////////////////////////////
            $this->Adressefoyer->bindModel(
                array(
                    'belongsTo' => array(
                        'Adresse' => array(
                            'className'     => 'Adresse',
                            'foreignKey'    => 'adresse_id'
                        )
                    )
                )
            );
            $adresse = $this->Adressefoyer->find(
                'first',
                array(
                    'conditions' => array(
                        'Adressefoyer.foyer_id' => $contratinsertion['Personne']['foyer_id'],
                        'Adressefoyer.rgadr' => '01',
                    )
                )
            );

            unset( $contratinsertion['Actioninsertion'] );
            $contratinsertion['Adresse'] = $adresse['Adresse'];

            //////////////////////////////////////////////////////////////////////////
            $foyer = $this->Foyer->find(
                'first',
                array(
                    'conditions' => array(
                        'Foyer.id' => $contratinsertion['Personne']['foyer_id']
                    )
                )
            );
            $contratinsertion['Foyer'] = $foyer['Foyer'];
            //////////////////////////////////////////////////////////////////////////
            $dossier = $this->Dossier->find(
                'first',
                array(
                    'conditions' => array(
                        'Dossier.id' => $contratinsertion['Foyer']['dossier_id']
                    )
                )
            );
            $contratinsertion['Foyer']['dossier_id'] = $dossier['Dossier']['id'];
            //////////////////////////////////////////////////////////////////////////
            $modecontact = $this->Modecontact->find(
                'first',
                array(
                    'conditions' => array(
                        'Modecontact.foyer_id' => $contratinsertion['Foyer']['id']
                    )
                )
            );
            $contratinsertion['Modecontact'] = $modecontact['Modecontact'];
            //////////////////////////////////////////////////////////////////////////

            $infofinanciere = $this->Infofinanciere->find(
                'first',
                array(
                    'conditions' => array(
                        'Infofinanciere.dossier_id' => $contratinsertion['Foyer']['dossier_id']
                    )
                )
            );
            $contratinsertion['Infofinanciere'] = $infofinanciere['Infofinanciere'];

            //////////////////////////////////////////////////////////////////////////
            // Récupération de l'utilisateur
            $user = $this->User->find(
                'first',
                array(
                    'conditions' => array(
                        'User.id' => $this->Session->read( 'Auth.User.id' )
                    )
                )
            );
            $contratinsertion['User'] = $user['User'];
            $contratinsertion['Serviceinstructeur'] = $user['Serviceinstructeur'];
// debug($contratinsertion);
            //////////////////////////////////////////////////////////////////////////
            $dsp = $this->Dsp->find(
                'first',
                array(
                    'conditions' => array(
                        'Dsp.personne_id' => $contratinsertion['Personne']['id']
                    )
                )
            );
            $contratinsertion['Dsp']['personne_id'] = $dsp['Personne']['id'];
            //////////////////////////////////////////////////////////////////////////
            $presta = $this->Prestation->find(
                'first',
                array(
                    'conditions' => array(
                        'Prestation.personne_id' => $contratinsertion['Personne']['id']
                    )
                )
            );
            $contratinsertion['Prestation'] = $presta['Prestation'];
        //////////////////////////////////////////////////////////////////////////
            $ddrsa = $this->Detaildroitrsa->find(
                'first',
                array(
                    'conditions' => array(
                        'Detaildroitrsa.dossier_id' => $dossier['Dossier']['id']
                    )
                )
            );
            $dossier['Dossier']['id'] = $ddrsa['Detaildroitrsa']['dossier_id'];

            /// Récupération des données de la table Suspension
            $situationdossierrsa = $this->Situationdossierrsa->find(
                'first',
                array(
                    'conditions' => array(
                        'Situationdossierrsa.dossier_id' => $dossier['Dossier']['id']
                    )
                )
            );
            $contratinsertion['Situationdossierrsa'] = $situationdossierrsa['Situationdossierrsa'];
            $contratinsertion['Situationdossierrsa']['dtclorsa'] = $this->Locale->date( '%d/%m/%Y', Set::classicExtract( $contratinsertion, 'Situationdossierrsa.dtclorsa' ) );


            if( !empty( $situationdossierrsa ) ) {
                /// Récupération des données de la table Suspension
                $suspension = $this->Suspensiondroit->find(
                    'all',
                    array(
                        'conditions' => array(
                            'Suspensiondroit.situationdossierrsa_id' => $situationdossierrsa['Situationdossierrsa']['id']
                        ),
                        'order' => array( 'Suspensiondroit.ddsusdrorsa DESC' )
                    )
                );

                if( !empty( $suspension ) ) {
                    $contratinsertion['Suspensiondroit'] = $suspension[0]['Suspensiondroit'];
                }
                $contratinsertion['Suspensiondroit']['ddsusdrorsa'] = $this->Locale->date( '%d/%m/%Y', Set::classicExtract( $contratinsertion, 'Suspensiondroit.ddsusdrorsa' ) );
            }



            $activite = $this->Activite->find(
                'first',
                array(
                    'conditions' => array(
                        'Activite.personne_id' => $contratinsertion['Personne']['id'],
                    )
                )
            );
            $contratinsertion['Activite'] = $activite['Activite'];

            //////////////////////////////////////////////////////////////////////////
            // Affichage des données réelles et non leurs variables
            foreach( array( 'tc', 'emp_occupe', 'duree_hebdo_emp', 'nat_cont_trav', 'duree_cdd', 'sect_acti_emp' ) as $varName ) {
                $contratinsertion['Contratinsertion'][$varName] = ( isset( $contratinsertion['Contratinsertion'][$varName] ) ? ${$varName}[$contratinsertion['Contratinsertion'][$varName]] : null );
            }

            $contratinsertion['Contratinsertion']['datevalidation_ci'] = strftime( '%d/%m/%Y', strtotime( $contratinsertion['Contratinsertion']['datevalidation_ci'] ) );
            $contratinsertion['Contratinsertion']['date_saisi_ci'] = strftime( '%d/%m/%Y', strtotime( $contratinsertion['Contratinsertion']['date_saisi_ci'] ) );
//             $contratinsertion['Contratinsertion']['typocontrat_id'] = $tc[$contratinsertion['Contratinsertion']['typocontrat_id']];
            $contratinsertion['Contratinsertion']['actions_prev'] = ( $contratinsertion['Contratinsertion']['actions_prev']  ? 'Oui' : 'Non' );
            $contratinsertion['Contratinsertion']['emp_trouv'] = ( $contratinsertion['Contratinsertion']['emp_trouv']  ? 'Oui' : 'Non' );

            /// Affichage de la date seulement en cas de " Validation à compter de "
            if( $contratinsertion['Contratinsertion']['decision_ci'] == 'V' ){
                $contratinsertion['Contratinsertion']['decision_ci'] = $decision_ci[$contratinsertion['Contratinsertion']['decision_ci']].' '.$contratinsertion['Contratinsertion']['datevalidation_ci'];
            }
            else{
                $contratinsertion['Contratinsertion']['decision_ci'] = $decision_ci[$contratinsertion['Contratinsertion']['decision_ci']];
            }

            /// Données Personne récupérées
            $contratinsertion['Personne']['dtnai'] = strftime( '%d/%m/%Y', strtotime( $contratinsertion['Personne']['dtnai'] ) );
            $contratinsertion['Personne']['qual'] = ( isset( $qual[$contratinsertion['Personne']['qual']] ) ? $qual[$contratinsertion['Personne']['qual']] : null );



            $contratinsertion['Contratinsertion']['dd_ci'] = strftime( '%d/%m/%Y', strtotime( $contratinsertion['Contratinsertion']['dd_ci'] ) );
            $contratinsertion['Contratinsertion']['df_ci'] = strftime( '%d/%m/%Y', strtotime( $contratinsertion['Contratinsertion']['df_ci'] ) );


            /// Données Foyer récupérées
            $contratinsertion['Foyer']['sitfam'] = ( isset( $sitfam[$foyer['Foyer']['sitfam']] ) ? $sitfam[$foyer['Foyer']['sitfam']] : null );
            $contratinsertion['Foyer']['typeocclog'] = ( isset( $typeocclog[$foyer['Foyer']['typeocclog']] ) ? $typeocclog[$foyer['Foyer']['typeocclog']] : null );

            $contratinsertion['Adresse']['typevoie'] = ( isset( $typevoie[$contratinsertion['Adresse']['typevoie']] ) ? $typevoie[$contratinsertion['Adresse']['typevoie']] : null );


            $contratinsertion['Structurereferente']['type_voie'] = ( isset( $typevoie[$contratinsertion['Structurereferente']['type_voie']] ) ? $typevoie[$contratinsertion['Structurereferente']['type_voie']] : null );


            $contratinsertion['Dossier']['matricule'] = ( isset( $dossier['Dossier']['matricule'] ) ? $dossier['Dossier']['matricule'] : null );
            $contratinsertion['Dossier']['dtdemrsa'] = strftime( '%d/%m/%Y', strtotime( $dossier['Dossier']['dtdemrsa'] ) );
            $contratinsertion['Dossier']['numdemrsa'] = Set::classicExtract( $dossier, 'Dossier.numdemrsa' );

            $contratinsertion['Detaildroitrsa']['oridemrsa'] = isset( $oridemrsa[$ddrsa['Detaildroitrsa']['oridemrsa']] ) ? $oridemrsa[$ddrsa['Detaildroitrsa']['oridemrsa']] : null ;

            /// Données Dsp récupérées
            $contratinsertion['Dsp']['topcouvsoc'] = ( ( isset( $dsp['Dsp']['topcouvsoc'] ) && ( $dsp['Dsp']['topcouvsoc'] == '1' ) ) ? 'Oui' : 'Non' );

            /// Données Référent lié à la personne récupérées
            $personne_referent = $this->PersonneReferent->find( 'first', array( 'conditions' => array( 'PersonneReferent.personne_id' => $contratinsertion['Personne']['id'] ) ) );
            $contratinsertion['PersonneReferent'] = $personne_referent['PersonneReferent'];

            $referentId = null;
            if( !empty( $personne_referent ) ){
                $referentId = Set::classicExtract( $personne_referent, 'PersonneReferent.referent_id' );
            }
            else{
                /// Population du select référents liés aux structures
                $referentId = Set::classicExtract( $contratinsertion, 'Contratinsertion.referent_id' );
            }

			if( !empty( $referentId ) ) {
                $referent = $this->Referent->findById( $referentId, null, null, -1 );
				$contratinsertion = Set::merge( $contratinsertion, $referent );
			}

            /// Code des actions engagées
            if( Configure::read( 'nom_form_ci_cg' ) == 'cg66' ){ ///FIXME : comment faire plus proprement
                $contratinsertion['Contratinsertion']['engag_object'] = Set::classicExtract( $contratinsertion, 'Contratinsertion.engag_object' );
            }
            else if ( Configure::read( 'nom_form_ci_cg' ) == 'cg93' ){
                $codesaction = $this->Action->find( 'list', array( 'fields' => array( 'code', 'libelle' ) ) );
                $this->set( 'codesaction', $codesaction );
// debug($codesaction);
// die();
                $v = null;
                if( isset( $codesaction[$contratinsertion['Contratinsertion']['engag_object']] ) ) {
                $v = $codesaction[$contratinsertion['Contratinsertion']['engag_object']];
                }
                $contratinsertion['Contratinsertion']['engag_object'] = $v;
            }

            $contratinsertion['Contratinsertion']['duree_engag'] = Set::enum( Set::classicExtract( $contratinsertion, 'Contratinsertion.duree_engag' ), $duree_engag_cg93 );

            /// Ajout de la traduction pour le rôle de la personne (DEM/ CJT)
            $contratinsertion['Prestation']['rolepers'] = Set::enum( Set::classicExtract( $contratinsertion, 'Prestation.rolepers' ), $rolepers );

            ///Permet d'afficher le type de demande en cas de contrat Complexe
           $contratinsertion['Contratinsertion']['type_demande'] = Set::enum( Set::classicExtract( $contratinsertion, 'Contratinsertion.type_demande' ), $options['type_demande'] );
           $contratinsertion['Contratinsertion']['avisraison_ci'] = Set::enum( Set::classicExtract( $contratinsertion, 'Contratinsertion.avisraison_ci' ), $avisraison_ci );


            ///Utilisé pour savoir si le contrat est pour une insertion vers le social / emploi
            if( $contratinsertion['Contratinsertion']['typeinsertion'] == 'SOC' ){
                $contratinsertion['Contratinsertion']['issociale'] = 'X';
            }
            else if( $contratinsertion['Contratinsertion']['typeinsertion'] == 'EMP' ){
                $contratinsertion['Contratinsertion']['isemploi'] = 'X';
            }

            ///Utilisé pour savoir si la personne est demandeur ou ayant droit
            if( $contratinsertion['Prestation']['rolepers'] == 'Demandeur du RSA' ){
                $contratinsertion['Contratinsertion']['isallocataire'] = 'X';
            }
            else if( $contratinsertion['Prestation']['rolepers'] != 'Demandeur du RSA' ){
                $contratinsertion['Contratinsertion']['isayantdroit'] = 'X';
            }

            ///Utilisé pour savoir si la personne est demandeur ou ayant droit
            if( $contratinsertion['Contratinsertion']['num_contrat'] == 'PRE' ){
                $contratinsertion['Contratinsertion']['ispremier'] = 'X';
            }
            else if( $contratinsertion['Contratinsertion']['num_contrat'] == 'REN' ){
                $contratinsertion['Contratinsertion']['isrenouvel'] = 'X';
            }
            else if( $contratinsertion['Contratinsertion']['num_contrat'] != ( 'REN' || 'PRE' ) ){
                $contratinsertion['Contratinsertion']['isavenant'] = 'X';
            }

            ///Permet d'afficher le nom de la zone géographique liée au contrat du cg58
            $zonelist = $this->Zonegeographique->find( 'list', array( 'fields' => array( 'libelle' ) ) );
            $contratinsertion['Contratinsertion']['zonegeographique_id'] = Set::enum( Set::classicExtract( $contratinsertion, 'Contratinsertion.zonegeographique_id' ), $zonelist );

            ///Permet d'afficher le nb d'ouverture de droit de la personne
            $contratinsertion['Dossier']['nbouv'] = count( Set::classicExtract( $contratinsertion, 'Dossier.dtdemrsa' ) );

            ///Permet d'afficher si rsa majoré ou non
            $soclmajValues = array_unique( Set::extract( $contratinsertion, '/Infofinanciere/natpfcre' ) );
            $contratinsertion['Infofinanciere']['rsamaj'] = ( array_intersects( $soclmajValues, array_keys( $soclmaj ) ) ) ? 'Oui' : 'Non';



// debug( $contratinsertion );
// die();
            if( Configure::read( 'nom_form_ci_cg' ) == 'cg58' ) {
                $this->_ged( $contratinsertion, 'Contratinsertion/contratinsertioncg58.odt' );
            }
            else{
                $this->_ged( $contratinsertion, 'Contratinsertion/contratinsertion.odt' );
            }

            ///Ajout pour le type de modèle de document
//             $this->_ged( $contratinsertion, 'Contratinsertion/contratinsertion_'.$modele.'.odt' );
        }

        function orientstruct( $orientstruct_id = null ) {
			/*$this->Gedooo->sendCohortePdfToClient(
				array(
					'conditions' => array(
						'Pdf.modele' => 'Orientstruct',
						'Pdf.fk_value' => $orientstruct_id
					)
				)
			);*/
			$this->Dossier->begin();

			$content = $this->Gedooo->getCohortePdfForClient(
				array(
					'conditions' => array(
						'modele' => 'Orientstruct',
						'Orientstruct.id' => $orientstruct_id
					),
					'joins' => array(
						array(
							'table'      => 'orientsstructs',
							'alias'      => 'Orientstruct',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								'Pdf.fk_value = Orientstruct.id'
							)
						)
					)
				)
			);

			$success = ( $content !== false ) && $this->Orientstruct->updateAll(
				array( 'Orientstruct.date_impression' => date( "'Y-m-d'" ) ),
				array( '"Orientstruct"."id"' => $orientstruct_id, '"Orientstruct"."date_impression" IS NULL' )
			);

			if( $content !== false ) { // date_impression
				$this->Dossier->commit();
				$this->Gedooo->sendPdfContentToClient( $content, sprintf( "orientations-%s.pdf", date( "Ymd-H\hi" ) ) );
				die();
			}
			else {
				$this->Dossier->rollback();
				// redirect referer
				debug( $this->referer() );
			}
        }

		/******************************************************************************/
        /* Notification de relances
		/******************************************************************************/

        /**
        * Notification de relances
        **/
        function notification_relance( $personne_id = null ) {
            $qual = $this->Option->qual();
            $this->set( 'qual', $qual );
            $typevoie = $this->Option->typevoie();
            $this->set( 'typevoie', $typevoie );

            $this->Personne->unbindModel(
                array(
                    'hasMany' => array( 'Contratinsertion', 'Rendezvous' ),
                    'hasOne' => array( 'Avispcgpersonne', 'Dsp', 'Dossiercaf', 'Titresejour' )
                )
            );
            // TODO: error404/error500 si on ne trouve pas les données
            $personne = $this->Personne->find(
                'first',
                array(
                    'conditions' => array(
                        'Personne.id' => $personne_id
                    )
                )
            );

            // Récupération de l'adresse lié à la personne
            $this->Adressefoyer->bindModel(
                array(
                    'belongsTo' => array(
                        'Adresse' => array(
                            'className'     => 'Adresse',
                            'foreignKey'    => 'adresse_id'
                        )
                    )
                )
            );
            $adresse = $this->Adressefoyer->find(
                'first',
                array(
                    'conditions' => array(
                        'Adressefoyer.foyer_id' => $personne['Personne']['foyer_id'],
                        'Adressefoyer.rgadr' => '01',
                    )
                )
            );
            $personne['Adresse'] = $adresse['Adresse'];

            // Récupération de l'utilisateur
            $user = $this->User->find(
                'first',
                array(
                    'conditions' => array(
                        'User.id' => $this->Session->read( 'Auth.User.id' )
                    )
                )
            );
            $personne['User'] = $user['User'];
            $personne['Serviceinstructeur'] = $user['Serviceinstructeur'];

            // Récupération de la structure referente liée à la personne
            $orientstruct = $this->Orientstruct->find(
                'first',
                array(
                    'conditions' => array(
                        'Orientstruct.personne_id' => $personne_id
                    )
                )
            );
            $personne['Orientstruct'] = $orientstruct['Orientstruct'];
            $personne['Structurereferente'] = $orientstruct['Structurereferente'];

            $personne['Structurereferente']['type_voie'] = ( isset( $typevoie[$personne['Structurereferente']['type_voie']] ) ? $typevoie[$personne['Structurereferente']['type_voie']] : null );
            $personne['Serviceinstructeur']['type_voie'] = ( isset( $typevoie[$personne['Serviceinstructeur']['type_voie']] ) ? $typevoie[$personne['Serviceinstructeur']['type_voie']] : null );

            $personne['Personne']['qual'] = ( isset( $qual[$personne['Personne']['qual']] ) ? $qual[$personne['Personne']['qual']] : null );

            $personne['Orientstruct']['daterelance'] = date_short( Set::extract( $personne, 'Orientstruct.daterelance' ) );

            ///Imprimé / Non imprimé
            if( empty( $personne['Orientstruct']['date_impression_relance'] ) ){
                $orientstruct['Orientstruct']['date_impression_relance'] = strftime( '%Y-%m-%d', mktime() );
                $this->Orientstruct->create();
                $this->Orientstruct->set( $orientstruct['Orientstruct'] );
                $this->Orientstruct->save( $orientstruct['Orientstruct'] );
            }

            $personne['Adresse']['typevoie'] = ( isset( $typevoie[$personne['Adresse']['typevoie']] ) ? $typevoie[$personne['Adresse']['typevoie']] : null );

            $this->_ged( $personne, 'Relance/notifications_relances.odt' );
        }


        /**
        * Notification de relances par cohorte
        **/
        function notifications_relances() {
            $params = array_multisize( $this->params['named'] );
            $params['User']['filtre_zone_geo'] = $this->Session->read( 'Auth.User.filtre_zone_geo' );
            $params['User']['id'] = $this->Session->read( 'Auth.User.id' );
            $params['User']['serviceinstructeur_id'] = $this->Session->read( 'Auth.User.serviceinstructeur_id' );
            $params['Zonegeographique'] = $this->Session->read( 'Auth.Zonegeographique' );
            $params['Jetons']['id'] = $this->Jetons->ids();

            $querydata = $this->Relance->querydata( 'gedooo', $params );
            $relances = $this->Orientstruct->find( 'all', $querydata );

            // Définition des variables & maccros
            // FIXME: chemins
            $phpGedooDir = dirname( __FILE__ ).'/../vendors/phpgedooo'; // FIXME: chemin
            $sMimeType  = "application/pdf";
            $sModele = $phpGedooDir.'/../modelesodt/Relance/notifications_relances.odt';

            // Inclusion des fichiers nécessaires à GEDOOo
            // FIXME
            $phpGedooDir = dirname( __FILE__ ).'/../vendors/phpgedooo';
            require_once( $phpGedooDir.DS.'GDO_Utility.class' );
            require_once( $phpGedooDir.DS.'GDO_FieldType.class' );
            require_once( $phpGedooDir.DS.'GDO_ContentType.class' );
            require_once( $phpGedooDir.DS.'GDO_IterationType.class' );
            require_once( $phpGedooDir.DS.'GDO_PartType.class' );
            require_once( $phpGedooDir.DS.'GDO_FusionType.class' );
            require_once( $phpGedooDir.DS.'GDO_MatrixType.class' );
            require_once( $phpGedooDir.DS.'GDO_MatrixRowType.class' );
            require_once( $phpGedooDir.DS.'GDO_AxisTitleType.class' );

            //
            // Organisation des données
            //
            $u = new GDO_Utility();
            $oMainPart = new GDO_PartType();
            $oIteration = new GDO_IterationType( 'relance' );

            $qual = $this->Option->qual();
            $typevoie = $this->Option->typevoie();

            foreach( $relances as $datas ) {
                $oDevPart = new GDO_PartType();

                $datas['Personne']['qual'] = $qual[$datas['Personne']['qual']];

                foreach( array( 'Adresse.typevoie', 'Structurereferente.type_voie', 'Serviceinstructeur.type_voie' ) as $tmpField ) {
                    list( $model, $field ) = explode( '.', $tmpField );
                    $datas[$model][$field] = Set::extract( $typevoie, Set::extract( $datas, $tmpField ) );
                    if( is_array( $datas[$model][$field] ) ) {
                        $datas[$model][$field] = null; // FIXME -> ajouter une erreur
                    }
                }

                $datas['Orientstruct']['daterelance'] = date_short( Set::extract( $datas, 'Orientstruct.daterelance' ) );


                if( empty( $datas['Orientstruct']['date_impression_relance'] ) ){
                    $datas['Orientstruct']['personne_id'] = Set::extract( $datas, 'Personne.id' );
                    $datas['Orientstruct']['date_impression_relance'] = strftime( '%Y-%m-%d', mktime() );
                    $this->Orientstruct->create();
                    $this->Orientstruct->set( $datas['Orientstruct'] );
                    $this->Orientstruct->save( $datas['Orientstruct'] );
                }

                foreach( $datas as $group => $details ) {
                    if( !empty( $details ) ) {
                        foreach( $details as $key => $value ) {
                            $oDevPart->addElement(
                                new GDO_FieldType(
                                    strtolower( $group ).'_'.strtolower( $key ),
                                    $value,
                                    'text'
                                )
                            );
                        }
                    }
                }

                $oIteration->addPart( $oDevPart );
            }

            $oMainPart->addElement($oIteration);

            $bTemplate = $u->ReadFile($sModele);
            $oTemplate = new GDO_ContentType(
                "",
                "modele.ott",
                $u->getMimeType($sModele),
                "binary",
                $bTemplate
            );

            $oFusion = new GDO_FusionType( $oTemplate, $sMimeType, $oMainPart );
            $oFusion->process();
            $oFusion->SendContentToClient();
        }


        /**
        * Notification d'APRE
        **/
        function apre( $apre_id = null ) {
            $qual = $this->Option->qual();
            $this->set( 'qual', $qual );
            $typevoie = $this->Option->typevoie();
            $this->set( 'typevoie', $typevoie );
            $sitfam = $this->Option->sitfam();
            $this->set( 'sitfam', $sitfam );

            $apre = $this->Apre->find(
                'first',
                array(
                    'conditions' => array(
                        'Apre.id' => $apre_id
                    ),
                    'recursive' => -1
                )
            );

            ///Aides liées à l'APRE
            foreach( $this->Apre->aidesApre as $model ) {
                $tables = $this->Apre->{$model}->find(
                    'first',
                    array(
                        'conditions' => array(
                            "$model.apre_id" => $apre['Apre']['id']
                        ),
                        'recursive' => -1
                    )
                );
                $apre[$model] = $tables[$model];
            }
// debug($tablesLiees);

            /// Récupération de la personne lié à l'APRE
            $personne = $this->Personne->find(
                'first',
                array(
                    'conditions' => array(
                        'Personne.id' => Set::classicExtract( $apre, 'Apre.personne_id' )
                    ),
                    'recursive' => -1
                )
            );
            $apre['Personne'] = $personne['Personne'];

            /// Récupération de l'adresse liée à la personne
            $this->Adressefoyer->bindModel(
                array(
                    'belongsTo' => array(
                        'Adresse' => array(
                            'className'     => 'Adresse',
                            'foreignKey'    => 'adresse_id'
                        )
                    )
                )
            );
            $adresse = $this->Adressefoyer->find(
                'first',
                array(
                    'conditions' => array(
                        'Adressefoyer.foyer_id' => Set::classicExtract( $personne, 'Personne.foyer_id' ),
                        'Adressefoyer.rgadr' => '01',
                    )
                )
            );
            $apre['Adresse'] = $adresse['Adresse'];

            /// Récupération de l'utilisateur
            $user = $this->User->find(
                'first',
                array(
                    'conditions' => array(
                        'User.id' => $this->Session->read( 'Auth.User.id' )
                    )
                )
            );
            $apre['User'] = $user['User'];

            /// Récupération de la structure referente liée à la personne
            $orientstruct = $this->Orientstruct->find(
                'first',
                array(
                    'conditions' => array(
                        'Orientstruct.personne_id' => Set::classicExtract( $personne, 'Personne.id' )
                    )
                )
            );
            $apre['Orientstruct'] = $orientstruct['Orientstruct'];

            /// Récupération du dernier contrat dinsertion lié à la personne
            $contrat = $this->Contratinsertion->find(
                'first',
                array(
                    'conditions' => array(
                        'Contratinsertion.personne_id' => Set::classicExtract( $personne, 'Personne.id' )
                    ),
                    'order' => 'Contratinsertion.datevalidation_ci ASC',
                    'recursive' => -1
                )
            );
            $apre['Contratinsertion'] = $contrat['Contratinsertion'];

            /// Récupération du dossier lié à la personne
            $foyer = $this->Foyer->find(
                'first',
                array(
                    'conditions' => array(
                        'Foyer.id' => $apre['Personne']['foyer_id']
                    )
                )
            );
            $apre['Foyer'] = $foyer['Foyer'];
            $dossier = $this->Dossier->find(
                'first',
                array(
                    'conditions' => array(
                        'Dossier.id' => $apre['Foyer']['dossier_id']
                    )
                )
            );
            $apre['Dossier'] = $dossier['Dossier'];

            $apre['Structurereferente'] = $orientstruct['Structurereferente'];
            $apre['Personne']['qual'] = Set::enum( Set::classicExtract( $apre, 'Personne.qual' ), $qual );
            $apre['Personne']['dtnai'] = date_short( Set::classicExtract( $apre, 'Personne.dtnai' ) );

            $apre['Structurereferente']['type_voie'] =  Set::enum( Set::classicExtract( $apre, 'Structurereferente.type_voie' ), $typevoie );

            $apre['Adresse']['typevoie'] = Set::enum( Set::classicExtract( $apre, 'Adresse.typevoie' ), $typevoie );
            $apre['Apre']['datedemandeapre'] = date_short( Set::classicExtract( $apre, 'Apre.datedemandeapre' ) );

            $apre['Contratinsertion']['datevalidation_ci'] = date_short( Set::classicExtract( $apre, 'Contratinsertion.datevalidation_ci' ) );

            $apre['Foyer']['sitfam'] = Set::enum( Set::classicExtract( $apre, 'Foyer.sitfam' ), $sitfam );

            ///Nombre d'enfants par foyer
            $nbEnfants = $this->Foyer->nbEnfants( Set::classicExtract( $apre, 'Foyer.id' ) );
            $apre['Foyer']['nbenfants'] = $nbEnfants;

            $apre['Referent']['qual'] = Set::enum( Set::classicExtract( $apre, 'Referent.qual' ), $qual );

            ///Modification du format de la date pour les aides
            foreach( $this->Apre->aidesApre as $model ) {
                $apre[$model]['ddform'] = date_short( Set::classicExtract( $apre, "$model.ddform" ) );
                $apre[$model]['dfform'] = date_short( Set::classicExtract( $apre, "$model.dfform" ) );
            }
            $apre['Actprof']['ddconvention'] = date_short( Set::classicExtract( $apre, 'Actprof.ddconvention' ) );
            $apre['Actprof']['dfconvention'] = date_short( Set::classicExtract( $apre, 'Actprof.dfconvention' ) );
            $apre['Apre']['dateentreeemploi'] = date_short( Set::classicExtract( $apre, 'Apre.dateentreeemploi' ) );

            unset( $apre['Apre']['Piecepresente'] );
            unset( $apre['Apre']['Piece'] );
            unset( $apre['Apre']['Piecemanquante'] );
            unset( $apre['Apre']['Natureaide'] );

// debug($apre);
// die();

            $this->_ged( $apre, 'APRE/apre.odt' );
        }

        /**
        * Notification de Relance d'APRE
        **/
        function relanceapre( $relanceapre_id = null ) {
            // TODO: error404/error500 si on ne trouve pas les données
            $qual = $this->Option->qual();
            $typevoie = $this->Option->typevoie();

            $relanceapre = $this->Relanceapre->find(
                'first',
                array(
                    'conditions' => array(
                        'Relanceapre.id' => $relanceapre_id
                    )
                )
            );

            unset( $relanceapre['Apre']['Piecepresente'] );
            unset( $relanceapre['Apre']['Piece'] );
            unset( $relanceapre['Apre']['Piecemanquante'] );
            unset( $relanceapre['Apre']['Natureaide'] );

/*
            $relanceapre['Relanceapre']['Piecemanquante'] = Set::extract( $relanceapre, '/Relanceapre/Piecemanquante/libelle' );

            if( !empty( $relanceapre['Relanceapre']['Piecemanquante'] ) ) {
                $relanceapre['Relanceapre']['Piecemanquante'] = '  - '.implode( "\n  - ", $relanceapre['Relanceapre']['Piecemanquante'] )."\n";
            }*/

            /**
            *   Données propre de l'APRE
            **/
            $apre = $this->Apre->find(
                'first',
                array(
                    'conditions' => array(
                        'Apre.id' => $relanceapre['Apre']['id']
                    )
                )
            );
            $piecesManquantesAides = Set::classicExtract( $apre, "Apre.Piece.Manquante" );

            $textePiecesManquantes = '';
            $relanceapre['Relanceapre']['Piecemanquante'] = '';;
            foreach( $piecesManquantesAides as $model => $pieces ) {
                if( !empty( $pieces ) ) {
                    $relanceapre['Relanceapre']['Piecemanquante'] .= __d( 'apre', $model, true )."\n" .'  - '.implode( "\n  - ", $pieces )."\n";
                }
            }

// debug($apre);
// debug( $relanceapre  );
// die();


            $personne = $this->Personne->find(
                'first',
                array(
                    'conditions' => array(
                        'Personne.id' => Set::classicExtract( $relanceapre, 'Apre.personne_id' )
                    )
                )
            );
            $relanceapre['Personne'] = $personne['Personne'];

            $this->Adressefoyer->bindModel(
                array(
                    'belongsTo' => array(
                        'Adresse' => array(
                            'className'     => 'Adresse',
                            'foreignKey'    => 'adresse_id'
                        )
                    )
                )
            );

            $adresse = $this->Adressefoyer->find(
                'first',
                array(
                    'conditions' => array(
                        'Adressefoyer.foyer_id' => Set::classicExtract( $relanceapre, 'Personne.foyer_id' ),
                        'Adressefoyer.rgadr' => '01',
                    )
                )
            );
            $relanceapre['Adresse'] = $adresse['Adresse'];


            ///Pour la qualité de la personne
            $relanceapre['Personne']['qual'] = Set::extract( $qual, Set::extract( $relanceapre, 'Personne.qual' ) );

            ///Pour la date de relance
            $relanceapre['Relanceapre']['daterelance'] =  $this->Locale->date( '%d/%m/%Y', Set::classicExtract( $relanceapre, 'Relanceapre.daterelance' ) );

            ///Pour l'adresse de la personne
            $relanceapre['Adresse']['typevoie'] = Set::extract( $typevoie, Set::extract( $relanceapre, 'Adresse.typevoie' ) );



            $this->_ged( $relanceapre, 'APRE/Relanceapre/relanceapre.odt' );
        }
    }
?>
