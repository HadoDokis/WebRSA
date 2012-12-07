<?php
	$domain = "actioncandidat_personne_".Configure::read( 'ActioncandidatPersonne.suffixe' );
	$this->pageTitle = __d( $domain, "ActionscandidatsPersonnes::{$this->action}" );

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}

	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>

<div class="with_treemenu">
	<?php
		echo $this->Xhtml->tag( 'h1', $this->pageTitle, array( 'class' => 'aere' ) );

		echo $this->Xhtml->tag(
			'p',
			'La fiche de liaison est un document conventionnel partagé qui engage tous les acteurs du PDI',
			array(
				'class' => 'remarque'
			)
		);

		echo $this->Xform->create( 'ActioncandidatPersonne', array( 'id' => 'candidatureform' ) );
		if( Set::check( $this->request->data, 'ActioncandidatPersonne.id' ) ){
			echo $this->Xform->input( 'ActioncandidatPersonne.id', array( 'type' => 'hidden' ) );
		}
	?>
	<fieldset class="actioncandidat">
		<legend class="actioncandidat" >Prescripteur / Référent</legend>
		<?php
			echo $this->Default->subform(
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
			echo $this->Ajax->observeField( 'ActioncandidatPersonneReferentId', array( 'update' => 'ActioncandidatPersonneStructurereferente', 'url' => Router::url( array( 'action' => 'ajaxstruct' ), true ) ) );


			echo $this->Xhtml->tag(
				'div',
				'<b></b>',
				array(
					'id' => 'ActioncandidatPersonneStructurereferente'
				)
			);

			echo $this->Default->subform(
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
			echo $this->Default->view(
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
			echo $this->Xhtml->tag(
				'dl',
				$this->Xhtml->tag( 'dt', 'Adresse' ).
				$this->Xhtml->tag(
					'dd',
					$this->Default->format( $personne, 'Adresse.numvoie' ).' '.$this->Default->format( $personne, 'Adresse.typevoie', array( 'options' => $options ) ).' '.$this->Default->format( $personne, 'Adresse.nomvoie' ).' '.$this->Default->format( $personne, 'Adresse.codepos' ).' '.$this->Default->format( $personne, 'Adresse.locaadr' )
				),
				array(
					'class' => 'allocataire infos'
				)
			);

			///Données propre aux données du foyer de la personne
			echo $this->Default->view(
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

			echo $this->Xhtml->tag(
				'dl',
				$this->Xhtml->tag( 'dt', 'Inscrit au Pole Emploi' ).
				$this->Xhtml->tag(
					'dd',
					$isInscrit
				).
				$this->Xhtml->tag( 'dt', ' N° identifiant : ' ).
				$this->Xhtml->tag(
					'dd',
					$idassedic
				),
				array(
					'class' => 'allocataire infos'
				)
			);

			///Données propre aux Dsps de la personne
			if( !empty( $dsp ) ) {
				echo $this->Default->view(
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

				echo $this->Default->view(
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
				echo $this->Default->subform(
					array(
						'Dsp.id' => array( 'type' => 'hidden' ),
						'Dsp.personne_id' => array( 'value' => $personne_id, 'type' => 'hidden' ),
						'Dsp.nivetu' => array( 'options' => $options['Dsp']['nivetu'], 'required' => true, 'empty' => true ),
						'Dsp.libautrqualipro' => array( 'type' => 'textarea' )
					)
				);
			}

			///Données propre au contrat d'engagement réciproque (CER)
			if( !empty( $contrat ) ) {
				echo $this->Default->view(
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
				echo $this->Xhtml->tag(
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
			echo $this->Default->subform(
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

			echo $this->Default->subform(
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
			echo $this->Default->subform(
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

			echo $this->Default->subform(
				array(
					'ActioncandidatPersonne.personne_id' => array( 'value' => $personne_id, 'type' => 'hidden' ),
					'ActioncandidatPersonne.actioncandidat_id' => array( 'type' => 'select' )
				),
				array(
					'domain' => $domain,
					'options' => $options
				)
			);

			///Ajax pour les données de l'action entreprise et de son partenaire lié
			echo $this->Ajax->observeField( 'ActioncandidatPersonneActioncandidatId', array( 'update' => 'ActioncandidatPartenairePartenaireId', 'url' => Router::url( array( 'action' => 'ajaxpart' ), true ) ) );
			echo $this->Xhtml->tag(
				'div',
				'<b></b>',
				array(
					'id' => 'ActioncandidatPartenairePartenaireId',
					'class' => 'aere'
				)
			);

			echo $this->Default->subform(
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
		<?php
			echo $this->Default->subform(
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
			echo $this->Xform->submit( 'Enregistrer', array( 'div' => false ) );
			echo $this->Xform->submit( 'Annuler', array( 'name' => 'Cancel', 'div' => false ) );
		?>
	</div>
	<?php echo $this->Xform->end();?>
</div>
<div class="clearer"><hr /></div>


<script type="text/javascript">
	document.observe( "dom:loaded", function() {



		<?php
			echo $this->Ajax->remoteFunction(
				array(
					'update' => 'ActioncandidatPartenairePartenaireId',
					'url' => Router::url( array( 'action' => 'ajaxpart', Set::extract( $this->request->data, 'ActioncandidatPersonne.actioncandidat_id' ) ), true )
				)
			).';';

// 			echo $this->Ajax->remoteFunction(
// 				array(
// 					'update' => 'ActioncandidatPersonneStructurereferente',
// 					'url' => Router::url( array( 'action' => 'ajaxstruct', Set::extract( $this->request->data, 'ActioncandidatPersonne.referent_id' ) ), true )
// 				)
// 			).';';

// 			echo $this->Ajax->remoteFunction(
// 				array(
// 					'update' => 'StructureData',
// 					'url' => Router::url( array( 'action' => 'ajaxreffonct', Set::extract( $this->request->data, 'Rendezvous.referent_id' ) ), true )
// 				)
// 			).';';
		?>


		<?php
			if( ( $this->action == 'add' ) && !empty( $referentId ) ) {
				echo $this->Ajax->remoteFunction(
					array(
						'update' => 'ActioncandidatPersonneStructurereferente',
						'url' => Router::url( array( 'action' => 'ajaxstruct', $referentId ), true)
					)
				);
			}
			else {

				echo $this->Ajax->remoteFunction(
					array(
						'update' => 'ActioncandidatPersonneStructurereferente',
						'url' => Router::url( array( 'action' => 'ajaxstruct', Set::extract( $this->request->data, 'ActioncandidatPersonne.referent_id' ) ), true)
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