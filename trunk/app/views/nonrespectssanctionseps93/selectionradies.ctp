<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<h1><?php echo $this->pageTitle = __d( 'nonrespectsanctionep93', "{$this->name}::{$this->action}", true );?></h1>

<?php
	echo '<ul class="actionMenu"><li>'.$xhtml->link(
		$xhtml->image(
			'icons/application_form_magnify.png',
			array( 'alt' => '' )
		).' Formulaire',
		'#',
		array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "var form = $$( 'form' ); form = form[0]; $( form ).toggle(); return false;" )
	).'</li></ul>';
?>

<?php echo $xform->create( 'Nonrespectsanctionep93', array( 'type' => 'post', 'action' => $this->action, 'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) ) ? 'folded' : 'unfolded' ) ) );?>
	<fieldset>
			<?php echo $xform->input( 'Search.active', array( 'type' => 'hidden', 'value' => true ) );?>

			<legend>Filtrer par Personne</legend>
			<?php
				echo $default2->subform(
					array(
						'Search.Personne.nom' => array( 'label' => __d( 'personne', 'Personne.nom', true ), 'type' => 'text' ),
						'Search.Personne.prenom' => array( 'label' => __d( 'personne', 'Personne.prenom', true ), 'type' => 'text' ),
						'Search.Personne.nomnai' => array( 'label' => __d( 'personne', 'Personne.nomnai', true ), 'type' => 'text' ),
						'Search.Personne.nir' => array( 'label' => __d( 'personne', 'Personne.nir', true ), 'type' => 'text', 'maxlength' => 15 ),
						'Search.Dossier.matricule' => array( 'label' => __d( 'dossier', 'Dossier.matricule', true ), 'type' => 'text', 'maxlength' => 15 ),
						'Search.Dossier.numdemrsa' => array( 'label' => __d( 'dossier', 'Dossier.numdemrsa', true ), 'type' => 'text', 'maxlength' => 15 ),
						'Search.Adresse.locaadr' => array( 'label' => 'Commune de l\'allocataire ', 'type' => 'text' ),
						'Search.Adresse.numcomptt' => array( 'label' => 'Numéro de commune au sens INSEE ', 'type' => 'select', 'options' => $mesCodesInsee, 'empty' => true )
					)
				);
			?>
		</fieldset>

	<div class="submit noprint">
		<?php echo $form->button( 'Rechercher', array( 'type' => 'submit' ) );?>
		<?php echo $form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
	</div>
<?php echo $form->end();?>

<?php if( isset( $radiespe ) ):?>
<?php
	if ( is_array( $radiespe ) && count( $radiespe ) > 0 ) {

		echo $default2->index(
			$radiespe,
			array(
				'Personne.nom',
				'Personne.prenom',
				'Personne.dtnai',
				'Adresse.locaadr',
				'Historiqueetatpe.date',
				'Typeorient.lib_type_orient',
				'Contratinsertion.present' => array( 'type' => 'boolean' ),
				'Foyer.enerreur' => array( 'type' => 'string', 'class' => 'foyer_enerreur' ),
				'Historiqueetatpe.chosen' => array( 'input' => 'checkbox', 'type' => 'boolean', 'domain' => 'nonrespectsanctionep93', 'sort' => false ),
			),
			array(
				'cohorte' => true,
				'hidden' => array(
					'Personne.id',
					'Historiqueetatpe.id'
				),
				'paginate' => 'Personne',
				'domain' => 'nonrespectsanctionep93',
				'labelcohorte' => 'Enregistrer'
			)
		);
	}
	else {
		echo $xhtml->tag( 'p', 'Aucun résultat ne correspond aux critères choisis.', array( 'class' => 'notice' ) );
	}
?>
<?php endif;?>
<?php if( !empty( $radiespe ) ):?>
	<?php echo $form->button( 'Tout cocher', array( 'onclick' => 'toutCocher()' ) );?>
	<?php echo $form->button( 'Tout décocher', array( 'onclick' => 'toutDecocher()' ) );?>
<?php endif;?>