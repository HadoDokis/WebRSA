<?php
	if( $this->action == 'add' ) {
		$this->pageTitle = 'Ajout d\'un CER';
	}
	else {
		$this->pageTitle = 'Édition d\'un CER';
	}

	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $javascript->link( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}
?>

<?php echo $this->element( 'dossier_menu', array( 'personne_id' => Set::classicExtract( $personne, 'Personne.id' ) ) );?>

<div class="with_treemenu">
	<h1><?php echo $this->pageTitle;?></h1>
	<?php
		if( $this->action == 'add' ) {
			echo $form->create( 'Contratinsertion', array( 'type' => 'post', 'id' => 'testform', 'url' => Router::url( null, true ) ) );
			echo '<div>';
			echo $form->input( 'Contratinsertion.id', array( 'type' => 'hidden', 'value' => '' ) );

			echo $form->input( 'Contratinsertion.personne_id', array( 'type' => 'hidden', 'value' => Set::classicExtract( $personne, 'Personne.id' ) ) );
			echo $form->input( 'Contratinsertion.rg_ci', array( 'type' => 'hidden'/*, 'value' => '' */) );
			echo '</div>';
		}
		else {
			echo $form->create( 'Contratinsertion', array( 'type' => 'post', 'id' => 'testform', 'url' => Router::url( null, true ) ) );
			echo '<div>';
			echo $form->input( 'Contratinsertion.id', array( 'type' => 'hidden' ) );

			echo $form->input( 'Contratinsertion.personne_id', array( 'type' => 'hidden', 'value' => Set::classicExtract( $personne, 'Personne.id' ) ) );

			echo '</div>';
		}
		echo '<div>';
		echo $xform->input( 'Personne.id', array( 'type' => 'hidden', 'value' => Set::classicExtract( $personne, 'Personne.id' ) ) );
		echo '</div>';
	?>
<script type="text/javascript">
	document.observe("dom:loaded", function() {
		observeDisableFieldsOnValue( 'ContratinsertionRgCi', [ 'ContratinsertionTypocontratId' ], 1, true );
	});
</script>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		dependantSelect( 'ContratinsertionReferentId', 'ContratinsertionStructurereferenteId' );
        <?php
			echo $ajax->remoteFunction(
				array(
					'update' => 'ContratinsertionPartenaire',
					'url' => Router::url( array( 'action' => 'ajaxaction', Set::extract( $this->data, 'Contratinsertion.actioncandidat_id' ) ), true )
				)
			);
		?>;
	});
    
</script>

