<?php
	// ajout de la classe de test contenant les fonctions startTest et tearDown
	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	// importation du modèle
	App::import('Model', 'Recoursapre');
	App::import('Core', 'Sanitize');

	class RecoursapreTestCase extends CakeAppModelTestCase {

		function testSearch() {
			$avisRecours = 'Recoursapre::demande';
			$criteresrecours=array('Recoursapre'=>array('matricule','numeroapre'),'Cohortecomiteapre'=>array('id'=>1));
			$result=$this->Recoursapre->search($avisRecours, $criteresrecours);
			$expected=array(
				'fields' => array(
					0 => '"Comiteapre"."id"',
					1 => '"Comiteapre"."datecomite"',
					2 => '"Comiteapre"."heurecomite"',
					3 => '"Comiteapre"."lieucomite"',
					4 => '"Comiteapre"."intitulecomite"',
					5 => '"Comiteapre"."observationcomite"',
					6 => '"ApreComiteapre"."id"',
					7 => '"ApreComiteapre"."apre_id"',
					8 => '"ApreComiteapre"."comiteapre_id"',
					9 => '"ApreComiteapre"."decisioncomite"',
					10 => '"ApreComiteapre"."montantattribue"',
					11 => '"ApreComiteapre"."observationcomite"',
					12 => '"ApreComiteapre"."recoursapre"',
					13 => '"ApreComiteapre"."daterecours"',
					14 => '"ApreComiteapre"."observationrecours"',
					15 => '"Dossier"."numdemrsa"',
					16 => '"Dossier"."matricule"',
					17 => '"Personne"."qual"',
					18 => '"Personne"."nom"',
					19 => '"Personne"."prenom"',
					20 => '"Personne"."dtnai"',
					21 => '"Personne"."nir"',
					22 => '"Adresse"."locaadr"',
					23 => '"Adresse"."codepos"',
					24 => '"Apre"."id"',
					25 => '"Apre"."datedemandeapre"',
					26 => '"Apre"."numeroapre"',
					27 => '"Apre"."mtforfait"'
				),
				'recursive' => -1,
				'joins' => array(
					0 => array(
						'table' => 'comitesapres',
						'alias' => 'Comiteapre',
						'type' => 'LEFT OUTER',
						'foreignKey' => '',
						'conditions' => array(
							0 => 'ApreComiteapre.comiteapre_id = Comiteapre.id'
						)
					),
					1 => array(
						'table' => 'apres',
						'alias' => 'Apre',
						'type' => 'LEFT OUTER',
						'foreignKey' => '',
						'conditions' => array(
							0 => 'ApreComiteapre.apre_id = Apre.id'
						)
					),
					2 => array(
						'table' => 'personnes',
						'alias' => 'Personne',
						'type' => 'INNER',
						'foreignKey' => '',
						'conditions' => array(
							0 => 'Personne.id = Apre.personne_id'
						)
					),
					3 => array(
						'table' => 'foyers',
						'alias' => 'Foyer',
						'type' => 'INNER',
						'foreignKey' => '',
						'conditions' => array(
							0 => 'Personne.foyer_id = Foyer.id'
						)
					),
					4 => array(
						'table' => 'dossiers_rsa',
						'alias' => 'Dossier',
						'type' => 'INNER',
						'foreignKey' => '',
						'conditions' => array(
							0 => 'Foyer.dossier_rsa_id = Dossier.id'
						)
					),
					5 => array(
						'table' => 'prestations',
						'alias' => 'Prestation',
						'type' => 'INNER',
						'foreignKey' => '',
						'conditions' => array(
							0 => 'Personne.id = Prestation.personne_id',
							1 => 'Prestation.natprest = \'RSA\'',
							2 => '( Prestation.rolepers = \'DEM\' OR Prestation.rolepers = \'CJT\' )'
						)
					),
					6 => array(
						'table' => 'adresses_foyers',
						'alias' => 'Adressefoyer',
						'type' => 'INNER',
						'foreignKey' => '',
						'conditions' => array(
							0 => 'Foyer.id = Adressefoyer.foyer_id',
							1 => 'Adressefoyer.rgadr = \'01\''
						)
					),
					7 => array(
						'table' => 'adresses',
						'alias' => 'Adresse',
						'type' => 'INNER',
						'foreignKey' => '',
						'conditions' => array(
							0 => 'Adresse.id = Adressefoyer.adresse_id'
						)
					)
				),
				'order' => array(
					0 => '"ApreComiteapre"."daterecours" ASC'
				),
				'conditions' => array(
					0 => 'ApreComiteapre.decisioncomite = \'REF\'',
					1 => 'ApreComiteapre.recoursapre IS NULL',
					'Comiteapre.id' => 1
				)
			);
			$this->assertEqual($expected,$result);

			//------------------------------------------------------------------

			$avisRecours = null;
			$criteresrecours=array('Recoursapre'=>array('matricule','numeroapre'));
			$result=$this->Recoursapre->search($avisRecours, $criteresrecours);
			$expected=array(
				'fields' => array(
					0 => '"Comiteapre"."id"',
					1 => '"Comiteapre"."datecomite"',
					2 => '"Comiteapre"."heurecomite"',
					3 => '"Comiteapre"."lieucomite"',
					4 => '"Comiteapre"."intitulecomite"',
					5 => '"Comiteapre"."observationcomite"',
					6 => '"ApreComiteapre"."id"',
					7 => '"ApreComiteapre"."apre_id"',
					8 => '"ApreComiteapre"."comiteapre_id"',
					9 => '"ApreComiteapre"."decisioncomite"',
					10 => '"ApreComiteapre"."montantattribue"',
					11 => '"ApreComiteapre"."observationcomite"',
					12 => '"ApreComiteapre"."recoursapre"',
					13 => '"ApreComiteapre"."daterecours"',
					14 => '"ApreComiteapre"."observationrecours"',
					15 => '"Dossier"."numdemrsa"',
					16 => '"Dossier"."matricule"',
					17 => '"Personne"."qual"',
					18 => '"Personne"."nom"',
					19 => '"Personne"."prenom"',
					20 => '"Personne"."dtnai"',
					21 => '"Personne"."nir"',
					22 => '"Adresse"."locaadr"',
					23 => '"Adresse"."codepos"',
					24 => '"Apre"."id"',
					25 => '"Apre"."datedemandeapre"',
					26 => '"Apre"."numeroapre"',
					27 => '"Apre"."mtforfait"'
				),
				'recursive' => -1,
				'joins' => array(
					0 => array(
						'table' => 'comitesapres',
						'alias' => 'Comiteapre',
						'type' => 'LEFT OUTER',
						'foreignKey' => '',
						'conditions' => array(
							0 => 'ApreComiteapre.comiteapre_id = Comiteapre.id'
						)
					),
					1 => array(
						'table' => 'apres',
						'alias' => 'Apre',
						'type' => 'LEFT OUTER',
						'foreignKey' => '',
						'conditions' => array(
							0 => 'ApreComiteapre.apre_id = Apre.id'
						)
					),
					2 => array(
						'table' => 'personnes',
						'alias' => 'Personne',
						'type' => 'INNER',
						'foreignKey' => '',
						'conditions' => array(
							0 => 'Personne.id = Apre.personne_id'
						)
					),
					3 => array(
						'table' => 'foyers',
						'alias' => 'Foyer',
						'type' => 'INNER',
						'foreignKey' => '',
						'conditions' => array(
							0 => 'Personne.foyer_id = Foyer.id'
						)
					),
					4 => array(
						'table' => 'dossiers_rsa',
						'alias' => 'Dossier',
						'type' => 'INNER',
						'foreignKey' => '',
						'conditions' => array(
							0 => 'Foyer.dossier_rsa_id = Dossier.id'
						)
					),
					5 => array(
						'table' => 'prestations',
						'alias' => 'Prestation',
						'type' => 'INNER',
						'foreignKey' => '',
						'conditions' => array(
							0 => 'Personne.id = Prestation.personne_id',
							1 => 'Prestation.natprest = \'RSA\'',
							2 => '( Prestation.rolepers = \'DEM\' OR Prestation.rolepers = \'CJT\' )'
						)
					),
					6 => array(
						'table' => 'adresses_foyers',
						'alias' => 'Adressefoyer',
						'type' => 'INNER',
						'foreignKey' => '',
						'conditions' => array(
							0 => 'Foyer.id = Adressefoyer.foyer_id',
							1 => 'Adressefoyer.rgadr = \'01\''
						)
					),
					7 => array(
						'table' => 'adresses',
						'alias' => 'Adresse',
						'type' => 'INNER',
						'foreignKey' => '',
						'conditions' => array(
							0 => 'Adresse.id = Adressefoyer.adresse_id'
						)
					)
				),
				'order' => array(
					0 => '"ApreComiteapre"."daterecours" ASC'
				),
				'conditions' => array()
			);
			$this->assertEqual($expected,$result);

			//------------------------------------------------------------------

			$avisRecours = 'abcdefgh';
			$criteresrecours=array('Recoursapre'=>array('matricule'=>123456, 'numeroapre'=>98765, 'datedemandeapre'=>'2009-06-10', 'datedemandeapre_from'=>array('year'=>2009, 'month'=>6, 'day'=>10), 'datedemandeapre_to'=>array('year'=>2010, 'month'=>8, 'day'=>24), 'nom'=>'Dupond'));
			$result=$this->Recoursapre->search($avisRecours, $criteresrecours);
			$expected=array(
				'fields' => array(
					0 => '"Comiteapre"."id"',
					1 => '"Comiteapre"."datecomite"',
					2 => '"Comiteapre"."heurecomite"',
					3 => '"Comiteapre"."lieucomite"',
					4 => '"Comiteapre"."intitulecomite"',
					5 => '"Comiteapre"."observationcomite"',
					6 => '"ApreComiteapre"."id"',
					7 => '"ApreComiteapre"."apre_id"',
					8 => '"ApreComiteapre"."comiteapre_id"',
					9 => '"ApreComiteapre"."decisioncomite"',
					10 => '"ApreComiteapre"."montantattribue"',
					11 => '"ApreComiteapre"."observationcomite"',
					12 => '"ApreComiteapre"."recoursapre"',
					13 => '"ApreComiteapre"."daterecours"',
					14 => '"ApreComiteapre"."observationrecours"',
					15 => '"Dossier"."numdemrsa"',
					16 => '"Dossier"."matricule"',
					17 => '"Personne"."qual"',
					18 => '"Personne"."nom"',
					19 => '"Personne"."prenom"',
					20 => '"Personne"."dtnai"',
					21 => '"Personne"."nir"',
					22 => '"Adresse"."locaadr"',
					23 => '"Adresse"."codepos"',
					24 => '"Apre"."id"',
					25 => '"Apre"."datedemandeapre"',
					26 => '"Apre"."numeroapre"',
					27 => '"Apre"."mtforfait"'
				),
				'recursive' => -1,
				'joins' => array(
					0 => array(
						'table' => 'comitesapres',
						'alias' => 'Comiteapre',
						'type' => 'LEFT OUTER',
						'foreignKey' => '',
						'conditions' => array(
							0 => 'ApreComiteapre.comiteapre_id = Comiteapre.id'
						)
					),
					1 => array(
						'table' => 'apres',
						'alias' => 'Apre',
						'type' => 'LEFT OUTER',
						'foreignKey' => '',
						'conditions' => array(
							0 => 'ApreComiteapre.apre_id = Apre.id'
						)
					),
					2 => array(
						'table' => 'personnes',
						'alias' => 'Personne',
						'type' => 'INNER',
						'foreignKey' => '',
						'conditions' => array(
							0 => 'Personne.id = Apre.personne_id'
						)
					),
					3 => array(
						'table' => 'foyers',
						'alias' => 'Foyer',
						'type' => 'INNER',
						'foreignKey' => '',
						'conditions' => array(
							0 => 'Personne.foyer_id = Foyer.id'
						)
					),
					4 => array(
						'table' => 'dossiers_rsa',
						'alias' => 'Dossier',
						'type' => 'INNER',
						'foreignKey' => '',
						'conditions' => array(
							0 => 'Foyer.dossier_rsa_id = Dossier.id'
						)
					),
					5 => array(
						'table' => 'prestations',
						'alias' => 'Prestation',
						'type' => 'INNER',
						'foreignKey' => '',
						'conditions' => array(
							0 => 'Personne.id = Prestation.personne_id',
							1 => 'Prestation.natprest = \'RSA\'',
							2 => '( Prestation.rolepers = \'DEM\' OR Prestation.rolepers = \'CJT\' )'
						)
					),
					6 => array(
						'table' => 'adresses_foyers',
						'alias' => 'Adressefoyer',
						'type' => 'INNER',
						'foreignKey' => '',
						'conditions' => array(
							0 => 'Foyer.id = Adressefoyer.foyer_id',
							1 => 'Adressefoyer.rgadr = \'01\''
						)
					),
					7 => array(
						'table' => 'adresses',
						'alias' => 'Adresse',
						'type' => 'INNER',
						'foreignKey' => '',
						'conditions' => array(
							0 => 'Adresse.id = Adressefoyer.adresse_id'
						)
					)
				),
				'order' => array(
					0 => '"ApreComiteapre"."daterecours" ASC'
				),
				'conditions' => array(
					0 => 'ApreComiteapre.decisioncomite = \'REF\'',
					1 => 'ApreComiteapre.recoursapre IS NOT NULL',
					2 => 'ApreComiteapre.daterecours IS NOT NULL',
					3 => 'Apre.datedemandeapre BETWEEN \'2009-6-10\' AND \'2010-8-24\'',
					4 => 'Personne.nom ILIKE \'%Dupond%\'',
					5 => 'Dossier.matricule ILIKE \'%123456%\'',
					6 => 'Apre.numeroapre ILIKE \'%98765%\''
				)
			);
			$this->assertEqual($expected,$result);
		}

	}
?>
