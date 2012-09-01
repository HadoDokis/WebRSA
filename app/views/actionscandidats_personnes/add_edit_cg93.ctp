<?php
	$domain = "actioncandidat_personne_".Configure::read( 'ActioncandidatPersonne.suffixe' );
	$this->pageTitle = __d( $domain, "ActionscandidatsPersonnes::{$this->action}", true );

	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $javascript->link( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}

	echo $this->element( 'dossier_menu', array( 'id' => $dossierId, 'personne_id' => $personne_id ) );
?>

<div class="with_treemenu">
	<?php
		echo $xhtml->tag( 'h1', $this->pageTitle, array( 'class' => 'aere' ) );

		echo $xhtml->tag(
			'p',
			'La fiche de liaison est un document conventionnel partagé qui engage tous les acteurs du PDI',
			array(
				'class' => 'remarque'
			)
		);

		echo $xform->create( 'ActioncandidatPersonne', array( 'id' => 'candidatureform' ) );
		if( Set::check( $this->data, 'ActioncandidatPersonne.id' ) ){
			echo $xform->input( 'ActioncandidatPersonne.id', array( 'type' => 'hidden' ) );
		}
	?>
	<fieldset class="actioncandidat">
		<legend class="actioncandidat" >Prescripteur / Référent</legend>
		<?php
			echo $default->subform(
				array(
					'ActioncandidatPersonne.ddaction' => array( 'dateFormat' => 'DMY', 'minYear' => date( 'Y' ) - 2, 'maxYear' => date( 'Y' ) + 2, 'empty' => false ),
					'ActioncandidatPersonne.referent_id' => array( 'value' => $referentId ),
				),
				array(
					'domain' => $domain,
					'options' => $options
				)
			);

			///Ajax pour les données du référent et de l'organisme auquel il est lié
			echo $ajax->observeField( 'ActioncandidatPersonneReferentId', array( 'update' => 'ActioncandidatPersonneStructurereferente', 'url' => Router::url( array( 'action' => 'ajaxstruct' ), true ) ) );


			echo $xhtml->tag(
				'div',
				'<b></b>',
				array(
					'id' => 'ActioncandidatPersonneStructurereferente'
				)
			);

			echo $default->subform(
				array(
					'ActioncandidatPersonne.motifdemande' => array( 'domain' => $domain )
				)
			);
		?>
	</fieldset>
	<fieldset class="actioncandidat">
		<legend class="actioncandidat" >Personne orientée / allocataire</legend>
		<?php
			///Données propre à la Personne
			echo $default->view(
				$personne,
				array(
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom',
					'Personne.dtnai'
				),
				array(
					'widget' => 'dl',
					'class' => 'allocataire infos',
					'options' => $options
				)
			);

			///Données propre à l'adresse de la Personne
			echo $xhtml->tag(
				'dl',
				$xhtml->tag( 'dt', 'Adresse' ).
				$xhtml->tag(
					'dd',
					$default->format( $personne, 'Adresse.numvoie' ).' '.$default->format( $personne, 'Adresse.typevoie', array( 'options' => $options ) ).' '.$default->format( $personne, 'Adresse.nomvoie' ).' '.$default->format( $personne, 'Adresse.codepos' ).' '.$default->format( $personne, 'Adresse.locaadr' )
				),
				array(
					'class' => 'allocataire infos'
				)
			);

			///Données propre aux données du foyer de la personne
			echo $default->view(
				$personne,
				array(
					'Foyer.Modecontact.0.numtel' => array( 'label' => 'N° de téléphone' ),
					'Foyer.Modecontact.0.adrelec' => array( 'label' => 'Email' ),
					'Detaildroitrsa.oridemrsa' => array( 'label' => 'Allocataire du ' ),
					'Foyer.Dossier.matricule' => array( 'label' => 'Numéro allocataire ' )
				),
				array(
					'widget' => 'dl',
					'class' => 'allocataire infos',
					'options' => $options
				)
			);

			///Données propre au Pole Emploi
			$isPoleemploi = Set::classicExtract( $personne, 'Activite.act' );
			$isInscrit = 'Non';
			$idassedic = null;
			if( $isPoleemploi == 'ANP' ) {
				$isInscrit = 'Oui';
				$idassedic = Set::classicExtract( $personne, 'Personne.idassedic' );
			}
			else {
				$isInscrit;
				$idassedic;
			}

			echo $xhtml->tag(
				'dl',
				$xhtml->tag( 'dt', 'Inscrit au Pole Emploi' ).
				$xhtml->tag(
					'dd',
					$isInscrit
				).
				$xhtml->tag( 'dt', ' N° identifiant : ' ).
				$xhtml->tag(
					'dd',
					$idassedic
				),
				array(
					'class' => 'allocataire infos'
				)
			);

			///Données propre aux Dsps de la personne
			if( !empty( $dsp ) ) {
				echo $default->view(
					$personne,
					array(
						'Dsp.nivetu' => array( 'label' => 'Niveau d\'étude', 'options' => $options['Dsp']['nivetu'] )
					),
					array(
						'widget' => 'dl',
						'class' => 'allocataire infos',
						'options' => $options
					)
				);

				echo $default->view(
					$personne,
					array(
						'Dsp.libautrqualipro' => array( 'label' => 'Expériences professionnelles, ou qualification, et/ou niveau de diplomes <br />' )
					),
					array(
						'widget' => 'dl',
						'class' => 'allocataire infos',
						'options' => $options
					)
				);
			}
			else{
				echo '<strong>Expériences professionnelles, ou qualification, et/ou niveau de diplomes </strong>';
				echo $default->subform(
					array(
						'Dsp.id' => array( 'type' => 'hidden' ),
						'Dsp.personne_id' => array( 'value' => $personneId, 'type' => 'hidden' ),
						'Dsp.nivetu' => array( 'options' => $options['Dsp']['nivetu'], 'required' => true, 'empty' => true ),
						'Dsp.libautrqualipro' => array( 'type' => 'textarea' )
					)
				);
			}

			///Données propre au contrat d'engagement réciproque (CER)
			if( !empty( $contrat ) ) {
				echo $default->view(
					$personne,
					array(
						'Contratinsertion.decision_ci' => array( 'label' => 'Contrat d\'engagement : ', 'options' => $options['Contratinsertion']['decision_ci'] ),
						'Contratinsertion.datevalidation_ci'=> array( 'label' => false )
					),
					array(
						'widget' => 'dl',
						'class' => 'allocataire infos',
						'options' => $options
					)
				);
			}
			else{
				echo '<strong>Contrat d\'engagement : </strong>';
				echo $xhtml->tag(
					'p',
					'Aucun contrat présent pour cette personne',
					array( 'class' => 'notice' )
				);
			}
		?>
	</fieldset>

	<fieldset class="actioncandidat">
		<legend class="actioncandidat" >Partenaire / Prestataire</legend>
		<?php
			echo $default->subform(
				array(
					'ActioncandidatPersonne.rendezvouspartenaire' => array( 'type' => 'hidden', 'value'=>1 ),
					'ActioncandidatPersonne.horairerdvpartenaire' => array(
						'type' => 'datetime',
						'dateFormat' => 'DMY', 'minYear' => date( 'Y' ) - 2, 'maxYear' => date( 'Y' ) + 2,
						'timeFormat'=>24, 'interval' => 5,
						'empty' => true,
						'required' => true
					),
				),
				array(
					'options' => $options,
					'domain' => $domain
				)
			);

			echo $default->subform(
				array(
					'ActioncandidatPersonne.pieceallocataire' => array( 'legend' => required( 'L\'allocataire est invité à se munir : ' ), 'type' => 'radio', 'separator' => '<br />', 'options' => $options['ActioncandidatPersonne']['pieceallocataire'] ),
					'ActioncandidatPersonne.autrepiece' => array( 'label' => false )
				)
			);

		?>
	</fieldset>

	<fieldset class="actioncandidat">
		<legend class="actioncandidat" >Résultats d'orientation</legend>
		<?php
			echo $default->subform(
				array(
					'ActioncandidatPersonne.bilanvenu' => array( 'type' => 'radio', 'separator' => '<br />',  'legend' => required( 'La personne s\'est présentée' ) ),
					'ActioncandidatPersonne.bilanrecu' => array( 'type' => 'radio', 'separator' => '<br />',  'legend' => required( 'La personne a été reçue' ) ),
					'ActioncandidatPersonne.daterecu' => array( 'type' => 'date', 'dateFormat' => 'DMY', 'minYear' => date( 'Y' ) - 2, 'maxYear' => date( 'Y' ) + 2, 'empty' => true ),
					'ActioncandidatPersonne.personnerecu' => array( 'type' => 'text' ),
					'ActioncandidatPersonne.presencecontrat' => array( 'type' => 'select', 'label' => 'Avec son contrat d\'Engagement Réciproque' ),
					'ActioncandidatPersonne.bilanretenu' => array( 'type' => 'radio', 'separator' => '<br />', 'legend' => required( 'La personne a été retenue' ) )
				),
				array(
					'options' => $options,
					'domain' => $domain
				)
			);

			echo $default->subform(
				array(
					'ActioncandidatPersonne.personne_id' => array( 'value' => $personneId, 'type' => 'hidden' ),
					'ActioncandidatPersonne.actioncandidat_id' => array( 'type' => 'select' )
				),
				array(
					'domain' => $domain,
					'options' => $options
				)
			);

			///Ajax pour les données de l'action entreprise et de son partenaire lié
			echo $ajax->observeField( 'ActioncandidatPersonneActioncandidatId', array( 'update' => 'ActioncandidatPartenairePartenaireId', 'url' => Router::url( array( 'action' => 'ajaxpart' ), true ) ) );
			echo $xhtml->tag(
				'div',
				'<b></b>',
				array(
					'id' => 'ActioncandidatPartenairePartenaireId',
					'class' => 'aere'
				)
			);

			echo $default->subform(
				array(
					'ActioncandidatPersonne.integrationaction' => array( 'type' => 'radio', 'separator' => '<br />',  'legend' => required( 'La personne souhaite intégrer l\'action' ) ),
					'ActioncandidatPersonne.precisionmotif',
					'ActioncandidatPersonne.dfaction' => array( 'dateFormat' => 'DMY', 'minYear' => date( 'Y' ) - 2, 'maxYear' => date( 'Y' ) + 2, 'empty' => true )
				),
				array(
					'domain' => $domain,
					'options' => $options
				)
			);
		?>
	</fieldset>

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
					'ActioncandidatPersonne.datesignature' => array( 'dateFormat' => 'DMY', 'minYear' => date( 'Y' ) - 2, 'maxYear' => date( 'Y' ) + 2, 'empty' => false )
				),
				array(
					'domain' => $domain
				)
			);
		?>
	</fieldset>

	<div class="submit">
		<?php
			echo $xform->submit( 'Enregistrer', array( 'div' => false ) );
			echo $xform->submit( 'Annuler', array( 'name' => 'Cancel', 'div' => false ) );
		?>
	</div>
	<?php echo $xform->end();?>
