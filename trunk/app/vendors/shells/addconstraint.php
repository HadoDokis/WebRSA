<?php

	App::import(array('Model', 'AppModel'));
	
	class AddconstraintShell extends Shell{
		
		function main() {
			if ($this->args && $this->args[0] == '?') {
				return $this->out('Usage: ./cake addcascade <table> [-all]');
			}
			
			$options = array(
				'all' => false,
			);
			
			foreach ($this->params as $key => $val) {
				foreach ($options as $name => $option) {
					if (isset($this->params[$name]) || isset($this->params['-'.$name]) || isset($this->params[$name{0}])) {
						$options[$name] = true;
					}
				}
			}
			
			Configure::write( 'debug', 0 );
			
			$db = ConnectionManager::getDataSource('default');
			if ($options['all']) {
				$this->args = $db->listSources();
			}
			
			if (empty($this->args)) {
				return $this->err('Usage: ./cake addcascade <table>');
			}
			
			$tables = $db->listSources();
			
			foreach ($this->args as $table) {
				$name = Inflector::classify($table);
				$Model = new AppModel(array(
					'name' => $name,
					'table' => $table,
				));
				
				$fields = $Model->query("SELECT column_name FROM information_schema.columns WHERE table_name = '{$table}' AND column_name LIKE '%_id';");
				
				$this->out( sprintf( '-- Ajout des contraintes pour la table %s.', $table ) );
				
				foreach( $fields as $field ) {
					$fieldName = $field[0]['column_name'];
					$parentTable = Inflector::tableize( substr( $fieldName, 0, -3 ) );
					$this->out( "ALTER TABLE {$table} DROP CONSTRAINT {$table}_{$fieldName}_fkey;" );
// 					$Model->query( "ALTER TABLE {$table} DROP CONSTRAINT {$table}_{$fieldName}_fkey;" );
					if( !$Model->_schema[$fieldName]['null'] ) {
						$this->out( "ALTER TABLE {$table} ADD CONSTRAINT {$table}_{$fieldName}_fkey FOREIGN KEY ({$fieldName}) REFERENCES {$parentTable}(id) ON DELETE CASCADE ON UPDATE CASCADE;" );
// 						$Model->query( "ALTER TABLE {$table} ADD CONSTRAINT {$table}_{$fieldName}_fkey FOREIGN KEY ({$fieldName}) REFERENCES {$parentTable}(id) ON DELETE CASCADE ON UPDATE CASCADE;" );
					}
					else {
						$this->out( "ALTER TABLE {$table} ADD CONSTRAINT {$table}_{$fieldName}_fkey FOREIGN KEY ({$fieldName}) REFERENCES {$parentTable}(id) ON DELETE SET NULL ON UPDATE CASCADE;" );
// 						$Model->query( "ALTER TABLE {$table} ADD CONSTRAINT {$table}_{$fieldName}_fkey FOREIGN KEY ({$fieldName}) REFERENCES {$parentTable}(id) ON DELETE SET NULL ON UPDATE CASCADE;" );
					}
				}
			}
		}
	}

?>
