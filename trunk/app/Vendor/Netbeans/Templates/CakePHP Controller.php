<#include "freemarker_functions.ftl">
<?php
	/**
	 * Code source de la classe ${class_name(name)?replace("Controller$", "","r")}Controller.
	 *
<#if php_version??>
	 * PHP ${php_version}
	 *
</#if>
<#if cake_branch = "1">
	 * @package app.controllers
<#else>
	 * @package app.Controller
</#if>
	 * @license ${license}
	 */
<#if cake_branch = "2">
	App::uses('AppController', 'Controller');
</#if>

	/**
	 * Classe ${class_name(name)?replace("Controller$", "","r")}Controller.
	 *
<#if cake_branch = "1">
	 * @package app.controllers
<#else>
	 * @package app.Controller
</#if>
	 */
	class ${class_name(name)?replace("Controller$", "","r")}Controller extends AppController
	{
		/**
		 * Nom
		 *
		 * @var string
		 */
		public $name = '${class_name(name)?replace("Controller$", "","r")}';

		/**
		 * Components utilisés.
		 *
		 * @var array
		 */
		public $components = array();

		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array();

		/**
		 * Modèles utilisés.
		 *
		 * @var array
		 */
		public $uses = array();

		/**
		 * Pagination sur les <élément>s de la table.
		 *
		 * @return void
		 */
		public function index() {
			$this->paginate = array(
				$this->modelClass => array(
					'limit' => 10
				)
			);

			$varname = Inflector::tableize( $this->modelClass );
			$this->set( $varname, $this->paginate() );
		}

		/**
		 * Formulaire d'ajout d'un élémént.
		 *
		 * @return void
		 */
		public function add() {
			$args = func_get_args();
			call_user_func_array( array( $this, 'edit' ), $args );
		}

		/**
		 * Formulaire de modification d'un <élément>.
		 *
		 * @return void
		 * @throws BadRequestException
		 */
		public function edit( $id = null ) {
			if( !empty( $this-><#if cake_branch = "2">request-></#if>data ) ) {

				$this->{$this->modelClass}->begin();
				$this->{$this->modelClass}->create( $this-><#if cake_branch = "2">request-></#if>data );

				if( $this->{$this->modelClass}->save() ) {
					$this->{$this->modelClass}->commit();
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'action' => 'index' ) );
				}
				else {
					$this->{$this->modelClass}->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			else if( $this->action == 'edit' ) {
				$this-><#if cake_branch = "2">request-></#if>data = $this->{$this->modelClass}->find(
					'first',
					array(
						'conditions' => array(
							"{$this->modelClass}.id" => $id
						),
						'contain' => false
					)
				);

				if( empty( $this-><#if cake_branch = "2">request-></#if>data  ) ) {
<#if cake_branch = "1">
					$this->cakeError( 'error404' );
<#else>
					throw new BadRequestException();
</#if>
				}
			}

<#if cake_branch = "1">
			$this->render( $this->action, null, 'edit' );
<#else>
			$this->render( 'edit' );
</#if>
		}

		/**
		 * Suppression d'un <élément> et redirection vers l'index.
		 *
		 * @param integer $id
		 */
		public function delete( $id ) {
			$this->{$this->modelClass}->begin();

			if( $this->{$this->modelClass}->delete( $id ) ) {
				$this->{$this->modelClass}->commit();
				$this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
			}
			else {
				$this->{$this->modelClass}->rollback();
				$this->Session->setFlash( 'Erreur lors de la suppression', 'flash/error' );
			}

			$this->redirect( array( 'action' => 'index' ) );
		}
	}
?>
