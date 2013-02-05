<?php
	$domain = 'cui';
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}
?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		//Utilisé en cas d'adresse de l'employeur différente pour les doc administratifs
		observeDisableFieldsetOnRadioValue(
			'cuiform',
			'data[Cui][isadresse2]',
			$( 'Adressebis' ),
			'O',
			false,
			true
		);

		//Utilisé si le contrat signé est de type CIE
		observeDisableFieldsetOnRadioValue(
			'cuiform',
			'data[Cui][secteur]',
			$( 'iscie' ),
			'cie',
			false,
			true
		);

		//Utilisé si le contrat signé est de type CAE
		observeDisableFieldsetOnRadioValue(
			'cuiform',
			'data[Cui][secteur]',
			$( 'iscae' ),
			'cae',
			false,
			true
		);

		//Dans le cas du CAE, affichage du champ hors ACI ou en ACI
		observeDisableFieldsetOnRadioValue(
			'cuiform',
			'data[Cui][secteur]',
			$( 'isaci' ),
			'cae',
			false,
			true
		);

		//Utilisé si le bénéficiaire bénéficie d'un rsa majoré

		observeDisableFieldsetOnRadioValue(
			'cuiform',
			'data[Cui][isbeneficiaire]',
			$( 'IsRsaMaj' ),
			'RSADEPT',
			true
		);

		//Utilisé si la personne est bénéficiaire
		observeDisableFieldsOnRadioValue(
			'cuiform',
			'data[Cui][isbeneficiaire]',
			[
				'CuiDureebenefaide06',
				'CuiDureebenefaide11',
				'CuiDureebenefaide23',
				'CuiDureebenefaide24'
			],
			undefined,
			false
		);
		//Utilisé si le type de contrat est un CDD
		observeDisableFieldsOnRadioValue(
			'cuiform',
			'data[Cui][typecontrat]',
			[
				'CuiDatefincontratYear',
				'CuiDatefincontratMonth',
				'CuiDatefincontratDay'
			],
			'CDD',
			true
		);

		//Utilisé si les périodes sont des périodes de professionnalisation
		observeDisableFieldsetOnRadioValue(
			'cuiform',
			'data[Cui][isperiodepro]',
			$( 'niveauqualif' ),
			'O',
			false,
			true
		);

		//Utilisé si le financement excclusif provient du Conseil Général
		observeDisableFieldsetOnRadioValue(
			'cuiform',
			'data[Cui][financementexclusif]',
			$( 'financementexclusif' ),
			'O',
			false,
			true
		);

		//Utilisé si le contrat signé est de type CAE et que la periode d'immersion est à Oui
		observeDisableFieldsetOnRadioValue(
			'cuiform',
			'data[Cui][iscae]',
			$( 'periodeimmersion' ),
			'O',
			false,
			true
		);

		dependantSelect( 'CuiPrestataireId', 'CuiOrgsuiviId' );

		dependantSelect( 'Accompagnementcui66MetieraffectationId', 'Accompagnementcui66SecteuraffectationId' );
		try { $( 'Accompagnementcui66MetieraffectationId' ).onchange(); } catch(id) { }

		dependantSelect( 'CuiMetieremploiproposeId', 'CuiSecteuremploiproposeId' );
		try { $( 'CuiMetieremploiproposeId' ).onchange(); } catch(id) { }
	});
</script>

<?php
	echo $this->Xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'cui', "Cuis::{$this->action}" )
	);

	echo $this->Xhtml->tag(
		'p',
		'<strong>CONTRAT UNIQUE D\'INSERTION</strong>'.'<br />'.' CONVENTION SIGNÉE ENTRE LE CONSEIL GÉNÉRAL, L\'EMPLOYEUR et LE SALARIÉ',
		array(
			'class' => 'remarque center'
		)
	);
?>
<?php
	echo $this->Xform->create( 'Cui', array( 'id' => 'cuiform' ) );
	if( Set::check( $this->request->data, 'Cui.id' ) ) {
		echo '<div>'.$this->Xform->input( 'Cui.id', array( 'type' => 'hidden' ) ).'</div>';
		echo '<div>'.$this->Xform->input( 'Accompagnementcui66.id', array( 'type' => 'hidden' ) ).'</div>';
	}
?>
<div>
	<?php

		echo $this->Default->subform(
			array(
				'Cui.personne_id' => array( 'value' => $personne_id, 'type' => 'hidden' ),
				'Cui.user_id' => array( 'type' => 'hidden', 'value' => $userConnected ),
				'Cui.montantrsapercu' => array( 'type' => 'hidden', 'value' => $montantrsapercu )
			),
			array(
				'domain' => $domain,
				'options' => $options
			)
		);
	?>
	<fieldset>
		<legend><?php echo required( __d( 'cui', 'Cui.secteur' )  );?></legend>
		<?php
			echo $this->Default2->subform(
				array(
					'Cui.secteur' => array(  'legend' => false, 'type' => 'radio', 'options' => $options['Cui']['secteur'] ),
					'Cui.numconvention',
					'Cui.numconventionobj'
				),
				array(
					'options' => $options
				)
			);
		?>
		<fieldset id="isaci">
			<?php
				echo $this->Default2->subform(
					array(
						'Cui.isaci' => array( 'type' => 'radio', 'legend' => false, 'options' => $options['Cui']['isaci'] )
					),
					array(
						'domain' => $domain,
						'options' => $options
					)
				);
			?>
		</fieldset>
	</fieldset>
