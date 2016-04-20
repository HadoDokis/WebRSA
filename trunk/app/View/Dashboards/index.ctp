<?php
	echo $this->Default3->titleForLayout();
	
	function getActionLink($url, $controller, $action) {
		if (WebrsaPermissions::check($controller, $action)) {
			return '<a href="'.$url.'">Effectuer&nbsp;l\'action</a>';
		} else {
			return '<span class="disabled">Effectuer&nbsp;l\'action</a>';
		}
	}

	$route = array(
		'controller' => 'dashboards',
		'action' => 'reset_cache',
	);
	foreach ($roles as $role) {
		$resetUrl = preg_replace('/%3A/', ':', Router::url($route+array(Hash::get($role, 'Role.id'))));
		
		$reset = WebrsaPermissions::check('dashboards', 'reset_cache') 
			? '<a href="'.$resetUrl.'">Recalculer les nombres (très longue attente)</a>' 
			: ''
		;
		
		echo '<h3>'.Hash::get($role, 'Role.name').'</h3>
			'.$reset.'
			<table>
				<thead>
					<th>Intitulé de l\'action</th>
					<th>Description</th>
					<th>Nombre de résultats depuis le '.Hash::get($role, 'Role.date_count').'</th>
					<th>Action</th>
				</thead>
				<tbody>'
		;
		
		foreach ((array)Hash::get($role, 'Actionrole') as $key => $action) {
			$class = $key%2 === 0 ? 'odd' : 'even';
			echo '<tr class="'.$class.'">
					<td>'.Hash::get($action, 'name').'</td>
					<td>'.Hash::get($action, 'description').'</td>
					<td>'.Hash::get($action, 'count').'</td>
					<td>'.getActionLink(Hash::get($action, 'url'), Hash::get($action, 'controller'), Hash::get($action, 'action')).'</td>
				</tr>'
			;
		}
		
		echo '</tbody></table><br/><br/>';
	}
	