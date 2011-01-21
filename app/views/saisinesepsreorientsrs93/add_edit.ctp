<?php
	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );

	if( $this->action == 'add' ) {
		$this->pageTitle = 'Ajout d\'une réorientation';
	}
	else {
		$this->pageTitle = 'Modification d\'une réorientation';
	}
?>
<div class="with_treemenu">
	<h1> <?php echo $this->pageTitle; ?> </h1>

	<?php
		if( Configure::read( 'debug' ) > 0 ) {
			echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
			echo $javascript->link( 'dependantselect.js' );
		}
	?>

	<script type="text/javascript">
		document.observe( "dom:loaded", function() {
			dependantSelect( 'Saisineepreorientsr93StructurereferenteId', 'Saisineepreorientsr93TypeorientId' );
			dependantSelect( 'Saisineepreorientsr93ReferentId', 'Saisineepreorientsr93StructurereferenteId' );

			try { $( 'Saisineepreorientsr93StructurereferenteId' ).onchange(); } catch(id) { }
			try { $( 'Saisineepreorientsr93ReferentId' ).onchange(); } catch(id) { }

			observeDisableFieldsOnValue(
				'Saisineepreorientsr93Accordaccueil',
				[ 'Saisineepreorientsr93Desaccordaccueil' ],
				'0',
				false
			);
		} );
	</script>

	<fieldset>
		<legend><?php if( $this->action == 'add' ):?>Ajout d'une réorientation<?php elseif( $this->action == 'edit' ):?>Modification d'une réorientation<?php endif;?></legend>
		<?php
			echo $xform->create();

			echo $default->subform(
				array(
					'Saisineepreorientsr93.id' => array( 'type' => 'hidden' ),
					'Saisineepreorientsr93.orientstruct_id' => array( 'type' => 'hidden' ),
					'Saisineepreorientsr93.typeorient_id' => array( 'label' => 'Type d\'orientation' ),
					'Saisineepreorientsr93.structurereferente_id' => array( 'label' => 'Type de structure' ),
					'Saisineepreorientsr93.referent_id' => array( 'label' => 'Nom du référent' ),
					'Saisineepreorientsr93.motifreorient_id',
					'Saisineepreorientsr93.commentaire',
					'Saisineepreorientsr93.accordaccueil',
					'Saisineepreorientsr93.desaccordaccueil',
					'Saisineepreorientsr93.accordallocataire',
					'Saisineepreorientsr93.urgent',
				),
				array(
					'options' => $options
				)
			);

			echo '<div class="input select"><span class="label">Personne soumise à droits et devoirs ?</span><span class="input">'.( $toppersdrodevorsa ? 'Oui' : 'Non' ).'</span></div>';

			echo $default->subform( array( 'Saisineepreorientsr93.datedemande' ) );

			echo '<div class="input select"><span class="label">Réorientation</span><span class="input">'.$nb_orientations.'</span></div>';

			echo '<div class="submit">';
			echo $xform->submit( 'Enregistrer', array( 'div' => false ) );
			echo $xform->submit( 'Annuler', array( 'name' => 'Cancel', 'div' => false ) );
			echo '</div>';
			echo $form->end();
		?>
	</fieldset>
</div>