<?php
	/**
	 * Code source de la classe GraphvizMpd2Shell.
	 *
	 * PHP 5.3
	 *
	 * @package Graphviz
	 * @subpackage Console.Command
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe GraphvizMpd2Shell ...
	 *
	 * @package Graphviz
	 * @subpackage Console.Command
	 */
	class GraphvizMpd2Shell extends AppShell
	{
		/**
		 * La constante à utiliser dans la méthode _stop() en cas de succès.
		 */
		const SUCCESS = 0;

		/**
		 * La constante à utiliser dans la méthode _stop() en cas d'erreur.
		 */
		const ERROR = 1;

		public $tasks = array(
			'Graphviz.GraphvizTables',
			'Graphviz.GraphvizRenderer'
		);

		public $options = array(
			'connection' => array(
				'short' => 'c',
				'help' => 'Le schéma à prendre en compte',
				'default' => 'default'
			),
			'fields' => array(
				'short' => 'f',
				'help' => 'Permet de spécifier si on veut la liste des champs ainsi que leur type',
				'choices' => array( 'true', 'false' ),
				'default' => 'false'
			),
			'tables' => array(
				'short' => 't',
				'help' => 'Permet de préciser, au moyen d\'une expression régulière, la liste des tables à prendre en compte.',
				'default' => null
			),
			'output' => array(
				'short' => 'o',
				'help' => 'Permet de spécifier le fichier de sortie',
				'default' => 'graphviz.dot'
			),
		);

		public function startup() {
			parent::startup();

			$this->params['fields'] = ( $this->params['fields'] === 'true' );
		}

		/**
		 * Méthode principale.
		 */
		public function main() {
			$summaries = $this->GraphvizTables->getSummaries();
			$this->GraphvizRenderer->render( $summaries );
			$this->_stop( self::SUCCESS );
		}

		/**
		 * Paramétrages et aides du shell.
		 */
		public function getOptionParser() {
			$Parser = parent::getOptionParser();

			$Parser->addOptions( $this->options );

			return $Parser;
		}
	}
?>