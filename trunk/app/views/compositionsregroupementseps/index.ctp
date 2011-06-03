<h1><?php echo $this->pageTitle = 'Composition de l\'Équipe pluridisciplinaire';?></h1>

<?php

	if ( $compteurs['Regroupementep'] == 0 ) {
		echo "<p class='error'>Merci d'ajouter au moins un regroupement avant d'en indiquer la composition.</p>";
	}
	elseif ( $compteurs['Fonctionmembreep'] == 0 ) {
		echo "<p class='error'>Merci d'ajouter au moins un membre avant d'en indiquer la composition.</p>";
	}
	else {
		echo '<table><thead>';
			echo $xhtml->tag(
				'tr',
				$xhtml->tag(
					'th',
					__d( 'regroupementep', 'Regroupementep.name', true )
				).
				$xhtml->tag(
					'th',
					'Actions',
					array(
						'class' => 'action'
					)
				)
			);
		echo '</thead><tbody>';
			foreach( $regroupementseps as $regroupementep ) {
				echo $xhtml->tag(
					'tr',
					$xhtml->tag(
						'td',
						$regroupementep['Regroupementep']['name']
					).
					$xhtml->tag(
						'td',
						$xhtml->editLink( 'Modifier', array( 'controller' => 'compositionsregroupementseps', 'action' => 'edit', $regroupementep['Regroupementep']['id'] ) )
					)
				);
			}
		echo '</table>';

		echo $default->button(
			'back',
			array(
				'controller' => 'gestionseps',
				'action'     => 'index'
			),
			array(
				'id' => 'Back'
			)
		);
	}

?>