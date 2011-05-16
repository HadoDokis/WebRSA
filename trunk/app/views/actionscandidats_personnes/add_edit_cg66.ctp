<?php
	$domain = "actioncandidat_personne_".Configure::read( 'ActioncandidatPersonne.suffixe' );
	echo $this->element( 'dossier_menu', array( 'id' => $dossierId, 'personne_id' => $personne_id ) );
	echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
?>

<script type="text/javascript">
	document.observe( "dom:loaded", function() {
		observeDisableFieldsOnRadioValue(
			'candidatureform',
			'data[ActioncandidatPersonne][rendezvouspartenaire]',
			[
					'ActioncandidatPersonneHorairerdvpartenaireDay',
					'ActioncandidatPersonneHorairerdvpartenaireMonth',
					'ActioncandidatPersonneHorairerdvpartenaireYear',
					'ActioncandidatPersonneHorairerdvpartenaireHour',
					'ActioncandidatPersonneHorairerdvpartenaireMin',
			],
			'1',
			true
		);

		observeDisableFieldsOnRadioValue(
			'candidatureform',
			'data[ActioncandidatPersonne][mobile]',
			[
				'ActioncandidatPersonneTypemobile',
				'ActioncandidatPersonneNaturemobile'
			],
			'1',
			true
		);


		<?php
			echo $ajax->remoteFunction(
				array(
					'update' => 'ActioncandidatPartenairePartenaireId',
					'url' => Router::url( array( 'action' => 'ajaxpart', Set::extract( $this->data, 'ActioncandidatPersonne.actioncandidat_id' ) ), true )
				)
			);
		?>;
		<?php
			echo $ajax->remoteFunction(
				array(
					'update' => 'ActioncandidatPrescripteurReferentId',
					'url' => Router::url( array( 'action' => 'ajaxreferent', $referentId ), true)
				)
			);           
		?>
	} );
