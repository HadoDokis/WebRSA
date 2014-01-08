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
		observeDisableFieldsetOnRadioValue(
			'cuiform',
			'data[Cui][typecontrat]',
			$( 'iscdd' ),
			'CDD',
			false,
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

		dependantSelect( 'CuiReferentId', 'CuiOrgsuiviId' );

//		dependantSelect( 'Accompagnementcui66MetieraffectationId', 'Accompagnementcui66SecteuraffectationId' );
//		try { $( 'Accompagnementcui66MetieraffectationId' ).onchange(); } catch(id) { }

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
				'Cui.personne_id' => array( 'value' => $this->request->data['Cui']['personne_id'], 'type' => 'hidden' ),
				'Cui.user_id' => array( 'type' => 'hidden', 'value' => $this->request->data['Cui']['user_id'] ),
				'Cui.montantrsapercu' => array( 'type' => 'hidden', 'value' => $this->request->data['Cui']['montantrsapercu'] ),
                'Cui.naturersa' => array( 'type' => 'hidden', 'value' => $this->request->data['Cui']['naturersa'] )
			),
			array(
				'domain' => $domain,
				'options' => $options
			)
		);
	?>
	<fieldset>
		<legend></legend>
		<?php
			echo $this->Default2->subform(
				array(
					'Cui.typecui' => array(  'required' => true, 'type' => 'select', 'empty' => true, 'options' => $options['Cui']['typecui'] )
				),
				array(
					'options' => $options
				)
			);

		?>
	</fieldset>
	<fieldset>
		<legend><?php echo required( __d( 'cui', 'Cui.secteur' )  );?></legend>
		<?php
			echo $this->Default2->subform(
				array(
					'Cui.secteurcui_id' => array(  'legend' => false, 'type' => 'select', 'options' => $secteurscuis )
				),
				array(
					'options' => $options
				)
			);

		?>
		<fieldset id="isaci" class="noborder">
			<?php
				echo $this->Default2->subform(
					array(
						'Cui.isaci' => array( 'type' => 'select', 'options' => $options['Cui']['isaci'] )
					),
					array(
						'domain' => $domain,
						'options' => $options
					)
				);
			?>
		</fieldset>
		<fieldset id="cdiae" class="noborder" style="padding: 0 0;">
			<?php
				echo $this->Default2->subform(
					array(
						'Cui.codeagrementcdiae'
					),
					array(
						'options' => $options
					)
				);

			?>
		</fieldset>
		<?php
			echo $this->Default2->subform(
				array(
					'Cui.numconvention',
					'Cui.numconventionobj' => array( 'type' => 'hidden', 'value' => ( isset( $this->request->data['Cui']['numconventionobj'] ) ? $this->request->data['Cui']['numconventionobj'] : null ) )
				),
				array(
					'options' => $options
				)
			);

            echo $this->Xform->fieldValue( 'Cui.numconventionobj', $this->request->data['Cui']['numconventionobj'], true, 'text' );

		?>
	</fieldset>
</div>
<script type="text/javascript">
//<![CDATA[
	document.observe( "dom:loaded", function() {
		observeDisableFieldsetOnValue(
			'CuiSecteurcuiId',
			$( 'isaci' ),
			['<?php echo implode( "', '", $secteur_isnonmarchand_id );?>'],
			false,
			true
		);

		observeDisableFieldsetOnValue(
			'CuiSecteurcuiId',
			$( 'iscae' ),
			['<?php echo implode( "', '", $secteur_isnonmarchand_id );?>'],
			false,
			true
		);

		observeDisableFieldsetOnValue(
			'CuiSecteurcuiId',
			$( 'iscie' ),
			['<?php echo implode( "', '", array_diff( array_keys( $secteurscuisForm['Secteurcui']['id'] ), $secteur_isnonmarchand_id ) );?>'],
			false,
			true
		);

		//on masque le code d'agréement sauf pour le CAE -> ACI
		observeDisableFieldsetOnValue(
			'CuiIsaci',
			$( 'cdiae' ),
			['enaci'],
			false,
			true
		);

		observeDisableFieldsetOnValue(
			'CuiTypecui',
			$( 'aidecg' ),
			['cuieav'],
			false,
			true
		);
	} );
