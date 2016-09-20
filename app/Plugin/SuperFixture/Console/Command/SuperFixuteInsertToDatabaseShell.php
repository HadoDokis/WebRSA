
<?php
	/**
	 * Code source de la classe SuperFixuteInsertToDatabaseShell.
	 *
	 * @package app.Console.Command
	 * @license Expression license is undefined on line 11, column 23 in Templates/CakePHP/CakePHP Shell.php.
	 */

	App::uses('XShell', 'Console/Command');
	
	/**
	 * La classe SuperFixuteInsertToDatabaseShell permet de peupler une base de donnée avec une SuperFixtures
	 * 
	 * Exemple de procédure (Note: aros et aros_acos peuplé par super fixtures):
	 * CREATE DATABASE "demo" WITH OWNER "webrsa" ENCODING 'UTF8';
	 * Export de la structure de la base 'trunk' et import de cette derniere dans 'demo'
	 * Modification du fichier app/Config/database.php -> 'database' => 'demo'
	 * sudo -u www-data lib/Cake/Console/cake SuperFixture.BakeSuperFixture ~/workspace/webrsa/app/Vendor/BakeSuperFixture/DossierBaker.php ~/workspace/webrsa/app/Test/SuperFixture/BaseTest.php
	 * sudo -u www-data lib/Cake/Console/cake SuperFixture.SuperFixuteInsertToDatabase ~/workspace/webrsa/app/Test/SuperFixture/BaseTest.php
	 * sudo -u www-data lib/Cake/Console/cake PermissionsDeveloppement
	 * 
	 * @package app.Console.Command
	 */
	class SuperFixuteInsertToDatabaseShell extends XShell
	{
		/**
		 * Tâches utilisées par ce shell.
		 *
		 * @var array
		 */
		public $tasks = array('ProgressBar');
		
		/**
		 * @var SuperFixture
		 */
		public $SuperFixture = null;
		
		/**
		 * Méthode principale.
		 */
		public function main() {
			$this->out();
			$this->out("\t\t<error>Attention</error>, cette action va écraser les données dans la base "
				. "'<warning>{$this->connection->config['database']}</warning>'.", 2);
			$in = $this->in("Voulez-vous continuer ?", array('o', 'n'));
			
			if ($in === 'n') {
				exit;
			}
			
			
			if (empty($this->args[0])) {
				$this->out();
				$this->out("Indiquez le chemin vers la super fixture à utiliser\n(note: vous pouvez utiliser le premier paramètre de la fonction pour indiquer le chemin)\nExemple:");
				$in = $this->in(TESTS.'SuperFixture'.DS);
			} else {
				$in = $this->args[0];
			}
			
			$this->_isSuperFixtureOrDie($in);
			$superFixtureDatas = $this->SuperFixture->getData();
			
			// Une super fixture peut être très lourde, les informations sont dans $superFixtureDatas donc on libère la mémoire
			unset($this->SuperFixture);
			
			// Nombre de lignes à insérer
			$count = 0;
			foreach ($superFixtureDatas as $datas) {
				$count += count($datas);
			}
			
			$this->connection->begin();
			
			$this->ProgressBar->start($count);
			
			foreach ($superFixtureDatas as $modelName => $datas) {
				$Model = ClassRegistry::init($modelName);
				$this->ProgressBar->next(
					$c = count($datas),
					"\t<success>".$modelName."</success> : inserting <info>".$c."</info> row".($c > 1 ? 's' : '')
				);
				
				// Recontruit la table
				$Model->deleteAll(array('1 = 1'));
				$Model->query("ALTER SEQUENCE {$Model->useTable}_{$Model->primaryKey}_seq RESTART WITH 1;");
				
				foreach ($datas as $data) {
					$Model->create($data);
					$Model->save(null, array('validate' => false, 'callbacks' => false)) or die;
				}
			}
			
			$this->out();
			$this->out('<success>'.$count." lignes ajouté avec succès</success>");
			$this->connection->commit();
		}
		
		/**
		 * Vérifi l'existance du fichier indiqué et sa conformité
		 * Place l'instance de la super fixture dans $this->SuperFixture
		 * 
		 * @param string $in
		 * @throws Exception
		 */
		protected function _isSuperFixtureOrDie($in) {
			if (!is_file($in) || is_dir($in) || !preg_match('/\\'.DS.'([^\\'.DS.']+)\.php/', $in, $match))  {
				throw new Exception("Aucun fichier n'a été trouvé sur '$in'");
			}
			
			require $in;

			if (class_exists($match[1])) {
				// Note : On doit instancier la SuperFixture pour vérifier ses interfaces, 
				// on aura besoin de cette instance plus tard
				$this->SuperFixture = new $match[1]();
				
				$interfaces = class_implements($this->SuperFixture);
				
				if (!in_array('SuperFixtureInterface', $interfaces)) {
					throw new Exception("La class {$match[1]} doit implémenter "
					. "'SuperFixtureInterface' pour être utilisable par ce shell");
				}
			} else {
				throw new Exception("Le nom de class doit correspondre au nom du fichier dans '$in'");
			}
		}
	}