</div>

<!--**************************************** Partie EMPLOYEUR *********************************************** -->
<fieldset>
	<legend>L'EMPLOYEUR</legend>
	<table class="noborder">
		<tr>
			<td class="cui1 noborder">
				<fieldset>
					<?php

						echo $this->Default->subform(
							array(
								'Cui.nomemployeur',
								'Cui.numvoieemployeur',
								'Cui.typevoieemployeur' => array( 'empty' => true, 'options' => $options['typevoie'] ),
								'Cui.nomvoieemployeur',
								'Cui.compladremployeur',
								'Cui.numtelemployeur',
								'Cui.emailemployeur',
								'Cui.codepostalemployeur',
								'Cui.villeemployeur'
							),
							array(
								'domain' => $domain,
								'options' => $options
							)
						);

						if( Configure::read( 'Cg.departement' ) == 66 ) {
							echo $this->Default->subform(
								array(
									'Cui.cantonemployeur' => array( 'empty' => true, 'options' => $cantons ),
									'Cui.secteuractiviteemployeur_id' => array( 'empty' => true, 'options' => $secteursactivites )
								),
								array(
									'domain' => $domain,
									'options' => $options
								)
							);
						}
						else{
							echo $this->Default->subform(
								array(
									'Cui.secteuractiviteemployeur_id' => array( 'empty' => true, 'options' => $secteursactivites )
								),
								array(
									'domain' => $domain,
									'options' => $options
								)
							);
						}

						echo $this->Xhtml->tag(
							'p',
							'Si l\'adresse à laquelle les documents administratifs et financiers doivent etre envoyés est différente de l\'adresse ci-dessus, remplir la partie ci-dessous',
							array(
								'class' => 'remarque'
							)
						);

						$error = Set::classicExtract( $this->validationErrors, 'Cui.isadresse2' );
						$class = 'radio'.( !empty( $error ) ? ' error' : '' );
						$thisDataAdressebis = Set::classicExtract( $this->request->data, 'Cui.isadresse2' );
						if( !empty( $thisDataAdressebis ) ) {
							$valueAdressebis = $thisDataAdressebis;
						}
						$input =  $this->Form->input( 'Cui.isadresse2', array( 'type' => 'radio' , 'options' => $options['Cui']['isadresse2'], 'div' => false, 'legend' => __d( 'cui', 'Cui.isadresse2' ), 'value' => $valueAdressebis ) );
						echo $this->Xhtml->tag( 'div', $input, array( 'class' => $class ) );

					?>
					<fieldset id="Adressebis">
						<?php
							echo $this->Default->subform(
								array(
									'Cui.numvoieemployeur2',
									'Cui.typevoieemployeur2' => array( 'empty' => true, 'options' => $options['typevoie'] ),
									'Cui.nomvoieemployeur2',
									'Cui.compladremployeur2',
									'Cui.numtelemployeur2',
									'Cui.emailemployeur2',
									'Cui.codepostalemployeur2',
									'Cui.villeemployeur2',
									'Cui.cantonemployeur2' => array( 'empty' => true, 'options' => $cantons ),
									'Cui.secteuractiviteemployeur2_id' => array( 'empty' => true, 'options' => $secteursactivites )
								),
								array(
									'domain' => $domain,
									'options' => $options
								)
							);
						?>
					</fieldset>
				</fieldset>
			</td>
			<td class="cui2 noborder">
				<fieldset>
					<?php
						echo $this->Default->subform(
							array(
								'Cui.statutemployeur' => array( 'empty' => true, 'options' => $options['Cui']['statutemployeur'] ),
								'Cui.serviceinstructeur_id'
							),
							array(
								'domain' => $domain,
								'options' => $options
							)
						);
						echo $this->Xhtml->tag( 'p', 'Paiement par virement: fournir un RIB de l\'employeur', array( 'class' => 'remarque center' ) );
						echo $this->Default->subform(
							array(
								'Cui.nomtiturib',
								'Cui.etaban',
								'Cui.guiban',
								'Cui.numcomptban',
								'Cui.nometaban',
								'Cui.clerib'
							),
							array(
								'domain' => $domain,
								'options' => $options
							)
						);
					?>
				</fieldset>
					<?php
						echo $this->Default->subform(
							array(
								'Cui.orgrecouvcotis' => array( 'legend' => required( __d( 'cui', 'Cui.orgrecouvcotis' )  ), 'type' => 'radio', 'options' => $options['Cui']['orgrecouvcotis'] )
							),
							array(
								'domain' => $domain,
								'options' => $options
							)
						);
					?>

			</td>
		</tr>
	</table>

	<fieldset id="iscie" class="invisible">
		<?php
			echo $this->Xhtml->tag( 'p', 'Si CIE, je déclare sur l\'honneur être à jour des versements de mes cotisations et contributions sociales, que cette embauche ne résulte pas du licenciement d\'un salarié en CDI, ne pas avoir procédé à un licenciement pour motif économique au cours des 6 derniers mois ou pour une raison autre que la faute grave' );
			echo $this->Default->subform(
				array(
					'Cui.iscie' => array( 'type' => 'radio', 'options' => $options['Cui']['iscie'], 'label' => false  )
				),
				array(
					'domain' => $domain,
					'options' => $options
				)
			);
		?>
	</fieldset>