//]]>
</script>

<!--**************************************** Partie EMPLOYEUR *********************************************** -->
<script type="text/javascript">
	//Sélection de l'employeur et de l'action liée à cet employeur
	document.observe("dom:loaded", function() {
		dependantSelect( 'CuiActioncandidatId', 'CuiPartenaireId' );

		observeDisableFieldsetOnValue(
			'CuiNewemployeur',
			$( 'newemployeur' ),
			['1'],
			false,
			true
		);

		observeDisableFieldsetOnValue(
			'CuiNewemployeur',
			$( 'employeur' ),
			['', undefined],
			true,
			true
		);

		<?php
			echo $this->Ajax->remoteFunction(
				array(
					'update' => 'CuiEmployeur',
					'url' => Router::url( array( 'action' => 'ajaxemployeur', Set::extract( $this->request->data, 'Cui.partenaire_id' ), suffix( Set::extract( $this->request->data, 'Cui.actioncandidat_id' ) ) ), true)
				)
			).';';
		?>
	});
</script>

<fieldset>
	<legend>L'EMPLOYEUR</legend>
	<?php
		// Si aucun employeur n'a été choisi mais qu'un nouvel employeur est créé
		$newemployeur = '0';
		if( !empty( $this->request->data['Cui']['newemployeur'] ) ) {
			$newemployeur = '1';
		}
		echo $this->Xform->input( 'Cui.newemployeur', array( 'label' => 'Nouvel employeur ?', 'type' => 'checkbox', 'checked' => $newemployeur ) );
	?>
	<fieldset id="employeur" class="noborder">
		<?php
			echo $this->Xform->inputs(
				array(
					'fieldset' => false,
					'legend' => false,
					'Cui.partenaire_id' => array( 'label' => __d( 'cui', 'Cui.partenaire_id' ), 'type' => 'select', 'options' => $employeursCui, 'empty' => true ),
					'Cui.actioncandidat_id' => array( 'label' => __d( 'cui', 'Cui.actioncandidat_id' ), 'type' => 'select', 'options' => $valeursactionsparpartenaires, 'empty' => true )
				)
			);
			//Partie ajax pour afficher l'employeur + action sélectionnée
			echo $this->Ajax->observeField( 'CuiPartenaireId', array( 'update' => 'CuiEmployeur', 'url' => Router::url( array( 'action' => 'ajaxemployeur' ), true ), 'with' => 'Form.serialize( $( \'cuiform\' ) )' ) );
			echo $this->Ajax->observeField( 'CuiActioncandidatId', array( 'update' => 'CuiEmployeur', 'url' => Router::url( array( 'action' => 'ajaxemployeur' ), true ), 'with' => 'Form.serialize( $( \'cuiform\' ) )' ) );

			echo $this->Xhtml->tag(
				'div',
				' ',
				array(
					'id' => 'CuiEmployeur'
				)
			);


		?>
	</fieldset>
	<fieldset id="newemployeur" class="noborder">
		<table class="noborder">
			<tr>
				<td class="cui1 noborder">
					<fieldset>
					<legend>Employeur</legend>
						<?php
							echo $this->Default2->subform(
								array(
									'Partenaire.id' => array( 'type' => 'hidden'),
									'Cui.partenaire_id' => array( 'type' => 'hidden' ),
									'Partenaire.libstruc' => array( 'required' => true ),
									'Partenaire.codepartenaire',
									'Partenaire.numvoie' => array( 'required' => true ),
									'Partenaire.typevoie' => array( 'options' => $options['typevoie'], 'required' => true ),
									'Partenaire.nomvoie' => array( 'required' => true ),
									'Partenaire.compladr',
									'Partenaire.numtel',
									'Partenaire.numfax',
									'Partenaire.email',
									'Partenaire.codepostal' => array( 'required' => true )
								)
							);
							if( Configure::read( 'Cg.departement' ) == '66' ) {
								echo $this->Default2->subform(
									array(
										'Partenaire.canton' => array( 'options' => $cantons, 'empty' => true )
									)
								);
							}

							echo $this->Default2->subform(
								array(
									'Partenaire.ville' => array( 'required' => true ),
									'Partenaire.iscui' => array( 'type' => 'hidden', 'value' => 1 )
								)
							);

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
//							if( !empty( $thisDataAdressebis ) ) {
//								$valueAdressebis = $thisDataAdressebis;
//							}
							$input =  $this->Form->input( 'Cui.isadresse2', array( 'type' => 'radio' , 'options' => $options['Cui']['isadresse2'], 'div' => false, 'legend' => __d( 'cui', 'Cui.isadresse2' ), 'value' => $thisDataAdressebis ) );
							echo $this->Xhtml->tag( 'div', $input, array( 'class' => $class ) );
						?>
                            <fieldset id="Adressebis">
                                <?php
                                    echo $this->Default2->subform(
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
					<td class="cui1 noborder">
						<fieldset>
						<?php
							echo $this->Default2->subform(
								array(
									'Partenaire.secteuractivitepartenaire_id' => array( 'empty' => true, 'options' => $secteursactivites ),
									'Partenaire.statut' => array( 'empty' => true, 'options' => $options['Cui']['statutemployeur'] ),
									'Partenaire.raisonsocialepartenairecui66_id' => array( 'empty' => true, 'options' => $options['Cui']['raisonsocialepartenairecui66_id'] ),
									'Partenaire.siret',
									'Partenaire.nomtiturib',
									'Partenaire.codeban',
									'Partenaire.guiban',
									'Partenaire.numcompt',
									'Partenaire.nometaban',
									'Partenaire.clerib',
									'Partenaire.orgrecouvcotis' => array( 'type' => 'radio', 'empty' => false, 'options' => $options['Cui']['orgrecouvcotis'] )
								)
							);

						?>
					</fieldset>
				</td>
			</tr>
		</table>
	</fieldset>

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
$codepos = Set::classicExtract( $dataCaf, 'Adresse.codepos' );
$depSplit = substr( $codepos, '0', 2 );
?>
<fieldset>
	<legend>LE SALARIÉ</legend>
	<table class="wide noborder">
		<tr>
			<td class="mediumSize noborder">
				<strong>Nom : </strong><?php echo Set::enum( Set::classicExtract( $dataCaf, 'Personne.qual'), $qual ).' '.Set::classicExtract( $dataCaf, 'Personne.nom' );?>
				<br />
				<?php if(  Set::classicExtract( $dataCaf, 'Personne.qual') != 'MR' ):?>
					<strong>Pour les femmes, nom patronymique : </strong><?php echo Set::classicExtract( $dataCaf, 'Personne.nomnai' );?>
				<?php endif;?>
				<br />
				<strong>Né(e) le : </strong>
					<?php
						echo date_short( Set::classicExtract( $dataCaf, 'Personne.dtnai' ) ).' <strong>à</strong>  '.Set::classicExtract( $dataCaf, 'Personne.nomcomnai' );
					?>
				<br />
				<strong>Adresse : </strong><br />
					<?php
						echo Set::extract( $dataCaf, 'Adresse.numvoie' ).' '.Set::extract( $options['typevoie'], Set::extract( $dataCaf, 'Adresse.typevoie' ) ).' '.Set::extract( $dataCaf, 'Adresse.nomvoie' ).'<br /> '.Set::extract( $dataCaf, 'Adresse.compladr' ).'<br /> '.Set::extract( $dataCaf, 'Adresse.codepos' ).' '.Set::extract( $dataCaf, 'Adresse.locaadr' );
					?>
				<br />
				<!-- Si on n'autorise pas la diffusion de l'email, on n'affiche rien -->
				<?php if( Set::extract( $dataCaf, 'Foyer.Modecontact.0.autorutiadrelec' ) == 'A' ):?>
					<strong>Adresse électronique : </strong><?php echo Set::extract( $dataCaf, 'Foyer.Modecontact.0.adrelec' );?>
				<?php endif;?>
			</td>
			<td class="mediumSize noborder">
				<strong>Prénoms : </strong><?php echo Set::classicExtract( $dataCaf, 'Personne.prenom' );?>
				<br />
				<strong>NIR : </strong><?php echo Set::classicExtract( $dataCaf, 'Personne.nir');?>
				<br />
				<strong>Département : </strong><?php echo Set::extract( $depSplit, $dept );?>
				<br />
				<strong>Canton : </strong><?php echo Set::extract( $dataCaf, 'Canton.canton' );?>
				<br />
				<strong>Nationalité : </strong><?php echo Set::enum( Set::classicExtract( $dataCaf, 'Personne.nati' ), $nationalite );?>
				<br />
				<strong>Référent en cours : </strong><?php echo Set::enum( Set::classicExtract( $dataCaf, 'Referent.qual' ), $qual ).' '.Set::classicExtract( $dataCaf, 'Referent.nom' ).' '.Set::classicExtract( $dataCaf, 'Referent.prenom' );?>
				<br />
				<!-- Si on n'autorise aps la diffusion du téléphone, on n'affiche rien -->
				<?php if( Set::extract( $dataCaf, 'Foyer.Modecontact.0.autorutitel' ) == 'A' ):?>
					<strong>Numéro de téléphone 1 : </strong><?php echo Set::extract( $dataCaf, 'Foyer.Modecontact.0.numtel' );?>
					<br />
					<strong>Numéro de téléphone 2 : </strong><?php echo Set::extract( $dataCaf, 'Foyer.Modecontact.1.numtel' );?>
				<?php endif;?>
			</td>
		</tr>
		<tr>
			<td class="noborder" colspan="2">
				<?php
					echo $this->Default->subform(
						array(
							'Cui.zoneadresseallocataire' => array( 'label' => '<strong>Zone couverte par l\'adresse de l\'allocataire</strong>', 'type' => 'select', 'options' => $options['Cui']['zoneadresseallocataire'] )
						),
						array(
							'domain' => $domain,
							'options' => $options
						)
					);
				?>
			</td>
		</tr>
		<tr>
			<td class="noborder" colspan="2">
				<?php
					echo $this->Default->subform(
						array(
							'Cui.datefintitresejour' => array( 'label' => '<strong>Date de fin de titre de séjour</strong>', 'dateFormat' => 'DMY', 'minYear' => date( 'Y' ) - 10, 'maxYear' => date( 'Y' ) + 3, 'selected' => Set::classicExtract( $dataCaf, 'Titresejour.dftitsej' ) )
						),
						array(
							'domain' => $domain,
							'options' => $options
						)
					);
				?>
			</td>
		</tr>
		<tr>
			<td class="noborder" colspan="2">
				<strong>Si bénéficiaire RSA, n° allocataire : </strong>
				<?php
					echo Set::classicExtract( $dataCaf, 'Dossier.matricule' ).'  <strong>relève de : </strong> '.Set::classicExtract( $dataCaf, 'Dossier.fonorg' );
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

        $listeMontantAAfficher = null;
        foreach( unserialize( $this->request->data['Cui']['montantrsapercu'] ) as $key => $montant ) {
            if( !empty( $montant ) ) {
                $listeMontantAAfficher .= $this->Xhtml->tag( 'h3', '' ).'<ul><li>'.$montant.' €</li></ul>';
            }
        }
        $listeRSA = null;
        foreach( unserialize( $this->request->data['Cui']['naturersa'] ) as $key => $nature ) {
            if( !empty( $nature ) ) {
                $nature = Set::enum( $nature, $rsaSocle);
                $listeRSA .= $this->Xhtml->tag( 'h3', '' ).'<ul><li>'.$nature.'</li></ul>';
            }
        }
    ?>
    <?php if( !empty( $listeRSA ) ):?>
        <table class="aere tooltips">
            <thead>
                <tr>
                    <th>Nature(s) RSA</th>
                    <th>Montant(s) RSA</th>
                </tr>
            </thead>
            <tbody>
                <?php echo $this->Xhtml->tableCells(
                    array(
                        $listeRSA,
                        $listeMontantAAfficher
                    )
                );
                ?>
            </tbody>
        </table>
    <?php else:?>
    <p class="notice">Aucune prestation trouvée</p>
    <?php endif;?>

    <?php
//       echo $this->Xform->fieldValue( 'Cui.naturersa', $listeNatureRSA, true, 'text' );

		echo $this->Xform->fieldValue( 'Cui.nbperscharge', Hash::get( $dataCaf, 'Foyer.nbenfants' ), true, 'text' );

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


			$isInscrit = '';
			$dernierIdentifiantpe = Set::classicExtract( $this->request->data, 'Historiqueetatpe.identifiantpe' );
			if( !empty( $dernierIdentifiantpe ) ){
				$isInscrit = '1';
			}
			else{
				$isInscrit = '0';
			}

			echo $this->Default->subform(
				array(
					'Cui.isinscritpe' => array( 'domain' => 'cui', 'type' => 'select', 'options' => $options['Cui']['isinscritpe'], 'empty' => true, 'selected' => $isInscrit )
				),
				array(
					'domain' => $domain,
					'options' => $options
				)
			);

            echo $this->Default->subform(
				array(
					'Cui.dateinscritpe' => array( 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date('Y'), 'minYear' => date( 'Y' ) - 50, 'selected' => isset( $this->request->data['Cui']['dateinscritpe'] ) ? $this->request->data['Cui']['dateinscritpe'] : null  )
				),
				array(
					'domain' => $domain,
					'options' => $options
				)
			);

            echo $this->Default->subform(
				array(
					'Cui.identifiantpe' => array( 'value' => isset( $this->request->data['Cui']['identifiantpe'] ) ? $this->request->data['Cui']['identifiantpe'] : $dernierIdentifiantpe  )
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
<script type="text/javascript">
document.observe("dom:loaded", function() {
	observeDisableFieldsOnValue( 'CuiIsinscritpe', [ 'CuiDateinscritpeYear', 'CuiDateinscritpeMonth', 'CuiDateinscritpeDay' ], 0, true, true );
});
</script>
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

		echo $this->Default->subform(
			array(
				'Cui.dateembauche' => array( 'dateFormat' => 'DMY', 'minYear' => date( 'Y' ) - 10, 'maxYear' => date( 'Y' ) + 3 ),
			),
			array(
				'domain' => $domain,
				'options' => $options
			)
		);

		echo '<fieldset id="iscdd" class="noborder" style="padding:0 0em;">';
			echo $this->Default->subform(
				array(
					'Cui.dureecdd' => array( 'type' => 'select', 'options' => $options['dureeprisecharge'], 'empty' => true )
				),
				array(
					'domain' => $domain,
					'options' => $options
				)
			);
			echo $this->Default->subform(
				array(
					'Cui.datefincontrat' => array( 'dateFormat' => 'DMY', 'minYear' => date( 'Y' ) - 10, 'maxYear' => date( 'Y' ) + 3 )
				),
				array(
					'domain' => $domain,
					'options' => $options
				)
			);
		echo '</fieldset>';
	?>


	<?php
		if( empty( $options['Coderomemetierdsp66'] ) ) {
			echo '<p class="notice">Veuillez paramétrer les tables codes ROME</p>';
		}
		else {
			echo $this->Default->subform(
				array(
					'Cui.secteuremploipropose_id' => array( 'options' => $secteursactivites ),
//					'Cui.metieremploipropose_id' => array( 'options' => $options['Coderomemetierdsp66'], 'selected' => $selected ),
					'Cui.metieremploipropose_id' => array( 'options' => $options['Coderomemetierdsp66'] ),
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
				$errors = Hash::filter( (array)$errors );
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
		$dataSelectedPresta = ( isset( $this->request->data['Cui']['orgsuivi_id'] ) && isset( $this->request->data['Cui']['referent_id'] ) ) ? ( $this->request->data['Cui']['orgsuivi_id'].'_'.$this->request->data['Cui']['referent_id'] ) : null;
		$selectedPresta = ( isset( $cui['Cui']['orgsuivi_id'] ) && isset( $cui['Cui']['referent_id'] ) ) ? ( $cui['Cui']['orgsuivi_id'].'_'.$cui['Cui']['referent_id'] ) : $dataSelectedPresta;

		echo $this->Default->subform(
			array(
				'Cui.tuteur',
				'Cui.fonctiontuteur',
				'Cui.orgsuivi_id' => array( 'options' => $structs, 'empty' => true ),
				'Cui.referent_id' => array( 'options' => $prestataires, 'empty' => true, 'selected' => $selectedPresta ),
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
							'Cui.isperiodepro' => array( 'type' => 'radio', 'label' => __d( 'cui', 'Cui.isperiodepro' ), 'options' => $options['Cui']['isperiodepro'] )
						),
						array(
							'domain' => $domain,
							'options' => $options
						)
					);
					echo '<fieldset><legend>Formations</legend>';
					echo $this->Default->subform(
						array(
							'Cui.formationinterne' => array( 'type' => 'checkbox', 'label' => __d( 'cui', 'Cui.formationinterne' ) ),
							'Cui.formationexterne' => array( 'type' => 'checkbox', 'label' => __d( 'cui', 'Cui.formationexterne' ) )
						),
						array(
							'fomain' => $domain,
							'options' => $options
						)
					);
					echo '</fieldset>';
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
</fieldset>

<!--********************* La prise en charge (cadre réservé au prescripteur) ********************** -->
<fieldset>
	<legend>LA PRISE EN CHARGE (CADRE RÉSERVÉ AU PRESCRIPTEUR)</legend>

	<?php
		echo $this->Default->subform(
			array(
				'Cui.dureeprisecharge' => array( 'type' => 'select', 'options' => $options['dureeprisecharge'], 'empty' => true ),
				'Cui.datedebprisecharge' => array( 'dateFormat' => 'DMY', 'minYear' => date( 'Y' ) - 10, 'maxYear' => date( 'Y' ) + 3, 'empty' => true )
			),
			array(
				'domain' => $domain,
				'options' => $options
			)
		);
		echo $this->Xhtml->tag( 'em','(identique à la date d\'embauche si convention initiale)' );

		echo $this->Default->subform(
			array(
				'Cui.datefinprisecharge' => array( 'dateFormat' => 'DMY', 'minYear' => date( 'Y' ) - 10, 'maxYear' => date( 'Y' ) + 3, 'empty' => true )
			),
			array(
				'domain' => $domain,
				'options' => $options
			)
		);
	?>
    <fieldset id="aidecg" class="noborder" style="padding: 0 0;">
        <?php
            echo $this->Default2->subform(
                array(
                    'Cui.aidecomplementairecg'
                ),
                array(
                    'options' => $options
                )
            );

        ?>
    </fieldset>
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
				$errors = Hash::filter( (array)$errors );
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
								'Cui.tauxfixe'// => array( 'value' => isset( $this->request->data['Cui']['tauxfixe'] ) ? $this->request->data['Cui']['tauxfixe'] : Configure::read( 'Cui.taux.fixe' ) )
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
								'Cui.tauxprisencharge'// => array( 'value' => isset( $this->request->data['Cui']['tauxprisencharge'] ) ? $this->request->data['Cui']['tauxprisencharge'] : Configure::read( 'Cui.taux.prisencharge' ) )
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
						?>
					</td>
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
					?>
				</fieldset>
				<?php
					echo $this->Default->subform(
						array(
							'Cui.datecontrat' => array( 'dateFormat' => 'DMY', 'minYear' => date( 'Y' ) - 10, 'maxYear' => date( 'Y' ) + 3, 'empty' => false ),
                            'Cui.datearrivee' => array( 'dateFormat' => 'DMY', 'minYear' => date( 'Y' ) - 10, 'maxYear' => date( 'Y' ) + 3, 'empty' => true )
						),
						array(
							'domain' => $domain,
							'options' => $options
						)
					);

                    $created = ( $this->action == 'add' ? date( 'Y-m-d' ) : $this->request->data['Cui']['created'] );
                    echo $this->Xform->fieldValue( 'Cui.created', $this->Locale->date( 'Date::lettre', $created ), true, 'text' );
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

<script type="text/javascript">
	var taux = [];
	<?php foreach( $taux_cgs_cuis as $t ): ?>
		<?php $isNonMarchand = ( in_array( $t['Tauxcgcui']['secteurcui_id'], $secteur_isnonmarchand_id ) ? 1 : 0 );?>
		taux.push( {
			'type': '<?php echo $t['Tauxcgcui']['typecui'];?>',
			'nonmarchand': '<?php echo $isNonMarchand;?>',
			'aci': '<?php echo $t['Tauxcgcui']['isaci'];?>',
			'nominal': '<?php echo $t['Tauxcgcui']['tauxnominal'];?>'
		} );
	<?php endforeach; ?>

 	function updateTauxfixeFromTypeSecteur( fieldType, fieldSecteur, fieldIsAci, fieldToUpdate ) {
		var calculable = ( $F( fieldType ) && $F( fieldSecteur ) );
		var isNonMarchand = in_array( $F( fieldSecteur ), ['<?php echo implode( "', '", $secteur_isnonmarchand_id );?>'] );

		// En cas de CUI, CAE, il faut choisir ACI ou Hors ACI
		if( $F( fieldType ) == 'cui' && isNonMarchand ) {
			calculable = calculable && $F( fieldIsAci );
		}

		if( !calculable ) {
			return;
		}

		taux.each( function( t ) {
			var found = (
				( $F( fieldType ) == t['type'] )
				&& ( ( isNonMarchand ? '1' : '0' ) == t['nonmarchand'] )
				&& ( $F( fieldIsAci ) == t['aci'] )
			);
			if( found ) {
				$( fieldToUpdate ).value = t['nominal'];
			}
		} );
 	}

 	<?php foreach( array( 'CuiTypecui', 'CuiSecteurcuiId', 'CuiIsaci' ) as $field ): ?>
		Event.observe( $( '<?php echo $field;?>' ), 'change', function() {
			updateTauxfixeFromTypeSecteur( 'CuiTypecui', 'CuiSecteurcuiId', 'CuiIsaci', 'CuiTauxfixe' );
		} );
	<?php endforeach; ?>
	updateTauxfixeFromTypeSecteur( 'CuiTypecui', 'CuiSecteurcuiId', 'CuiIsaci', 'CuiTauxfixe' );
</script>

	<script type="text/javascript">
		document.observe( "dom:loaded", function() {
			<?php
				$fields = array(
					'CuiDatedebprisecharge' => array(
						'duree' => 'CuiDureeprisecharge',
						'fin' => 'CuiDatefinprisecharge'
					),
					'CuiDateembauche' => array(
						'duree' => 'CuiDureecdd',
						'fin' => 'CuiDatefincontrat'
					),
				);
			?>
			<?php foreach( $fields as $debut => $t ):?>
				<?php foreach( array( 'Day', 'Month', 'Year' ) as $suffix ):?>
					Event.observe( $( '<?php echo "{$debut}{$suffix}";?>' ), 'change', function() {
						updateDateFromDateDuree(  '<?php echo $debut;?>', '<?php echo $t['duree'];?>', '<?php echo $t['fin'];?>' );
					} );
				<?php endforeach;?>
				Event.observe( $( '<?php echo $t['duree'];?>' ), 'change', function() {
					updateDateFromDateDuree(  '<?php echo $debut;?>', '<?php echo $t['duree'];?>', '<?php echo $t['fin'];?>' );
				} );

				updateDateFromDateDuree(  '<?php echo $debut;?>', '<?php echo $t['duree'];?>', '<?php echo $t['fin'];?>' );
			<?php endforeach;?>

		} );

	</script>