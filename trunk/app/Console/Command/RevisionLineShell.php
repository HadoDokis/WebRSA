<?php
	/**
	 * Fichier source de la classe RevisionLineShell.
	 *
	 * PHP 5.3
	 *
	 * @package app.Console.Command
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	App::uses('XShell', 'Console/Command');

	/**
	 * La classe RevisionLineShell permet d'obtenir les informations sur une ligne en particulier
	 * Permet d'obtenir la révision, l'auteur et la date de dernière modification
	 *
	 * @package app.Console.Command
	 */
	class RevisionLineShell extends XShell
	{
		public function main() {
			if (!isset($this->args[0])){
				$this->args[0] = $this->in('Indiquez le fichier (ex: app/Model/AppModel.php)');
			}
			
			if (!file_exists($this->args[0])) {
				$this->out("Le fichier n'a pas été trouvé!");
				exit;
			}
			
			if (!isset($this->args[1])){
				$this->args[1] = $this->in('Indiquez la ligne');
			}
			
			if (!is_numeric($this->args[1])) {
				$this->out("Vous devez indiquer un numero de ligne !");
				exit;
			}
			
			exec('svn blame -v -x -b svn://scm.adullact.net/svn/webrsa/trunk/'.$this->args[0], $output);
			preg_match('/([\d]+)[\s]+([\w]+).+\(([\w.]+ [\d]+ [\S]+ [\d]+)\)(.*)/', $output[$this->args[1] -1], $matches);
			list(, $revision, $author, $date, $line) = $matches;
			
			$this->out();
			$this->out(sprintf("RevisionLine %s %d", $this->args[0], $this->args[1]));
			$this->out();
			$this->out(sprintf("Révision: %d", $revision));
			$this->out(sprintf("Auteur: %s", $author));
			$this->out(sprintf("Date: %s", $date));
			$this->out();
			$this->out(sprintf("Content:\n%s", $line));
			$this->out();
			$this->out(sprintf("https://adullact.net/scm/browser.php?group_id=613&commit=%s", $revision));
		}
	}