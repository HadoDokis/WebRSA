<#include "freemarker_functions.ftl">
<?php
	/**
	 * Code source de la classe ${class_name(name)}.
	 *
<#if php_version??>
	 * PHP ${php_version}
	 *
</#if>
<#if cake_branch = "1">
	 * @package app.controllers.components
<#else>
	 * @package app.Controller.Component
</#if>
	 * @license ${license}
	 */

	/**
	 * Classe ${class_name(name)}.
	 *
<#if cake_branch = "1">
	 * @package app.controllers.components
<#else>
	 * @package app.Controller.Component
</#if>
	 */
	class ${class_name(name)?replace("Component$", "","r")}Component extends Component
	{
		/**
		 * Contrôleur utilisant ce component.
		 *
		 * @var Controller
		 */
		public $Controller = null;

		/**
		 * Paramètres de ce component
		 *
		 * @var array
		 */
		public $settings = array( );

		/**
		 * Components utilisés par ce component.
		 *
		 * @var array
		 */
		public $components = array( );

<#if cake_branch = "1">
		/**
		 * Appelée avant Controller::beforeFilter().
		 *
		 * @param object $controller Controller with components to initialize
		 * @return void
		 * @access public
		 * @link http://book.cakephp.org/view/65/MVC-Class-Access-Within-Components
		 */
		public function initialize( &$controller ) {
			$this->Controller = $controller;
		}
<#else>
		/**
		 * Appelée avant Controller::beforeFilter().
		 *
		 * @param Controller $controller Controller with components to initialize
		 * @return void
		 * @link http://book.cakephp.org/2.0/en/controllers/components.html#Component::initialize
		 */
		public function initialize( Controller $controller ) {
			$this->Controller = $controller;
		}
</#if>

<#if cake_branch = "1">
		/**
		 * Called after the Controller::beforeFilter() and before the controller action
		 *
		 * @param object $controller Controller with components to startup
		 * @return void
		 * @access public
		 * @link http://book.cakephp.org/view/65/MVC-Class-Access-Within-Components
		 */
		public function startup( &$controller ) {
		}
<#else>
		/**
		 * Called after the Controller::beforeFilter() and before the controller action
		 *
		 * @param Controller $controller Controller with components to startup
		 * @return void
		 * @link http://book.cakephp.org/2.0/en/controllers/components.html#Component::startup
		 */
		public function startup( Controller $controller ) {
		}
</#if>

<#if cake_branch = "1">
		/**
		 * Called after the Controller::beforeRender(), after the view class is loaded, and before the
		 * Controller::render()
		 *
		 * @param object $controller Controller with components to beforeRender
		 * @return void
		 * @access public
		 */
		public function beforeRender(&$controller) {
		}
<#else>
		/**
		 * Called before the Controller::beforeRender(), and before
		 * the view class is loaded, and before Controller::render()
		 *
		 * @param Controller $controller Controller with components to beforeRender
		 * @return void
		 * @link http://book.cakephp.org/2.0/en/controllers/components.html#Component::beforeRender
		 */
		public function beforeRender( Controller $controller ) {
		}
</#if>

<#if cake_branch = "1">
		/**
		 * Called after Controller::render() and before the output is printed to the browser.
		 *
		 * @param object $controller Controller with components to shutdown
		 * @return void
		 * @access public
		 */
		public function shutdown(&$controller) {
		}
<#else>
		/**
		 * Called after Controller::render() and before the output is printed to the browser.
		 *
		 * @param Controller $controller Controller with components to shutdown
		 * @return void
		 * @link @link http://book.cakephp.org/2.0/en/controllers/components.html#Component::shutdown
		 */
		public function shutdown( Controller $controller ) {
		}
</#if>

<#if cake_branch = "1">
		/**
		 * Called before Controller::redirect().
		 *
		 * @param object $controller Controller with components to beforeRedirect
		 * @return void
		 * @access public
		 */
		public function beforeRedirect(&$controller, $url, $status = null, $exit = true) {
		}
<#else>
		/**
		 * Called before Controller::redirect().  Allows you to replace the url that will
		 * be redirected to with a new url. The return of this method can either be an array or a string.
		 *
		 * If the return is an array and contains a 'url' key.  You may also supply the following:
		 *
		 * - `status` The status code for the redirect
		 * - `exit` Whether or not the redirect should exit.
		 *
		 * If your response is a string or an array that does not contain a 'url' key it will
		 * be used as the new url to redirect to.
		 *
		 * @param Controller $controller Controller with components to beforeRedirect
		 * @param string|array $url Either the string or url array that is being redirected to.
		 * @param integer $status The status code of the redirect
		 * @param boolean $exit Will the script exit.
		 * @return array|null Either an array or null.
		 * @link @link http://book.cakephp.org/2.0/en/controllers/components.html#Component::beforeRedirect
		 */
		public function beforeRedirect( Controller $controller, $url, $status = null, $exit = true ) {

		}
</#if>
	}
?>