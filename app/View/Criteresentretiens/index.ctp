<?php
    $this->pageTitle = 'Recherche par Entretiens';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
	}
?>

<h1><?php echo $this->pageTitle;?></h1>

<?php
    echo '<ul class="actionMenu"><li>'.$this->Xhtml->link(
        $this->Xhtml->image(
            'icons/application_form_magnify.png',
            array( 'alt' => '' )
        ).' Formulaire',
        '#',
        array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Search' ).toggle(); return false;" )
    ).'</li></ul>';
?>
<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}
?>

<?php echo $this->Xform->create( 'Critereentretien', array( 'type' => 'post', 'action' => $this->action,  'id' => 'Search', 'class' => ( isset( $results ) ? 'folded' : 'unfolded' ) ) );?>
	<?php echo $this->Xform->input( 'Critereentretien.index', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );?>
	<?php
		echo $this->Search->blocAllocataire();
		echo $this->Search->blocAdresse(
			(array)Hash::get( $options, 'Adresse.numcom' ),
			(array)Hash::get( $options, 'Canton.canton' )
		);
	?>
	<fieldset>
		<legend>Recherche par dossier</legend>
		<?php
			echo $this->Form->input( 'Dossier.numdemrsa', array( 'label' => 'Numéro de demande RSA' ) );
			echo $this->Form->input( 'Dossier.matricule', array( 'label' => __d( 'dossier', 'Dossier.matricule' ), 'maxlength' => 15 ) );

			$valueDossierDernier = isset( $this->request->data['Dossier']['dernier'] ) ? $this->request->data['Dossier']['dernier'] : true;
			echo $this->Form->input( 'Dossier.dernier', array( 'label' => 'Uniquement la dernière demande RSA pour un même allocataire', 'type' => 'checkbox', 'checked' => $valueDossierDernier ) );
			echo $this->Search->etatdosrsa($etatdosrsa);
		?>
	</fieldset>
	<fieldset>
		<legend>Filtrer par Entretiens</legend>
		<?php
			$valueDossierDernier = isset( $this->request->data['Dossier']['dernier'] ) ? $this->request->data['Dossier']['dernier'] : true;
			echo $this->Default2->subform(
				array(
					'Entretien.arevoirle' => array( 'label' => __d( 'entretien', 'Entretien.arevoirle' ), 'type' => 'date', 'dateFormat' => 'MY', 'empty' => true, 'minYear' => date( 'Y' ) - 2, 'maxYear' => date( 'Y' ) + 2 ),
					'Entretien.structurereferente_id' => array( 'label' => __d( 'entretien', 'Entretien.structurereferente_id' ), 'empty' => true, 'options' => $options['PersonneReferent']['structurereferente_id'] ),
					'Entretien.referent_id' => array( 'label' => __d( 'entretien', 'Entretien.referent_id' ), 'empty' => true, 'options' => $options['PersonneReferent']['referent_id']  ),
					'Entretien.dateentretien' => array( 'type' => 'checkbox' )
				),
				array(
					'options' => $options
				)
			);

			echo $this->Html->tag(
					'fieldset',
					$this->Html->tag( 'legend', __d( 'entretien', 'Entretien.dateentretien_checkbox' ) )
					.$this->Default2->subform(
					array(
						'Entretien.dateentretien_from' => array( 'label' => __d( 'entretien', 'Entretien.dateentretien_from' ), 'empty' => true, 'type' => 'date', 'dateFormat' => 'DMY', 'minYear' => 2009, 'maxYear' => date('Y')+1 ),
						'Entretien.dateentretien_to' => array( 'label' => __d( 'entretien', 'Entretien.dateentretien_to' ), 'empty' => true, 'type' => 'date', 'dateFormat' => 'DMY', 'minYear' => 2009, 'maxYear' => date('Y')+1 ),
					),
					array(
						'options' => $options
					)
				)
			);
		?>
	</fieldset>

	<?php
		echo $this->Search->referentParcours(
			$options['PersonneReferent']['structurereferente_id'],
			$options['PersonneReferent']['referent_id']
		);
		echo $this->Search->paginationNombretotal();
	?>

    <div class="submit noprint">
        <?php echo $this->Xform->button( 'Rechercher', array( 'type' => 'submit' ) );?>
        <?php echo $this->Xform->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
    </div>

<?php
	echo $this->Xform->end();
	echo $this->Search->observeDisableFormOnSubmit( 'Search' );
?>

<script type="text/javascript">
	document.observe( "dom:loaded", function() {
		observeDisableFieldsetOnCheckbox( 'EntretienDateentretien', $( 'EntretienDateentretienFromDay' ).up( 'fieldset' ), false );
		dependantSelect( 'EntretienReferentId', 'EntretienStructurereferenteId' );
	} );
</script>
<?php if( isset( $results ) ): ?>
	<?php
		echo $this->Default3->configuredindex(
			$results,
			array(
				'options' => $options
			)
		);
	?>

	<ul class="actionMenu">
		<li><?php
			echo $this->Xhtml->printLinkJs(
				'Imprimer le tableau', array( 'onclick' => 'printit(); return false;', 'class' => 'noprint' )
			);
		?></li>
		<li><?php
			echo $this->Xhtml->exportLink(
				'Télécharger le tableau', array( 'controller' => 'criteresentretiens', 'action' => 'exportcsv' ) + Hash::flatten( $this->request->data, '__' ), $this->Permissions->check( 'criteresentretiens', 'exportcsv' )
			);
		?></li>
	</ul>
<?php endif;?>