</fieldset>

<!--**************************************** Partie SALARIE *********************************************** -->
<?php
/**
*   Fonction pour récupérer le nom du département de la personne
*   On récupère le code postal et on récupère les 2 premiers chiffres que l'on
*   compare avec la table Departements nouvellement créée
*/
$codepos = Set::classicExtract( $personne, 'Adresse.codepos' );
$depSplit = substr( $codepos, '0', 2 );
?>
<fieldset>
	<legend>LE SALARIÉ</legend>
	<table class="wide noborder">
		<tr>
			<td class="mediumSize noborder">
				<strong>Nom : </strong><?php echo Set::enum( Set::classicExtract( $personne, 'Personne.qual'), $qual ).' '.Set::classicExtract( $personne, 'Personne.nom' );?>
				<br />
				<?php if(  Set::classicExtract( $personne, 'Personne.qual') != 'MR' ):?>
					<strong>Pour les femmes, nom patronymique : </strong><?php echo Set::classicExtract( $personne, 'Personne.nomnai' );?>
				<?php endif;?>
				<br />
				<strong>Né(e) le : </strong>
					<?php
						echo date_short( Set::classicExtract( $personne, 'Personne.dtnai' ) ).' <strong>à</strong>  '.Set::classicExtract( $personne, 'Personne.nomcomnai' );
					?>
				<br />
				<strong>Adresse : </strong><br />
					<?php
						echo Set::extract( $personne, 'Adresse.numvoie' ).' '.Set::extract( $options['typevoie'], Set::extract( $personne, 'Adresse.typevoie' ) ).' '.Set::extract( $personne, 'Adresse.nomvoie' ).'<br /> '.Set::extract( $personne, 'Adresse.compladr' ).'<br /> '.Set::extract( $personne, 'Adresse.codepos' ).' '.Set::extract( $personne, 'Adresse.locaadr' );
					?>
				<br />
				<!-- Si on n'autorise pas la diffusion de l'email, on n'affiche rien -->
				<?php if( Set::extract( $personne, 'Foyer.Modecontact.0.autorutiadrelec' ) == 'A' ):?>
					<strong>Adresse électronique : </strong><?php echo Set::extract( $personne, 'Foyer.Modecontact.0.adrelec' );?>
				<?php endif;?>
			</td>
			<td class="mediumSize noborder">
				<strong>Prénoms : </strong><?php echo Set::classicExtract( $personne, 'Personne.prenom' );?>
				<br />
				<strong>NIR : </strong><?php echo Set::classicExtract( $personne, 'Personne.nir');?>
				<br />
				<strong>Département : </strong><?php echo Set::extract( $depSplit, $dept );?>
				<br />
				<strong>Canton : </strong><?php echo Set::extract( $personne, 'Canton.canton' );?>
				<br />
				<strong>Nationalité : </strong><?php echo Set::enum( Set::classicExtract( $personne, 'Personne.nati' ), $nationalite );?>
				<br />
				<strong>Date de fin de titre de séjour : </strong><?php echo date_short( Set::classicExtract( $personne, 'Titresejour.dftitsej' ) );?>
				<br />
				<strong>Référent en cours : </strong><?php echo Set::enum( Set::classicExtract( $personne, 'PersonneReferent.referent_id' ), $referents );?>
				<br />
				<!-- Si on n'autorise aps la diffusion du téléphone, on n'affiche rien -->
				<?php if( Set::extract( $personne, 'Foyer.Modecontact.0.autorutitel' ) == 'A' ):?>
					<strong>Numéro de téléphone 1 : </strong><?php echo Set::extract( $personne, 'Foyer.Modecontact.0.numtel' );?>
					<br />
					<strong>Numéro de téléphone 2 : </strong><?php echo Set::extract( $personne, 'Foyer.Modecontact.1.numtel' );?>
				<?php endif;?>
			</td>
		</tr>
		<tr>
			<td class="noborder" colspan="2">
				<strong>Si bénéficiaire RSA, n° allocataire : </strong>
				<?php
					echo Set::classicExtract( $personne, 'Dossier.matricule' ).'  <strong>relève de : </strong> '.Set::classicExtract( $personne, 'Dossier.fonorg' );
				?>
			</td>
		</tr>
	</table>
	<?php
		echo $this->Default2->subform(
			array(
				'Cui.compofamiliale'  => array( 'empty' => true, 'type' => 'radio', 'options' => $options['Cui']['compofamiliale'] )
			),
			array(
				'options' => $options
			)
		);
	?>
</fieldset>

<!--********************* Situation SALARIE avant la signature de la convention ********************** -->

