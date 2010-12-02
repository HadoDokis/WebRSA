<?php
	class MaintiensreorientsepsController extends AppController
	{
		public $helpers = array( 'Default' );

		public $uses = array( 'Maintienreorientep', 'Personne' );

		/**
		* FIXME: on triche pour ne pas passer par la gestion des ACL
		*/

		public function beforeFilter() {
		}

		/**
		* TODO
		* allocataire soumis à droits et devoirs
		* figurant dans un dossier ouvert
		* ne figurant pas dans la liste des dossiers d'EP non traités ou en cours de traitement
		* ne possédant pas de contrat d'insertion (CER) validé, orienté en « Emploi » dans la période comprise entre 6 et 12 mois auparavant (?)
		* ne possédant pas d'orientation « Emploi » non contractualisée depuis 2 mois (?)
		*
		* http://nuts-and-bolts-of-cakephp.com/2008/09/05/example-of-cakephps-containable-for-deep-model-bindings/
		*/

		public function index() {
// 			$this->paginate = array(
// 				'conditions' => array(
// 					'Contratinsertion.df_ci <' => date( 'Y-m-d', strtotime( '-1 mons' ) ),
// 					'Contratinsertion.datevalidation_ci IS NOT NULL',
// 					'Contratinsertion.personne_id IN (
// 						SELECT personnes.id
// 							FROM personnes
// 								INNER JOIN prestations ON personnes.id = prestations.personne_id
// 								INNER JOIN foyers ON personnes.foyer_id = foyers.id
// 								INNER JOIN dossiers ON foyers.dossier_id = dossiers.id
// 								INNER JOIN situationsdossiersrsa ON situationsdossiersrsa.dossier_id = dossiers.id
// 								INNER JOIN calculsdroitsrsa ON personnes.id = calculsdroitsrsa.personne_id
// 							WHERE
// 								prestations.natprest = \'RSA\'
// 								AND prestations.rolepers IN ( \'DEM\', \'CJT\' )
// 								AND dossiers.dtdemrsa <= \''.date( 'Y-m-d', strtotime( '-6 mons' ) ).'\'
// 								AND situationsdossiersrsa.etatdosrsa IN ( \'2\', \'3\', \'4\' )
// 								AND calculsdroitsrsa.toppersdrodevorsa = \'1\'
// 								/*AND orientsstructs.date_valid IS NOT NULL
// 								AND orientsstructs.statut_orient = \'Orienté\'
// 								AND orientsstructs.date_valid >= \''.date( 'Y-m-d', strtotime( '-1 mons' ) ).'\'*/
// 						EXCEPT
// 						SELECT contratsinsertion.personne_id
// 							FROM contratsinsertion
// 							WHERE
// 								contratsinsertion.df_ci > \''.date( 'Y-m-d' ).'\'
// 						)',
// 				),
// 				'order' => array( 'Contratinsertion.df_ci ASC' ),
// 				'limit' => 10
// 			);
// $this->Personne->Foyer->Dossier;
			$this->paginate = array(
				'conditions' => array(
					'Prestation.natprest' => 'RSA',
					'Prestation.rolepers' => array( 'DEM', 'CJT' ),
					'Dossier.dtdemrsa <=' => date( 'Y-m-d', strtotime( '-6 mons' ) ),
					'Situationdossierrsa.etatdosrsa' => array( '2', '3', '4' ),
// 					'Personne.id NOT IN (
// 						SELECT contratsinsertion.personne_id
// 							FROM contratsinsertion
// 							WHERE
// 					)'
				),
				'contain' => false,
				'joins' => array(
					array(
						'table'      => 'foyers',
						'alias'      => 'Foyer',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Personne.foyer_id = Foyer.id' )
					),
					array(
						'table'      => 'prestations',
						'alias'      => 'Prestation',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Personne.id = Prestation.personne_id' )
					),
					array(
						'table'      => 'dossiers',
						'alias'      => 'Dossier',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Dossier.id = Foyer.dossier_id' )
					),
					array(
						'table'      => 'situationsdossiersrsa',
						'alias'      => 'Situationdossierrsa',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Situationdossierrsa.dossier_id = Dossier.id' )
					),
					array(
						'table'      => 'detailsdroitsrsa',
						'alias'      => 'Detaildroitrsa',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Detaildroitrsa.dossier_id = Dossier.id' )
					),
				),
				'limit' => 10
			);
			$this->set( 'possibles', $this->paginate( $this->Personne ) );
		}
	}
?>