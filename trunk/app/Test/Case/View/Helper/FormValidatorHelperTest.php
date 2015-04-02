<?php
	/**
	 * Code source de la classe DepartementTest.
	 *
	 * @package app.Test.Case.Utility
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'Controller', 'Controller' );
	App::uses( 'View', 'View' );
	App::uses( 'AppHelper', 'View/Helper' );
	App::uses( 'FormValidatorHelper', 'View/Helper' );
	
	/**
	 * Surcharge de la classe pour pouvoir accéder aux méthodes protégées
	 * 
	 * @package app.Test.Case.Utility
	 */
//	class DepartementTestMoi extends Departement
//	{
//		public static function compareTypeorient( array $data1, array $data2 ) {
//			return self::_compareTypeorient( $data1, $data2 );
//		}
//	}

	/**
	 * La classe DepartementTest réalise les tests unitaires de la classe utilitaire Departement.
	 *
	 * @package app.Test.Case.Utility
	 */
	class FormValidatorHelperTest extends CakeTestCase
	{		
		/**
		 * Fixtures utilisés.
		 *
		 * @var array
		 */
		public $fixtures = array(
			'app.Foyer',
			'app.Personne',
			'app.Prestation',
		);
		
		/**
		 * Préparation du test.
		 */
		public function setUp() {
			parent::setUp();
			$Request = new CakeRequest();
			$this->Controller = new Controller( $Request );
			$this->View = new View( $this->Controller );
			$this->FormValidator = new FormValidatorHelper( $this->View );
			$this->FormValidator->request->data = array(
				'Personne' => array(
					'id' => 1,
					'foyer_id' => 1,
					'qual' => 'MR',
					'nom' => 'A',
					'prenom' => 'TOM',
					'nomnai' => 'A',
					'prenom2' => null,
					'prenom3' => null,
					'nomcomnai' => 'CARCASSONNE',
					'dtnai' => '1956-11-02',
					'rgnai' => null,
					'typedtnai' => 'N',
					'nir' => '15611111111111  ',
					'topvalec' => true,
					'sexe' => '1',
					'nati' => null,
					'dtnati' => null,
					'pieecpres' => null,
					'idassedic' => '11111111',
					'numagenpoleemploi' => null,
					'dtinscpoleemploi' => null,
					'numfixe' => null,
					'numport' => null,
					'haspiecejointe' => '0',
					'email' => null,
					'nom_complet' => 'MR A TOM',
					'nom_complet_court' => 'A TOM',
					'age' => '58'
				),
				'Foyer' => array(
					'id' => 1,
					'dossier_id' => 1,
					'sitfam' => 'CEL',
					'ddsitfam' => '1956-11-02',
					'typeocclog' => null,
					'mtvallocterr' => null,
					'mtvalloclog' => null,
					'contefichliairsa' => null,
					'mtestrsa' => null,
					'raisoctieelectdom' => null,
					'regagrifam' => 'SA',
					'haspiecejointe' => '0',
					'enerreur' => null,
					'sansprestation' => null
				),
				'Prestation' => array(
					'personne_id' => 1,
					'natprest' => 'RSA',
					'rolepers' => 'DEM',
					'topchapers' => true,
					'id' => 1
				)
			);
			$this->FormValidator->request->params = array(
				'plugin' => null,
				'controller' => 'users',
				'action' => 'index',
				'named' => array( 'foo' => 'bar' ),
				'pass' => array( )
			);
			
			Configure::write( 'ValidationJS.enabled', true );
			Configure::write( 'ValidationOnchange.enabled', true );
			Configure::write( 'ValidationOnsubmit.enabled', true );
		}
		
		public function testGenerateValidationRules(){
			$additionnal = array(
				'model1' => array(
					'champ1' => array( 'regle1' => array(
						'rule' => 'notEmpty',
						'message' => 'custom'
					)),
					'champ2' => array( 'regle2' => array(
						'rule' => array(
							'notEmptyIf',
							'field',
							true,
							array(1,2,3)
						),
						'message' => 'custom'
					))
				)
			);
			
			$result = $this->FormValidator->generateValidationRules()->validationJson;
			$expected = '{"Personne":{"qual":[{"rule":"notEmpty"}],"nom":[{"rule":"notEmpty"}],"prenom":[{"rule":"notEmpty"}],"nir":[{"rule":["between",13,15],"message":"Le NIR doit \\u00eatre compris entre 13 et 15 caract\\u00e8res","allowEmpty":true},{"rule":"alphaNumeric","message":"Veuillez entrer une valeur alpha-num\\u00e9rique.","allowEmpty":true}],"dtnai":[{"rule":"date","message":"Veuillez v\\u00e9rifier le format de la date."},{"rule":"notEmpty","message":"Champ obligatoire"}],"rgnai":[{"rule":["comparison",">",0],"message":"Veuillez entrer un nombre positif.","allowEmpty":true},{"rule":"numeric","message":"Veuillez entrer une valeur num\\u00e9rique.","allowEmpty":true}],"numfixe":{"phoneFr":{"rule":["phoneFr"],"allowEmpty":true}},"numport":{"phoneFr":{"rule":["phoneFr"],"allowEmpty":true}},"email":{"email":{"rule":["email"],"allowEmpty":true}},"haspiecejointe":[{"rule":["inList",["0","1"]],"message":"Veuillez entrer une valeur parmi 0, 1","allowEmpty":true}]},"Foyer":{"dossier_id":{"numeric":{"rule":["numeric"]}},"haspiecejointe":[{"rule":["inList",["0","1"]],"message":"Veuillez entrer une valeur parmi 0, 1","allowEmpty":true}]},"Prestation":{"rolepers":[{"rule":"notEmpty","message":"Champ obligatoire"}]}}';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
			
			$result2 = $this->FormValidator->generateValidationRules(null, false)->validationJson;
			$expected2 = 'undefined';
			$this->assertEqual( $result2, $expected2, var_export( $result, true ) );
			
			$result3 = $this->FormValidator->generateValidationRules($additionnal)->validationJson;
			$expected3 = '{"Personne":{"qual":[{"rule":"notEmpty"}],"nom":[{"rule":"notEmpty"}],"prenom":[{"rule":"notEmpty"}],"nir":[{"rule":["between",13,15],"message":"Le NIR doit \u00eatre compris entre 13 et 15 caract\u00e8res","allowEmpty":true},{"rule":"alphaNumeric","message":"Veuillez entrer une valeur alpha-num\u00e9rique.","allowEmpty":true}],"dtnai":[{"rule":"date","message":"Veuillez v\u00e9rifier le format de la date."},{"rule":"notEmpty","message":"Champ obligatoire"}],"rgnai":[{"rule":["comparison",">",0],"message":"Veuillez entrer un nombre positif.","allowEmpty":true},{"rule":"numeric","message":"Veuillez entrer une valeur num\u00e9rique.","allowEmpty":true}],"numfixe":{"phoneFr":{"rule":["phoneFr"],"allowEmpty":true}},"numport":{"phoneFr":{"rule":["phoneFr"],"allowEmpty":true}},"email":{"email":{"rule":["email"],"allowEmpty":true}},"haspiecejointe":[{"rule":["inList",["0","1"]],"message":"Veuillez entrer une valeur parmi 0, 1","allowEmpty":true}]},"Foyer":{"dossier_id":{"numeric":{"rule":["numeric"]}},"haspiecejointe":[{"rule":["inList",["0","1"]],"message":"Veuillez entrer une valeur parmi 0, 1","allowEmpty":true}]},"Prestation":{"rolepers":[{"rule":"notEmpty","message":"Champ obligatoire"}]},"model1":{"champ1":{"regle1":{"rule":"notEmpty","message":"custom"}},"champ2":{"regle2":{"rule":["notEmptyIf","field",true,[1,2,3]],"message":"custom"}}}}';
			$this->assertEqual( $result3, $expected3, var_export( $result, true ) );
			
			$result4 = $this->FormValidator->generateValidationRules($additionnal, false)->validationJson;
			$expected4 = '{"model1":{"champ1":{"regle1":{"rule":"notEmpty","message":"custom"}},"champ2":{"regle2":{"rule":["notEmptyIf","field",true,[1,2,3]],"message":"custom"}}}}';
			$this->assertEqual( $result4, $expected4, var_export( $result, true ) );
		}
		
		public function testGenerateTraductions(){
			$result = $this->FormValidator->generateTraductions()->traductions;
			$expected = 'undefined';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
			
			$result2 = $this->FormValidator->generateValidationRules()->generateTraductions()->traductions;
			$expected2 = '{"notEmpty":"Champ obligatoire","between":"","alphaNumeric":"","date":"Veuillez entrer une date valide","comparison":"Veuillez entrer une valeur %s %s","numeric":"Veuillez entrer une valeur num\u00e9rique","phoneFr":"Ce num\u00e9ro de t\u00e9l\u00e9phone n\'est pas valide","email":"Veuillez saisir une adresse de courrier \u00e9lectronique valide","inList":"Veuillez entrer une valeur parmi %s"}';
			$this->assertEqual( $result2, $expected2, var_export( $result, true ) );
		}
		
		public function testRemoveValidations(){
			$removeList1 = array('Personne' => 'nom');
			$removeList2 = array('Personne', 'Foyer');
			
			$result = $this->FormValidator->generateValidationRules()->removeValidations( $removeList1 )->validationJson;
			$expected = '{"Personne":{"qual":[{"rule":"notEmpty"}],"prenom":[{"rule":"notEmpty"}],"nir":[{"rule":["between",13,15],"message":"Le NIR doit \u00eatre compris entre 13 et 15 caract\u00e8res","allowEmpty":true},{"rule":"alphaNumeric","message":"Veuillez entrer une valeur alpha-num\u00e9rique.","allowEmpty":true}],"dtnai":[{"rule":"date","message":"Veuillez v\u00e9rifier le format de la date."},{"rule":"notEmpty","message":"Champ obligatoire"}],"rgnai":[{"rule":["comparison",">",0],"message":"Veuillez entrer un nombre positif.","allowEmpty":true},{"rule":"numeric","message":"Veuillez entrer une valeur num\u00e9rique.","allowEmpty":true}],"numfixe":{"phoneFr":{"rule":["phoneFr"],"allowEmpty":true}},"numport":{"phoneFr":{"rule":["phoneFr"],"allowEmpty":true}},"email":{"email":{"rule":["email"],"allowEmpty":true}},"haspiecejointe":[{"rule":["inList",["0","1"]],"message":"Veuillez entrer une valeur parmi 0, 1","allowEmpty":true}]},"Foyer":{"dossier_id":{"numeric":{"rule":["numeric"]}},"haspiecejointe":[{"rule":["inList",["0","1"]],"message":"Veuillez entrer une valeur parmi 0, 1","allowEmpty":true}]},"Prestation":{"rolepers":[{"rule":"notEmpty","message":"Champ obligatoire"}]}}';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
			
			$result2 = $this->FormValidator->generateValidationRules()->removeValidations()->validationJson;
			$expected2 = 'undefined';
			$this->assertEqual( $result2, $expected2, var_export( $result, true ) );
			
			$result3 = $this->FormValidator->generateValidationRules()->removeValidations($removeList2)->validationJson;
			$expected3 = '{"Prestation":{"rolepers":[{"rule":"notEmpty","message":"Champ obligatoire"}]}}';
			$this->assertEqual( $result3, $expected3, var_export( $result, true ) );
		}
		
		public function testGenerateJavascript(){
			$result = $this->FormValidator->generateJavascript();
			$expected = '<script type="text/javascript">
		<!--//--><![CDATA[//><!--
			// Variables pour validation javascript
			var validationRules = {"Personne":{"qual":[{"rule":"notEmpty"}],"nom":[{"rule":"notEmpty"}],"prenom":[{"rule":"notEmpty"}],"nir":[{"rule":["between",13,15],"message":"Le NIR doit \\u00eatre compris entre 13 et 15 caract\\u00e8res","allowEmpty":true},{"rule":"alphaNumeric","message":"Veuillez entrer une valeur alpha-num\\u00e9rique.","allowEmpty":true}],"dtnai":[{"rule":"date","message":"Veuillez v\\u00e9rifier le format de la date."},{"rule":"notEmpty","message":"Champ obligatoire"}],"rgnai":[{"rule":["comparison",">",0],"message":"Veuillez entrer un nombre positif.","allowEmpty":true},{"rule":"numeric","message":"Veuillez entrer une valeur num\\u00e9rique.","allowEmpty":true}],"numfixe":{"phoneFr":{"rule":["phoneFr"],"allowEmpty":true}},"numport":{"phoneFr":{"rule":["phoneFr"],"allowEmpty":true}},"email":{"email":{"rule":["email"],"allowEmpty":true}},"haspiecejointe":[{"rule":["inList",["0","1"]],"message":"Veuillez entrer une valeur parmi 0, 1","allowEmpty":true}]},"Foyer":{"dossier_id":{"numeric":{"rule":["numeric"]}},"haspiecejointe":[{"rule":["inList",["0","1"]],"message":"Veuillez entrer une valeur parmi 0, 1","allowEmpty":true}]},"Prestation":{"rolepers":[{"rule":"notEmpty","message":"Champ obligatoire"}]}};
			var traductions = {"notEmpty":"Champ obligatoire","between":"","alphaNumeric":"","date":"Veuillez entrer une date valide","comparison":"Veuillez entrer une valeur %s %s","numeric":"Veuillez entrer une valeur num\\u00e9rique","phoneFr":"Ce num\\u00e9ro de t\\u00e9l\\u00e9phone n\'est pas valide","email":"Veuillez saisir une adresse de courrier \\u00e9lectronique valide","inList":"Veuillez entrer une valeur parmi %s"};
			var validationJS = 1;
			var validationOnchange = 1;
			var validationOnsubmit = 1;
		//--><!]]>
		</script>';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}
	}
?>