<?php
	$this->pageTitle = 'Recherche par DSPs';
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
	}
?>
<h1><?php echo $this->pageTitle;?></h1>
<?php
	if( is_array( $this->request->data ) ) {
		echo '<ul class="actionMenu"><li>'.$this->Xhtml->link(
			$this->Xhtml->image(
				'icons/application_form_magnify.png',
				array( 'alt' => '' )
			).' Formulaire',
			'#',
			array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Search' ).toggle(); return false;" )
		).'</li></ul>';
	}
	$pagination = $this->Xpaginator->paginationBlock( 'Personne', $this->passedArgs );
?>

<?php echo $this->Form->create( 'Dsp', array( 'type' => 'post', 'action' => '/index/', 'id' => 'Search', 'class' => ( ( !empty( $this->request->data ) ) ? 'folded' : 'unfolded' ) ) );?>
	<?php
		echo $this->Search->blocAllocataire();
		echo $this->Search->blocAdresse( $mesCodesInsee, $cantons );
	?>
	<fieldset>
		<legend>Recherche par dossier</legend>
		<?php
			echo $this->Form->input( 'Dossier.numdemrsa', array( 'label' => 'Numéro de demande RSA' ) );
			echo $this->Form->input( 'Dossier.matricule', array( 'label' => __d( 'dossier', 'Dossier.matricule' ), 'maxlength' => 15 ) );

			echo $this->Form->input( 'Calculdroitrsa.toppersdrodevorsa', array( 'label' => 'Soumis à Droit et Devoir', 'type' => 'select', 'options' => $options['Calculdroitrsa']['toppersdrodevorsa'], 'empty' => true ) );
			echo $this->Search->natpf( $options['Detailcalculdroitrsa']['natpf'] );

			$valueDossierDernier = isset( $this->request->data['Dossier']['dernier'] ) ? $this->request->data['Dossier']['dernier'] : true;
			echo $this->Form->input( 'Dossier.dernier', array( 'label' => 'Uniquement la dernière demande RSA pour un même allocataire', 'type' => 'checkbox', 'checked' => $valueDossierDernier ) );
			echo $this->Search->etatdosrsa($etatdosrsa);
		?>
	</fieldset>

	<fieldset>
		<legend>Données socio-professionnelles</legend>
		<?php
			if( Configure::read( 'Cg.departement' ) == 93 ) {
				echo $this->Form->input( 'Detaildifsoc.difsoc', array( 'label' => 'Difficultés sociales', 'type' => 'select', 'options' => $options['Detaildifsoc']['difsoc'], 'empty' => true ) );
				echo $this->Form->input( 'Detailaccosocindi.nataccosocindi', array( 'label' => 'Domaine d\'accompagnement individuel', 'type' => 'select', 'options' => $options['Detailaccosocindi']['nataccosocindi'], 'empty' => true ) );
				echo $this->Form->input( 'Detaildifdisp.difdisp', array( 'label' => 'Obstacles à la recherche d\'emploi', 'type' => 'select', 'options' => $options['Detaildifdisp']['difdisp'], 'empty' => true ) );
			}
		?>

		<fieldset>
			<legend>Situation professionnelle</legend>
			<?php
				echo $this->Form->input( 'Dsp.nivetu', array( 'label' => "Quelle est votre niveau d'étude ? ", 'type' => 'select', 'options' => $options['Donnees']['nivetu'], 'empty' => true ) );
				echo $this->Form->input( 'Dsp.hispro', array( 'label' => "Passé professionnel ", 'type' => 'select', 'options' => $options['Donnees']['hispro'], 'empty' => true ) );

				echo $this->Romev3->fieldset( 'Deractromev3', array( 'options' => $options ) );
				echo $this->Form->input( 'Dsp.libsecactderact', array( 'label' => __d( 'dsp', 'Dsp.libsecactderact' ) ) );
				echo $this->Form->input( 'Dsp.libderact', array( 'label' => __d( 'dsp', 'Dsp.libderact' ) ) );

				if( Configure::read( 'Cg.departement' ) == 66 ) {
					echo '<fieldset><legend>Dernière activité (ROME V2)</legend>';
						echo $this->Form->input( 'Dsp.libsecactderact66_secteur_id' , array( 'label' => "Dans quel secteur d'activité avez-vous exercé votre activité professionnelle ? ", 'type' => 'select', 'options' => $options['Coderomesecteurdsp66'], 'empty' => true ) );
						echo $this->Form->input( 'Dsp.libderact66_metier_id' , array( 'label' => "Précisez quelle a été l'activité professionnelle ? ", 'type' => 'select', 'options' => $options['Coderomemetierdsp66'], 'empty' => true ) );
					echo '</fieldset>';
				}

				if( Configure::read( 'Cg.departement' ) != 93 ) {
					echo $this->Romev3->fieldset( 'Deractdomiromev3', array( 'options' => $options ) );
				}

				echo $this->Form->input( 'Dsp.libsecactdomi', array( 'label' => __d( 'dsp', 'Dsp.libsecactdomi' ) ) );
				echo $this->Form->input( 'Dsp.libactdomi', array( 'label' => __d( 'dsp', 'Dsp.libactdomi' ) ) );

				if( Configure::read( 'Cg.departement' ) == 66 ) {
					echo '<fieldset><legend>Dernière activité dominante (ROME V2)</legend>';
						echo $this->Form->input( 'Dsp.libsecactdomi66_secteur_id' , array( 'label' => "Dans quel secteur d'activité avez-vous exercé votre activité professionnelle dominante ? ", 'type' => 'select', 'options' => $options['Coderomesecteurdsp66'], 'empty' => true ) );
						echo $this->Form->input( 'Dsp.libactdomi66_metier_id' , array( 'label' => "Précisez quelle a été l'activité professionnelle dominante ? ", 'type' => 'select', 'options' => $options['Coderomemetierdsp66'], 'empty' => true ) );
					echo '</fieldset>';
				}

				echo $this->Romev3->fieldset( 'Actrechromev3', array( 'options' => $options ) );
				echo $this->Form->input( 'Dsp.libsecactrech', array( 'label' => __d( 'dsp', 'Dsp.libsecactrech' ) ) );
				echo $this->Form->input( 'Dsp.libemploirech', array( 'label' => __d( 'dsp', 'Dsp.libemploirech' ) ) );

				if( Configure::read( 'Cg.departement' ) == 66 ) {
					echo '<fieldset><legend>Emploi recherché (ROME V2)</legend>';
						echo $this->Form->input('Dsp.libsecactrech66_secteur_id' , array('label' => "Quel est le secteur d'activité recherché ? ",  'type' => 'select', 'options' => $options['Coderomesecteurdsp66'], 'empty' => true ) );
						echo $this->Form->input( 'Dsp.libemploirech66_metier_id' , array( 'label' => "Quel est l'emploi recherché ? ", 'type' => 'select', 'options' => $options['Coderomemetierdsp66'], 'empty' => true ) );
					echo '</fieldset>';
				}
			?>
		</fieldset>
	</fieldset>

	<?php
		echo $this->Search->referentParcours( $structuresreferentesparcours, $referentsparcours );
		echo $this->Search->paginationNombretotal();
	?>

	<div class="submit noprint">
		<?php echo $this->Form->button( 'Filtrer', array( 'type' => 'submit' ) );?>
		<?php echo $this->Form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
	</div>