<script type="text/javascript">
	function checkDatesToRefresh() {
		if( ( $F( 'ContratinsertionDdCiMonth' ) ) && ( $F( 'ContratinsertionDdCiYear' ) ) && ( $F( 'ContratinsertionDureeEngag' ) ) ) {
			var correspondances = new Array();
			// FIXME: voir pour les array associatives
			<?php
				$duree_engag = 'duree_engag_'.Configure::read( 'nom_form_ci_cg' );
				foreach( $$duree_engag as $index => $duree ):?>correspondances[<?php echo $index;?>] = <?php echo str_replace( ' mois', '' ,$duree );?>;<?php endforeach;?>

			setDateIntervalCer( 'ContratinsertionDdCi', 'ContratinsertionDfCi', correspondances[$F( 'ContratinsertionDureeEngag' )], false );
			//INFO: setDateInterval2 permet de conserver le jour lors du choix de la durée
			//      setDateInterval affiche le dernier jour du mois lors du choix de la durée
			//      setDateIntervalCer affiche pour la date de fin le "jour du début - 1".
		}
	}

	document.observe( "dom:loaded", function() {
		Event.observe( $( 'ContratinsertionDdCiDay' ), 'change', function() {
			checkDatesToRefresh();
		} );
		Event.observe( $( 'ContratinsertionDdCiMonth' ), 'change', function() {
			checkDatesToRefresh();
		} );
		Event.observe( $( 'ContratinsertionDdCiYear' ), 'change', function() {
			checkDatesToRefresh();
		} );

		Event.observe( $( 'ContratinsertionDureeEngag' ), 'change', function() {
			checkDatesToRefresh();
		} );

		// form, radioName, fieldsetId, value, condition, toggleVisibility
		observeDisableFieldsetOnRadioValue(
			'testform',
			'data[Contratinsertion][forme_ci]',
			$( 'Contratsuite' ),
			'C',
			false,
			true
		);

		// form, radioName, fieldsetId, value, condition, toggleVisibility
		observeDisableFieldsetOnRadioValue(
			'testform',
			'data[Contratinsertion][forme_ci]',
			$( 'faitsuitea' ),
			'C',
			false,
			true
		);

		observeDisableFieldsetOnCheckbox(
			'ContratinsertionFaitsuitea',
			'Raisonfaitsuitea',
			false,
			true
		);


		//Autre cas de suspension / radiation
		observeDisableFieldsetOnRadioValue(
			'testform',
			'data[Contratinsertion][avisraison_suspension_ci]',
			$( 'Suspensionautre' ),
			'A',
			false,
			true
		);

		//Autre cas de suspension / radiation
		observeDisableFieldsetOnRadioValue(
			'testform',
			'data[Contratinsertion][avisraison_radiation_ci]',
			$( 'Radiationautre' ),
			'A',
			false,
			true
		);

		//Autre cas de suspension / radiation
		observeDisableFieldsetOnRadioValue(
			'testform',
			'data[Contratinsertion][raison_ci]',
			$( 'Tablesuspension' ),
			'S',
			false,
			false
		);

		//Autre cas de suspension / radiation
		observeDisableFieldsetOnRadioValue(
			'testform',
			'data[Contratinsertion][raison_ci]',
			$( 'Tableradiation' ),
			'R',
			false,
			false
		);

		<?php
		$ref_id = Set::extract( $this->data, 'Contratinsertion.referent_id' );
			echo $ajax->remoteFunction(
				array(
					'update' => 'StructurereferenteRef',
					'url' => Router::url(
						array(
							'action' => 'ajaxstruct',
							Set::extract( $this->data, 'Contratinsertion.structurereferente_id' )
						),
						true
					)
				)
			).';';
			echo $ajax->remoteFunction(
				array(
					'update' => 'ReferentRef',
					'url' => Router::url(
						array(
							'action' => 'ajaxref',
							Set::extract( $this->data, 'Contratinsertion.referent_id' )
						),
						true
					)
				)
			).';';
		?>
	} );
