<?php
    App::import('Sanitize');

	class Etatliquidatif extends AppModel
	{
		var $name = 'Etatliquidatif';

		var $displayField = 'etatliquidatif';

        var $actsAs = array(
            'Frenchfloat' => array(
                'fields' => array(
                    'montanttotalapre'
                )
            )
        );

		var $belongsTo = array( 'Budgetapre' );



		var $hasAndBelongsToMany = array(
			'Apre' => array(
				'with' => 'ApreEtatliquidatif'
			)
		);

        var $validate = array(
            'budgetapre_id' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
            ),
            'typeapre' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
            ),
			// FIXME: faire les autres
        );

        var $sousRequeteApreNbpaiementeff = '( SELECT COUNT( apres_etatsliquidatifs.id ) FROM apres_etatsliquidatifs WHERE apres_etatsliquidatifs.apre_id = "Apre"."id" AND apres_etatsliquidatifs.montantattribue IS NOT NULL GROUP BY apres_etatsliquidatifs.apre_id )';

        /**
        *   Récupération de la liste de toutes les APREs selon des conditions
        *   @param array $conditions
        */

        function listeApres( $conditions ) {
            $queryData = array(
                'fields' => array(
                    'Apre.id',
                    'Apre.personne_id',
                    'Apre.numeroapre',
                    'Apre.statutapre',
                    'Apre.datedemandeapre',
                    'Apre.mtforfait',
                    'Apre.montantaverser',
                    'Apre.nbenf12',
                    'Apre.nbpaiementsouhait',
                    'Apre.montantdejaverse',
                    'Personne.nom',
                    'Personne.prenom',
                    'Personne.qual',
                    'Dossier.numdemrsa',
                    'Adresse.locaadr',
                    'Adresse.numvoie',
                    'Adresse.nomvoie',
                    'Adresse.complideadr',
                    'Adresse.compladr',
                    'Adresse.typevoie',
                    'Adresse.codepos',
                ),
                'joins' => array(
                    array(
                        'table'      => 'personnes',
                        'alias'      => 'Personne',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Apre.personne_id = Personne.id' )
                    ),
                    array(
                        'table'      => 'foyers',
                        'alias'      => 'Foyer',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Personne.foyer_id = Foyer.id' )
                    ),
                    array(
                        'table'      => 'dossiers_rsa',
                        'alias'      => 'Dossier',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Foyer.dossier_rsa_id = Dossier.id' )
                    ),
                    array(
                        'table'      => 'adresses_foyers',
                        'alias'      => 'Adressefoyer',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Foyer.id = Adressefoyer.foyer_id', 'Adressefoyer.rgadr = \'01\'' )
                    ),
                    array(
                        'table'      => 'adresses',
                        'alias'      => 'Adresse',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
                    ),
                ),
                'recursive' => 1,
                'conditions' => $conditions
            );

            ///Jointure sur les tables des aides liées à l'APRE
            $this->Apre =& ClassRegistry::init( 'Apre' );

            $queryData['joins'] = array_merge( $queryData['joins'], $this->Apre->joinsAidesLiees() );

            return $queryData;
        }

        /**
        *   Récupération de la liste des APREs selon des conditions pour un état liquidatif donné ( $id )
        *   @param array $conditions
        *   @param int $etatliquidatif_id -> identifiant de l'état liquidatif
        */

        function listeApresEtatLiquidatif( $conditions, $etatliquidatif_id ) {
            $conditions = Set::merge(
                $conditions,
                array(
                    'Apre.id IN ( SELECT apres_etatsliquidatifs.apre_id FROM apres_etatsliquidatifs INNER JOIN etatsliquidatifs ON apres_etatsliquidatifs.etatliquidatif_id = etatsliquidatifs.id WHERE etatsliquidatifs.datecloture IS NOT NULL AND apres_etatsliquidatifs.etatliquidatif_id = '.Sanitize::clean( $etatliquidatif_id ).' )'
                )
            );

            ///
            $queryData['joins'][] = array(
                'table'      => 'apres_etatsliquidatifs',
                'alias'      => 'ApreEtatliquidatif',
                'type'       => 'INNER',
                'foreignKey' => false,
                'conditions' => array(
                    'Apre.id = ApreEtatliquidatif.apre_id',
                    'ApreEtatliquidatif.etatliquidatif_id' => $etatliquidatif_id
                )
            );

            foreach( array_keys( $this->ApreEtatliquidatif->schema() ) as $fieldName ) {
                $queryData['fields'][] = "ApreEtatliquidatif.{$fieldName}";
            }

            return $this->listeApres( $conditions );
        }

        /**
        *   Récupération de la liste des APREs selon des conditions pour un état liquidatif donné ( $id )
        *   @param array $conditions
        *   @param int $etatliquidatif_id -> identifiant de l'état liquidatif
        */

        function listeApresEtatLiquidatifNonTermine( $conditions, $etatliquidatif_id ) {
            $conditions = Set::merge(
                $conditions,
                array(
                    'Apre.id IN ( SELECT apres_etatsliquidatifs.apre_id FROM apres_etatsliquidatifs INNER JOIN etatsliquidatifs ON apres_etatsliquidatifs.etatliquidatif_id = etatsliquidatifs.id WHERE etatsliquidatifs.datecloture IS NULL AND apres_etatsliquidatifs.etatliquidatif_id = '.Sanitize::clean( $etatliquidatif_id ).' )'
                )
            );

            $queryData = $this->listeApres( $conditions );

            /// Création du champ virtuel montant total pour connaître les montants attribués à une APRE complémentaire
            $this->Apre =& ClassRegistry::init( 'Apre' );
            $fieldTotal = array();
            foreach( $this->Apre->aidesApre as $modelAide ) {
                $fieldTotal[] = "\"{$modelAide}\".\"montantaide\"";
            }
            $queryData['fields'][] = '( COALESCE( '.implode( ', 0 ) + COALESCE( ', $fieldTotal ).', 0 ) ) AS "Apre__montanttotal"';
/*
            $queryData['fields'][] = '( SELECT SUM( apres_etatsliquidatifs.montantattribue ) FROM apres_etatsliquidatifs WHERE apres_etatsliquidatifs.apre_id = "Apre"."id" GROUP BY apres_etatsliquidatifs.apre_id ) AS "Apre__montantattribue"';

            $queryData['fields'][] = '( SELECT COUNT( apres_etatsliquidatifs.id ) FROM apres_etatsliquidatifs WHERE apres_etatsliquidatifs.apre_id = "Apre"."id" AND apres_etatsliquidatifs.montantattribue IS NOT NULL GROUP BY apres_etatsliquidatifs.apre_id ) AS "Apre__nbpaiementeff"';*/


            return $queryData;
        }

        /**
        * Création du champ virtuel montant total pour connaître les montants attribués à une APRE complémentaire
        */

        /*function _sousRequeteApreMontanttotal() {
            $this->Apre =& ClassRegistry::init( 'Apre' );
            $fieldTotal = array();
            foreach( $this->Apre->aidesApre as $modelAide ) {
                $fieldTotal[] = "\"{$modelAide}\".\"montantaide\"";
            }
            return '( COALESCE( '.implode( ', 0 ) + COALESCE( ', $fieldTotal ).', 0 ) )';
        }*/

        /**
        *   Récupération de la liste des APREs selon des conditions pour un état liquidatif donné ( $id )
        *   @param array $conditions
        *   @param int $etatliquidatif_id -> identifiant de l'état liquidatif
        */

        function listeApresEtatLiquidatifNonTerminePourVersement( $conditions, $etatliquidatif_id ) {
            $conditions = Set::merge(
                $conditions,
                array(
                    'Apre.id IN ( SELECT apres_etatsliquidatifs.apre_id FROM apres_etatsliquidatifs INNER JOIN etatsliquidatifs ON apres_etatsliquidatifs.etatliquidatif_id = etatsliquidatifs.id WHERE etatsliquidatifs.datecloture IS NULL AND apres_etatsliquidatifs.etatliquidatif_id = '.Sanitize::clean( $etatliquidatif_id ).' AND apres_etatsliquidatifs.montantattribue IS NULL AND ( ( '.$this->sousRequeteApreNbpaiementeff.' <> "Apre"."nbpaiementsouhait" OR "Apre"."nbpaiementsouhait" IS NULL ) OR ( Apre.montantdejaverse <> Apre.montantaverser /*'.$this->Apre->sousRequeteMontanttotal().'*/ ) ) )'
                )
            );

            $queryData = $this->listeApres( $conditions );

//             $queryData['fields'][] = $this->Apre->sousRequeteMontanttotal().'  AS "Apre__montanttotal"';

            $queryData['fields'][] = '( SELECT SUM( apres_etatsliquidatifs.montantattribue ) FROM apres_etatsliquidatifs WHERE apres_etatsliquidatifs.apre_id = "Apre"."id" GROUP BY apres_etatsliquidatifs.apre_id ) AS "Apre__montantattribue"';

            $queryData['fields'][] = $this->sousRequeteApreNbpaiementeff.' AS "Apre__nbpaiementeff"';

            ///
            $queryData['joins'][] = array(
                'table'      => 'apres_etatsliquidatifs',
                'alias'      => 'ApreEtatliquidatif',
                'type'       => 'INNER',
                'foreignKey' => false,
                'conditions' => array(
                    'Apre.id = ApreEtatliquidatif.apre_id',
                    'ApreEtatliquidatif.etatliquidatif_id' => $etatliquidatif_id
                )
            );

            foreach( array_keys( $this->ApreEtatliquidatif->schema() ) as $fieldName ) {
                $queryData['fields'][] = "ApreEtatliquidatif.{$fieldName}";
            }

            return $queryData;;
        }


        /**
        *   Retourne une requête cakePhp permettant d'obtenir la liste des APREs
        *   non passées dans un état liquidatif donné, selon certaines conditions
        *   @param array $conditions
        *   @return array $queryData -> Requête au format cakePhp
        **/

        function  listeApresSansEtatLiquidatif( $conditions ) {
            $conditions = Set::merge(
                $conditions,
                array(
                    'Apre.id NOT IN ( SELECT apres_etatsliquidatifs.apre_id FROM apres_etatsliquidatifs INNER JOIN etatsliquidatifs ON apres_etatsliquidatifs.etatliquidatif_id = etatsliquidatifs.id WHERE etatsliquidatifs.datecloture IS NOT NULL )'
                )
            );

            $queryData = $this->listeApres( $conditions );
            $queryData['fields'] = array(
                'Apre.id',
                'Apre.personne_id',
                'Apre.numeroapre',
                'Apre.datedemandeapre',
                'Apre.mtforfait',
                'Apre.nbenf12',
                'Apre.quota',
                'Personne.nom',
                'Personne.prenom',
                'Dossier.numdemrsa',
                'Adresse.locaadr',
            );

            /// Création du champ virtuel montant total pour connaître les montants attribués à une APRE complémentaire
            $this->Apre =& ClassRegistry::init( 'Apre' );
            $fieldTotal = array();
            foreach( $this->Apre->aidesApre as $modelAide ) {
                $fieldTotal[] = "\"{$modelAide}\".\"montantaide\"";
            }
            $queryData['fields'][] = '( COALESCE( '.implode( ', 0 ) + COALESCE( ', $fieldTotal ).', 0 ) ) AS "Apre__montanttotal"';

            return $queryData;
        }

        /**
        *   Retourne une requête cakePhp permettant d'obtenir la liste des APREs
        *   non passées dans un état liquidatif donné, selon certaines conditions
        *   @param array $conditions
        *   @return array $queryData -> Requête au format cakePhp
        **/

        function  listeApresPourEtatLiquidatif( $etatliquidatif_id, $conditions ) {
            $conditions = Set::merge(
                $conditions,
                array(
                    'Apre.id NOT IN ( SELECT apres_etatsliquidatifs.apre_id FROM apres_etatsliquidatifs INNER JOIN etatsliquidatifs ON apres_etatsliquidatifs.etatliquidatif_id = etatsliquidatifs.id WHERE etatsliquidatifs.datecloture IS NOT NULL )
                    OR Apre.id IN ( SELECT apres_etatsliquidatifs.apre_id FROM apres_etatsliquidatifs INNER JOIN etatsliquidatifs ON apres_etatsliquidatifs.etatliquidatif_id = etatsliquidatifs.id WHERE ( '.$this->sousRequeteApreNbpaiementeff.' <> "Apre"."nbpaiementsouhait" ) OR ( Apre.montantdejaverse <> Apre.montantaverser/*.$this->Apre->sousRequeteMontanttotal().*/ ) )'
                )
            );
// debug($conditions);
            $queryData = $this->listeApres( $conditions );
            $queryData['fields'] = array(
                'Apre.id',
                'Apre.personne_id',
                'Apre.numeroapre',
                'Apre.datedemandeapre',
                'Apre.mtforfait',
                'Apre.montantaverser',
                'Apre.nbenf12',
                'Apre.quota',
                'Personne.nom',
                'Personne.prenom',
                'Dossier.numdemrsa',
                'Adresse.locaadr',
            );


            /**
            *   On ne veut afficher que les APREs complémentaires
            **/
            $jointure = !(
                is_array( $conditions ) &&
                array_key_exists( 'Apre.statutapre', $conditions ) &&
                $conditions['Apre.statutapre'] == 'F'
            );

            /**
            *   On ne souhaite afficher QUE les APREs complémentaires passées en comité
            *   avec une décision d'ACCORD
            */
            if( $jointure == true ) {

                $queryData['joins'][] = array(
                    'table'      => 'apres_comitesapres',
                    'alias'      => 'ApreComiteapre',
                    'type'       => 'INNER',
                    'foreignKey' => false,
                    'conditions' => array(
                        'Apre.id = ApreComiteapre.apre_id',
                        'ApreComiteapre.decisioncomite' => 'ACC'
                    )
                );
            }

            return $queryData;
        }

        /**
        *   Récupération de la liste des APREs pour le fichier HOPEYRA selon un état liquidaif donné
        *   @param int $id --> Id de l'état liquidatif
        */

        function hopeyra( $id, $typeapre ) {

            $champAllocation = null;
            if( $typeapre == 'forfaitaire' ) {
                $champAllocation = '"Apre"."mtforfait" AS "Apre__allocation"';
            }
            else if( $typeapre == 'complementaire' ) {
                $champAllocation = '"ApreEtatliquidatif"."montantattribue" AS "Apre__allocation"';
            }
            else {
                $this->cakeError( 'error500' );
            }

            $this->Apre->unbindModelAll();

            $queryData = array(
                'fields' => array(
                    $champAllocation,
                    'Apre.nbenf12',
                    'Apre.statutapre',
                    'Personne.qual',
                    'Personne.nom',
                    'Personne.prenom',
                    'Dossier.numdemrsa',
                    'Dossier.matricule',
                    'Adresse.typevoie',
                    'Paiementfoyer.titurib',
                    'Paiementfoyer.nomprenomtiturib',
                    'Paiementfoyer.etaban',
                    'Paiementfoyer.guiban',
                    'Paiementfoyer.numcomptban',
                    'Paiementfoyer.clerib',
                    'Domiciliationbancaire.libelledomiciliation',
//                     'Tiersprestataireapre.typevoie'
                ),
                'joins' => array(
                    array(
                        'table'      => 'personnes',
                        'alias'      => 'Personne',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Apre.personne_id = Personne.id' )
                    ),
                    array(
                        'table'      => 'foyers',
                        'alias'      => 'Foyer',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Personne.foyer_id = Foyer.id' )
                    ),
                    array(
                        'table'      => 'paiementsfoyers',
                        'alias'      => 'Paiementfoyer',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Paiementfoyer.foyer_id = Foyer.id' )
                    ),
                    array(
                        'table'      => 'dossiers_rsa',
                        'alias'      => 'Dossier',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Foyer.dossier_rsa_id = Dossier.id' )
                    ),
                    array(
                        'table'      => 'adresses_foyers',
                        'alias'      => 'Adressefoyer',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Foyer.id = Adressefoyer.foyer_id', 'Adressefoyer.rgadr = \'01\'' )
                    ),
                    array(
                        'table'      => 'adresses',
                        'alias'      => 'Adresse',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
                    ),
                    array(
                        'table'      => 'apres_etatsliquidatifs',
                        'alias'      => 'ApreEtatliquidatif',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Apre.id = ApreEtatliquidatif.apre_id' )
                    ),
                    array(
                        'table'      => 'domiciliationsbancaires',
                        'alias'      => 'Domiciliationbancaire',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array(
                            'Domiciliationbancaire.codebanque = Paiementfoyer.etaban',
                            'Domiciliationbancaire.codeagence = Paiementfoyer.guiban'
                        )
                    ),
                ),
                'recursive' => 1,
                'conditions' => array( 'ApreEtatliquidatif.etatliquidatif_id' => Sanitize::clean( $id ) ),
                'order' => array( 'Dossier.matricule ASC' )
            );

            $this->Apre =& ClassRegistry::init( 'Apre' );
            $queryData['joins'] = array_merge( $queryData['joins'], $this->Apre->joinsAidesLiees( true ) );

            return $this->Apre->find( 'all', $queryData );
        }


        /**
        *   Récupération de la liste des APREs pour le fichier PDF selon un état liquidatif donné ( $id )
        *   @param int $id
        */

        function pdf( $id, $typeapre ) {

            $champAllocation = null;
            if( $typeapre == 'forfaitaire' ) {
                $champAllocation = '"Apre"."mtforfait" AS "Apre__allocation"';
            }
            else if( $typeapre == 'complementaire' ) {
                $champAllocation = '"ApreEtatliquidatif"."montantattribue" AS "Apre__allocation"';
            }
            else {
                $this->cakeError( 'error500' );
            }

            $this->Apre->unbindModelAll();
            return $this->Apre->find(
                'all',
                array(
                    'fields' => array(
                        'Paiementfoyer.titurib',
                        'Paiementfoyer.nomprenomtiturib', // FIXME ?
                        'Adresse.numvoie',
                        'Adresse.typevoie',
                        'Adresse.nomvoie',
                        'Adresse.complideadr',
                        'Adresse.compladr',
                        'Adresse.compladr',
                        'Adresse.codepos',
                        'Adresse.locaadr',
                        'Paiementfoyer.etaban',
                        'Paiementfoyer.guiban',
                        'Paiementfoyer.numcomptban',
                        'Paiementfoyer.clerib',
                        'Domiciliationbancaire.libelledomiciliation',
                        $champAllocation
                    ),
                    'joins' => array(
                        array(
                            'table'      => 'personnes',
                            'alias'      => 'Personne',
                            'type'       => 'INNER',
                            'foreignKey' => false,
                            'conditions' => array( 'Apre.personne_id = Personne.id' )
                        ),
                        array(
                            'table'      => 'foyers',
                            'alias'      => 'Foyer',
                            'type'       => 'INNER',
                            'foreignKey' => false,
                            'conditions' => array( 'Personne.foyer_id = Foyer.id' )
                        ),
                        array(
                            'table'      => 'adresses_foyers',
                            'alias'      => 'Adressefoyer',
                            'type'       => 'INNER',
                            'foreignKey' => false,
                            'conditions' => array( 'Foyer.id = Adressefoyer.foyer_id', 'Adressefoyer.rgadr = \'01\'' )
                        ),
                        array(
                            'table'      => 'adresses',
                            'alias'      => 'Adresse',
                            'type'       => 'INNER',
                            'foreignKey' => false,
                            'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
                        ),
                        array(
                            'table'      => 'paiementsfoyers',
                            'alias'      => 'Paiementfoyer',
                            'type'       => 'LEFT OUTER',
                            'foreignKey' => false,
                            'conditions' => array( 'Paiementfoyer.foyer_id = Foyer.id' )
                        ),
                        array(
                            'table'      => 'apres_etatsliquidatifs',
                            'alias'      => 'ApreEtatliquidatif',
                            'type'       => 'INNER',
                            'foreignKey' => false,
                            'conditions' => array( 'Apre.id = ApreEtatliquidatif.apre_id' )
                        ),
                        array(
                            'table'      => 'domiciliationsbancaires',
                            'alias'      => 'Domiciliationbancaire',
                            'type'       => 'LEFT OUTER',
                            'foreignKey' => false,
                            'conditions' => array(
                                'Domiciliationbancaire.codebanque = Paiementfoyer.etaban',
                                'Domiciliationbancaire.codeagence = Paiementfoyer.guiban'
                            )
                        ),
                    ),
                    'recursive' => 1,
                    'conditions' => array( 'ApreEtatliquidatif.etatliquidatif_id' => Sanitize::clean( $id ) ),
                    'order' => array( 'Paiementfoyer.nomprenomtiturib ASC' )
                )
            );
        }
    }
?>