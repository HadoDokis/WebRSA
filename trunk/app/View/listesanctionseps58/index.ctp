<h1><?php echo $this->pageTitle = 'Liste des sanctions'; ?></h1>

	<ul class="actionMenu">
		<?php
			echo '<li>'.$xhtml->addLink(
				'Ajouter',
				array( 'controller' => 'listesanctionseps58', 'action' => 'add' )
			).' </li>';
		?>
	</ul>

	<?php if ( $sanctionsValides == false ) { ?>
		<p class="error">Attention il y a une erreur dans vos sanctions. Merci de la corriger pour que les EPs fonctionnent correctement.</p>
	<?php } ?>

	<?php if ( empty( $sanctions ) ) { ?>
		<p class="notice">Aucune sanction n'a encore été enregistrée.</p>
	<?php }
	else { ?>
		<table><thead>

		<?php
			echo $xhtml->tag(
				'tr',
				$xhtml->tag(
					'th',
					__d( 'listesanctionep58', 'Listesanctionep58.rang', true )
				).
				$xhtml->tag(
					'th',
					__d( 'listesanctionep58', 'Listesanctionep58.sanction', true )
				).
				$xhtml->tag(
					'th',
					__d( 'listesanctionep58', 'Listesanctionep58.duree', true )
				).
				$xhtml->tag(
					'th',
					'Actions',
					array( 'colspan' => 2 )
				)
			);
		?>
		</thead><tbody>

		<?php
			foreach( $sanctions as $sanction ) {
				echo $xhtml->tag(
					'tr',
					$xhtml->tag(
						'td',
						$sanction['Listesanctionep58']['rang']
					).
					$xhtml->tag(
						'td',
						$sanction['Listesanctionep58']['sanction']
					).
					$xhtml->tag(
						'td',
						$sanction['Listesanctionep58']['duree']
					).
					$xhtml->tag(
						'td',
						$xhtml->editLink( 'Modifier', array( 'controller' => 'listesanctionseps58', 'action' => 'edit', $sanction['Listesanctionep58']['id'] ), true )
					).
					$xhtml->tag(
						'td',
						$xhtml->deleteLink( 'Supprimer', array( 'controller' => 'listesanctionseps58', 'action' => 'delete', $sanction['Listesanctionep58']['id'] ), true )
					)
				);
			}
		?>
		</tbody></table>
	<?php }
	echo $default->button(
		'back',
		array(
			'controller' => 'parametrages',
			'action'     => 'index'
		),
		array(
			'id' => 'Back'
		)
	);
	?>