<fieldset>
	<legend>SITUATION DU SALARIE AVANT LA SIGNATURE DE LA CONVENTION </legend>
		<?php
			echo $this->Default->subform(
				array(
					'Cui.niveauformation'  => array( 'empty' => true, 'options' => $options['Cui']['niveauformation'] ),
					'Cui.dureesansemploi' => array( 'legend' => required( __d( 'cui', 'Cui.dureesansemploi' )  ), 'type' => 'radio', 'options' => $options['Cui']['dureesansemploi'] )
				),
				array(
					'domain' => $domain,
					'options' => $options
				)
			);


			$valueIdentifiantpe = '';
			$dernierIdentifiantpe = Set::classicExtract( $personne, 'Historiqueetatpe.identifiantpe' );
			if( !empty( $dernierIdentifiantpe ) ){
				$valueIdentifiantpe = $dernierIdentifiantpe;
				echo $this->Xform->fieldValue( 'Cui.isinscritpe', 'Oui' );
			}
			else{
				$valueIdentifiantpe = $dernierIdentifiantpe;
				echo $this->Xform->fieldValue( 'Cui.isinscritpe', 'Non' );
			}
			echo $this->Default->subform(
				array(
					'Cui.identifiantpe' => array( 'value' => isset( $this->request->data['Cui']['identifiantpe'] ) ? $this->request->data['Cui']['identifiantpe'] : $valueIdentifiantpe  )
				),
				array(
					'domain' => $domain,
					'options' => $options
				)
			);
		?>
		<?php if( !empty( $valueIdentifiantpe ) ):?>
			<fieldset id="InscritPE" class="invisible">
				<?php
					echo $this->Default->subform(
						array(
							'Cui.dureeinscritpe' => array( 'legend' => __d( 'cui', 'Cui.dureeinscritpe' ), 'type' => 'radio', 'options' => $options['Cui']['dureeinscritpe'] ),
						),
						array(
							'domain' => $domain,
							'options' => $options
						)
					);
				?>
			</fieldset>
		<?php endif;?>

			<table class="noborder">
				<tr>
					<td class="cui2 noborder">
						<?php
							echo $this->Default->subform(
								array(
									'Cui.isbeneficiaire' => array( 'label' => __d( 'cui', 'Cui.isbeneficiaire' ), 'type' => 'radio', 'options' => $options['Cui']['isbeneficiaire'] )
								),
								array(
									'domain' => $domain,
									'options' => $options
								)
							);
						?>
					</td>
					<td class="cui2 noborder">
						<fieldset id="IsRsaMaj" style="border: 0; padding: 0;">
							<?php
								echo $this->Default->subform(
									array(
										'Cui.rsadeptmaj' => array( 'label' => __d( 'cui', 'Cui.rsadeptmaj' ), 'type' => 'radio', 'options' => $options['Cui']['rsadeptmaj'], 'id' => 'fre' )
									),
									array(
										'domain' => $domain,
										'options' => $options
									)
								);
							?>
						</fieldset>
					</td>
				</tr>
			</table>

			<fieldset id="IsBeneficiaire" class="invisible">
				<?php
					echo $this->Default->subform(
						array(
							'Cui.dureebenefaide' => array( 'label' => ( __d( 'cui', 'Cui.dureebenefaide' )  ), 'type' => 'radio', 'options' => $options['Cui']['dureebenefaide'] )
						),
						array(
							'domain' => $domain,
							'options' => $options
						)
					);
					echo $this->Xhtml->tag( 'p', '( Pour les bénéficiaires du RSA, y compris la période antérieure au 01/06/2009 en RMI ou API )', array( 'class' => 'remarque' ) );
				?>
			</fieldset>
			<?php
				echo $this->Default->subform(
					array(
						'Cui.handicap' => array( 'label' => ( __d( 'cui', 'Cui.handicap' )  ), 'type' => 'radio', 'options' => $options['Cui']['handicap'] )
					),
					array(
						'domain' => $domain,
						'options' => $options
					)
				);
			?>
</fieldset>

<!--********************* Le contrat de travail ********************** -->

