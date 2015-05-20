<?php
	/**
	 * Code source de la classe Cuis66.
	 *
	 * @package app.Controller
	 * @license Expression license is undefined on line 11, column 23 in Templates/CakePHP/CakePHP Controller.php.
	 */
	App::uses('AppController', 'Controller');
	App::uses('CakeEmail', 'Network/Email');
	App::uses( 'WebrsaEmailConfig', 'Utility' );

	/**
	 * La classe Cuis66 ...
	 *
	 * @package app.Controller
	 */
	class Cuis66Controller extends AppController
	{
		/**
		 * Nom du contrôleur.
		 *
		 * @var string
		 */
		public $name = 'Cuis66';

		/**
		 * Modèles utilisés.
		 *
		 * @var array
		 */
		public $uses = array( 'Cui', 'Option', 'Personne' );
		
		/**
		 * Components utilisés.
		 *
		 * @var array
		 */
		public $components = array(
			'Allocataires',
			'DossiersMenus',
			'Gestionzonesgeos',
			//'Gedooo.Gedooo',
			//'InsertionsAllocataires',
			'Jetons2', // FIXME: à cause de DossiersMenus
			//'Search.Filtresdefaut' => array( 'search' ),
			/*'Search.SearchPrg' => array(
				'actions' => array(
					'search' => array( 'filter' => 'Search' ),
				)
			),*/
		);

		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array(
			'Ajax2' => array(
				'className' => 'Prototype.PrototypeAjax',
				'useBuffer' => false
			),
			//'Allocataires',
			'Default3' => array(
				'className' => 'Default.DefaultDefault'
			),
			//'Search.SearchForm',
			'Observer' => array(
				'className' => 'Prototype.PrototypeObserver',
				'useBuffer' => true
			),
			'Romev3', 'Cake1xLegacy.Ajax'
		);

		/**
		 * Correspondances entre les méthodes publiques correspondant à des
		 * actions accessibles par URL et le type d'action CRUD.
		 *
		 * @var array
		 */
		public $crudMap = array(
			'index' => 'read',
			'add' => 'create',
			'edit' => 'update',
			'annule' => 'delete',
			'delete' => 'delete',
			'view' => 'read',
			'email' => 'read',
			'email_send' => 'create',
			'email_add' => 'create',
			'email_edit' => 'update',
			'email_delete' => 'delete',
		);
		
		/**
		 * Nom de l'array contenant la config pour l'envoi d'e-mails
		 * @see app/Config/email.php
		 * @var String
		 */
		public $configEmail = 'mail_employeur_cui';
		
		/**
		 * Liste des CUI du bénéficiaire.
		 * 
		 * @param integer $personne_id
		 */
		public function index( $personne_id ) {
			$dossierMenu = $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) );

			$this->_setEntriesAncienDossier( $personne_id, 'Cui' );
			
			$query = $this->Cui->Cui66->queryIndex($personne_id);
			$results = $this->Cui->find( 'all', $query );
			
			$messages = $this->Cui->Cui66->messages( $personne_id );
			$addEnabled = $this->Cui->Cui66->addEnabled( $messages );

			// Options
			$options = $this->Cui->Cui66->options( array( 'allocataire' => false, 'find' => false, 'autre' => false ) );

			$this->set( compact( 'results', 'dossierMenu', 'messages', 'addEnabled', 'personne_id', 'options' ) );
		}
		
		/**
		 * Formulaire d'ajout de fiche de CUI
		 *
		 * @param integer $personne_id L'id de la Personne à laquelle on veut ajouter un CUI
		 */
		public function add( $personne_id ) {
			$args = func_get_args();
			call_user_func_array( array( $this, 'edit' ), $args );
		}

		/**
		 * Méthode générique d'ajout et de modification de CUI
		 *
		 * @param integer $id L'id de la personne (add) ou du CUI (edit)
		 */
		public function edit( $id = null ) {
			if( $this->action === 'add' ) {
				$personne_id = $id;
				$id = null;
				$mailEmployeur = false;
			}
			else {
				$personne_id = $this->Cui->personneId( $id );
			}

			$dossierMenu = $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) );
			$this->Jetons2->get( $dossierMenu['Dossier']['id'] );
			
			// INFO: champ non obligatoire
			unset( $this->Cui->Entreeromev3->validate['familleromev3_id']['notEmpty'] );

			// Retour à l'index en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossierMenu['Dossier']['id'] );
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			if( !empty( $this->request->data ) ) {
				$this->Cui->Cui66->begin();
				if( $this->Cui->Cui66->saveAddEdit( $this->request->data, $this->Session->read( 'Auth.User.id' ) ) ) {
					$this->Cui->Cui66->commit();
					$cui_id = $this->Cui->id;
					$this->Cui->Cui66->updatePositionsCuisById( $cui_id );
					// TODO Sauvegarder les informations de Partenairecui dans les parametrages (Voir confirmation)
					$this->Jetons2->release( $dossierMenu['Dossier']['id'] );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'action' => 'index', $personne_id ) );
				}
				else {
					$this->Cui->Cui66->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			else {
				$this->request->data = $this->Cui->Cui66->prepareFormDataAddEdit( $personne_id, $id );
			}
			
			// Options
			$options = $this->Cui->Cui66->options( array( 'allocataire' => false, 'find' => true, 'autre' => true ) );

			// Ajout de la liste des partenaires
			$options['Cui']['partenaire_id'] = $this->Cui->Partenaire->find( 'list', array( 'order' => array( 'Partenaire.libstruc' ) ) );
			
			// Liste des cantons pour l'adresse du partenaire
			$options['Adressecui']['canton'] = $this->Gestionzonesgeos->listeCantons();
			$options['Adressecui']['canton2'] = $options['Adressecui']['canton'];

			$urlmenu = "/cuis66/index/{$personne_id}";

			$Allocataire = ClassRegistry::init( 'Allocataire' );
			
			$queryPersonne = $Allocataire->searchQuery();
			$queryPersonne['conditions']['Personne.id'] = $personne_id;
			$queryPersonne['fields'] = array_merge(
				array(				
					'Dossier.matricule',
					'Dossier.dtdemrsa',
					'Dossier.fonorg',
					'Referentparcours.nom_complet' => $queryPersonne['fields']['Referentparcours.nom_complet'],
					'Titresejour.dftitsej',
					'Departement.name'
				),
				$this->Cui->Personne->fields(),
				$this->Cui->Personne->Foyer->Adressefoyer->Adresse->fields()
			);
			
			// Jointure spéciale adresse actuelle / département pour obtenir le nom du dpt
			$queryPersonne['joins'][] = array(
				'table' => 'departements',
				'alias' => 'Departement',
				'type' => 'LEFT OUTER',
				'conditions' => array(
					'SUBSTRING( Adresse.codepos FROM 1 FOR 2 ) = Departement.numdep'
				)
			);
			$queryPersonne['joins'][] = array(
				'table' => 'titressejour',
				'alias' => 'Titresejour',
				'type' => 'LEFT OUTER',
				'conditions' => array(
					'Titresejour.personne_id' => $personne_id
				)
			);
			
			$personne = $this->Cui->Personne->find('first', $queryPersonne);
			$personne['Foyer']['nb_enfants'] = $this->Cui->Personne->Prestation->getNbEnfants( $personne_id );

			$correspondancesChamps = json_encode( $this->Cui->Partenairecui->Partenairecui66->correspondancesChamps );
			$this->set( compact( 'options', 'personne_id', 'dossierMenu', 'urlmenu', 'personne', 'mailEmployeur', 'correspondancesChamps' ) );
			$this->render( 'edit' );
		}
		
		public function view( $id = null ) {
			$personne_id = $this->Cui->personneId( $id );

			$dossierMenu = $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) );
			$this->Jetons2->get( $dossierMenu['Dossier']['id'] );
			
			$query = $this->Cui->Cui66->queryView( $id );
			$this->request->data = $this->Cui->Cui66->find( 'first', $query );
					
			// Options
			$options = $this->Cui->Cui66->options( array( 'allocataire' => false, 'find' => true, 'autre' => true ) );

			$options['Adressecui']['canton'] = $this->Gestionzonesgeos->listeCantons();
			$options['Adressecui']['canton2'] = $options['Adressecui']['canton'];

			$urlmenu = "/cuis66/index/{$personne_id}";

			$Allocataire = ClassRegistry::init( 'Allocataire' );
			
			$queryPersonne = $Allocataire->searchQuery();
			$queryPersonne['conditions']['Personne.id'] = $personne_id;
			$fields = array(
				'Personne.nom',
				'Personne.prenom',
				'Personne.dtnai',
				'Personne.nir',
				'Personne.nomcomnai',
				'Personne.nati',
				'Adresse.numvoie',
				'Adresse.libtypevoie',
				'Adresse.nomvoie',
				'Adresse.codepos',
				'Adresse.lieudist',
				'Adresse.complideadr',
				'Adresse.compladr',
				'Adresse.nomcom',
				'Adresse.canton',				
				'Dossier.matricule',
				'Dossier.dtdemrsa',
				'Dossier.fonorg',
				'Referentparcours.nom_complet' => $queryPersonne['fields']['Referentparcours.nom_complet'],
				'Titresejour.dftitsej'
			);
			$queryPersonne['fields'] = $fields;
			
			// Jointure spéciale adresse actuelle / département pour obtenir le nom du dpt
			$queryPersonne['fields'][] = 'Departement.name';
			$queryPersonne['joins'][] = array(
				'table' => 'departements',
				'alias' => 'Departement',
				'type' => 'LEFT OUTER',
				'conditions' => array(
					'SUBSTRING( Adresse.codepos FROM 1 FOR 2 ) = Departement.numdep'
				)
			);
			$queryPersonne['joins'][] = array(
				'table' => 'titressejour',
				'alias' => 'Titresejour',
				'type' => 'LEFT OUTER',
				'conditions' => array(
					'Titresejour.personne_id' => $personne_id
				)
			);
			
			$personne = $this->Cui->Personne->find('first', $queryPersonne);
			$personne['Foyer']['nb_enfants'] = $this->Cui->Personne->Prestation->getNbEnfants( $personne_id );

			$this->set( compact( 'options', 'personne_id', 'dossierMenu', 'urlmenu', 'personne' ) );
		}
		
		
		public function annule( $cui66_id ){
			$query = array(
				'fields' => array(
					'Cui.id',
					'Cui.personne_id'
				),
				'conditions' => array(
					'Cui66.id' => $cui66_id
				),
				'joins' => array(
					$this->Cui->join( 'Cui66' )
				)
			);
			$result = $this->Cui->find('first');
			
			$cui_id = $result['Cui']['id'];
			$personne_id = $result['Cui']['personne_id'];

			$dossierMenu = $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) );
			$this->Jetons2->get( $dossierMenu['Dossier']['id'] );
			
			// Retour à l'index en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossierMenu['Dossier']['id'] );
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			if( !empty( $this->request->data ) ) {
				$this->Cui->Cui66->begin();
				if( $this->Cui->Cui66->annule( $this->request->data, $this->Session->read( 'Auth.User.id' ) ) ) {
					$this->Cui->Cui66->commit();
					$this->Cui->Cui66->updatePositionsCuisById( $cui_id );
					$this->Jetons2->release( $dossierMenu['Dossier']['id'] );
					$this->Session->setFlash( 'Le CUI à été annulé.', 'flash/success' );
					$this->redirect( array( 'action' => 'index', $personne_id ) );
				}
				else {
					$this->Cui->Cui66->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			else {
				$this->request->data = array( 'Cui66' => array( 'id' => $cui66_id ) );
			}

			$urlmenu = "/cuis66/index/{$personne_id}";

			$this->set( compact( 'options', 'personne_id', 'dossierMenu', 'urlmenu' ) );
		}
		
		
		public function delete( $cui_id ){
			$this->Cui->begin();
			$success = $this->Cui->delete($cui_id);
			$this->_setFlashResult('Delete', $success);

			if ($success) {
				$this->Cui->commit();
			} else {
				$this->Cui->rollback();
			}
			$this->redirect($this->referer());
		}
		
		public function email_delete( $id ){			
			$this->Cui->Emailcui->begin();
			$success = $this->Cui->Emailcui->delete($id);
			$this->_setFlashResult('Delete', $success);

			if ($success) {
				$this->Cui->Emailcui->commit();
			} else {
				$this->Cui->Emailcui->rollback();
			}
			$this->redirect($this->referer());
		}
		
		public function notification( $cui66_id ){
			$this->Cui->Cui66->id = $cui66_id;
			$this->Cui->Cui66->saveField( 'notifie', 1 );
			$this->Session->setFlash( 'Le CUI à été notifié.');
			
			$result = $this->Cui->Cui66->find( 'first', array( 'fields' => 'cui_id', 'conditions' => array( 'Cui66.id' => $cui66_id ) ) );
			$cui_id = $result['Cui66']['cui_id'];
			$this->Cui->Cui66->updatePositionsCuisById( $cui_id );
			$this->redirect($this->referer());
		}
		
		/**
		 * Liste des Emails du CUI du bénéficiaire.
		 * 
		 * @param integer $personne_id
		 */
		public function email( $personne_id = null, $cui_id = null ) {
			if ( empty($personne_id) || empty($cui_id) || !is_numeric($personne_id) || !is_numeric($cui_id) ){
				throw new NotFoundException();
			}
			$dossierMenu = $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) );

			$this->_setEntriesAncienDossier( $personne_id, 'Cui' );
			
			$urlmenu = "/cuis66/index/{$personne_id}";

			$query = array(
				'conditions' => array(
					'Emailcui.cui_id' => $cui_id,
					'Emailcui.personne_id' => $personne_id
				),
				'order' => array( 'Emailcui.created DESC' )
			);
			$results = $this->Cui->Emailcui->find( 'all', $query );
			
			$messages = $this->Cui->Emailcui->messages( $personne_id );
			$addEnabled = $this->Cui->Emailcui->addEnabled( $messages );

			// Options
			$options = $this->Cui->Cui66->options( array( 'allocataire' => false, 'find' => false, 'autre' => false ) );

			$this->set( compact( 'results', 'dossierMenu', 'messages', 'addEnabled', 'personne_id', 'options', 'cui_id', 'urlmenu' ) );
		}
		
		/**
		 * Formulaire d'ajout d'un e-mail pour le CUI
		 *
		 * @param integer $personne_id L'id de la Personne à laquelle on veut ajouter un CUI
		 * @param integer $cui_id L'id du cui visé
		 */
		public function email_add( $personne_id, $cui_id ) {
			$args = func_get_args();
			call_user_func_array( array( $this, 'email_edit' ), $args );
		}
		
		/**
		 * Méthode générique d'ajout et de modification d'E-mail CUI
		 *
		 * @param integer $personne_id L'id de la personne
		 * @param integer $id L'id du cui visé (add) ou l'id du l'Emailcui
		 */
		public function email_edit( $personne_id = null, $id = null ) {
			if ( ($personne_id === null && $id === null) || ($this->action === 'email_add' && $id === null) || ($personne_id !== null && !is_numeric($personne_id)) || ($id !== null && !is_numeric($id)) ){
				throw new NotFoundException();
			}
			if( $this->action === 'email_add' ) {
				$cui_id = $id;
				$email_id = null;
			}
			else {
				$cui_id = isset( $this->request->data['Emailcui']['cui_id'] ) ? $this->request->data['Emailcui']['cui_id'] : null;
				$email_id = $id;
			}

			$dossierMenu = $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) );
			$this->Jetons2->get( $dossierMenu['Dossier']['id'] );
			
			// Retour à l'index en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossierMenu['Dossier']['id'] );
				$this->redirect( array( 'action' => 'email', $personne_id, $cui_id ) );
			}

			if( !empty( $this->request->data ) ) {
				$this->Cui->Emailcui->begin();
				if( $this->Cui->Emailcui->saveAddEdit( $this->request->data, $this->Session->read( 'Auth.User.id' ) ) ) { 
					$this->Cui->Emailcui->commit();
					$this->Jetons2->release( $dossierMenu['Dossier']['id'] );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'action' => 'email', $personne_id, $cui_id ) );
				}
				else {
					$this->Cui->Emailcui->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			else {
				$this->request->data = $this->Cui->Emailcui->prepareFormDataAddEdit( $personne_id, $cui_id, $email_id );
			}
			
			// Options
			$options = $this->Cui->Emailcui->options( array( 'allocataire' => false, 'find' => false, 'autre' => false ) );

			$urlmenu = "/cuis66/index/{$personne_id}";
			
			$this->set( compact( 'options', 'personne_id', 'dossierMenu', 'urlmenu', 'personne', 'mailEmployeur', 'correspondancesChamps', 'files' ) );
			$this->render( 'email_edit' );
		}
		
		public function email_view( $personne_id = null, $id = null ) {
			if ( ($personne_id === null && $id === null) || ($this->action === 'email_add' && $id === null) || ($personne_id !== null && !is_numeric($personne_id)) || ($id !== null && !is_numeric($id)) ){
				throw new NotFoundException();
			}
			$email_id = $id;
			unset( $id );

			$dossierMenu = $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) );
			
			$query = $this->Cui->Emailcui->queryView( $email_id );
			$result = $this->Cui->Emailcui->find( 'first', $query );
			$result['Emailcui']['pj'] = explode( '_', $result['Emailcui']['pj'] );
			$result['Emailcui']['piecesmanquantes'] = explode( '_', $result['Emailcui']['piecesmanquantes'] );
			$this->request->data = $result;
			
			// Options
			$options = $this->Cui->Emailcui->options( array( 'allocataire' => false, 'find' => false, 'autre' => false ) );

			$urlmenu = "/cuis66/index/{$personne_id}";
			
			$this->set( compact( 'options', 'personne_id', 'dossierMenu', 'urlmenu', 'personne', 'mailEmployeur', 'correspondancesChamps', 'files' ) );
		}
		
		public function email_send( $personne_id, $cui_id, $email_id ){
			$datas = $this->Cui->Emailcui->find('first', array( 'conditions' => array( 'Emailcui.id' => $email_id ) ) );
			$data = $datas['Emailcui'];
			
			$Piecemailcui66 = ClassRegistry::init( 'Piecemailcui66' );
			
			$filesIds = explode( '_', $data['pj'] );
			
			$filesNames = array();
			foreach( $filesIds as $fileId ){
				$filesNames = array_merge( $filesNames, $Piecemailcui66->getFichiersLiesById( $fileId ) );
			}
			
			$Email = new CakeEmail( $this->configEmail );
			if ( !empty($data['emailredacteur']) ){
				$Email->replyTo( $data['emailredacteur'] );
			}
			
			$Email	->subject( $data['titre'] )
					->attachments( $filesNames );
			
			// Si le mode debug est activé, on envoi l'e-mail à l'éméteur ( @see app/Config/email.php )
			if ( WebrsaEmailConfig::isTestEnvironment() ){
				$Email->to ( WebrsaEmailConfig::getValue( $this->configEmail, 'to', $Email->to() ) );
			}
			else{
				$Email->to( $data['emailemployeur'] );
			}
		
			$this->Cui->Emailcui->id = $email_id;
			$this->Cui->Emailcui->begin();
			try {
				if ( $Email->send( $data['message'] ) ){
					$this->Session->setFlash( 'E-mail envoyé avec succès', 'flash/success' );
					if ( !$this->Cui->Emailcui->saveField( 'dateenvoi', date('Y-m-d G:i:s') ) ){
						$this->Cui->Emailcui->rollback();
						$this->Session->setFlash( 'Sauvegarde en base impossible!', 'flash/error' );
					}
					$this->Cui->Emailcui->commit();
				}
				else{
					$this->Cui->Emailcui->rollback();
					throw new Exception( 'Envoi E-mail échoué!' );
				}
			}
			catch (Exception $e) {
				$this->Cui->Emailcui->rollback();
				$this->Session->setFlash( 'Erreur lors de l\'envoi de l\'E-mail.', 'flash/error' );
			}
			
			$this->Cui->Cui66->updatePositionsCuisById( $cui_id );
			$this->redirect( array( 'action' => 'email', $personne_id, $cui_id ) );
		}
		
		public function ajax_generate_email(){
			$query = array(
				'conditions' => array(
					'id' => $this->request->data['Emailcui_textmailcui66_id']
				)
			);
			$modelEmail = ClassRegistry::init( 'Textmailcui66' )->find( 'first', $query );
			
			$options = $this->Cui->Emailcui->options( array( 'allocataire' => false, 'find' => false, 'autre' => false ) );
			
			$text = $modelEmail['Textmailcui66']['contenu'];
			preg_match_all('/#([A-Z][a-z_0-9]+)\.([a-z_0-9]+)#/', $text, $matches);
			
			$erreurs = array();
			foreach( $this->request->data as $input => $data ){
				if ( $input === 'Emailcui_insertiondate' ){
					$formatedDate = strftime("%A %d %B %Y", strtotime($data));
					$text = str_replace( '#Emailcui.insersiondate#', $formatedDate, $text );
				}
				elseif ( $input === 'Emailcui_piecesmanquantes' ){
					$piecesmanquantes = explode( '_', $data);
					
					foreach ($piecesmanquantes as $num_piece => $id_piece){
						$piecesmanquantes[$num_piece] = isset($options['Piecemanquantecui66'][$id_piece]) ? $options['Piecemanquantecui66'][$id_piece] : '';
					}
					$text = str_replace( '#Emailcui.piecesmanquantes#', implode("\n", $piecesmanquantes), $text );
				}
				elseif ( preg_match( '/^Emailcui_[\w]+$/', $input ) ){
					$input = str_replace( '.id', '_id', str_replace( '_', '.', $input ) );
					$text = str_replace( '#' . $input . '#', $data, $text );
				}
			}
			
			$options = $this->Cui->Cui66->options( array( 'allocataire' => false, 'find' => false, 'autre' => false ) );
			
			foreach( $matches[1] as $key => $value ){
				$modelName = $value;
				$fieldName = $matches[2][$key];
			
				if ( $modelName !== 'Emailcui' && isset($this->request->data[ $modelName . '_id' ]) ){
					if ( !isset($$modelName) ){
						$$modelName = ClassRegistry::init( $modelName );
						$$modelName->id = $this->request->data[ $modelName . '_id' ];
					}

					$fieldValue = $$modelName->field( $fieldName );
					
					$isDate = preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])/", $fieldValue);
					
					if ( $isDate ){
						$tradFieldValue = strftime("%A %d %B %Y", strtotime($fieldValue));
					}
					else{
						$tradFieldValue = isset( $options[$modelName][$fieldName][$fieldValue] ) ? 
							$options[$modelName][$fieldName][$fieldValue] : 
							$fieldValue
						;
					}
					
					if ( empty($tradFieldValue) ){
						$erreurs[] = __d( 'cuis66', $modelName . '.' . $fieldName );
					}
					else{
						$text = str_replace( '#' . $modelName . '.' . $fieldName . '#', $tradFieldValue, $text );
					}
				}
			}
			
			// On retire les retours à la ligne en trop
			$text = preg_replace('/[\n\r]{3,}/', "\n\n", $text);
			
			if ( !empty($erreurs) ){
				$text = "[[[----------ERREURS----------]]]\n" . implode("\n", $erreurs) . "\n\nMerci de compléter les champs requis pour envoyer cet e-mail.";
			}
			
			$json = array(
				'EmailcuiTitre' => $modelEmail['Textmailcui66']['sujet'],
				'EmailcuiMessage' => $text
			);
			
			$this->set( compact( 'json' ) );
			$this->layout = 'ajax';
			$this->render( '/Elements/json' );
		}
	} // FIXME: Cui.findecontrat reste en base si on passe d'un cdd à un cdi
?>