</script>
<!--/************************************************************************/ -->
<?php echo $javascript->link( 'dependantselect.js' ); ?>
<div class="with_treemenu">
	<?php
		echo $xhtml->tag(
			'h1',
			$this->pageTitle = __d( $domain, "ActionscandidatsPersonnes::{$this->action}", true )
		);
	?>
	<?php
		echo $xform->create( 'ActioncandidatPersonne', array( 'id' => 'candidatureform' ) );
		if( Set::check( $this->data, 'ActioncandidatPersonne.id' ) ){
			echo $xform->input( 'ActioncandidatPersonne.id', array( 'type' => 'hidden' ) );
		}
	?>
	<fieldset>
		<legend>Informations de candidature</legend>
		<?php
			echo $default->subform(
				array(
					'ActioncandidatPersonne.personne_id' => array( 'value' => $personneId, 'type' => 'hidden' ),
					'ActioncandidatPersonne.actioncandidat_id' => array( 'type' => 'select', 'options' => $actionsfiche ),
					'ActioncandidatPersonne.referent_id' => array( 'value' => $referentId ),
				),
				array(
					'domain' => $domain,
					'options' => $options
				)
			);

			echo $ajax->observeField( 'ActioncandidatPersonneActioncandidatId', array( 'update' => 'ActioncandidatPartenairePartenaireId', 'url' => Router::url( array( 'action' => 'ajaxpart' ), true ) ) );

			echo $xhtml->tag(
				'div',
				'<b>Partenaire</b>',
				array(
					'id' => 'ActioncandidatPartenairePartenaireId'
				)
			);
			
			echo $ajax->observeField( 'ActioncandidatPersonneReferentId', array( 'update' => 'ActioncandidatPrescripteurReferentId', 'url' => Router::url( array( 'action' => 'ajaxreferent' ), true ) ) );

			echo $xhtml->tag(
				'div',
				'<b>Prescripteur</b>',
				array(
					'id' => 'ActioncandidatPrescripteurReferentId'
				)
			);           

		?>
	</fieldset>
	<fieldset>
		<legend>Informations du candidat</legend>
		<?php
			echo $default->view(
				$personne,
				array(
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom'
				),
				array(
					'widget' => 'dl',
					'class' => 'allocataire infos',
					'options' => $options
				)
			);

			$labelMatricule = Set::extract( $personne, 'Foyer.Dossier.fonorg' );
			echo $default->view(
				$personne,
				array(
					'Personne.dtnai',
					'Foyer.Modecontact.numtel' => array( 'label' => 'N° de téléphone' ),
					'Foyer.Dossier.matricule' => array( 'label' => "N° {$labelMatricule}" )
				),
				array(
					'widget' => 'dl',
					'class' => 'allocataire infos'
				)
			);
			if( !empty( $identifiantpe ) ){
				echo $xhtml->tag(
					'dl', 
					$xhtml->tag( 'dt', 'N° Pôle Emploi') . $xhtml->tag( 'dd', $identifiantpe['Informationpe']['identifiantpe']),
					array( 'class' => 'allocataire infos' )
				);
			}

			
			echo $xhtml->tag(
				'dl',
				$xhtml->tag( 'dt', 'Adresse' ).
				$xhtml->tag(
					'dd',
					$default->format( $personne, 'Adresse.numvoie' ).' '.$default->format( $personne, 'Adresse.typevoie', array( 'options' => $options ) ).' '.$default->format( $personne, 'Adresse.nomvoie' ).'<br />'.$default->format( $personne, 'Adresse.codepos' ).' '.$default->format( $personne, 'Adresse.locaadr' )
				),
				array(
					'class' => 'allocataire infos'
				)
			);
		?>
	</fieldset>
	<fieldset>
		<legend><?php echo required( 'Motif de la demande' ); ?></legend>
			<?php
				echo $default->subform(
					array(
						'ActioncandidatPersonne.motifdemande' => array( 'label' => false, 'required' => false )
					),
					array(
						'domain' => $domain
					)
				);
			?>
	</fieldset>
	<fieldset>
		<legend>Mobilité</legend>
		<?php
			echo $default->subform(
				array(
					'ActioncandidatPersonne.mobile' => array( 'type' => 'radio' , 'legend' => 'Etes-vous mobile ?', 'div' => false, 'options' => array( '0' => 'Non', '1' => 'Oui' ) ),
					'ActioncandidatPersonne.naturemobile' => array( 'label' => 'Nature de la mobilité', 'empty' => true ),
					'ActioncandidatPersonne.typemobile'=> array( 'label' => 'Type de mobilité ' ),
				),
				array(
					'domain' => $domain,
					'options' => $options
				)
			);
		?>
	</fieldset>
	<fieldset>
		<legend>Rendez-vous</legend>
		<?php 
			echo $default->subform(
				array(
					'ActioncandidatPersonne.rendezvouspartenaire' => array( 'type' => 'radio' , 'legend' => 'Rendez-vous', 'div' => false, 'options' => array( '0' => 'Non', '1' => 'Oui' ) ),
					'ActioncandidatPersonne.horairerdvpartenaire' => array(
						'type' => 'datetime',
						'label' => 'Rendez-vous fixé le ',
						'dateFormat' => 'DMY', 'minYear' => date( 'Y' ) - 2, 'maxYear' => date( 'Y' ) + 2,
						'timeFormat'=>24, 'interval' => 5,
						'empty' => true
					),
				),
				array(
					'options' => $options,
					'domain' => $domain
				)
			);            
		?>
	</fieldset>
	<?php 
		echo $default->subform(
			array(
				'ActioncandidatPersonne.enattente' => array( 'type' => 'radio', 'div' => false, 'legend' => 'Candidature en attente', 'options' => array( 'N' => 'Non', 'O' => 'Oui' ) )
			),
			array(
				'options' => $options,
				'domain' => $domain
			)
		);
	?>

	<fieldset class="loici">
		<p>
			<strong>Engagement:</strong><br />
			<em>Je m’engage à me rendre disponible afin d’être présent à la prestation ou au rendez vous qui me sera fixé. En cas de force majeure, je m’engage à prévenir le conseiller d’insertion ou l’assistante sociale chargé de mon suivi.<br />
			Nous vous rappelons que dans le cas où vous ne donneriez pas suite à ce rendez vous sans motif valable, vous seriez convoqué(e) par l'Equipe Pluridisciplinaire Locale (Commission Audition), pour non respect de vos obligations dans le cadre de votre contrat.<br />
			</em>
		</p>
		<?php
			echo $default->subform(
				array(
					'ActioncandidatPersonne.datesignature' => array( 'dateFormat' => 'DMY', 'empty' => false )
				),
				array(
					'domain' => $domain
				)
			);
		?>
	</fieldset>

	<?php if( $this->action == 'edit' ):?>

		<p class="center"><em><strong>A remplir par le partenaire :</strong></em></p>
		<fieldset class="partenaire bilan">
			<?php
				echo $xhtml->tag(
					'dl',
					'Bilan d\'accueil : '
				);

				echo $default->subform(
					array(
						'ActioncandidatPersonne.bilanvenu' => array( 'type' => 'radio', 'separator' => '<br />',  'legend' => false ),
						'ActioncandidatPersonne.bilanretenu' => array( 'type' => 'radio', 'separator' => '<br />', 'legend' => false ),
					),
					array(
						'domain' => $domain,
						'options' => $options
					)
				);

				echo $default->subform(
					array(
						'ActioncandidatPersonne.infocomplementaire',
						'ActioncandidatPersonne.datebilan' => array( 'dateFormat' => 'DMY', 'empty' => false )
					),
					array(
						'domain' => $domain,
						'options' => $options
					)
				);
			?>
		</fieldset>
		<fieldset>
			<legend>Sortie</legend>
			<?php 
				echo $default->subform(
					array(
						'ActioncandidatPersonne.sortiele',
						'ActioncandidatPersonne.motifsortie_id'
					),
					array(
						'domain' => $domain,
						'options' => $options
					)
				);
			?>
		</fieldset>
		
		
	<?php endif;?>
	<div class="submit">
		<?php
			echo $xform->submit( 'Enregistrer', array( 'div' => false ) );
			echo $xform->submit( 'Annuler', array( 'name' => 'Cancel', 'div' => false ) );
		?>
	</div>
	<?php echo $xform->end();?>
</div>
<div class="clearer"><hr /></div>