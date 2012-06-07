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

	if( Configure::read( 'debug' ) > 0 ) {
		if( Configure::read( 'Cg.departement' ) == 66 ) {
			echo $javascript->link( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
		}
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>

<?php echo $form->create( 'Filtre', array( 'url'=> Router::url( null, true ), 'id' => 'Filtre', 'class' => ( !empty( $this->data ) ? 'folded' : 'unfolded' ) ) );?>
	<fieldset>
		<?php  echo $xform->input( 'Filtre.index', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );?>

		<legend><?php  echo __d( 'nonorientationproep', 'Nonorientationsproseps'.Configure::read( 'Cg.departement' ).'::legend', true );?></legend>
		<?php if( Configure::read( 'Cg.departement' ) == 58 ):?>
		<?php
			$df_ci_from = Set::check( $this->data, 'Filtre.df_ci_from' ) ? Set::extract( $this->data, 'Filtre.df_ci_from' ) : strtotime( '-1 week' );
			$df_ci_to = Set::check( $this->data, 'Filtre.df_ci_to' ) ? Set::extract( $this->data, 'Filtre.df_ci_to' ) : strtotime( 'now' );
		?>
		<?php echo $form->input( 'Filtre.df_ci_from', array( 'label' => 'Le (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $df_ci_from ) );?>
		<?php echo $form->input( 'Filtre.df_ci_to', array( 'label' => 'Et le (exclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120,  'maxYear' => date( 'Y' ) + 5, 'selected' => $df_ci_to ) );?>
		<?php endif;?>

		<?php
			$nbmoispardefaut = array();
			if( Configure::read( 'Cg.departement' ) == 66 ) {
				echo $form->input( 'Canton.canton', array( 'label' => 'Canton', 'type' => 'select', 'options' => $cantons, 'empty' => true ) );
				echo $form->input( 'Adresse.locaadr', array( 'label' => 'Commune', 'type' => 'text' ) );
				echo $form->input( 'Filtre.structurereferente_id', array( 'label' => 'Structure référente', 'type' => 'select', 'options' => $structs, 'empty' => true ) );
				echo $form->input( 'Filtre.referent_id', array( 'label' => 'Référent', 'type' => 'select', 'options' => $referents, 'empty' => true ) );

			}
			else if( Configure::read( 'Cg.departement' ) == 93 ) {
				echo $form->input( 'Filtre.dureenonreorientation', array( 'label' => 'Parcours social sans réorientation emploi depuis ', 'type' => 'select', 'options' => $nbmoisnonreorientation ) );
			}
		?>
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
					<th><?php echo $xpaginator->sort( 'N° de dossier', 'Dossier.numdemrsa' );?></th>
					<th><?php echo $xpaginator->sort( 'Allocataire', 'Personne.nom' );?></th>
					<th><?php echo $xpaginator->sort( 'Date de naissance', 'Personne.dtnai' );?></th>
					<th><?php echo $xpaginator->sort( 'Code postal', 'Adresse.codepos' );?></th>
					<th><?php echo __d( 'foyer', 'Foyer.enerreur', true );?></th>
					<th><?php echo $xpaginator->sort( 'Date de validation de l\'orientation', 'Orientstruct.date_valid' );?></th>
					<th><?php echo $xpaginator->sort( 'Nombre de jours depuis la fin du contrat lié', 'Contratinsertion.nbjours' );?></th>
					<th><?php echo $xpaginator->sort( 'Type d\'orientation', 'Typeorient.lib_type_orient' );?></th>
					<th><?php echo $xpaginator->sort( 'Structure référente', 'Structurereferente.lib_struc' );?></th>
					<th><?php echo $xpaginator->sort( 'Référent', 'Referent.nom' );?></th>
					<?php if( Configure::read( 'Cg.departement' ) == 58 ):?>
						<th>Passage en COV ?</th>
					<?php endif;?>
					<?php if( Configure::read( 'Cg.departement' ) == 93 ):?>
						<th>Passage en EP ?</th>
					<?php endif;?>
					<?php if( Configure::read( 'Cg.departement' ) == 66 ):?>
						<th>Action</th>
					<?php endif;?>
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
							echo $xhtml->tag( 'td', $orientstruct['Typeorient']['lib_type_orient'] );
							echo $xhtml->tag( 'td', $orientstruct['Structurereferente']['lib_struc'] );
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
							if( /*Configure::read( 'Cg.departement' ) == 58 || */Configure::read( 'Cg.departement' ) == 93 ){
								echo $xhtml->tag(
									'td',
									$form->input( 'Nonorientationproep.'.$key.'.orientstruct_id', array( 'type' => 'hidden', 'value' => $orientstruct['Orientstruct']['id'] ) ).
									$form->input( 'Nonorientationproep.'.$key.'.personne_id', array( 'type' => 'hidden', 'value' => $orientstruct['Personne']['id'] ) ).
									$form->input( 'Nonorientationproep.'.$key.'.user_id', array( 'type' => 'hidden', 'value' => $orientstruct['Orientstruct']['user_id'] ) ).
									$form->input( 'Nonorientationproep.'.$key.'.passageep', array( 'class' => 'enabled passageep', 'type' => 'checkbox', 'label' => false ) )
								);
							}
							if( Configure::read( 'Cg.departement' ) == 66 ){
								echo $xhtml->tag(
									'td',
									$xhtml->viewLink(
										'Voir le dossier',
										array( 'controller' => 'rendezvous', 'action' => 'index', $orientstruct['Personne']['id'] ),
										true,
										true
									)
								);
							}
						echo "</tr>";
					?>
				<?php endforeach;?>
			</tbody>
		</table>
		<?php echo $pagination;?>
		<?php if( Configure::read( 'Cg.departement' ) != 66 ):?>
			<?php
			// Passage des champs du filtre lorsqu'on renvoie le formulaire du bas
			if( isset( $this->data['Filtre'] ) && is_array( $this->data['Filtre'] ) ) {
				foreach( Set::flatten( $this->data['Filtre'] ) as $hiddenfield => $hiddenvalue ) {
					echo '<div>'.$xform->input( "Filtre.$hiddenfield", array( 'type' => 'hidden', 'value' => $hiddenvalue, 'id' => 'FiltreBasDureenonreorientation' ) ).'</div>';
				}
			}
		?>
		<?php echo $form->end( 'Enregistrer' );?>
		<ul class="actionMenu">
			<li><?php
				echo $xhtml->exportLink(
					'Télécharger le tableau',
					array( 'controller' => 'nonorientationsproseps', 'action' => 'exportcsv', implode_assoc( '/', ':', array_unisize( $this->data ) ) )
				);
			?></li>
		</ul>

		<?php
			if( Configure::read( 'Cg.departement' ) == 58 ){
				echo $form->button( 'Tout cocher COV', array( 'onclick' => 'toutCocherCov(\'input[type="checkbox"].passagecov.enabled\', \'passagecov\', \'passageep\')' ) );
				echo $form->button( 'Tout décocher COV', array( 'onclick' => 'toutDecocherCov(\'input[type="checkbox"].passagecov.enabled\', \'passagecov\', \'passageep\')' ) );
			}
		?>
		<?php
			if( Configure::read( 'Cg.departement' ) != 58 ){
				echo $form->button( 'Tout cocher EP', array( 'onclick' => 'toutCocherCov(\'input[type="checkbox"].passageep.enabled\', \'passageep\', \'passagecov\')' ) );
				echo $form->button( 'Tout décocher EP', array( 'onclick' => 'toutDecocherCov(\'input[type="checkbox"].passageep.enabled\', \'passageep\', \'passagecov\')' ) );
			}
		?>
	<?php endif;?>
<?php endif;?>

<?php endif;?>

<?php if( Configure::read( 'Cg.departement' ) == 58 ):?>
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
<?php endif;?>
<?php if( Configure::read( 'Cg.departement' ) == 66 ):?>
<script type="text/javascript">
	document.observe("dom:loaded", function() {
            dependantSelect( 'FiltreReferentId', 'FiltreStructurereferenteId' );
            try { $( 'FiltreStructurereferenteId' ).onchange(); } catch(id) { }
	} );
</script>
<?php endif;?>