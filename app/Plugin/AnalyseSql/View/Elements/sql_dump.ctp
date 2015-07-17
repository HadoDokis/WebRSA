<?php
/**
 * SQL Dump element.  Dumps out SQL log information
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.View.Elements
 * @since         CakePHP(tm) v 1.3
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
if (!class_exists('ConnectionManager') || Configure::read('debug') < 2) {
	return false;
}
$noLogs = !isset($logs);
if ($noLogs):
	$sources = ConnectionManager::sourceList();

	$logs = array();
	foreach ($sources as $source):
		$db = ConnectionManager::getDataSource($source);
		if (!method_exists($db, 'getLog')):
			continue;
		endif;
		$logs[$source] = $db->getLog();
	endforeach;
endif;

if ($noLogs || isset($_forced_from_dbo_)):
	foreach ($logs as $source => $logInfo):
		$text = $logInfo['count'] > 1 ? 'queries' : 'query';
		printf(
			'<table class="cake-sql-log" id="cakeSqlLog_%s" summary="Cake SQL Log" cellspacing="0">',
			preg_replace('/[^A-Za-z0-9_]/', '_', uniqid(time(), true))
		);
		printf('<caption>(%s) %s %s took %s ms</caption>', $source, $logInfo['count'], $text, $logInfo['time']);
	?>
	<thead>
		<tr><th>Nr</th><th>Query</th><th>Error</th><th>Affected</th><th>Num. rows</th><th>Took (ms)</th></tr>
	</thead>
	<tbody>
	<?php
		foreach ($logInfo['log'] as $k => $i) :
			$i += array('error' => '');
			if (!empty($i['params']) && is_array($i['params'])) {
				$bindParam = $bindType = null;
				if (preg_match('/.+ :.+/', $i['query'])) {
					$bindType = true;
				}
				foreach ($i['params'] as $bindKey => $bindVal) {
					if ($bindType === true) {
						$bindParam .= h($bindKey) ." => " . h($bindVal) . ", ";
					} else {
						$bindParam .= h($bindVal) . ", ";
					}
				}
				$i['query'] .= " , params[ " . rtrim($bindParam, ', ') . " ]";
			}
			$input = '<input type="hidden" id="sqlvalue_' . ($k + 1) . '" value="' . preg_replace("/[\t\n ]+/", " ", h($i['query'])) . '">';
			echo "<tr><td class=\"action\">" . ($k + 1) . "<a class=\"view\" href=\"#query" . ($k + 1) . "\" id=\"linkaction_" . ($k + 1) . "\">Analyse</a></td><td>" . h($i['query']) . $input . "<pre style=\"display:none;\" id=\"analyse_" . ($k + 1) . "\"></pre></td><td>{$i['error']}</td><td style = \"text-align: right\">{$i['affected']}</td><td style = \"text-align: right\">{$i['numRows']}</td><td style = \"text-align: right\">{$i['took']}</td></tr>\n";
		endforeach;
	?>
	</tbody></table>
	<?php
	endforeach;
else:
	echo '<p>Encountered unexpected $logs cannot generate SQL log</p>';
endif;
?>
<script>
	/**
	 * Contient le contenu des parenthèses retiré du rapport
	 * @type Array
	 */
	var brackets = [];
	
	/**
	 * Transforme un SELECT [0] FROM ... en SELECT (contenu de bracket[0]) FROM ...
	 * 
	 * @param {HTML} span Le <span> contenant un texte de type [0]
	 * @returns {void}
	 */
	function restoreBrackets( span ) {
		var innerText = span.target.innerHTML;
		if ( brackets[innerText.substr(1, innerText.length -2)] !== undefined ) {
			span.target.innerHTML = '('+ brackets[innerText.substr(1, innerText.length -2)] +')';
		}
	}
	
	/**
	 * Ajoute un évenement de clic au lien Analyse
	 */
	$$('table.cake-sql-log td.action a.view').each(function(link){
		link.observe('click', function(){
			var cutId = link.id.split('_'),
				sqlNumber = cutId[1],
				sql = $('sqlvalue_'+sqlNumber).value;
				
			/**
			 * On affiche le bloc <pre> et on y colle une image de charchement
			 */
			$('analyse_'+sqlNumber).style.display = 'block';
			$('analyse_'+sqlNumber).innerHTML = '<div class="center"><?php echo $this->Html->image('/img/ajax-loader_gray.gif');?></div>';
			link.remove();
			
			/**
			 * On demande à AnalysesqlsController de produire un rapport sur le contenu de sql
			 */
			new Ajax.Request('<?php echo Router::url( array( 'plugin' => 'analyse_sql', 'controller' => 'analysesqls', 'action' => 'ajax_analyse' ) ); ?>/', {
				asynchronous:true, 
				evalScripts:true, 
				parameters: {
					'sql': sql.replace("\t", "")
				}, 
				requestHeaders: {Accept: 'application/json'},
				/**
				 * En cas de succès, en rempli la variable brackets et on insert le rapport dans la balise <pre>
				 * On ajoute également un evenement au clic sur les span du rapport qui lance restoreBrackets()
				 * 
				 * @param {object} request
				 * @param {json} json
				 * @returns {void}
				 */
				onComplete:function(request, json) {
					brackets = json.innerBrackets;
					$('analyse_'+sqlNumber).innerHTML = json.text;
					$('analyse_'+sqlNumber).select('span').each(function(span){
						span.observe('click', restoreBrackets, span);
					});
				},
				
				/**
				 * Affiche d'un message d'érreur en cas de problème
				 * 
				 * @returns {void}
				 */
				onFailure:function() {
					$('analyse_'+sqlNumber).innerHTML = '<?php echo addslashes(__('onFailure'));?>';
				},
				onException:function() {
					$('analyse_'+sqlNumber).innerHTML = '<?php echo addslashes(__('onException'));?>';
				}
			});
		});
	});
</script>