<h1><?php echo $this->pageTitle = __d( 'nonorientationproep', 'Nonorientationsproseps'.Configure::read( 'Cg.departement' ).'::index', true ); ?></h1>

<?php

	if( !empty( $this->data ) ) {
		echo '<ul class="actionMenu"><li>'.$xhtml->link(
			$xhtml->image(
				'icons/application_form_magnify.png',
				array( 'alt' => '' )
			).' Formulaire',
			'#',
			array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Filtre' ).toggle(); return false;" )
		).'</li></ul>';
	}

?>

<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<script type="text/javascript">
	function togglePassageCovEp( checkbox, cbClass, otherCbClass ) {
		var otherCbName = $( checkbox ).readAttribute( 'name' ).replace( cbClass, otherCbClass );
		var otherInputSelector = 'input[name="' + otherCbName + '"]';
		if( $( checkbox ).checked ) {
			$$( otherInputSelector ).each( function ( elmt ) { $( elmt ).removeClassName( 'enabled' ); $( elmt ).disable(); } );
		}
		else {
			$$( otherInputSelector ).each( function ( elmt ) { $( elmt ).addClassName( 'enabled' ); $( elmt ).enable(); } );
		}
	}

	function toutCocherCov( selecteur, cbClass, otherCbClass ) {
		if( selecteur == undefined ) {
			selecteur = 'input[type="checkbox"]';
		}

		$$( selecteur ).each( function( checkbox ) {
			$( checkbox ).checked = true;
			togglePassageCovEp( checkbox, cbClass, otherCbClass );
		} );
	}

	function toutDecocherCov( selecteur, cbClass, otherCbClass ) {
		if( selecteur == undefined ) {
			selecteur = 'input[type="checkbox"]';
		}

		$$( selecteur ).each( function( checkbox ) {
			$( checkbox ).checked = false;
			togglePassageCovEp( checkbox, cbClass, otherCbClass );
		} );
	}
</script>