</div>
<div class="clearer"><hr /></div>


<script type="text/javascript">
	document.observe( "dom:loaded", function() {



		<?php
			echo $ajax->remoteFunction(
				array(
					'update' => 'ActioncandidatPartenairePartenaireId',
					'url' => Router::url( array( 'action' => 'ajaxpart', Set::extract( $this->data, 'ActioncandidatPersonne.actioncandidat_id' ) ), true )
				)
			).';';

// 			echo $ajax->remoteFunction(
// 				array(
// 					'update' => 'ActioncandidatPersonneStructurereferente',
// 					'url' => Router::url( array( 'action' => 'ajaxstruct', Set::extract( $this->data, 'ActioncandidatPersonne.referent_id' ) ), true )
// 				)
// 			).';';

// 			echo $ajax->remoteFunction(
// 				array(
// 					'update' => 'StructureData',
// 					'url' => Router::url( array( 'action' => 'ajaxreffonct', Set::extract( $this->data, 'Rendezvous.referent_id' ) ), true )
// 				)
// 			).';';
		?>


		<?php
			if( ( $this->action == 'add' ) && !empty( $referentId ) ) {
				echo $ajax->remoteFunction(
					array(
						'update' => 'ActioncandidatPersonneStructurereferente',
						'url' => Router::url( array( 'action' => 'ajaxstruct', $referentId ), true)
					)
				);
			}
			else {

				echo $ajax->remoteFunction(
					array(
						'update' => 'ActioncandidatPersonneStructurereferente',
						'url' => Router::url( array( 'action' => 'ajaxstruct', Set::extract( $this->data, 'ActioncandidatPersonne.referent_id' ) ), true)
					)
				);
			}
		?>

			dependantSelect(
				'RendezvousReferentId',
				'RendezvousStructurereferenteId'
			);

		///Bilan si personne reçue
		observeDisableFieldsOnRadioValue(
			'candidatureform',
			'data[ActioncandidatPersonne][bilanrecu]',
			[
				'ActioncandidatPersonneDaterecuDay',
				'ActioncandidatPersonneDaterecuMonth',
				'ActioncandidatPersonneDaterecuYear',
				'ActioncandidatPersonnePersonnerecu',
				'ActioncandidatPersonnePresencecontrat'
			],
			'N',
			false
		);

		///Bilan si personne reçue
		observeDisableFieldsOnRadioValue(
			'candidatureform',
			'data[ActioncandidatPersonne][pieceallocataire]',
			[
				'ActioncandidatPersonneAutrepiece'
			],
			'AUT',
			true
		);

	} );
</script>