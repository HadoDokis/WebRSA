<?php 
echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<h1><?php echo $this->pageTitle = __d( 'defautinsertionep66', "{$this->name}::{$this->action}", true );?></h1>

<ul class="actionMenu">
	<?php
		echo '<li>'.$xhtml->link(
			$xhtml->image(
				'icons/application_form_magnify.png',
				array( 'alt' => '' )
			).' Formulaire',
			'#',
			array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Search' ).toggle(); return false;" )
		).'</li>';
	?>
</ul>

<?php echo $form->create( 'Defautinsertionep66', array( 'type' => 'post', 'action' => $this->action, 'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) ) ? 'folded' : 'unfolded' ) ) );?>
	<?php echo $form->input( 'Defautinsertionep66.recherche', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );?>
	<fieldset>
		<legend>Recherche par personne</legend>
		<?php echo $form->input( 'Personne.nom', array( 'label' => 'Nom ', 'type' => 'text' ) );?>
		<?php echo $form->input( 'Personne.prenom', array( 'label' => 'Prénom ', 'type' => 'text' ) );?>
		<?php echo $form->input( 'Personne.dtnai', array( 'label' => 'Date de naissance', 'type' => 'date', 'dateFormat' => 'DMY', 'minYear' => date( 'Y' ) - 80, 'maxYear' => date( 'Y' ), 'empty' => true ) );?>
		<?php echo $form->input( 'Personne.nir', array( 'label' => 'NIR', 'maxlength' => 15 ) );?>
		<?php if( $this->action == 'selectionradies' ):?>
			<?php echo $form->input( 'Historiqueetatpe.identifiantpe', array( 'label' => 'Identifiant Pôle Emploi', 'maxlength' => 15 ) );?>
		<?php endif;?>
		<?php echo $form->input( 'Dossier.matricule', array( 'label' => 'N° CAF', 'maxlength' => 15 ) );?>
		<?php echo $form->input( 'Adresse.locaadr', array( 'label' => 'Commune de l\'allocataire ', 'type' => 'text' ) );?>
		<?php echo $form->input( 'Adresse.numcomptt', array( 'label' => 'Numéro de commune au sens INSEE', 'type' => 'select', 'options' => $mesCodesInsee, 'empty' => true ) );?>

		<?php
			if( Configure::read( 'CG.cantons' ) ) {
				echo $form->input( 'Adresse.canton', array( 'label' => 'Canton', 'type' => 'select', 'options' => $cantons, 'empty' => true ) );
			}
		?>
		<?php echo $form->input( 'Orientstruct.date_valid', array( 'label' => 'Mois d\'orientation', 'type' => 'date', 'dateFormat' => 'MY', 'minYear' => date( 'Y' ) - 5, 'maxYear' => date( 'Y' ) + 1, 'empty' => true ) );?>
		
		<?php echo $search->etatdosrsa($etatdosrsa); ?>
	</fieldset>

	<div class="submit noprint">
		<?php echo $form->button( 'Rechercher', array( 'type' => 'submit' ) );?>
		<?php echo $form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
	</div>
<?php echo $form->end();?>

<?php if( isset( $personnes ) ):?>
<?php
	if ( is_array( $personnes ) && count( $personnes ) > 0 ) {
		echo $default2->index(
			$personnes,
			array(
				'Personne.nom',
				'Personne.prenom',
				'Personne.dtnai',
				'Orientstruct.date_valid',
				'Foyer.enerreur' => array( 'type' => 'string', 'class' => 'foyer_enerreur' ),
				'Situationdossierrsa.etatdosrsa'
			),
			array(
				'cohorte' => false,
				'paginate' => 'Personne',
				'actions' => array(
					'Orientsstructs::index' => array( 'label' => 'Voir', 'url' => array( 'controller' => 'bilansparcours66', 'action' => 'add', '#Personne.id#', 'Bilanparcours66__examenauditionpe:'.$actionbp ) )
				),
				'options' => array('Situationdossierrsa'=>array('etatdosrsa'=> $etatdosrsa))
			)
		);
	}
	else{
		echo $xhtml->tag( 'p', 'Aucun résultat ne correspond aux critères choisis.', array( 'class' => 'notice' ) );
	}
?>
<?php endif;?>