<fieldset>
	<legend>LE CONTRAT DE TRAVAIL</legend>
	<?php
		echo $this->Default->subform(
			array(
				'Cui.typecontrat' => array( 'label' => ( __d( 'cui', 'Cui.typecontrat' )  ), 'type' => 'radio', 'options' => $options['Cui']['typecontrat'] )
			),
			array(
				'domain' => $domain,
				'options' => $options
			)
		);
	?>
	<table class="cui3 noborder">
		<tr>
			<td class=" noborder">
				<?php
					echo $this->Default->subform(
						array(
							'Cui.dateembauche' => array( 'dateFormat' => 'DMY', 'minYear' => date( 'Y' ) - 2, 'maxYear' => date( 'Y' ) + 2 ),
						),
						array(
							'domain' => $domain,
							'options' => $options
						)
					);
				?>
			</td>
			<td class=" noborder">
				<?php
					echo $this->Default->subform(
						array(
							'Cui.datefincontrat' => array( 'dateFormat' => 'DMY', 'minYear' => date( 'Y' ) - 2, 'maxYear' => date( 'Y' ) + 2 )
						),
						array(
							'domain' => $domain,
							'options' => $options
						)
					);
				?>
			</td>
		</tr>
	</table>
	<?php
		if( empty( $options['Coderomemetierdsp66'] ) ) {
			echo '<p class="notice">Veuillez paramétrer les tables codes ROME</p>';
		}
		else {
			$dataSelected = ( isset( $this->request->data['Cui']['secteuremploipropose_id'] ) && isset( $this->request->data['Cui']['metieremploipropose_id'] ) ) ? ( $this->request->data['Cui']['secteuremploipropose_id'].'_'.$this->request->data['Cui']['metieremploipropose_id'] ) : null;
			$selected = ( isset( $cui['Cui']['secteuremploipropose_id'] ) && isset( $cui['Cui']['metieremploipropose_id'] ) ) ? ( $cui['Cui']['secteuremploipropose_id'].'_'.$cui['Cui']['metieremploipropose_id'] ) : $dataSelected;
			echo $this->Default->subform(
				array(
					'Cui.secteuremploipropose_id' => array( 'options' => $secteursactivites ),
					'Cui.metieremploipropose_id' => array( 'options' => $options['Coderomemetierdsp66'], 'selected' => $selected ),
					'Cui.salairebrut'
				),
				array(
					'domain' => $domain,
					'options' => $options
				)
			);
		}
	?>
	<table class="cuiduree noborder">
		<tr>
			<?php
				$nbErrors = count( $this->validationErrors['Cui'] );
				$errors = array(
					'dureehebdosalarieheure' => Set::extract( $this->validationErrors, 'Cui.dureehebdosalarieheure' ),
					'dureehebdosalarieminute' => Set::extract( $this->validationErrors, 'Cui.dureehebdosalarieminute' ),
				);
				unset(
					$this->validationErrors['Cui']['dureehebdosalarieheure'],
					$this->validationErrors['Cui']['dureehebdosalarieminute']
				);
				$errors = Hash::filter( $errors );
			?>
			<td class="dureehebdo noborder<?php echo ( ( $nbErrors == 0 ) ? '' : ' error' );?>"><?php echo required( 'Durée hebdomadaire de travail du salarié indiquée sur le contrat de travail' ); ?></td>
			<td class="dureehebdo noborder<?php echo ( ( $nbErrors == 0 ) ? '' : ' error' );?>">
				<?php
					echo $this->Xform->input( 'Cui.dureehebdosalarieheure', array( 'div' => false, 'label' => false, 'type' => 'text' ) ).' H '.$this->Xform->input( 'Cui.dureehebdosalarieminute', array( 'div' => false, 'label' => false, 'type' => 'text' ) );

					if( !empty( $errors ) ) {
						echo '<ul class="error">';
						if( !empty( $errors['dureehebdosalarieheure'] ) ) {
							echo '<li><strong>Heure:</strong> '.$errors['dureehebdosalarieheure'].'</li>';
						}
						if( !empty( $errors['dureehebdosalarieminute'] ) ) {
							echo '<li><strong>Minutes:</strong> '.$errors['dureehebdosalarieminute'].'</li>';
						}
						echo '</ul>';
					}
				?>
			</td>
			<td class="noborder">
				<?php
					echo $this->Default->subform(
						array(
							'Cui.modulation' => array( 'label' => __d( 'cui', 'Cui.modulation' ), 'type' => 'radio', 'options' => $options['Cui']['modulation'] )
						),
						array(
							'domain' => $domain,
							'options' => $options
						)
					);
				?>
			</td>
		</tr>
	</table>

</fieldset>

