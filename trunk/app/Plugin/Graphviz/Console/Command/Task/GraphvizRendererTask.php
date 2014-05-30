<?php
	/**
	 * Code source de la classe GraphvizRendererTask.
	 *
	 * PHP 5.3
	 *
	 * @package Graphviz
	 * @subpackage Console.Command.Task
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe GraphvizRendererTask ...
	 *
	 * @package Graphviz
	 * @subpackage Console.Command.Task
	 */
	class GraphvizRendererTask extends AppShell
	{
		public $tables = array();

		public function renderTable( $summary ) {
			$label = $summary['Table']['name'];

			if( !empty( $this->params['fields'] ) ) {
				$fields = array();
				foreach( $summary['Table']['fields'] as $fieldName => $fieldType ) {
					$fields[] = "{$fieldName}: {$fieldType}";
				}
				$label = '{'.$label.'|'.implode( '\l', $fields ).'\l}';
			}

			return "\t\"{$summary['Table']['name']}\" [label=\"{$label}\", shape=record];\n";
		}

		public function renderAssociation( $summary ) {
			$return = '';

			foreach( $summary['Table']['relations'] as $relation ) {
				if( in_array( $relation['To']['table'], $this->tables ) ) {
					$taillabel = $headlabel = null;
					$return .= "\t\"{$summary['Table']['name']}\" -> \"{$relation['To']['table']}\" [dir=\"forward\", taillabel=\"{$taillabel}\", headlabel=\"{$headlabel}\", label=\"{$relation['Foreignkey']['name']}\", arrowhead=\"normal\"];\n";
				}
			}

			return $return;
		}

		public function render( array $summaries ) {
			$this->tables = Hash::extract( $summaries, '{n}.Table.name' );
			$content = '';

			foreach( $summaries as $summary ) {
				$content .= $this->renderTable( $summary );
			}

			foreach( $summaries as $summary ) {
				$content .= $this->renderAssociation( $summary );
			}

			$content = "digraph G {\n{$content}}";

			return $this->createFile( $this->params['output'], $content );
		}
	}
?>