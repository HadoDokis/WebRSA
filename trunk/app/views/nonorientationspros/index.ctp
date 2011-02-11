<h1><?php echo $this->pageTitle = __d( 'nonorientationpro', 'Nonorientationspros::index', true ); ?></h1>

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
		<legend>Durée de non orientation depuis le parcours social ou socioprofessionel vers le parcours professionnel</legend>
		<?php echo $form->input( 'Filtre.dureenonreorientation', array( 'label' => 'Contrat pour l\'orientation sociale terminé depuis', 'type' => 'select', 'options' => $nbmoisnonreorientation ) );?>
	</fieldset>
	<div class="submit">
		<?php echo $form->button( 'Filtrer', array( 'type' => 'submit' ) );?>
		<?php echo $form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
	</div>
<?php echo $form->end();?>

<?php if( !empty( $this->data ) ):?>
	<?php if( empty( $cohorte ) ):?>
		<p class="notice"><?php echo 'Aucun allocataire ne correspond à vos critères de recherche.';?>
	<?php else: ?>
		<?php echo $form->create( 'Nonorientationpro', array( 'url'=> Router::url( null, true ) ) ); ?>
		<table class="tooltips">
			<thead>
				<tr>
					<th>N° de dossier</th>
					<th>Allocataire</th>
					<th>Date de naissance</th>
					<th>Code postal</th>
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
							echo $xhtml->tag( 'td', $locale->date( __( 'Locale->date', true ), $orientstruct['Orientstruct']['date_valid'] ) );
							echo $xhtml->tag( 'td', $orientstruct['Contratinsertion']['nbjours'] );
							echo $xhtml->tag( 'td', $orientstruct['Structurereferente']['lib_struc'] );
							echo $xhtml->tag( 'td', $orientstruct['Typeorient']['lib_type_orient'] );
							echo $xhtml->tag( 'td', implode( ' ', array( $orientstruct['Referent']['qual'], $orientstruct['Referent']['nom'], $orientstruct['Referent']['prenom'] ) ) );
							echo $xhtml->tag(
								'td',
								$form->input( 'Nonorientationpro.'.$key.'.orientstruct_id', array( 'type' => 'hidden', 'value' => $orientstruct['Orientstruct']['id'] ) ).
								$form->input( 'Nonorientationpro.'.$key.'.personne_id', array( 'type' => 'hidden', 'value' => $orientstruct['Personne']['id'] ) ).
								$form->input( 'Nonorientationpro.'.$key.'.passageep', array( 'type' => 'checkbox', 'label' => false ) )
							);
						echo "</tr>";
					?>
				<?php endforeach;?>
			</tbody>
		</table>
		<?php echo $form->end( 'Valider' );?>

	<?php endif;?>
<?php endif;?>