<!--********************* Les actions d'accompagnement et de formation prévues ********************** -->
<fieldset>
	<legend>LES ACTIONS D'ACCOMPAGNEMENT ET DE FORMATION PRÉVUES</legend>
	<?php
		$dataSelectedPresta = ( isset( $this->request->data['Cui']['orgsuivi_id'] ) && isset( $this->request->data['Cui']['prestataire_id'] ) ) ? ( $this->request->data['Cui']['prestataire_id'].'_'.$this->request->data['Cui']['prestataire_id'] ) : null;
		$selectedPresta = ( isset( $cui['Cui']['orgsuivi_id'] ) && isset( $cui['Cui']['prestataire_id'] ) ) ? ( $cui['Cui']['prestataire_id'].'_'.$cui['Cui']['prestataire_id'] ) : $dataSelectedPresta;

		echo $this->Default->subform(
			array(
				'Cui.tuteur',
				'Cui.fonctiontuteur',
				'Cui.orgsuivi_id' => array( 'options' => $structs, 'empty' => true ),
				'Cui.prestataire_id' => array( 'options' => $prestataires, 'empty' => true, 'selected' => $selectedPresta ),
				'Cui.referent_id' => array( 'options' => $referents, 'empty' => true ),
				'Cui.isaas' => array( 'label' => __d( 'cui', 'Cui.isaas' ), 'type' => 'radio', 'options' => $options['Cui']['isaas'] )
			),
			array(
				'domain' => $domain,
				'options' => $options
			)
		);
	?>
	<table class="cui5 noborder">
		<tr>
			<td class="noborder">
				<?php
					echo $this->Xhtml->tag(
						'p',
						'Actions d\'accompagnement professionnel',
						array(
							'class' => 'center'
						)
					);
				?>
			</td>
			<td class="noborder">
				<?php
					echo $this->Xhtml->tag(
						'p',
						'Actions de formation',
						array(
							'class' => 'center'
						)
					);
				?>
			</td>
		</tr>
		<tr>
			<td colspan="2" class="noborder">
				<?php
					echo $this->Xhtml->tag(
						'div',
						'Indiquez 1, 2 ou 3 dans la case selon que l\'action est mobilisée à l\'initiative de: 1 l\'employeur, 2 le salarié, 3 le prescripteur',
						array(
							'class' => 'remarque aere'
						)
					);
				?>
			</td>
		</tr>
		<tr>
			<td class="cui5 noborder">
				<?php
					echo $this->Xhtml->tag( 'p', 'Type d\'actions : ' );
					echo $this->Default->subform(
						array(
							'Cui.remobilisation' => array( 'type' => 'select', 'empty' => true, 'options' => $options['Cui']['remobilisation'] ),
							'Cui.aidereprise' => array( 'type' => 'select', 'empty' => true, 'options' => $options['Cui']['aidereprise'] ),
							'Cui.elaboprojetpro' => array( 'type' => 'select', 'empty' => true, 'options' => $options['Cui']['elaboprojetpro'] ),
							'Cui.evaluation' => array( 'type' => 'select', 'empty' => true, 'options' => $options['Cui']['evaluation'] ),
							'Cui.aiderechemploi' => array( 'type' => 'select', 'empty' => true, 'options' => $options['Cui']['aiderechemploi'] ),
							'Cui.autre' => array( 'type' => 'text' )
						),
						array(
							'domain' => $domain,
							'options' => $options
						)
					);
				?>
			</td>
			<td class="cui5 noborder">
				<?php
					echo $this->Xhtml->tag( 'p', 'Type d\'actions : ' );
					echo $this->Default->subform(
						array(
							'Cui.adaptation' => array( 'type' => 'select', 'empty' => true, 'options' => $options['Cui']['adaptation'] ),
							'Cui.remiseniveau' => array( 'type' => 'select', 'empty' => true, 'options' => $options['Cui']['remiseniveau'] ),
							'Cui.prequalification' => array( 'type' => 'select', 'empty' => true, 'options' => $options['Cui']['prequalification'] ),
							'Cui.nouvellecompetence' => array( 'type' => 'select', 'empty' => true, 'options' => $options['Cui']['nouvellecompetence'] ),
							'Cui.formqualif' => array( 'type' => 'select', 'empty' => true, 'options' => $options['Cui']['formqualif'] ),
							'Cui.formation' => array( 'type' => 'radio', 'label' => __d( 'cui', 'Cui.formation' ), 'options' => $options['Cui']['formation'] ),
							'Cui.isperiodepro' => array( 'type' => 'radio', 'label' => __d( 'cui', 'Cui.isperiodepro' ), 'options' => $options['Cui']['isperiodepro'] )
						),
						array(
							'domain' => $domain,
							'options' => $options
						)
					);
				?>
				<fieldset id="niveauqualif" class="invisible">
					<?php
						echo $this->Default->subform(
							array(
								'Cui.niveauqualif' => array( 'options' => $options['Cui']['niveauformation'], 'empty' => true )
							),
							array(
								'domain' => $domain,
								'options' => $options
							)
						);
					?>
				</fieldset>
				<?php
					echo $this->Xhtml->tag( 'p', 'Une ou plusieurs de ces actions s\'inscrivent elles dans le cadre de la validation des acquis de l\'expérience ?' );
					echo $this->Default->subform(
						array(
							'Cui.validacquis' => array( 'type' => 'radio', 'legend' => false, 'options' => $options['Cui']['validacquis'] )
						),
						array(
							'domain' => $domain,
							'options' => $options
						)
					);
				?>
			</td>
		</tr>
	</table>
</fieldset>
<fieldset id="iscae" class="invisible">
	<?php
		echo $this->Default->subform(
			array(
				'Cui.iscae' => array( 'type' => 'radio', 'legend' => __d( 'cui', 'Cui.iscae' ), 'options' => $options['Cui']['iscae'] )
			),
			array(
				'domain' => $domain,
				'options' => $options
			)
		);
	?>
	<fieldset id="periodeimmersion" class="invisible">
		<fieldset>
			<legend>L'ENTREPRISE D'ACCUEIL</legend>
			<?php
				echo $this->Xform->input( 'Accompagnementcui66.user_id', array( 'type' => 'hidden', 'value' => $userConnected ) );

				echo $this->Xform->input( 'Accompagnementcui66.typeaccompagnementcui66', array( 'required' => true, 'label' => __d( 'accompagnementcui66', 'Accompagnementcui66.typeaccompagnementcui66' ), 'type' => 'select', 'options' => $options['Accompagnementcui66']['typeaccompagnementcui66'], 'empty' => true ) );

				echo $this->Default->subform(
					array(
						'Accompagnementcui66.cui_id' => array( 'type' => 'hidden' ),
						'Accompagnementcui66.nomentaccueil',
						'Accompagnementcui66.numvoieentaccueil',
						'Accompagnementcui66.typevoieentaccueil' => array( 'options' => $options['typevoie'] ),
						'Accompagnementcui66.nomvoieentaccueil',
						'Accompagnementcui66.compladrentaccueil',
						'Accompagnementcui66.codepostalentaccueil',
						'Accompagnementcui66.villeentaccueil',
						'Accompagnementcui66.numtelentaccueil',
						'Accompagnementcui66.emailentaccueil',
						'Accompagnementcui66.activiteentaccueil',
						'Accompagnementcui66.siretentaccueil'
					),
					array(
						'options' => $options
					)
				);

			?>
		</fieldset>
		<fieldset>
			<legend>PÉRIODE D'IMMERSION</legend>
			<?php
				echo $this->Default->subform(
					array(
						'Accompagnementcui66.datedebperiode' => array( 'dateFormat' => 'DMY', 'minYear' => date('Y')-2, 'maxYear' => date('Y')+2, 'empty' => false ),
						'Accompagnementcui66.datefinperiode' => array( 'dateFormat' => 'DMY', 'minYear' => date('Y')-2, 'maxYear' => date('Y')+2, 'empty' => false )
					),
					array(
						'options' => $options
					)
				);

			?>
			<table class="periodeimmersion wide aere noborder">
				<tr>
					<td class="noborder mediumSize">Soit un nombre de jours équivalent à </td>
					<td class="noborder mediumSize" id="Accompagnementcui66Nbjourperiode"></td>
				</tr>
			</table>
			<?php
				echo $this->Default->subform(
					array(
						'Accompagnementcui66.secteuraffectation_id' => array( 'empty' => true, 'options' => $secteursactivites ),
						'Accompagnementcui66.metieraffectation_id' => array( 'empty' => true, 'options' => $options['Coderomemetierdsp66'], 'selected' => @$this->request->data['Accompagnementcui66']['secteuraffectation_id'].'_'.@$this->request->data['Accompagnementcui66']['metieraffectation_id'] ),
						'Accompagnementcui66.objectifimmersion' => array( 'type' => 'radio', 'separator' => '<br />', 'options' => $options['Accompagnementcui66']['objectifimmersion'] ),
						'Accompagnementcui66.datesignatureimmersion' => array( 'dateFormat' => 'DMY', 'minYear' => date('Y')-2, 'maxYear' => date('Y')+2, 'empty' => false )
					),
					array(
						'options' => $options
					)
				);
			?>
		</fieldset>
	</fieldset>