<?php echo $this->Form->end();?>

<script type="text/javascript">
<?php if ( Configure::read( 'Cg.departement' ) == 66 ):?>
	document.observe("dom:loaded", function() {
 		dependantSelect( 'DspLibderact66MetierId', 'DspLibsecactderact66SecteurId' );
 		try { $( 'DspLibderact66MetierId' ).onchange(); } catch(id) { }

		dependantSelect( 'DspLibactdomi66MetierId', 'DspLibsecactdomi66SecteurId' );
		try { $( 'DspLibactdomi66MetierId' ).onchange(); } catch(id) { }

		dependantSelect( 'DspLibemploirech66MetierId', 'DspLibsecactrech66SecteurId' );
		try { $( 'DspLibemploirech66MetierId' ).onchange(); } catch(id) { }
	} );
<?php endif;?>
</script>

<?php
	if( isset( $results ) ) {
		echo $this->Default3->configuredindex(
			$results,
			array(
				'options' => $options
			)
		);
		/*
			// TODO, feinte
			if( !empty( $result['DspRev']['id'] ) ) {
				$viewLink = $this->Xhtml->viewLink(
					'Voir le dossier « '.$title.' »',
					array( 'controller' => 'dsps', 'action' => 'view_revs', $result['DspRev']['id'] ),
					$this->Permissions->check( 'dsps', 'view_revs' )
				);
			}
			else {
				$viewLink = $this->Xhtml->viewLink(
					'Voir le dossier « '.$title.' »',
					array( 'controller' => 'dsps', 'action' => 'view', $result['Personne']['id'] ),
					$this->Permissions->check( 'dsps', 'view' )
				);
			}
		 */
	}
?>
<?php if( isset( $results ) ): ?>
		<ul class="actionMenu">
			<li><?php
				echo $this->Xhtml->printLinkJs(
					'Imprimer le tableau',
					array( 'onclick' => 'printit(); return false;', 'class' => 'noprint' )
				);
			?></li>
			<li><?php
 				echo $this->Xhtml->exportLink(
 					'Télécharger le tableau',
 					array( 'controller' => 'dsps', 'action' => 'exportcsv' ) + Hash::flatten( $this->request->data, '__' ),
 					$this->Permissions->check( 'dsps', 'exportcsv' )
 				);
			?></li>
		</ul>
<?php endif; ?>

<?php echo $this->Search->observeDisableFormOnSubmit( 'Search' ); ?>
