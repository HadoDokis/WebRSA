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
			<?php echo $form->input( 'Filtre.dureenonreorientation', array( 'label' => 'Contrat pour l\'orientation sociale terminé depuis', 'type' => 'select', 'options' => $nbmoisnonreorientation ) );?>
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
					<th>Passage en EP ?</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach( $cohorte as $key => $orientstruct ):?>
					<?php
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
							echo $xhtml->tag(
								'td',
								$form->input( 'Nonorientationproep.'.$key.'.orientstruct_id', array( 'type' => 'hidden', 'value' => $orientstruct['Orientstruct']['id'] ) ).
								$form->input( 'Nonorientationproep.'.$key.'.personne_id', array( 'type' => 'hidden', 'value' => $orientstruct['Personne']['id'] ) ).
								$form->input( 'Nonorientationproep.'.$key.'.user_id', array( 'type' => 'hidden', 'value' => $orientstruct['Orientstruct']['user_id'] ) ).
								$form->input( 'Nonorientationproep.'.$key.'.passageep', array( 'type' => 'checkbox', 'label' => false ) )
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
		<?php echo $form->button( 'Tout cocher', array( 'onclick' => 'toutCocher()' ) );?>
		<?php echo $form->button( 'Tout décocher', array( 'onclick' => 'toutDecocher()' ) );?>
	<?php endif;?>

<?php endif;?>