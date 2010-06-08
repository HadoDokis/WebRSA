<?php echo $html->tag( 'h1', $this->pageTitle ); ?>

<ul class="actionMenu">
	<?php
		echo '<li>'.$default->button(
			'add',
			array(
				'controller' => 'seanceseps',
				'action' => 'add'
			),
			array( 'enabled' => $permissions->check( 'seanceseps', 'add' ) )
		).'</li>';

		if( is_array( $this->data ) ) {
			echo '<li>'.$html->link(
				$html->image(
					'icons/application_form_magnify.png',
					array( 'alt' => '' )
				).' Formulaire',
				'#',
				array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Search' ).toggle(); return false;" )
			).'</li>';
		}
	?>
</ul>

<?php
	echo $default->search(
		array(
			'Seanceep.ep_id',
			'Seanceep.dateseance' => array( 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 2, 'minYear' => date( 'Y' ) - 2 ),
			'Seanceep.structurereferente_id',
		),
		array(
			'options' => $options,
			'id' => 'Search',
			'add' => false
		)
	);

	if( isset( $seanceseps ) ) {
		echo $default->index(
			$seanceseps,
			array(
				'Ep.name',
				'Structurereferente.lib_struc',
				'Seanceep.dateseance' => array( 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 2, 'minYear' => date( 'Y' ) - 2 ),
				'Seanceep.demandesreorient' => array( 'options' => $options ),
			),
			array(
				'actions' => array(
	// 				'Seanceep.view',
					'Seanceep.edit',
// 					'Seanceep.delete',
					'Seanceep.ordre',
					'Seanceep.equipe',
				)
			)
		);
	}

//     echo $default->button(
//         'back',
//         array(
//             'controller' => 'eps',
//             'action'     => 'indexparams'
//         ),
//         array(
//             'id' => 'Back'
//         )
//     );
?>