<?php echo $form->create( 'Filtre', array( 'url'=> Router::url( null, true ), 'id' => 'Filtre', 'class' => ( !empty( $this->data ) ? 'folded' : 'unfolded' ) ) );?>
	<fieldset>
		<legend><?php  echo __d( 'nonorientationproep', 'Nonorientationsproseps'.Configure::read( 'Cg.departement' ).'::legend', true );?></legend>
		<?php if( Configure::read( 'Cg.departement' ) == 58 ):?>
		<?php
			$df_ci_from = Set::check( $this->data, 'Filtre.df_ci_from' ) ? Set::extract( $this->data, 'Filtre.df_ci_from' ) : strtotime( '-1 week' );
			$df_ci_to = Set::check( $this->data, 'Filtre.df_ci_to' ) ? Set::extract( $this->data, 'Filtre.df_ci_to' ) : strtotime( 'now' );
		?>
		<?php echo $form->input( 'Filtre.df_ci_from', array( 'label' => 'Le (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $df_ci_from ) );?>
		<?php echo $form->input( 'Filtre.df_ci_to', array( 'label' => 'Et le (exclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120,  'maxYear' => date( 'Y' ) + 5, 'selected' => $df_ci_to ) );?>
		<?php else:?>
			<?php echo $form->input( 'Filtre.dureenonreorientation', array( 'label' => 'Parcours social sans réorientation emploi depuis ', 'type' => 'select', 'options' => $nbmoisnonreorientation ) );?>
		<?php endif;?>
	</fieldset>
	<div class="submit">
		<?php echo $form->button( 'Filtrer', array( 'type' => 'submit' ) );?>
		<?php echo $form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
	</div>
<?php echo $form->end();?>
<?php $pagination = $xpaginator->paginationBlock( 'Orientstruct', $this->passedArgs ); ?>

<?php if( !empty( $this->data ) ):?>
	<?php if( empty( $cohorte ) ):?>
		<p class="notice"><?php echo 'Aucun allocataire ne correspond à vos critères de recherche.';?>
	<?php else: ?>
		<?php echo $form->create( 'Nonorientationproep', array( 'url'=> Router::url( null, true ) ) ); ?>
		<?php echo $pagination;?>
		<table class="tooltips">
			<thead>
				<tr>
					<th>N° de dossier</th>
					<th>Allocataire</th>
					<th>Date de naissance</th>
					<th>Code postal</th>
					<th><?php echo __d( 'foyer', 'Foyer.enerreur', true );?></th>
					<th>Date de validation de l'orientation</th>
					<th>Nombre de jours depuis la fin du contrat lié</th>
					<th>Type d'orientation</th>
					<th>Structure</th>
					<th>Référent</th>
					<?php if( Configure::read( 'Cg.departement' ) == 58 ):?>
						<th>Passage en COV ?</th>
					<?php endif;?>
					<th>Passage en EP ?</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach( $cohorte as $key => $orientstruct ):?>
					<?php
// debug($orientstruct);
						// FIXME: date ouverture de droits -> voir flux instruction
						echo "<tr>";
							echo $xhtml->tag( 'td', $orientstruct['Dossier']['numdemrsa'] );
							echo $xhtml->tag( 'td', implode( ' ', array( $orientstruct['Personne']['qual'], $orientstruct['Personne']['nom'], $orientstruct['Personne']['prenom'] ) ) );
							echo $xhtml->tag( 'td', $locale->date( __( 'Locale->date', true ), $orientstruct['Personne']['dtnai'] ) );
							echo $xhtml->tag( 'td', $orientstruct['Adresse']['codepos'] );
							echo $xhtml->tag( 'td', $orientstruct['Foyer']['enerreur'], array( 'class' => 'foyer_enerreur '.( empty( $orientstruct['Foyer']['enerreur'] ) ? 'empty' : null ) ) );
							echo $xhtml->tag( 'td', $locale->date( __( 'Locale->date', true ), $orientstruct['Orientstruct']['date_valid'] ) );
							echo $xhtml->tag( 'td', $orientstruct['Contratinsertion']['nbjours'] );
							echo $xhtml->tag( 'td', $orientstruct['Structurereferente']['lib_struc'] );
							echo $xhtml->tag( 'td', $orientstruct['Typeorient']['lib_type_orient'] );
							echo $xhtml->tag( 'td', implode( ' ', array( $orientstruct['Referent']['qual'], $orientstruct['Referent']['nom'], $orientstruct['Referent']['prenom'] ) ) );
							if( Configure::read( 'Cg.departement' ) == 58 ){
								echo $xhtml->tag(
									'td',
									$form->input( 'Nonorientationproep.'.$key.'.orientstruct_id', array( 'type' => 'hidden', 'value' => $orientstruct['Orientstruct']['id'] ) ).
									$form->input( 'Nonorientationproep.'.$key.'.typeorient_id', array( 'type' => 'hidden', 'value' => $orientstruct['Typeorient']['id'] ) ).
									$form->input( 'Nonorientationproep.'.$key.'.structurereferente_id', array( 'type' => 'hidden', 'value' => $orientstruct['Structurereferente']['id'] ) ).
									$form->input( 'Nonorientationproep.'.$key.'.personne_id', array( 'type' => 'hidden', 'value' => $orientstruct['Personne']['id'] ) ).
									$form->input( 'Nonorientationproep.'.$key.'.user_id', array( 'type' => 'hidden', 'value' => $orientstruct['Orientstruct']['user_id'] ) ).
									$form->input( 'Nonorientationproep.'.$key.'.passagecov', array( 'class' => 'enabled passagecov', 'type' => 'checkbox', 'label' => false ) )
								);
							}
							echo $xhtml->tag(
								'td',
								$form->input( 'Nonorientationproep.'.$key.'.orientstruct_id', array( 'type' => 'hidden', 'value' => $orientstruct['Orientstruct']['id'] ) ).
								$form->input( 'Nonorientationproep.'.$key.'.personne_id', array( 'type' => 'hidden', 'value' => $orientstruct['Personne']['id'] ) ).
								$form->input( 'Nonorientationproep.'.$key.'.user_id', array( 'type' => 'hidden', 'value' => $orientstruct['Orientstruct']['user_id'] ) ).
								$form->input( 'Nonorientationproep.'.$key.'.passageep', array( 'class' => 'enabled passageep', 'type' => 'checkbox', 'label' => false ) )
							);
						echo "</tr>";
					?>
				<?php endforeach;?>
			</tbody>
		</table>
		<?php echo $pagination;?>
		<?php
			// Passage des champs du filtre lorsqu'on renvoie le formulaire du bas
			if( isset( $this->data['Filtre'] ) && is_array( $this->data['Filtre'] ) ) {
				foreach( Set::flatten( $this->data['Filtre'] ) as $hiddenfield => $hiddenvalue ) {
					echo '<div>'.$xform->input( "Filtre.$hiddenfield", array( 'type' => 'hidden', 'value' => $hiddenvalue, 'id' => 'FiltreBasDureenonreorientation' ) ).'</div>';
				}
			}
		?>
		<?php echo $form->end( 'Enregistrer' );?>
		<?php
			echo $form->button( 'Tout cocher COV', array( 'onclick' => 'toutCocherCov(\'input[type="checkbox"].passagecov.enabled\', \'passagecov\', \'passageep\')' ) );
			echo $form->button( 'Tout décocher COV', array( 'onclick' => 'toutDecocherCov(\'input[type="checkbox"].passagecov.enabled\', \'passagecov\', \'passageep\')' ) );
		?>
		<?php
			echo $form->button( 'Tout cocher EP', array( 'onclick' => 'toutCocherCov(\'input[type="checkbox"].passageep.enabled\', \'passageep\', \'passagecov\')' ) );
			echo $form->button( 'Tout décocher EP', array( 'onclick' => 'toutDecocherCov(\'input[type="checkbox"].passageep.enabled\', \'passageep\', \'passagecov\')' ) );
		?>
	<?php endif;?>

<?php endif;?>

<script type="text/javascript">
	$$( 'input[type="checkbox"].passagecov' ).each( function( checkbox ) {
		$( checkbox ).observe( 'change', function() {
			togglePassageCovEp( $(this), 'passagecov', 'passageep' );
		} );
	} );

	$$( 'input[type="checkbox"].passageep' ).each( function( checkbox ) {
		$( checkbox ).observe( 'change', function() {
			togglePassageCovEp( $(this), 'passageep', 'passagecov' );
		} );
	} );
</script>