</script>
<fieldset>
	<table class="wide noborder">
		<tr>
			<td class="mediumSize noborder">
				<strong>Statut de la personne : </strong><?php echo Set::extract( $rolepers, Set::extract( $personne, 'Prestation.rolepers' ) ); ?>
				<br />
				<strong>Nom : </strong><?php echo Set::enum( Set::classicExtract( $personne, 'Personne.qual') , $qual ).' '.Set::classicExtract( $personne, 'Personne.nom' );?>
				<br />
				<strong>Prénom : </strong><?php echo Set::classicExtract( $personne, 'Personne.prenom' );?>
				<br />
				<strong>Date de naissance : </strong><?php echo date_short( Set::classicExtract( $personne, 'Personne.dtnai' ) );?>
			</td>
			<td class="mediumSize noborder">
				<strong>N° Service instructeur : </strong>
				 <?php
					$libservice = Set::enum( Set::classicExtract( $personne, 'Suiviinstruction.typeserins' ),  $typeserins );
					if( isset( $libservice ) ) {
						echo $libservice;
					}
					else{
						echo 'Non renseigné';
					}
                ?>
				<br />
				<strong>N° demandeur : </strong><?php echo Set::classicExtract( $personne, 'Dossier.numdemrsa' );?>
				<br />
				<strong>N° CAF/MSA : </strong><?php echo Set::classicExtract( $personne, 'Dossier.matricule' );?>
				<br />
				<strong>Inscrit au Pôle emploi</strong>
				<?php
					$isPoleemploi = Set::classicExtract( $personne, 'Activite.act' );
					if( $isPoleemploi == 'ANP' )
						echo 'Oui';
					else
						echo 'Non';
				?>
				<br />
				<strong>N° identifiant : </strong><?php echo Set::classicExtract( $personne, 'Personne.idassedic' );?>
			</td>
		</tr>
		<tr>
			<td class="mediumSize noborder">
				<strong>Adresse : </strong><br /><?php echo Set::classicExtract( $personne, 'Adresse.numvoie' ).' '.Set::enum( Set::classicExtract( $personne, 'Adresse.typevoie' ), $typevoie ).' '.Set::classicExtract( $personne, 'Adresse.nomvoie' ).'<br /> '.Set::classicExtract( $personne, 'Adresse.codepos' ).' '.Set::classicExtract( $personne, 'Adresse.locaadr' );?>
			</td>
		<tr>
			<td class="mediumSize noborder">
				<strong>Tél. fixe : </strong>
				<?php
					$numtelfixe = Set::classicExtract( $personne, 'Personne.numfixe' );
					if( !empty( $numtelfixe ) ) {
						echo Set::extract( $personne, 'Personne.numfixe' );
					}
					else{
						echo $xform->input( 'Personne.numfixe', array( 'label' => false, 'type' => 'text' ) );

					}
				?>
			</td>
			<td class="mediumSize noborder">
				<strong>Tél. portable : </strong>
				<?php
					$numtelport = Set::extract( $personne, 'Personne.numport' );
					if( !empty( $numtelport ) ) {
						echo Set::extract( $personne, 'Personne.numport' );
					}
					else{
						echo $xform->input( 'Personne.numport', array( 'label' => false, 'type' => 'text' ) );
					}
				?>
			</td>
		</tr>
		<tr>
			<td colspan="2" class="mediumSize noborder">
				<strong>Adresse mail : </strong>
				<?php
					$email = Set::extract( $personne, 'Personne.email' );
					if( !empty( $email ) ) {
						echo Set::extract( $personne, 'Personne.email' );
					}
					else{
						echo $xform->input( 'Personne.email', array( 'label' => false, 'type' => 'text' ) );
					}
				?>
			</td>
		</tr>
	</table>
</fieldset>

