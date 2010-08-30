<?php

	require_once( dirname( __FILE__ ).'/../cake_app_controller_test_case.php' );

	App::import('Controller','App');
	App::import('Model','Group');
	App::import('Model','Jeton');
	App::import('Model','Connection');
	App::import('Model','AroAco');
	App::import('Model','Structurereferente');

	/**
	* Classe servant d'intermédiaire pour réécrire certaines fonctions
	*/
	class TestAppController extends AppController {
		var $autoRender = false;
		var $redirectUrl;
		var $redirectStatus;
		var $renderedAction;
		var $renderedLayout;
		var $renderedFile;
		var $stopped;
		var $variable;
		var $name='App';
		var $condition;
		var $error;
		var $parameters;
	
		function redirect($url, $status = null, $exit = true) {
			$this->redirectUrl = $url;
			$this->redirectStatus = $status;
		}
	
		function render($action = null, $layout = null, $file = null) {
			$this->renderedAction = $action;
			$this->renderedLayout = (is_null($layout) ? $this->layout : $layout);
			$this->renderedFile = $file;
		}
	
		function _stop($status = 0) {
			$this->stopped = $status;
		}

		function cakeError($method, $messages) {
			global $varTest;
			$varTest = array($method,$messages);
		}

		function assert( $condition, $error = 'error500', $parameters = array() ) {
			$this->condition = $condition;
			$this->error = $error;
			$this->parameters = $parameters;
		}
	}

	/**
	* Classe utilisée pour les tests de pagination
	*/
	class ControllerItem extends CakeTestModel {
		var $name = 'ControllerItem';
		var $useTable = 'items';
		var $invalidFields = array('name' => 'error_msg');
		var $lastQuery = null;
		function beforeFind($query) {
			$this->lastQuery = $query;
		}
		function find($type, $options = array()) {
			if ($type == 'popular') {
				$conditions = array($this->name . '.' . $this->primaryKey .' > ' => '1');
				$options = Set::merge($options, compact('conditions'));
				return parent::find('all', $options);
			}
			return parent::find($type, $options);
		}
	}

	/**
	* Classe utilisée pour les tests de pagination
	*/
	class ControllerPartitem extends CakeTestModel {
		var $name = 'ControllerPartitem';
		var $useTable = 'partitems';
		var $data = array('name' => 'Some Name');
		var $alias = 'ControllerPartitem';
	}

	/**
	* Classe de test
	*/
	class AppControllerTest extends CakeAppControllerTestCase
	{

		/**
		* Fonction servant à se déconnecter
		* Détruit la session et supprime les jetons
		*/
		function logout() {
			$this->Connection =& ClassRegistry::init( "Connection" );
			if( $user_id = $this->AppController->Session->read( 'Auth.User.id' ) ) {
                if( valid_int( $user_id ) ) {
                    $this->Jeton = ClassRegistry::init( 'Jeton' ); // FIXME: dans Jetons
                    $this->Jeton->deleteAll(
                        array(
                            '"Jeton"."user_id"' => $user_id
                        )
                    );
                    // Utilisateurs concurrents
                    if( Configure::read( 'Utilisateurs.multilogin' ) == false ) {
                        $this->Connection->deleteAll( array( 'Connection.user_id' => $user_id ) );
                    }
                    // Fin utilisateurs concurrents
                }
            }
            $this->AppController->Session->delete( 'Auth' );
		}

		/**
		* Fonction servant à se connecter
		* Les informations de l'utilisateur sont passées en paramètre dans un array
		*/
		function login($authUser) {
			$this->Utilisateur->Service->recursive=-1;
			$group =  $this->Group->findById($authUser['User']['group_id']);
			$authUser['User']['aroAlias'] = 'Utilisateur:'. $authUser['User']['username'];
			$this->AppController->Session->write( 'Auth', $authUser );
		}

		/**
		* Fonction de test pour le chargement des permissions
		*/
		function testLoadPermissions() {
			// instantiation des classes Group et AroAco
			$this->Group =& ClassRegistry::init( "Group" );
			$this->AppController->AroAco =& ClassRegistry::init( "AroAco" );

			/// Test pour 3 permissions accordées sur 3

			// déconnexion au cas où une connexion serait active
			$this->logout();

			// connexion
			$authUser = array (
				"User" => array (
						"id" => 1,
						"group_id" => 1,
						"serviceinstructeur_id" => 1,
						"username" => "test1",
						"nom" => "bono",
						"prenom" => "jean",
						'date_naissance' => '1985-03-23',
						'date_deb_hab' => '2009-01-01',
						'date_fin_hab' => '2020-12-31',
						'numtel' => '0123456789',
						'filtre_zone_geo' => ''
					)
			);
			$this->login($authUser);

			// chargement des permissions
			$this->AppController->_loadPermissions();
			// lecture des permissions
			$result=$this->AppController->Session->read('Auth.Permissions');
			// résultat attendu
			$expected=array(
				"Dossiers" => 1,
				"Dossiers:index" => 1,
				"Users" => 1
			);
			//test de concordance
			$this->assertEqual($result,$expected);

			//------------------------------------------------------------------

			/// Test pour 2 permissions accordées sur 3

			$this->logout();

			$authUser = array (
				"User" => array (
					'id' => '2',
					'group_id' => '2',
					'serviceinstructeur_id' => '1',
					'username' => 'test2',
					'password' => 'motdepassesur40caracteresquineserapaslu.',
					'nom' => 'zétofraie',
					'prenom' => 'mélanie',
					'date_naissance' => '1983-12-25',
					'date_deb_hab' => '2009-01-01',
					'date_fin_hab' => '2020-12-31',
					'numtel' => '0213456789',
					'filtre_zone_geo' => ''
				)
			);
			$this->login($authUser);

			$this->AppController->_loadPermissions();

			$result=$this->AppController->Session->read('Auth.Permissions');
			$expected=array(
				"Dossiers" => 1,
				"Dossiers:index" => 0,
				"Users" => 1
			);
			$this->assertEqual($result,$expected);

			$this->logout();
		}

		/**
		* Fonction de test pour le chargement des zones geographiques
		*/
		function testLoadZonesgeographiques() {

			/// Tests pour un utilisateur qui doit se limiter à sa zone géographique (Montpellier pour le test)

			$this->logout();

			$authUser = array (
				"User" => array (
					'id' => '3',
					'group_id' => '1',
					'serviceinstructeur_id' => '1',
					'username' => 'test3',
					'password' => 'motdepassesur40caracteresquineserapaslu.',
					'nom' => 'deuf',
					'prenom' => 'john',
					'date_naissance' => '1980-01-01',
					'date_deb_hab' => '2009-01-01',
					'date_fin_hab' => '2020-12-31',
					'numtel' => '0312456789',
					'filtre_zone_geo' => true
				)
			);
			$this->login($authUser);
			$this->AppController->_loadZonesgeographiques();
			$this->assertEqual(array(1=>34090),$this->AppController->Session->read( 'Auth.Zonegeographique'));

			//------------------------------------------------------------------

			/// Test pour quelqu'un sans réstiction de zone

			$this->logout();

			$authUser = array (
				"User" => array (
					"id" => 1,
					"group_id" => 1,
					"serviceinstructeur_id" => 1,
					"username" => "test1",
					"nom" => "bono",
					"prenom" => "jean",
					'date_naissance' => '1985-03-23',
					'date_deb_hab' => '2009-01-01',
					'date_fin_hab' => '2020-12-31',
					'numtel' => '0123456789',
					'filtre_zone_geo' => ''
				)
			);
			$this->login($authUser);
			$this->AppController->_loadZonesgeographiques();
			$this->assertEqual(null,$this->AppController->Session->read( 'Auth.Zonegeographique'));

			$this->logout();
		}

		/**
		* Test pour vérifier si un controller est une action possible uniquement pour un administrateur ou non
		*/
		function testIsAdminAction() {

			/// Test pour une action admin

			$this->AppController->name="apres";
			$this->assertTrue($this->AppController->_isAdminAction());

			//------------------------------------------------------------------

			/// Test pour une autre action admin

			$this->AppController->name="servicesinstructeurs";
			$this->assertTrue($this->AppController->_isAdminAction());

			//------------------------------------------------------------------

			/// Test pour une action non admin

			$this->AppController->name="groups";
			$this->assertFalse($this->AppController->_isAdminAction());
		}

		/**
		* Fonction de test des structures
		*/
		function testCheckDecisionsStructures() {
			$this->Structurereferente =& ClassRegistry::init( "Structurereferente" );
			// variable utilisée lors de la réécriture de la fonction cakeError
			global $varTest;

			//------------------------------------------------------------------

			/// Test lorsque les 3 structures ne sont pas remplies

			$varTest = null;
			$this->AppController->_checkDecisionsStructures();
			$data=array(
				0 => 'incompleteStructure',
				1 => array(
					'missing' => array(
						'structurereferente' => array(
							'Gestion des APREs' => 1,
							'Gestion des CERs' => 1
						)
					),
					'structures' => array(
						2 => 'Assedic Nimes',
						3 => 'MSA du Gard',
						4 => 'Pole emploi Mont Sud'
					)
				)
			);
			$this->assertEqual($data,$varTest);

			//------------------------------------------------------------------

			/// Test lorsqu'une des structures n'est pas remplie

			$varTest = null;
			$this->Structurereferente->read(null, 1);
			$this->Structurereferente->set('contratengagement', '0');
			$this->Structurereferente->save();

			$this->AppController->_checkDecisionsStructures();
			$data=array(
				0 => "incompleteStructure",
				1 => array(
						"missing" => array(
							"structurereferente" => array(
								"Gestion des APREs" => 1,
								"Gestion des CERs" => ""
							)
						),
						"structures" => array(
							'3' => 'MSA du Gard',
							'2' => "Assedic Nimes"
						)
				)
			);
			$this->assertEqual($data,$varTest);

			//------------------------------------------------------------------

			/// Test lorsque les 3 structures sont remplies

			$varTest = null;
			$this->Structurereferente->read(null, 2);
			$this->Structurereferente->set('apre', '1');
			$this->Structurereferente->save();
			$this->Structurereferente->read(null, 3);
			$this->Structurereferente->set('apre', '1');
			$this->Structurereferente->save();

			$this->AppController->_checkDecisionsStructures();
			$this->assertNull($varTest);
		}

		/**
		* Fonction vérifiant que toutes les informations requises pour un utilisateur sont présentes
		*/
		function testCheckDonneesUtilisateursEtServices() {
			$this->Structurereferente =& ClassRegistry::init( "Structurereferente" );
			global $varTest;

			//------------------------------------------------------------------

			/// Test lorsqu'il manque le prénom d'un utilisateur

			$varTest = null;
			$user['User']['nom']="Azerty";
			$user['User']['prenom']="";
			$user['User']['serviceinstructeur_id']=1;
			$user['User']['date_deb_hab']=date( 'Y-m-d', strtotime( '-4 year' ) );
			$user['User']['date_fin_hab']=date( 'Y-m-d', strtotime( '+4 year' ) );
			$this->AppController->_checkDonneesUtilisateursEtServices($user);
			$data=array(
				0 => 'incompleteUser',
				1 => array(
					'missing' => array(
						'user' => array(
							'Nom' => '',
							'Prénom' => 1,
							'service instructeur' => '',
							'Date de début d\'habilitation' => '',
							'Date de fin d\'habilitation' => '',
						),
						'serviceinstructeur' => array(
							'Nom du service instructeur' => '',
							'Numéro du département du service instructeur' => '',
							'Type de service instructeur' => '',
							'Numéro de la commune du service instructeur' => '',
							'Numero d\'agréement du service instructeur' => '',
						)
					)
				)
			);
			$this->assertEqual($data,$varTest);

			//------------------------------------------------------------------

			/// Test lorsqu'il manque le nom et le prénom d'un utilisateur ainsi que le nom du service instructeur et son numéro d'agréement

			$varTest = null;
			$user['User']['nom']="";
			$user['User']['prenom']="";
			$user['User']['serviceinstructeur_id']=2;
			$user['User']['date_deb_hab']=date( 'Y-m-d', strtotime( '-4 year' ) );
			$user['User']['date_fin_hab']=date( 'Y-m-d', strtotime( '+4 year' ) );
			$this->AppController->_checkDonneesUtilisateursEtServices($user);
			$data=array(
				0 => 'incompleteUser',
				1 => array(
					'missing' => array(
						'user' => array(
							'Nom' => 1,
							'Prénom' => 1,
							'service instructeur' => '',
							'Date de début d\'habilitation' => '',
							'Date de fin d\'habilitation' => '',
						),
						'serviceinstructeur' => array(
							///FIXME: vérifier le nom du service instructeur car il n'apparait pas
							'Nom du service instructeur' => '',
							'Numéro du département du service instructeur' => '',
							'Type de service instructeur' => '',
							'Numéro de la commune du service instructeur' => '',
							'Numero d\'agréement du service instructeur' => 1,
						)
					)
				)
			);
			$this->assertEqual($data,$varTest);

			//------------------------------------------------------------------

			/// Test lorsque toutes les informations sont complétées

			$varTest = null;
			$user['User']['nom']="Azerty";
			$user['User']['prenom']="Qwerty";
			$user['User']['serviceinstructeur_id']=1;
			$user['User']['date_deb_hab']=date( 'Y-m-d', strtotime( '-4 year' ) );
			$user['User']['date_fin_hab']=date( 'Y-m-d', strtotime( '+4 year' ) );
			$this->AppController->_checkDonneesUtilisateursEtServices($user);
			$this->assertNull($varTest);
		}

		/**
		* Test sur les dates d'habilitations d'un utilisateur
		*/
		function testCheckHabilitations() {
			global $varTest;
			$varTest=null;
			$this->logout();

			/// Test lorsqu'un utilisateur est pleinement habilité

			$authUser = array (
				"User" => array (
						"id" => 1,
						"group_id" => 1,
						"serviceinstructeur_id" => 1,
						"username" => "test1",
						"nom" => "bono",
						"prenom" => "jean",
						'date_naissance' => '1985-03-23',
						'date_deb_hab' => date( 'Y-m-d', strtotime( '-4 year' ) ),
						'date_fin_hab' => date( 'Y-m-d', strtotime( '+4 year' ) ),
						'numtel' => '0123456789',
						'filtre_zone_geo' => ''
					)
			);
			$this->login($authUser);
			$this->AppController->_checkHabilitations();
			$this->assertNull($varTest);

			//------------------------------------------------------------------

			/// Test lorsqu'un utilisateur n'est pas encore habilité

			$varTest=null;
			$this->logout();

			$authUser = array (
				"User" => array (
						"id" => 1,
						"group_id" => 1,
						"serviceinstructeur_id" => 1,
						"username" => "test1",
						"nom" => "bono",
						"prenom" => "jean",
						'date_naissance' => '1985-03-23',
						'date_deb_hab' => date( 'Y-m-d', strtotime( '+4 year' ) ),
						'date_fin_hab' => date( 'Y-m-d', strtotime( '+8 year' ) ),
						'numtel' => '0123456789',
						'filtre_zone_geo' => ''
					)
			);
			$this->login($authUser);
			$this->AppController->_checkHabilitations();
			$data=array(
    			0 => 'dateHabilitationUser',
    			1 => array(
           			'habilitations' => array(
						'date_deb_hab' => date( 'Y-m-d', strtotime( '+4 year' ) ),
						'date_fin_hab' => date( 'Y-m-d', strtotime( '+8 year' ) )
                	)
        		)
			);
			$this->assertEqual($data,$varTest);

			//------------------------------------------------------------------

			/// Test lorsqu'un utilisateur n'est plus habilité

			$varTest=null;
			$this->logout();

			$authUser = array (
				"User" => array (
						"id" => 1,
						"group_id" => 1,
						"serviceinstructeur_id" => 1,
						"username" => "test1",
						"nom" => "bono",
						"prenom" => "jean",
						'date_naissance' => '1985-03-23',
						'date_deb_hab' => date( 'Y-m-d', strtotime( '-8 year' ) ),
						'date_fin_hab' => date( 'Y-m-d', strtotime( '-4 year' ) ),
						'numtel' => '0123456789',
						'filtre_zone_geo' => ''
					)
			);
			$this->login($authUser);
			$this->AppController->_checkHabilitations();
			$data=array(
	    			0 => 'dateHabilitationUser',
	    			1 => array(
		   			'habilitations' => array(
							'date_deb_hab' => date( 'Y-m-d', strtotime( '-8 year' ) ),
							'date_fin_hab' => date( 'Y-m-d', strtotime( '-4 year' ) )
		        		)
				)
			);
			$this->assertEqual($data,$varTest);

			$this->logout();

			//------------------------------------------------------------------

			/// Test lorsqu'un utilisateur est habilité à partir d'aujourd'hui

			$varTest=null;
			$this->logout();

			$authUser = array (
				"User" => array (
						"id" => 1,
						"group_id" => 1,
						"serviceinstructeur_id" => 1,
						"username" => "test1",
						"nom" => "bono",
						"prenom" => "jean",
						'date_naissance' => '1985-03-23',
						'date_deb_hab' => date( 'Y-m-d', strtotime( 'now' ) ),
						'date_fin_hab' => date( 'Y-m-d', strtotime( '+4 year' ) ),
						'numtel' => '0123456789',
						'filtre_zone_geo' => ''
					)
			);
			$this->login($authUser);
			$this->AppController->_checkHabilitations();
			$this->assertNull($varTest);

			//------------------------------------------------------------------

			/// Test lorsqu'un utilisateur n'est plus habilité aujourd'hui
			/// FIXME: à réparer car il n'est plus habilité

			$varTest=null;
			$this->logout();

			$authUser = array (
				"User" => array (
						"id" => 1,
						"group_id" => 1,
						"serviceinstructeur_id" => 1,
						"username" => "test1",
						"nom" => "bono",
						"prenom" => "jean",
						'date_naissance' => '1985-03-23',
						'date_deb_hab' => date( 'Y-m-d', strtotime( '-4 year' ) ),
						'date_fin_hab' => date( 'Y-m-d', strtotime( 'now' ) ),
						'numtel' => '0123456789',
						'filtre_zone_geo' => ''
					)
			);
			$this->login($authUser);
			$this->AppController->_checkHabilitations();
			$data=array(
	    			0 => 'dateHabilitationUser',
	    			1 => array(
		   			'habilitations' => array(
							'date_deb_hab' => date( 'Y-m-d', strtotime( '-4 year' ) ),
							'date_fin_hab' => date( 'Y-m-d', strtotime( 'now' ) )
		        		)
				)
			);
			$this->assertEqual($data,$varTest);

			$this->logout();
		}

		/**
		* Fonction servant à vérifier que le contenu des variables Apre.montantMaxComplementaires et Apre.periodeMontantMaxComplementaires
		* est bien écrit dans le fichier de configuration et que les erreurs si jamais il en manque fonctionnent bien
		*/
		function testCheckDonneesApre() {
			// on mémorise les valeurs présentent
			$montantMaxComplementaires = Configure::read('Apre.montantMaxComplementaires');//2600
			$periodeMontantMaxComplementaires = Configure::read('Apre.periodeMontantMaxComplementaires');//24
			global $varTest;

			/// Si les données sont présentes on ne doit pas avoir d'erreur

			$varTest=null;
			$this->AppController->_checkDonneesApre();
			$this->assertNull($varTest);

			//------------------------------------------------------------------

			/// On supprime la valeur de Apre.montantMaxComplementaires et on vérifie que l'erreur est bien présente

			$varTest=null;
			Configure::delete('Apre.montantMaxComplementaires');
			$this->AppController->_checkDonneesApre();
			$data=array(
				0 => 'incompleteApre',
				1 => array(
					'montantMaxComplementaires' => '',
					'periodeMontantMaxComplementaires' => 24
				)
			);
			$this->assertEqual($data,$varTest);

			//------------------------------------------------------------------

			/// On supprime la valeur de Apre.periodeMontantMaxComplementaires et on vérifie les 2 erreurs

			$varTest=null;
			Configure::delete('Apre.periodeMontantMaxComplementaires');
			$this->AppController->_checkDonneesApre();
			$data=array(
				0 => 'incompleteApre',
				1 => array(
					'montantMaxComplementaires' => '',
					'periodeMontantMaxComplementaires' => ''
				)
			);
			$this->assertEqual($data,$varTest);

			//------------------------------------------------------------------

			/// On test l'erreur quand il manque l'autre variable encore non testée

			$varTest=null;
			// on réécrit la premier valeur
			Configure::write('Apre.montantMaxComplementaires',$montantMaxComplementaires);
			$this->AppController->_checkDonneesApre();
			$data=array(
				0 => 'incompleteApre',
				1 => array(
					'montantMaxComplementaires' => 2600,
					'periodeMontantMaxComplementaires' => ''
				)
			);
			$this->assertEqual($data,$varTest);
			// on réécrit la dernière valeur
			Configure::write('Apre.periodeMontantMaxComplementaires',$periodeMontantMaxComplementaires);
		}

		/**
		* Fonction de test du beforeFilter
		*/
		function testBeforeFilter(){
			/// FIXME: si aucun utilisateur ne se connecte avant l'appel de la fonction, la fonction plante et les tests s'arrêtent
			$this->logout();

			//------------------------------------------------------------------

			/// test pour le premier utilisateur

			$authUser = array (
				"User" => array (
						"id" => 1,
						"group_id" => 1,
						"serviceinstructeur_id" => 1,
						"username" => "test1",
						"nom" => "bono",
						"prenom" => "jean",
						'date_naissance' => '1985-03-23',
						'date_deb_hab' => '2009-01-01',
						'date_fin_hab' => '2020-12-31',
						'numtel' => '0123456789',
						'filtre_zone_geo' => ''
					)
			);
			$this->login($authUser);
			$return=$this->AppController->beforeFilter();
			$group=array(
			    'id' => 1,
			    'name' => 'Administrateurs',
			    'parent_id' => 0
			);
			$this->assertEqual($group,$this->AppController->Session->read('Auth.Group'));
			$serviceinstructeur=array(
			    'id' => 1,
			    'lib_service' => 'Service 1',
			    'num_rue' => '16',
			    'nom_rue' => 'collines',
			    'complement_adr' => '',
			    'code_insee' => '30900',
			    'code_postal' => '30000',
			    'ville' => 'Nimes',
			    'numdepins' => '034',
			    'typeserins' => 'P',
			    'numcomins' => '111',
			    'numagrins' => '11',
			    'type_voie' => 'ARC'
			);
			$this->assertEqual($serviceinstructeur,$this->AppController->Session->read('Auth.Serviceinstructeur'));
			$this->logout();

			//------------------------------------------------------------------

			/// test pour le second utilisateur

			$authUser = array (
				"User" => array (
					'id' => '2',
					'group_id' => '2',
					'serviceinstructeur_id' => '2',
					'username' => 'test2',
					'password' => 'motdepassesur40caracteresquineserapaslu.',
					'nom' => 'zétofraie',
					'prenom' => 'mélanie',
					'date_naissance' => '1983-12-25',
					'date_deb_hab' => '2009-01-01',
					'date_fin_hab' => '2020-12-31',
					'numtel' => '0213456789',
					'filtre_zone_geo' => ''
				)
			);
			$this->login($authUser);
			$return=$this->AppController->beforeFilter();
			$group=array(
			    'id' => 2,
			    'name' => 'Utilisateurs',
			    'parent_id' => 0
			);
			$this->assertEqual($group,$this->AppController->Session->read('Auth.Group'));
			$serviceinstructeur=array(
				'id' => '2',
				'lib_service' => 'Service 2',
				'num_rue' => '775',
				'nom_rue' => 'moulin',
				'complement_adr' => null,
				'code_insee' => '34080',
				'code_postal' => '34000',
				'ville' => 'Lattes',
				'numdepins' => '034',
				'typeserins' => 'P',
				'numcomins' => '111',
				'numagrins' => null,
				'type_voie' => 'ARC'
			);
			$this->assertEqual($serviceinstructeur,$this->AppController->Session->read('Auth.Serviceinstructeur'));
			$this->logout();
		}

		/**
		* Test de la fonction paginate
		*/
		function testPaginate() {
			// Tests volé dans la partie de test sur le coeur de cakephp
			$this->AppController->uses = array('ControllerItem', 'ControllerPartitem');
			$this->AppController->passedArgs[] = '1';
			$this->AppController->params['url'] = array();
			$this->AppController->constructClasses();

			$results = Set::extract($this->AppController->paginate('ControllerItem'), '{n}.ControllerItem.id');
			$this->assertEqual($results, array(1, 2, 3));

			$results = Set::extract($this->AppController->paginate('ControllerPartitem'), '{n}.ControllerPartitem.id');
			$this->assertEqual($results, array(1, 2, 3, 4, 5, 6));

			$this->AppController->modelClass = null;

			$this->AppController->uses[0] = 'Plugin.ControllerItem';
			$results = Set::extract($this->AppController->paginate(), '{n}.ControllerItem.id');
			$this->assertEqual($results, array(1, 2, 3));

			$this->AppController->passedArgs = array('page' => '-1');
			$results = Set::extract($this->AppController->paginate('ControllerItem'), '{n}.ControllerItem.id');
			$this->assertEqual($this->AppController->params['paging']['ControllerItem']['page'], 1);
			$this->assertEqual($results, array(1, 2, 3));

			$this->AppController->passedArgs = array('sort' => 'ControllerItem.id', 'direction' => 'asc');
			$results = Set::extract($this->AppController->paginate('ControllerItem'), '{n}.ControllerItem.id');
			$this->assertEqual($this->AppController->params['paging']['ControllerItem']['page'], 1);
			$this->assertEqual($results, array(1, 2, 3));

			$this->AppController->passedArgs = array('sort' => 'ControllerItem.id', 'direction' => 'desc');
			$results = Set::extract($this->AppController->paginate('ControllerItem'), '{n}.ControllerItem.id');
			$this->assertEqual($this->AppController->params['paging']['ControllerItem']['page'], 1);
			$this->assertEqual($results, array(3, 2, 1));

			$this->AppController->passedArgs = array('sort' => 'id', 'direction' => 'desc');
			$results = Set::extract($this->AppController->paginate('ControllerItem'), '{n}.ControllerItem.id');
			$this->assertEqual($this->AppController->params['paging']['ControllerItem']['page'], 1);
			$this->assertEqual($results, array(3, 2, 1));

			$this->AppController->passedArgs = array('sort' => 'NotExisting.field', 'direction' => 'desc');
			$results = Set::extract($this->AppController->paginate('ControllerItem'), '{n}.ControllerItem.id');
			$this->assertEqual($this->AppController->params['paging']['ControllerItem']['page'], 1, 'Invalid field in query %s');
			$this->assertEqual($results, array(1, 2, 3));

			$this->AppController->passedArgs = array('page' => '1 " onclick="alert(\'xss\');">');
			$this->AppController->paginate = array('limit' => 1);
			$this->AppController->paginate('ControllerItem');
			$this->assertIdentical($this->AppController->params['paging']['ControllerItem']['page'], 1, 'XSS exploit opened %s');
			$this->assertIdentical($this->AppController->params['paging']['ControllerItem']['options']['page'], 1, 'XSS exploit opened %s');

			/// FIXME : modification de app_controller.php
			// pour les tests suivant, il manque la partie servant à repérer si la limite est à 0
			/**
			* Code à ajouter pour ce ça fonctionne :
			*
				// la ligne suivante existe déjà
				extract($options = array_merge(array('page' => 1, 'limit' => 20), $defaults, $options));

				// made in gaëtan
				$options['limit'] = (empty($options['limit']) || !is_numeric($options['limit'])) ? 1 : $options['limit'];
				extract($options);
				// fin made in gaëtan
			*
			*/

			$this->AppController->passedArgs = array();
			$this->AppController->paginate = array('limit' => 0);
			$this->AppController->paginate('ControllerItem');
			$this->assertIdentical($this->AppController->params['paging']['ControllerItem']['page'], 1);
			$this->assertIdentical($this->AppController->params['paging']['ControllerItem']['pageCount'], 3);
			$this->assertIdentical($this->AppController->params['paging']['ControllerItem']['prevPage'], false);
			$this->assertIdentical($this->AppController->params['paging']['ControllerItem']['nextPage'], true);
		
			$this->AppController->passedArgs = array();
			$this->AppController->paginate = array('limit' => 'garbage!');
			$this->AppController->paginate('ControllerItem');
			$this->assertIdentical($this->AppController->params['paging']['ControllerItem']['page'], 1);
			$this->assertIdentical($this->AppController->params['paging']['ControllerItem']['pageCount'], 3);
			$this->assertIdentical($this->AppController->params['paging']['ControllerItem']['prevPage'], false);
			$this->assertIdentical($this->AppController->params['paging']['ControllerItem']['nextPage'], true);
		}
	}
?>
