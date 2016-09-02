<?php
	/**
	 * Code source de la classe Fichedeliaisons.
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses('AppController', 'Controller');

	/**
	 * La classe Fichedeliaisons ...
	 *
	 * @package app.Controller
	 */
	class FichedeliaisonsController extends AppController
	{
		/**
		 * Nom du contrôleur.
		 *
		 * @var string
		 */
		public $name = 'Fichedeliaisons';

		/**
		 * Components utilisés.
		 *
		 * @var array
		 */
		public $components = array(
			'Default',
			'DossiersMenus',
			'Fileuploader',
			'Gedooo.Gedooo',
			'Jetons2',
			'Search.SearchPrg' => array(
				'actions' => array('search')
			),
		);

		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array(
			'Cake1xLegacy.Ajax',
			'Csv',
			'Default',
			'Default2',
			'Default3' => array(
				'className' => 'ConfigurableQuery.ConfigurableQueryDefault'
			),
			'Fileuploader',
			'Locale',
			'Xform',
		);

		/**
		 * Modèles utilisés.
		 *
		 * @var array
		 */
		public $uses = array(
			'Fichedeliaison',
			'Primoanalyse',
		);
		
		/**
		 * Utilise les droits d'un autre Controller:action
		 * sur une action en particulier
		 * 
		 * @var array
		 */
		public $commeDroit = array(
			
		);
		
		/**
		 * Méthodes ne nécessitant aucun droit.
		 *
		 * @var array
		 */
		public $aucunDroit = array(
			'ajaxfiledelete',
			'ajaxfileupload',
		);
		
		/**
		 * Correspondances entre les méthodes publiques correspondant à des
		 * actions accessibles par URL et le type d'action CRUD.
		 *
		 * @var array
		 */
		public $crudMap = array(
			'add' => 'create',
			'ajaxfiledelete' => 'delete',
			'ajaxfileupload' => 'create',
			'avis' => 'read',
			'delete' => 'delete',
			'download' => 'read',
			'edit' => 'update',
			'filelink' => 'read',
			'fileview' => 'read',
			'index' => 'read',
			'indexparams' => 'read',
			'validation' => 'update',
			'view' => 'read',
		);
		
		/**
		 * Pagination sur la table.
		 * 
		 * @param integer $foyer_id
		 */
		public function index($foyer_id) {
			$this->set('dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu(array('foyer_id' => $foyer_id)));
			$this->set('fichedeliaisons', $this->Fichedeliaison->find('all', $this->Fichedeliaison->getIndexQuery($foyer_id)));
			$this->set('primoanalyses', $this->Primoanalyse->find('all', $this->Primoanalyse->getIndexQuery($foyer_id)));
			$this->set('foyer_id', $foyer_id);
			$this->_setOptions();
		}

		/**
		 * Formulaire d'ajout.
		 * 
		 * @param integer $foyer_id
		 */
		public function add($foyer_id) {
			$this->_edit($foyer_id);
			$this->view = 'edit';
		}
		
		/**
		 * Méthode générique pour add et edit
		 * 
		 * @param integer $foyer_id
		 */
		protected function _edit($foyer_id) {
			$dossierMenu = $this->DossiersMenus->getAndCheckDossierMenu(array('foyer_id' => $foyer_id));
			$dossier_id = $dossierMenu['Dossier']['id'];
			$this->set('dossierMenu', $dossierMenu);
			$this->set('urlMenu', '/Fichedeliaisons/index/#Foyer_id#');
			$this->set('concerne', $this->Fichedeliaison->FichedeliaisonPersonne->optionsConcerne($foyer_id));
			$this->_setOptions();
			
			$this->Jetons2->get($dossier_id);
			
			if (!empty($this->request->data)) {
				if (isset($this->request->data['Cancel'])) {
					$this->Jetons2->release($dossier_id);
					$this->redirect(array('action' => 'index', $foyer_id));
				}
				
				$data = $this->request->data;
				$data['Fichedeliaison']['user_id'] = $this->Session->read('Auth.User.id');
				$data['Fichedeliaison']['foyer_id'] = $foyer_id;
				
				$this->Fichedeliaison->begin();
				$this->Fichedeliaison->create($data['Fichedeliaison']);
				$success = $this->Fichedeliaison->save();
				
				$fichedeliaison_id = $this->Fichedeliaison->id;
				
				if ($success) {
					// On reconstruit les liens entre Fichedeliaison et Personne
					$this->Fichedeliaison->FichedeliaisonPersonne->deleteAllUnbound(array('fichedeliaison_id' => $fichedeliaison_id));
					foreach ((array)Hash::get($data, 'FichedeliaisonPersonne.personne_id') as $personne_id) {
						$insert = array(
							'personne_id' => $personne_id,
							'fichedeliaison_id' => $fichedeliaison_id,
						);
						$this->Fichedeliaison->FichedeliaisonPersonne->create($insert);
						$success = $this->Fichedeliaison->FichedeliaisonPersonne->save() && $success;
					}
				}
				
				if ($success) {
					$this->Fichedeliaison->commit();
					$this->Fichedeliaison->updatePositionsById($fichedeliaison_id);
					$this->Jetons2->release($dossier_id);
					$this->Session->setFlash('Enregistrement effectué', 'flash/success');
					$this->redirect(array('action' => 'index', $foyer_id));
				}
				else {
					$this->Fichedeliaison->rollback();
					$this->Session->setFlash('Erreur lors de l\'enregistrement', 'flash/error');
				}
			}
		}

		/**
		 * Formulaire de modification.
		 * 
		 * @param integer $fichedeliaison_id
		 */
		public function edit($fichedeliaison_id) {
			$foyer_id = $this->Fichedeliaison->foyerId($fichedeliaison_id);
			$this->_edit($foyer_id);
			$this->request->data = $this->Fichedeliaison->find('first', array('conditions' => array('id' => $fichedeliaison_id)));
			$this->request->data['FichedeliaisonPersonne']['personne_id'] = 
				Hash::extract(
					$this->Fichedeliaison->FichedeliaisonPersonne->find('all', 
						array('conditions' => array('fichedeliaison_id' => $fichedeliaison_id))
					), 
					'{n}.FichedeliaisonPersonne.personne_id'
				)
			;
		}

		/**
		 * Visualisation
		 * 
		 * @param integer $fichedeliaison_id
		 */
		public function view($fichedeliaison_id) {
			$foyer_id = $this->Fichedeliaison->foyerId($fichedeliaison_id);
			$dossierMenu = $this->DossiersMenus->getAndCheckDossierMenu(array('foyer_id' => $foyer_id));
			
			$this->set('dossierMenu', $dossierMenu);
			$this->set('urlMenu', '/Fichedeliaisons/index/#Foyer_id#');
			$this->set('foyer_id', $foyer_id);
			$this->set('concerne', $this->Fichedeliaison->FichedeliaisonPersonne->optionsConcerne($foyer_id));
			$this->_setOptions();
			
			$this->request->data = $this->Fichedeliaison->Avistechniquefiche->prepareFormDataAvis($fichedeliaison_id);
			$this->request->data['FichedeliaisonPersonne']['personne_id'] = 
				Hash::extract(
					$this->Fichedeliaison->FichedeliaisonPersonne->find('all', 
						array('conditions' => array('fichedeliaison_id' => $fichedeliaison_id))
					), 
					'{n}.FichedeliaisonPersonne.personne_id'
				)
			;
		}
		
		/**
		 * Formulaire d'avis technique
		 * 
		 * @param integer $fichedeliaison_id
		 */
		public function avis($fichedeliaison_id) {
			$this->_avis($fichedeliaison_id);
		}
		
		/**
		 * Formulaire de validation
		 * 
		 * @param integer $fichedeliaison_id
		 */
		public function validation($fichedeliaison_id) {
			$this->_avis($fichedeliaison_id);
		}
		
		/**
		 * Fonction générique pour l'avis technique et la validation
		 * 
		 * @param integer $fichedeliaison_id
		 */
		protected function _avis($fichedeliaison_id) {
			$foyer_id = $this->Fichedeliaison->foyerId($fichedeliaison_id);
			$dossierMenu = $this->DossiersMenus->getAndCheckDossierMenu(array('foyer_id' => $foyer_id));
			$dossier_id = $dossierMenu['Dossier']['id'];
			$this->set('dossierMenu', $dossierMenu);
			$this->set('urlMenu', '/Fichedeliaisons/index/#Foyer_id#');
			$this->set('concerne', $this->Fichedeliaison->FichedeliaisonPersonne->optionsConcerne($foyer_id));
			$this->_setOptions();
			$saveAlias = $this->action === 'avis' ? 'Avistechniquefiche' : 'Validationfiche';
			
			$this->Jetons2->get($dossier_id);
			if (!empty($this->request->data)) {
				if (isset($this->request->data['Cancel'])) {
					$this->Jetons2->release($dossier_id);
					$this->redirect(array('action' => 'index', $foyer_id));
				}
				
				$data = $this->request->data;
				$data[$saveAlias]['user_id'] = $this->Session->read('Auth.User.id');
				$data[$saveAlias]['fichedeliaison_id'] = $fichedeliaison_id;
				$data[$saveAlias]['etape'] = $this->action;
				
				$this->Fichedeliaison->begin();
				$this->Fichedeliaison->Avistechniquefiche->create($data[$saveAlias]);
				
				$success = $this->Fichedeliaison->Avistechniquefiche->save();
				
				if ($success) {
					$this->Fichedeliaison->commit();
					$etat = current($this->Fichedeliaison->updatePositionsById($fichedeliaison_id));
					
					if ($etat === 'decisionvalid') {
						$this->_createPrimoanalyse($fichedeliaison_id);
					}
					
					$this->Jetons2->release($dossier_id);
					$this->Session->setFlash('Enregistrement effectué', 'flash/success');
					$this->redirect(array('action' => 'index', $foyer_id));
				}
				else {
					$this->Fichedeliaison->rollback();
					$this->Session->setFlash('Erreur lors de l\'enregistrement', 'flash/error');
				}
			}
			
			$this->request->data = Hash::merge(
				$this->Fichedeliaison->Avistechniquefiche->prepareFormDataAvis($fichedeliaison_id),
				$this->request->data
			);
		}

		/**
		 * Suppression et redirection vers l'index.
		 *
		 * @param integer $fichedeliaison_id
		 */
		public function delete($fichedeliaison_id) {
			$foyer_id = $this->Fichedeliaison->foyerId($fichedeliaison_id);
			
			$this->Fichedeliaison->Avistechniquefiche->deleteAllUnBound(array('fichedeliaison_id' => $fichedeliaison_id));
			$this->Fichedeliaison->FichedeliaisonPersonne->deleteAllUnBound(array('fichedeliaison_id' => $fichedeliaison_id));
			$this->Fichedeliaison->deleteAllUnBound(array('id' => $fichedeliaison_id));
			
			$this->Session->setFlash('Suppression effectué', 'flash/success');
			$this->redirect(array('action' => 'index', $foyer_id));
		}
		
		/**
		 * Envoi d'un fichier temporaire depuis le formualaire.
		 */
		public function ajaxfileupload() {
			$this->Fileuploader->ajaxfileupload();
		}

		/**
		 * Suppression d'un fichier temporaire.
		 */
		public function ajaxfiledelete() {
			$this->Fileuploader->ajaxfiledelete();
		}

		/**
		 * Visualisation d'un fichier temporaire.
		 *
		 * @param integer $id
		 */
		public function fileview($id) {
			$this->Fileuploader->fileview($id);
		}

		/**
		 * Visualisation d'un fichier stocké.
		 *
		 * @param integer $id
		 */
		public function download($id) {
			$this->Fileuploader->download($id);
		}

		/**
		 * Liste des fichiers liés à une orientation.
		 *
		 * @param integer $fichedeliaison_id
		 */
		public function filelink($fichedeliaison_id) {
			$foyer_id = $this->Fichedeliaison->foyerId($fichedeliaison_id);
			$this->set('dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu(array('foyer_id' => $foyer_id)));
			
			$this->Fileuploader->filelink($fichedeliaison_id, array('action' => 'index', $foyer_id));
			$this->set('urlmenu', "/fichedeliaisons/index/{$foyer_id}");
			$this->set('options', array());
		}
		
		/**
		 * Options
		 */
		protected function _setOptions() {
			$options = array();
			$actif = array('conditions' => array('actif' => 1));
			
			$options['Fichedeliaison'] = array(
				'motiffichedeliaison_id' => $this->Fichedeliaison->Motiffichedeliaison->find('list'),
				'actif_motiffichedeliaison_id' => $this->Fichedeliaison->Motiffichedeliaison->find('list', $actif),
				'expediteur_id' => $this->Fichedeliaison->Expediteur->find('list'),
			);
			$options['Fichedeliaison']['destinataire_id'] = $options['Fichedeliaison']['expediteur_id'];
			
			$gestionnaires = $this->User->find(
                'all',
                array(
                    'fields' => array(
                        'User.nom_complet',
                        'User.id',
                  ),
                    'conditions' => array(
                        'User.isgestionnaire' => 'O'
                  ),
                    'joins' => array(
                        $this->User->join('Poledossierpcg66', array('type' => 'INNER')),
                  ),
                    'order' => array('User.nom ASC', 'User.prenom ASC'),
                    'contain' => false
              )
			);
			
            $options['Primoanalyse'] = array(
				'user_id' => Hash::combine($gestionnaires, '{n}.User.id', '{n}.User.nom_complet'),
				'propositionprimo_id' => $this->Primoanalyse->Propositionprimo->find('list'),
			);
			
			$options = Hash::merge(
				$options,
				$this->Fichedeliaison->enums(),
				$this->Fichedeliaison->Avistechniquefiche->enums(),
				$this->Fichedeliaison->Validationfiche->enums(),
				$this->Primoanalyse->enums()
			);
			
			$this->set('options', $options);
			return $options;
		}
		
		/**
		 * Parametrages liés
		 */
		public function indexparams(){
			
		}
		
		/**
		 * Permet la création de la primoanalyse d'une fiche de liaison
		 * 
		 * @param integer $fichedeliaison_id
		 */
		protected function _createPrimoanalyse($fichedeliaison_id) {
			$havePrimoanalyse = $this->Primoanalyse->find('first', 
				array('conditions' => array('fichedeliaison_id' => $fichedeliaison_id))
			);
			
			if (empty($havePrimoanalyse)) {
				$this->Primoanalyse->create(array('fichedeliaison_id' => $fichedeliaison_id, 'etat' => 'attaffect'));
				return $this->Primoanalyse->save();
			}
		}
	}
?>