<fieldset>
	<legend>Type de Contrat</legend>
		<table class="wide noborder">
			<tr>
				<td class="noborder">
					<?php
						$error = Set::classicExtract( $this->validationErrors, 'Contratinsertion.forme_ci' );
						$class = 'radio'.( !empty( $error ) ? ' error' : '' );

						$thisDataFormeCi = Set::classicExtract( $this->data, 'Contratinsertion.forme_ci' );
						if( !empty( $thisDataFormeCi ) ) {
							$valueFormeci = $thisDataFormeCi;
						}
						$input =  $form->input( 'Contratinsertion.forme_ci', array( 'type' => 'radio' , 'options' => $forme_ci, 'legend' => required( __d( 'contratinsertion', 'Contratinsertion.forme_ci', true )  ), 'value' => $valueFormeci ) );

						echo $xhtml->tag( 'div', $input, array( 'class' => $class ) );
					?>
				</td>
			</tr>
			<tr>
				<td class="noborder" colspan="2">
					<strong>Date d'ouverture du droit ( RMI, API, rSa ) : </strong><?php echo date_short( Set::classicExtract( $personne, 'Dossier.dtdemrsa' ) );?>
				</td>
			</tr>
			<tr>
				<td class="mediumSize noborder">
					<strong>Ouverture de droit ( nombre d'ouvertures ) : </strong><?php echo $numouverturedroit; ?>
				</td>
				<td class="mediumSize noborder">
					<strong>rSa majoré</strong>
					<?php
						$isRsaMajore = Set::classicExtract( $personne, 'Detailcalculdroitrsa.majore' );
						if( $isRsaMajore )
							echo 'Oui';
						else
							echo 'Non';
					?>
				</td>
			</tr>

			<?php if( Configure::read( 'nom_form_ci_cg' ) == 'cg66' ):?>
				<tr>
					<td class="noborder">
						<?php
							echo $xform->input( 'Contratinsertion.num_contrat', array( 'label' => false , 'type' => 'select', 'options' => $options['num_contrat'], 'empty' => true, 'value' => $tc ) );
						?>
					</td>
					<td class="noborder">
						<?php
							if( $nbrCi != 0 ) {
								echo '(nombre de renouvellement) : '.( $nbrCi - 1 );
							}
							else {
								echo '(nombre de renouvellement) : 0';
							}
						?>
					</td>
				</tr>
			<?php endif;?>

			<?php if( Configure::read( 'nom_form_ci_cg' ) == 'cg93' ):?>
				<tr>
					<td class="noborder">
						<?php
							echo $xform->input( 'Contratinsertion.num_contrat', array( 'label' => false , 'type' => 'hidden', 'value' => $tc ) );
							echo Set::enum( $tc, $options['num_contrat'] );

						?>
					</td>
					<td class="noborder">
						<?php echo '(nombre de renouvellement) : '.$nbrCi;?>
					</td>
				</tr>
			<?php endif;?>
		</table>
	</fieldset>
	<fieldset>
		<fieldset class="noborder" id="Contratsuite">
			<table class="wide noborder">
				<tr>
					<td colspan="2" class="noborder center" id="contrat">
						<em>Ce contrat est établi pour : </em>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="noborder">
						<div class="demi"><?php echo $form->input( 'Contratinsertion.type_demande', array( 'label' => 'Raison : ' , 'type' => 'radio', 'div' => false, 'separator' => '</div><div class="demi">', 'options' => $options['type_demande'], 'legend' => false ) );?></div>
					</td>
				</tr>
			</table>
		</fieldset>
		<fieldset class="noborder" id="faitsuitea">
			<?php
				echo $html->tag(
					'span',
					$form->input(
						'Contratinsertion.faitsuitea',
						array(
							'type'=>'checkbox',
							'label'=> 'Ce contrat fait suite à'
						)
					)
				);
			?>
		</fieldset>
		<fieldset class="noborder" id="Raisonfaitsuitea">
			<div class="demi">
				<?php echo $form->input( 'Contratinsertion.raison_ci', array( 'label' => 'Raison : ' , 'type' => 'radio', 'div' => false, 'separator' => '</div><div class="demi">', 'options' => $raison_ci, 'legend' => false ) );?>
			</div>
				<table class="wide noborder">
					<tr>
						<td class="noborder">
							<fieldset id="Tablesuspension" class="noborder">
								<table  class="wide noborder">
									<tr>
										<td class="noborder">
											<?php
												if( isset( $suspension ) && !empty( $suspension ) ) {
													echo $html->tag(
														'fieldset',
														'Date de suspension : '.$locale->date( '%d/%m/%Y', $suspension[0]['Suspensiondroit']['ddsusdrorsa']),
														array(
															'id' => 'dtsuspension',
															'class' => 'noborder'
														)
													);
												}
												else{
													echo 'Date de suspension : '.$form->input( 'Contratinsertion.datesuspensionparticulier', array( 'label' => false, 'type' => 'date' , 'dateFormat' => 'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1, 'empty' => true ) );
												}
											?>
										</td>
									</tr>
									<tr>
										<td class="noborder">
											<?php
												echo $form->input( 'Contratinsertion.avisraison_suspension_ci', array( 'type' => 'radio', 'separator' => '<br />', 'options' => $avisraison_ci, 'legend' => false,   ) );
											?>
											<fieldset id="Suspensionautre" class="invisible">
												<?php
													$AutreavissuspensionId = Set::classicExtract( $this->data, 'Autreavissuspension.id' );
													$ContratinsertionId = Set::classicExtract( $this->data, 'Contratinsertion.id' );
													if( $this->action == 'edit' && !empty( $AutreavissuspensionId ) ) {
														echo $form->input( 'Autreavissuspension.id', array( 'type' => 'hidden' ) );
														echo $form->input( 'Autreavissuspension.contratinsertion_id', array( 'type' => 'hidden', 'value' => $ContratinsertionId ) );
													}
													$selected = Set::extract( $this->data, '/Autreavissuspension/autreavissuspension' );
													if( empty( $selected ) ){
														$selected = Set::extract( $this->data, '/Autreavissuspension/Autreavissuspension' );
													}

													echo $form->input( 'Autreavissuspension.Autreavissuspension', array( 'multiple' => 'checkbox', 'type' => 'select', 'separator' => '<br />', 'options' => $options['autreavissuspension'], 'selected' => $selected, 'label' => false,   ) );
												?>
											</fieldset>
										</td>
									</tr>
								</table>
							</fieldset>
						</td>
						<td class="noborder">
							<fieldset id="Tableradiation" class="noborder">
								<table class="wide noborder">
									<tr>
										<td class="noborder" id="dtradiation">
											<?php
												if( isset( $situationdossierrsa['Situationdossierrsa']['dtclorsa'] ) ) {
													echo $html->tag(
														'span',
														'Date de radiation : '.$locale->date( '%d/%m/%Y', $situationdossierrsa['Situationdossierrsa']['dtclorsa'] ),
														array(
															'id' => 'dtradiation'
														)
													);
												}
												else{
													echo 'Date de radiation'.$form->input( 'Contratinsertion.dateradiationparticulier', array( 'label' => false, 'type' => 'date' , 'dateFormat' => 'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1, 'empty' => true ) );
												}
											?>
										</td>
									</tr>
									<tr>
										<td class="noborder">
											<?php
												echo $form->input( 'Contratinsertion.avisraison_radiation_ci', array( 'type' => 'radio', 'separator' => '<br />', 'options' => $avisraison_ci, 'legend' => false ) );
											?>
											<fieldset id="Radiationautre" class="invisible">
												<?php

													$AutreavisradiationId = Set::classicExtract( $this->data, 'Autreavisradiation.id' );
													$ContratinsertionId = Set::classicExtract( $this->data, 'Contratinsertion.id' );
													if( $this->action == 'edit' && !empty( $AutreavisradiationId ) ) {
														echo $form->input( 'Autreavisradiation.id', array( 'type' => 'hidden' ) );
														echo $form->input( 'Autreavisradiation.contratinsertion_id', array( 'type' => 'hidden', 'value' => $ContratinsertionId ) );
													}
													$selected = Set::extract( $this->data, '/Autreavisradiation/autreavisradiation' );
													if( empty( $selected ) ){
														$selected = Set::extract( $this->data, '/Autreavisradiation/Autreavisradiation' );
													}

													echo $form->input( 'Autreavisradiation.Autreavisradiation', array( 'multiple' => 'checkbox', 'type' => 'select', 'separator' => '<br />', 'options' => $options['autreavisradiation'], 'selected' => $selected, 'label' => false,   ) );
												?>
											</fieldset>
										</td>
									</tr>
								</table>
							</fieldset>
						</td>
					</tr>
				</fieldset>
			</table>
			<table class="noborder">
				<tr>
					<td colspan="2" class="noborder center">
						<em><strong>Lorsque le contrat conditionne l'ouverture du droit, il ne sera effectif qu'après décision du Président du Conseil Général</strong></em>
					</td>
				</tr>
			</table>
		</fieldset>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		observeDisableFieldsetOnCheckbox(
			'ContratinsertionFaitsuitea',
			'Raisonfaitsuitea',
			false,
			true
		);
	} );
</script>

<fieldset>
	<legend>Type d'orientation</legend>
	<table class="wide noborder">
		<tr>
			<td class="noborder">
				<?php echo $xform->input( 'Contratinsertion.structurereferente_id', array( 'label' => 'Nom de l\'organisme de suivi', 'type' => 'select', 'options' => $structures, 'selected' => $struct_id, 'empty' => true, 'required' => true ) );?>
				<?php echo $ajax->observeField( 'ContratinsertionStructurereferenteId', array( 'update' => 'StructurereferenteRef', 'url' => Router::url( array( 'action' => 'ajaxstruct' ), true ) ) ); ?>
			</td>
			<td class="noborder">
				<?php echo $xform->input( 'Contratinsertion.referent_id', array('label' => 'Nom du référent chargé du suivi :', 'type' => 'select', 'options' => $referents, 'empty' => true, 'selected' => $struct_id.'_'.$referent_id ) );?>
				<?php echo $ajax->observeField( 'ContratinsertionReferentId', array( 'update' => 'ReferentRef', 'url' => Router::url( array( 'action' => 'ajaxref' ), true ) ) ); ?>
			</td>
		</tr>
		<tr>
			<td class="wide noborder"><div id="StructurereferenteRef"></div></td>

			<td class="wide noborder"><div id="ReferentRef"></div></td>
		</tr>
	</table>

</fieldset>

<script type="text/javascript">
	Event.observe( $( 'ContratinsertionStructurereferenteId' ), 'change', function( event ) {
		$( 'ReferentRef' ).update( '' );
	} );
</script>

<fieldset class="loici">
	<p>
		Loi N°2008-1249 du 1er Décembre, généralisant le revenu de solidarité active et réformant les politiques d'engagement réciproque : <strong>Contrat librement débattu avec engagements réciproques</strong> ( articles L.263.35 et L.262.36 )<br />
		<strong>Respect du Contrat</strong> ( Article L-262-37 1° et 2° ) :<br />
		<em>"Sauf décision prise au regard de la situation particulière du bénéficiaire, le versement du revenu de solidarité active est suspendu, en tout ou partie, par le Président du Conseil Général :<br />
		lorsque, du fait du bénéficiaire et sans motif légitime, le projet personnalisé d'accès à l'emploi ou l'un des contrats mentionnés aux articles L.262-35 et L.262-36 ne sont pas établis dans les délais prévus ou ne sont pas renouvelés.<br />
		lorsque, sans motif légitime, les dispositions du projet personnalisé d'accès à l'emploi ou les stipulations de l'un des contrats mentionnés aux articles L.262-35 et L.262-36 ne sont pas respectés par le bénéficiaire."<br />
		</em>
		<strong>Lorsque le bénéficiaire ne respecte pas les conditions de ce contrat, l'organisme signataire le signale au Président du conseil Général.</strong>
	</p>
</fieldset>

<fieldset>
	<legend class="title" title="Exemples: logement, santé, disponibilité, autonomie, ...">Situation personnelle et familiale </legend>
	<?php echo $form->input( 'Contratinsertion.sitfam_ci', array( 'label' => false, 'type' => 'textarea', 'rows' => 10 ) );?>
</fieldset>
<fieldset>
	<legend class="title" title="Exemples: qualification, connaissances et compétences, formation recherchée, nature de l'emploi ou des emplois recherchés, ...">Situation professionnelle </legend>
	<?php echo $form->input( 'Contratinsertion.sitpro_ci', array( 'label' => false, 'type' => 'textarea', 'rows' => 10 ) );?>
</fieldset>
<fieldset>
	<legend class="title" title="Exemples: ce que j'attends, ce que je propose">Observation(s) éventuelle(s) du bénéficiaire du contrat</legend>
	<?php echo $form->input( 'Contratinsertion.observ_benef', array( 'label' => false, 'type' => 'textarea', 'rows' => 10 ) );?>
</fieldset>
<fieldset>
	<legend class="title" title="Projets et démarches que le bénéficiaire du contrat s'engage à entreprendre au regard de la proposition du référent">Projet négocié <?php echo REQUIRED_MARK;?></legend>
	<?php echo $form->input( 'Contratinsertion.nature_projet', array( 'label' => false, 'type' => 'textarea', 'rows' => 10 ) );?>
</fieldset>

<fieldset>
	<table class="wide noborder">
		<tr>
			<td class="mediumSize noborder">
				<fieldset>
					<legend><strong>Positionnement éventuel sur l'action d'insertion</strong></legend>
					<?php
                        echo $form->input( 'Contratinsertion.actioncandidat_id', array( 'label' => 'Intitulé de l\'action', 'type' => 'select', 'options' => $actionsSansFiche, 'empty' => true ) );                 
                        echo $ajax->observeField( 'ContratinsertionActioncandidatId', array( 'update' => 'ContratinsertionPartenaire', 'url' => Router::url( array( 'action' => 'ajaxaction' ), true ) ) );
                        echo $xhtml->tag(
                            'div',
                            ' ',
                            array(
                                'id' => 'ContratinsertionPartenaire'
                            )
                        );
                        
                        echo $form->input( 'Contratinsertion.engag_object', array( 'label' => 'Engagement sur l\'action', 'type' => 'textarea' ) );
					?>

				</fieldset>
			</td>
			<td class="mediumSize noborder">
				<fieldset>
					<legend> <strong>Action(s) déjà en cours</strong></legend>
						<?php if( !empty( $fichescandidature ) ):?>
						<table>
							<thead>
								<tr>
									<th>Action engagée</th>
									<th>Partenaire / Prestataire</th>
									<th>Prescripteur</th>
									<th>Date de début de l'action</th>
									<th>Fiche de candidature ?</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach( $fichescandidature as $key => $fiche )
									{
										echo '<tr>';
											echo $xhtml->tag('td', $fiche['Actioncandidat']['name']);
											echo $xhtml->tag('td', $fiche['Actioncandidat']['Contactpartenaire']['Partenaire']['libstruc'] );
											echo $xhtml->tag('td', $fiche['Referent']['qual'].' '.$fiche['Referent']['nom'].' '.$fiche['Referent']['prenom'] );
											echo $xhtml->tag('td', date_short( $fiche['Actioncandidat']['ddaction'] ) );
											echo $xhtml->tag('td', $fiche['Actioncandidat']['hasfichecandidature'] ? 'Oui' : 'Non' );
											echo $xhtml->tag('td', $xhtml->viewLink( 'Voir', array( 'controller' => 'actionscandidats_personnes', 'action' => 'index', $fiche['ActioncandidatPersonne']['personne_id'] ) ) );
										echo '</tr>';
									}
								?>
							</tbody>
						</table>
						<?php else:?>
							<p class="notice">Aucune action engagée pour cet allocataire.</p>
						<?php endif;?>
				</fieldset>
			</td>
		</tr>
	</table>
</fieldset>
<fieldset>
	<p>
		Entre <?php echo Set::enum( Set::classicExtract( $personne, 'Personne.qual') , $qual ).' '.$personne['Personne']['nom'].' '.$personne['Personne']['prenom'];?> bénéficiare du rSa et le Département représenté par le référent signataire désigné par l'organisme choisi par le Président du Conseil Général, il est conclu le présent contrat visant à faciliter son insertion sociale ou professionnelle.<br />
		Le bénéficiaire <strong>s'engage à respecter les orientations et le suivi</strong> du parcours d'insertion, ainsi que les différents moyens d'actions proposés. Le Département, représenté par le référent signataire désigné par l'organisme choisi par le Président du Conseil Général <strong>s'engage à mettre en oeuvre les actions pré-citées et/ou un accompagnement adapté.</strong>
	</p>
</fieldset>
<fieldset>
	<table class="wide noborder">
		<tr>
			<td colspan="2" class="noborder center">
				<em>Le présent contrat est conclu pour une durée de <?php echo REQUIRED_MARK;?></em>
				<?php echo $form->input( 'Contratinsertion.duree_engag', array( 'label' => false, 'div' => false, 'type' => 'select', 'options' => $duree_engag_cg66, 'empty' => true )  ); ?>
			</td>
		</tr>
		<tr>
			<td class="mediumSize noborder">
				<strong>Du <?php echo REQUIRED_MARK;?></strong><?php echo $form->input( 'Contratinsertion.dd_ci', array( 'label' => false, 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1 , 'empty' => true)  );?>
			</td>
			<td class="mediumSize noborder">
				<strong>Au <?php echo REQUIRED_MARK;?></strong><?php echo $form->input( 'Contratinsertion.df_ci', array( 'label' => false, 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1 , 'empty' => true ) ) ;?>
			</td>
		</tr>
	</table>
</fieldset>
<fieldset>
	<table class="wide noborder">
		<tr>
			<td class="signature noborder center">
				<strong>Le bénéficiaire du contrat</strong><br /><?php echo Set::enum( Set::classicExtract( $personne, 'Personne.qual'), $qual ).' '.$personne['Personne']['nom'].' '.$personne['Personne']['prenom'];?>
			</td>
			<td class="signature noborder center">
				<strong>Le Référent</strong><br />
				<?php
					echo $xhtml->tag(
						'div',
						$xhtml->tag( 'span', ( isset( $ReferentNom ) ? $ReferentNom : ' ' ), array( 'id' => 'ReferentNom' ) )
					);
					echo $ajax->observeField( 'ContratinsertionReferentId', array( 'update' => 'ReferentNom', 'url' => Router::url( array( 'action' => 'ajaxref' ), true ) ) );
				?>
			</td>
		</tr>
		<tr>
			<td class="mediumSize noborder"></td>
			<td class="mediumSize noborder">
				<p class="caution center">Attention : lorsque le contrat conditionne le paiement du rsa il ne sera effectif qu'après décision du Président du Conseil Général. La responsabilité du référent signataire n'est nullement engagée par la signature de ce contrat</p>
			</td>
		</tr>
	</table>
	<br />
		<?php echo $form->input( 'Contratinsertion.lieu_saisi_ci', array( 'label' => __d( 'contratinsertion', 'Contratinsertion.lieu_saisi_ci', true ).REQUIRED_MARK, 'type' => 'text', 'maxlength' => 50 )  ); ?><br />
		<?php echo $form->input( 'Contratinsertion.date_saisi_ci', array( 'label' => __d( 'contratinsertion', 'Contratinsertion.date_saisi_ci', true ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1, 'empty' => true )  ); ?>

</fieldset>
<script type="text/javascript">
	Event.observe( $( 'ContratinsertionDdCiDay' ), 'change', function( event ) {
		$( 'ContratinsertionDateSaisiCiDay' ).value = $F( 'ContratinsertionDdCiDay' );
	} );
	Event.observe( $( 'ContratinsertionDdCiMonth' ), 'change', function( event ) {
		$( 'ContratinsertionDateSaisiCiMonth' ).value = $F( 'ContratinsertionDdCiMonth' );
	} );
	Event.observe( $( 'ContratinsertionDdCiYear' ), 'change', function( event ) {
		$( 'ContratinsertionDateSaisiCiYear' ).value = $F( 'ContratinsertionDdCiYear' );
	} );
</script>


<fieldset class="cnilci">
	<p>
		<em>Conformément à la loi "Informatique et liberté" n°78-17 du 06 janvier 1978 relative à l'informatique, aux fichiers et aux libertés nous nous engageons à prendre toutes les précautions afin de préserver la sécurité de ces informations et notamment empêcher qu'elles soient déformées, endommagées ou communiquées à des tiers. Les coordonnées informations liées à l'adresse, téléphone et mail seront utilisées uniquement pour permettre la prise de contact, dans le cadre du parcours d'engagement réciproque.</em>
	</p>
</fieldset>
	<div class="submit">
		<?php echo $form->submit( 'Enregistrer', array( 'div' => false ) );?>
		<?php echo $form->submit('Annuler', array( 'name' => 'Cancel', 'div' => false ) );?>
	</div>
	<?php echo $form->end();?>
</div>

<div class="clearer"><hr /></div>
