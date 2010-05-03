<?php

	require_once( dirname( __FILE__ ).'/../cake_app_controller_test_case.php' );
/*	require_once( dirname( __FILE__ ).'/../../../../cake/console/error.php' );
	require_once( dirname( __FILE__ ).'/../../../app_error.php' );  */

	App::import('Controller','App');
	App::import('Model','Group');
	App::import('Model','Jeton');
	App::import('Model','Connection');
	App::import('Model','AroAco');
	App::import('Model','Structurereferente');

	class TestAppController extends AppController {
		var $autoRender = false;
		var $redirectUrl;
		var $redirectStatus;
		var $renderedAction;
		var $renderedLayout;
		var $renderedFile;
		var $stopped;
		var $name='App';
	
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
	}

	class AppControllerTest extends CakeAppControllerTestCase
	{

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

		function login($authUser) {
			$this->Utilisateur->Service->recursive=-1;
			$group =  $this->Group->findById($authUser['User']['group_id']);
			$authUser['User']['aroAlias'] = 'Utilisateur:'. $authUser['User']['username'];
			$this->AppController->Session->write( 'Auth', $authUser );
		}

		function testLoadPermissions() {
			$this->Group =& ClassRegistry::init( "Group" );

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

			$this->AppController->AroAco =& ClassRegistry::init( "AroAco" );
			$this->AppController->_loadPermissions();

			$result=$this->AppController->Session->read('Auth.Permissions');
			$expected=array(
				"Dossiers" => 1,
				"Dossiers:index" => 1,
				"Users" => 1
			);
			$this->assertEqual($result,$expected);

			//------------------------------------------------------------------

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

			$this->AppController->AroAco =& ClassRegistry::init( "AroAco" );
			$this->AppController->_loadPermissions();

			$result=$this->AppController->Session->read('Auth.Permissions');
			$expected=array(
				"Dossiers" => 1,
				"Dossiers:index" => 0,
				"Users" => 1
			);
			$this->assertEqual($result,$expected);
		}

		function testLoadZonesgeographiques() {
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
			$this->assertEqual(array(1=>34000),$this->AppController->Session->read( 'Auth.Zonegeographique'));

			//------------------------------------------------------------------

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
		}

		/**
		*
		*/
		function testIsAdminAction() {
			$this->AppController->name="apres";
			$this->assertTrue($this->AppController->_isAdminAction());

			//------------------------------------------------------------------

			$this->AppController->name="servicesinstructeurs";
			$this->assertTrue($this->AppController->_isAdminAction());

			//------------------------------------------------------------------

			$this->AppController->name="groups";
			$this->assertFalse($this->AppController->_isAdminAction());
		}

		/**
		*
		*/
		function testCheckDecisionsStructures() {
			$this->Structurereferente =& ClassRegistry::init( "Structurereferente" );
			global $varTest;

			//------------------------------------------------------------------

			$varTest = null;
			$this->AppController->_checkDecisionsStructures();
			$data=array(
				0 => 'incompleteStructure',
				1 => array(
					'missing' => array(
						'structurereferente' => array(
							'Gestion des APREs' => 1,
							'Gestion des Contrats d\'engagement' => 1
						)
					),
					'structures' => array(
						'2' => 'Assedic Nimes',
						'3' => 'Pole emploi Mont Sud'
					)
				)
			);
			$this->assertEqual($data,$varTest);

			//------------------------------------------------------------------

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
								"Gestion des Contrats d'engagement" => ""
							)
						),
						"structures" => array(
							'2' => "Assedic Nimes"
						)
				)
			);
			$this->assertEqual($data,$varTest);

			//------------------------------------------------------------------

			$varTest = null;
			$this->Structurereferente->read(null, 2);
			$this->Structurereferente->set('apre', '1');
			$this->Structurereferente->save();

			$this->AppController->_checkDecisionsStructures();
			$this->assertNull($varTest);
		}

		/**
		*
		*/
		function testCheckDonneesUtilisateursEtServices() {
			$this->Structurereferente =& ClassRegistry::init( "Structurereferente" );
			global $varTest;

			//------------------------------------------------------------------

			$varTest = null;
			$user['User']['nom']="Azerty";
			$user['User']['prenom']="";
			$user['User']['serviceinstructeur_id']=1;
			$user['User']['date_deb_hab']="2006-01-01";
			$user['User']['date_fin_hab']="2020-12-31";
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

			$varTest = null;
			$user['User']['nom']="";
			$user['User']['prenom']="";
			$user['User']['serviceinstructeur_id']=2;
			$user['User']['date_deb_hab']="2006-01-01";
			$user['User']['date_fin_hab']="2020-12-31";
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
							'Nom du service instructeur' => 1,
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

			$varTest = null;
			$user['User']['nom']="Azerty";
			$user['User']['prenom']="Qwerty";
			$user['User']['serviceinstructeur_id']=1;
			$user['User']['date_deb_hab']="2006-01-01";
			$user['User']['date_fin_hab']="2020-12-31";
			$this->AppController->_checkDonneesUtilisateursEtServices($user);
			$this->assertNull($varTest);
		}

		/**
		*
		*/
		function testCheckHabilitations() {
			global $varTest;
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
						'date_deb_hab' => date( 'Y-m-d', strtotime( '-1 year' ) ),
						'date_fin_hab' => date( 'Y-m-d', strtotime( '+1 year' ) ),
						'numtel' => '0123456789',
						'filtre_zone_geo' => ''
					)
			);
			$this->login($authUser);
			$this->AppController->_checkHabilitations();
			$this->assertNull($varTest);

			//------------------------------------------------------------------

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
						'date_deb_hab' => date( 'Y-m-d', strtotime( '+1 year' ) ),
						'date_fin_hab' => date( 'Y-m-d', strtotime( '+2 year' ) ),
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
						'date_deb_hab' => '2011-03-30',
						'date_fin_hab' => '2012-03-30'
                	)
        		)
			);
			$this->assertEqual($data,$varTest);

			//------------------------------------------------------------------

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
						'date_deb_hab' => date( 'Y-m-d', strtotime( '-2 year' ) ),
						'date_fin_hab' => date( 'Y-m-d', strtotime( '-1 year' ) ),
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
						'date_deb_hab' => '2008-03-30',
						'date_fin_hab' => '2009-03-30'
                	)
        		)
			);
			$this->assertEqual($data,$varTest);
		}

		/**
		*
		*/
		function testCheckDonneesApre() {
			$montantMaxComplementaires = Configure::read('Apre.montantMaxComplementaires');//2600
			$periodeMontantMaxComplementaires = Configure::read('Apre.periodeMontantMaxComplementaires');//24
			global $varTest;

			$varTest=null;
			$this->AppController->_checkDonneesApre();
			$this->assertNull($varTest);

			//------------------------------------------------------------------

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
			Configure::write('Apre.montantMaxComplementaires',$montantMaxComplementaires);

			//------------------------------------------------------------------

			$varTest=null;
			Configure::delete('Apre.periodeMontantMaxComplementaires');
			$this->AppController->_checkDonneesApre();
			$data=array(
				0 => 'incompleteApre',
				1 => array(
					'montantMaxComplementaires' => 2600,
					'periodeMontantMaxComplementaires' => ''
				)
			);
			$this->assertEqual($data,$varTest);
			Configure::write('Apre.periodeMontantMaxComplementaires',$periodeMontantMaxComplementaires);
		}
	}
?>