</fieldset>

<script type="text/javascript" >

function calculNbDays() {
	var Datedebperiode = $F( 'Accompagnementcui66DatedebperiodeDay' );
	var Datefinperiode = $F( 'Accompagnementcui66DatefinperiodeDay' );

	$( 'Accompagnementcui66Nbjourperiode' ).update( ( Datefinperiode - Datedebperiode ) );
}

$( 'Accompagnementcui66DatefinperiodeDay' ).observe( 'blur', function( event ) { calculNbDays(); } );
</script>
<!--********************* La prise en charge (cadre réservé au prescripteur) ********************** -->
<fieldset>
	<legend>LA PRISE EN CHARGE (CADRE RÉSERVÉ AU PRESCRIPTEUR)</legend>
	<table class="cui5 noborder">
		<tr>
			<td class="noborder">
				<?php
					echo $this->Default->subform(
						array(
							'Cui.datedebprisecharge' => array( 'dateFormat' => 'DMY', 'minYear' => date( 'Y' ) - 2, 'maxYear' => date( 'Y' ) + 2, 'empty' => true )
						),
						array(
							'domain' => $domain,
							'options' => $options
						)
					);
					echo $this->Xhtml->tag( 'em','(identique à la date d\'embauche si convention initiale)' );
				?>
			</td>
			<td class="noborder">
				<?php
					echo $this->Default->subform(
						array(
							'Cui.datefinprisecharge' => array( 'dateFormat' => 'DMY', 'minYear' => date( 'Y' ) - 2, 'maxYear' => date( 'Y' ) + 2, 'empty' => true )
						),
						array(
							'domain' => $domain,
							'options' => $options
						)
					);
				?>
			</td>
		</tr>
	</table>
	<table class="cuiduree noborder">
		<tr>
			<?php
				$nbErrors3 = count($this->validationErrors['Cui']);
				$errors = array(
					'dureehebdoretenueheure' => Set::extract( $this->validationErrors, 'Cui.dureehebdoretenueheure' ),
					'dureehebdoretenueminute' => Set::extract( $this->validationErrors, 'Cui.dureehebdoretenueminute' ),
				);
				unset(
					$this->validationErrors['Cui']['dureehebdoretenueheure'],
					$this->validationErrors['Cui']['dureehebdoretenueminute']
				);
				$errors = Hash::filter( $errors );
			?>
			<td class="dureehebdo noborder<?php echo ( ( $nbErrors3 == 0 ) ? '' : ' error' );?>"><?php echo required( 'Durée hebdomadaire retenue pour le calcul de l\'aide' ); ?></td>
			<td class="dureehebdo noborder<?php echo ( ( $nbErrors3 == 0 ) ? '' : ' error' );?>">
				<?php
					echo $this->Xform->input( 'Cui.dureehebdoretenueheure', array( 'div' => false, 'label' => false, 'type' => 'text' ) ).' H '.$this->Xform->input( 'Cui.dureehebdoretenueminute', array( 'div' => false, 'label' => false, 'type' => 'text' ) );

					if( !empty( $errors ) ) {
						echo '<ul class="error">';
						if( !empty( $errors['dureehebdoretenueheure'] ) ) {
							echo '<li><strong>Heure:</strong> '.$errors['dureehebdoretenueheure'].'</li>';
						}
						if( !empty( $errors['dureehebdoretenueminute'] ) ) {
							echo '<li><strong>Minutes:</strong> '.$errors['dureehebdoretenueminute'].'</li>';
						}
						echo '</ul>';
					}
				?>
			</td>
			<td class="noborder">
				<?php
					echo $this->Default->subform(
						array(
							'Cui.opspeciale' => array( 'type' => 'text' )
						),
						array(
							'domain' => $domain,
							'options' => $options
						)
					);

				?>
			</td>
		</tr>
	</table>

		<table class="nbrCi wide noborder">
			<tr class="nbrCi">
				<td class="noborder">
					<?php
						echo $this->Default->subform(
							array(
								'Cui.tauxfixe' => array( 'value' => isset( $this->request->data['Cui']['tauxfixe'] ) ? $this->request->data['Cui']['tauxfixe'] : Configure::read( 'Cui.taux.fixe' ) )
							),
							array(
								'domain' => $domain,
								'options' => $options
							)
						);
					?>
				</td>
				<!-- <td class="noborder"><?php /*echo __d( 'cui', 'Cui.tauxfixe' ); ?></td>
				<td class="noborder"> <?php echo Configure::read( 'Cui.taux.fixe' ).' %' ; */?> </td> -->
			</tr>
		</table>
		<table class="wide noborder">
			<tr>
				<td class="noborder">
				<?php
					echo $this->Xhtml->tag( 'hr /');

					echo $this->Xhtml->tag( 'p','Dans le cas d\'un contrat prescrit par le Conseil Général ou pour son compte (sur la base d\'une convention d\'objectifs et de moyens)', array( 'class' => 'aere' ) );
				?>
				</td>
			</tr>
		</table>
		<table class="nbrCi wide noborder">
			<tr class="nbrCi">
				<td class="noborder">
					<?php
						echo $this->Default->subform(
							array(
								'Cui.tauxprisencharge' => array( 'value' => isset( $this->request->data['Cui']['tauxprisencharge'] ) ? $this->request->data['Cui']['tauxprisencharge'] : Configure::read( 'Cui.taux.prisencharge' ) )
							),
							array(
								'domain' => $domain,
								'options' => $options
							)
						);
					?>
				</td>
				<!-- <td class="noborder"><?php /*echo __d( 'cui', 'Cui.tauxfixe' ); ?></td>
				<td class="noborder"> <?php echo Configure::read( 'Cui.taux.fixe' ).' %' ; */?> </td> -->
			</tr>
		</table>
		<table class="wide noborder">
			<tr>
				<td class="noborder">
				<?php    echo $this->Default->subform(
						array(
							'Cui.financementexclusif' => array( 'type' => 'radio', 'legend' => __d( 'cui', 'Cui.financementexclusif' ), 'options' => $options['Cui']['financementexclusif'] )
						),
						array(
							'domain' => $domain,
							'options' => $options
						)
					);
				?>
				</td>
			</tr>
		</table>
		<fieldset id="financementexclusif" class="invisible">
			<table class="nbrCi wide noborder">
				<tr class="nbrCi">

					<td class="noborder">
						<?php
							echo $this->Default->subform(
								array(
									'Cui.tauxfinancementexclusif' => array( 'value' => isset( $this->request->data['Cui']['tauxfinancementexclusif'] ) ? $this->request->data['Cui']['tauxfinancementexclusif'] : Configure::read( 'Cui.taux.financementexclusif' ) )
								),
								array(
									'domain' => $domain,
									'options' => $options
								)
							);
