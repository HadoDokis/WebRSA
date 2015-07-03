<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}

	echo $this->Default3->titleForLayout();

	$departement = Configure::read( 'Cg.departement' );

	$actions = array();
	if( $departement == 66 ) {
		if( $this->Permissions->check( 'ajoutdossierscomplets', 'add' ) ) {
			$actions['/Ajoutdossierscomplets/add'] = array( 'class' => 'add', 'domain' => 'dossiers' );
		}
	}
	else {
		if( $this->Permissions->check( 'ajoutdossiers', 'wizard' ) ) {
			$actions['/Ajoutdossiers/wizard'] = array( 'class' => 'add', 'domain' => 'dossiers' );
		}
	}

	if( $this->Permissions->check( 'dossierssimplifies', 'add' ) ) {
		if( $departement != 58 ) {
			$actions['/Dossierssimplifies/add'] = array( 'class' => 'add', 'domain' => 'dossiers' );
		}
	}

	$actions['/Dossiers/index/#toggleform'] =  array(
		'onclick' => '$( \'DossiersIndexForm\' ).toggle(); return false;'
	);

	// TODO: permettre de spécifier le domaine en params pour chaque entrée
	echo $this->Default3->actions( $actions );
?>

<?php echo $this->Form->create( null, array( 'type' => 'post', 'url' => array( 'action' => 'index' ), 'id' => 'DossiersIndexForm', 'class' => ( !empty( $this->request->params['named'] ) ? 'folded' : 'unfolded' ) ) );?>
	<fieldset>
		<legend><?php echo __d( 'search_plugin', 'Search.Dossier' );?></legend>
		<?php
			echo $this->Allocataires->blocDossier( array( 'prefix' => 'Search', 'options' => $options, 'fieldset' => false ) );
			echo $this->Form->input( 'Search.Dossier.anciennete_dispositif', array( 'label' => 'Ancienneté dans le dispositif', 'type' => 'select', 'options' => $options['Dossier']['anciennete_dispositif'], 'empty' => true ) );
			echo $this->Form->input( 'Search.Serviceinstructeur.id', array( 'label' => __( 'lib_service' ), 'type' => 'select' , 'options' => $options['Serviceinstructeur']['id'], 'empty' => true ) );
			echo $this->Form->input( 'Search.Dossier.fonorg', array( 'label' => 'Organisme émetteur du dossier', 'type' => 'select' , 'options' => $options['Dossier']['fonorg'], 'empty' => true ) );
			echo $this->Form->input( 'Search.Foyer.sitfam', array( 'label' => 'Filtrer par situation familiale', 'type' => 'select', 'empty' => true, 'options' => $options['Foyer']['sitfam'] ) );
		?>
	</fieldset>
	<?php
		echo $this->Allocataires->blocAdresse( array( 'prefix' => 'Search', 'options' => $options ) );
		echo $this->Allocataires->blocAllocataire( array( 'prefix' => 'Search', 'options' => $options ) );
	?>
	<?php
			echo '<fieldset><legend>&nbsp;</legend>';
			echo $this->Xform->input( 'Search.Dsp.natlog', array( 'label' => 'Conditions de logement', 'type' => 'select', 'empty' => true, 'options' => $options['Dsp']['natlog'] ) );
			if( $departement == 66 ) {
				echo $this->Xform->input( 'Search.Personne.has_prestation', array( 'label' => 'Rôle de la personne ?', 'type' => 'select', 'options' => $options['Prestation']['exists'], 'empty' => true ) );
			}
			if( $departement == 58 ) {
				echo $this->Xform->input( 'Search.Activite.act', array( 'label' => 'Code activité', 'type' => 'select', 'empty' => true, 'options' => $options['Activite']['act'] ) );
			}
			echo '</fieldset>';
		?>
	<fieldset>
		<legend>Recherche par parcours de l'allocataire</legend>
		<?php
			if( $departement == 58 ){
				echo $this->Form->input( 'Search.Propoorientationcov58.referentorientant_id', array( 'label' => 'Travailleur social chargé de l\'évaluation', 'type' => 'select', 'options' => $options['Propoorientationcov58']['referentorientant_id'], 'empty' => true ) );
				echo $this->Form->input( 'Search.Personne.etat_dossier_orientation', array( 'label' => __d( 'personne', 'Personne.etat_dossier_orientation' ), 'type' => 'select', 'options' => $options['Personne']['etat_dossier_orientation'], 'empty' => true ) );
				echo $this->Form->input( 'Search.Personne.has_dsp', array( 'label' => 'Possède une DSP ?', 'type' => 'select', 'options' => $options['Personne']['has_dsp'], 'empty' => true ) );
			}
			else if( $departement != 93 ){
				echo $this->Form->input( 'Search.Personne.has_orientstruct', array( 'label' => 'Possède une orientation ? ', 'type' => 'select', 'options' => $options['Personne']['has_orientstruct'], 'empty' => true ) );
			}
			if( $departement == 66 ){
				echo $this->Form->input( 'Search.Personne.has_cui', array( 'label' => 'Possède un CUI ? ', 'type' => 'select', 'options' => $options['Personne']['has_cui'], 'empty' => true ) );
			}
			echo $this->Form->input( 'Search.Personne.has_contratinsertion', array( 'label' => 'Possède un CER ? ', 'type' => 'select', 'options' => $options['Personne']['has_contratinsertion'], 'empty' => true ) );
		?>
	</fieldset>
	<?php
		echo $this->Allocataires->blocReferentparcours( array( 'prefix' => 'Search', 'options' => $options ) );
		echo $this->Allocataires->blocPagination( array( 'prefix' => 'Search', 'options' => $options ) );
		echo $this->Allocataires->blocScript( array( 'prefix' => 'Search', 'options' => $options ) );
	?>
	<div class="submit noprint">
		<?php echo $this->Form->button( 'Rechercher', array( 'type' => 'submit' ) );?>
		<?php echo $this->Form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
	</div>
<?php
	echo $this->Form->end();
	echo $this->Observer->disableFormOnSubmit();
?>

<?php
	if( isset( $results ) ) {
		echo $this->Default3->configuredindex(
			$results,
			array(
				'options' => $options
			)
		);
	}
?>
<?php if( isset( $results ) ): ?>
	<ul class="actionMenu">
		<li><?php
			echo $this->Xhtml->printLinkJs(
				'Imprimer le tableau',
				array(
					'onclick' => 'printit(); return false;',
					'class' => 'noprint'
				)
			);
		?></li>
		<li><?php
			echo $this->Xhtml->exportLink(
				'Télécharger le tableau',
				array( 'controller' => 'dossiers', 'action' => 'exportcsv' ) + Hash::flatten( $this->request->data, '__' ),
				$this->Permissions->check( 'dossiers', 'exportcsv' )
			);
		?></li>
	</ul>
<?php endif; ?>