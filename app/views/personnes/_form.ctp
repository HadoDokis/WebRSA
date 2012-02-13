<fieldset>
	<legend>État civil</legend>
		<?php
			echo $form->input( 'Personne.qual', array( 'label' => required( __d( 'personne', 'Personne.qual', true ) ), 'type' => 'select', 'options' => $qual, 'empty' => true ) );
			echo $form->input( 'Personne.nom', array( 'label' => required( __d( 'personne', 'Personne.nom', true ) ) ) );
			echo $form->input( 'Personne.nomnai', array( 'label' => __d( 'personne', 'Personne.nomnai', true ) ) );
			echo $form->input( 'Personne.prenom', array( 'label' => required( __d( 'personne', 'Personne.prenom', true ) ) ) );
			echo $form->input( 'Personne.prenom2', array( 'label' => __d( 'personne', 'Personne.prenom2', true ) ) );
			echo $form->input( 'Personne.prenom3', array( 'label' => __d( 'personne', 'Personne.prenom3', true ) ) );
			echo $form->input( 'Personne.typedtnai', array( 'label' => __d( 'personne', 'Personne.typedtnai', true ), 'type' => 'select', 'options' => $typedtnai, 'empty' => true ) );
			echo $form->input( 'Personne.dtnai', array( 'label' => required( __d( 'personne', 'Personne.dtnai', true ) ), 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 1, 'minYear' => ( date( 'Y' ) - 100 ), 'empty' => true ) );
			echo $form->input( 'Personne.nomcomnai', array( 'label' => __d( 'personne', 'Personne.nomcomnai', true ) ) );
			echo $form->input( 'Personne.rgnai', array( 'label' => __d( 'personne', 'Personne.rgnai', true ), 'maxlength' => 2) );
			echo $form->input( 'Personne.nir', array( 'label' =>  __d( 'personne', 'Personne.nir', true ) ) );
			if( $this->action != 'wizard' ){
				echo $default->view(
					$personne,
					array(
						'Foyer.sitfam' => array( 'options' => $sitfam ),
					),
					array(
						'widget' => 'table',
						'id' => 'dossierInfosOrganisme'
					)
				);
			}
			echo $form->input( 'Personne.topvalec', array( 'label' => __d( 'personne', 'Personne.topvalec', true ) ) );
			echo $form->input( 'Personne.numfixe', array( 'label' => __d( 'personne', 'Personne.numfixe', true ) ) );
			echo $form->input( 'Personne.numport', array( 'label' => __d( 'personne', 'Personne.numport', true ) ) );
		?>
</fieldset>

<fieldset>
	<legend>Nationalité</legend>
	<?php echo $form->input( 'Personne.nati', array( 'label' => __d( 'personne', 'Personne.nati', true ), 'type' => 'select', 'options' => $nationalite, 'empty' => true ) );?>
	<?php echo $form->input( 'Personne.dtnati', array( 'label' => __d( 'personne', 'Personne.dtnati', true ), 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => ( date( 'Y' ) - 100 ), 'empty' => true ) );?>
	<?php echo $form->input( 'Personne.pieecpres', array( 'label' => __d( 'personne', 'Personne.pieecpres', true ), 'type' => 'select', 'options' => $pieecpres, 'empty' => true ) );?>
</fieldset>

<script type="text/javascript">
	document.observe( "dom:loaded", function() {
		observeDisableFieldsOnValue( 'PersonneQual', [ 'PersonneNomnai' ], 'MME', false );
		observeDisableFieldsOnValue( 'PrestationRolepers', [ 'PersonneQual' ], 'ENF', true );

		$( 'PersonneTypedtnai' ).observe( 'change', function( event ) {
			var type = $F( 'PersonneTypedtnai' );
			if( type == 'J' ) {
				$( 'PersonneDtnaiDay' ).value = '01';
			}
			else if( type == 'O' ) {
				$( 'PersonneDtnaiDay' ).value = '31';
				$( 'PersonneDtnaiMonth' ).value = '12';
			}
		});
	} );
</script>