// 								echo __d( 'cui', 'Cui.tauxfinancementexclusif' );
						?>
					</td>
<!--						<td class="noborder">
						<?php echo Configure::read( 'Cui.taux.financementexclusif' ).' %' ;?>
					</td>-->

				</tr>

			</table>
		</fieldset>
		<table class="wide noborder">
			<tr>
				<td class="noborder">
				<fieldset id="organisme" class="invisible">
					<?php
						if( Configure::read( 'nom_form_cui_cg' ) == 'cg93' ){
							echo $this->Default->subform(
								array(
									'Cui.orgapayeur' => array(  'type' => 'radio', 'legend' => __d( 'cui', 'Cui.orgapayeur' ), 'options' => $options['Cui']['orgapayeur'], 'value' => 'ASP' ),
									'Cui.organisme' => array( 'value' => 'Agence de Services et de Paiement Délégation régionale Ile de France' ),
									'Cui.adresseorganisme' => array( 'value' => 'Le Cérame hall 1  47 avenue des Genottes BP 8460 95 807 CERGY PONTOISE CEDEX' )
								),
								array(
									'domain' => $domain,
									'options' => $options
								)
							);
						}
// 							else if( Configure::read( 'nom_form_cui_cg' ) == 'cg66' ){
// 								echo $this->Default->subform(
// 									array(
// 										'Cui.orgapayeur' => array(  'type' => 'radio', 'legend' => __d( 'cui', 'Cui.orgapayeur' ), 'options' => $options['Cui']['orgapayeur'] ),
// 										'Cui.organisme',
// 										'Cui.adresseorganisme'
// 									),
// 									array(
// 										'domain' => $domain,
// 										'options' => $options
// 									)
// 								);
// 							}
					?>
				</fieldset>
				<?php
					echo $this->Default->subform(
						array(
							'Cui.datecontrat' => array( 'dateFormat' => 'DMY', 'minYear' => date( 'Y' ) - 2, 'maxYear' => date( 'Y' ) + 2, 'empty' => false )
						),
						array(
							'domain' => $domain,
							'options' => $options
						)
					);
				?>
			</td>
		</tr>
	</table>
</fieldset>

<div class="submit">
	<?php
		echo $this->Xform->submit( 'Enregistrer', array( 'div' => false ) );
		echo $this->Xform->submit( 'Annuler', array( 'name' => 'Cancel', 'div' => false ) );
	?>
</div>
<?php echo $this->Xform->end();?>