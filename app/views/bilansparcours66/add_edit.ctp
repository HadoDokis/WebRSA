<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php
	$domain = 'bilanparcours66';

	echo $this->element( 'dossier_menu', array( 'personne_id' => Set::classicExtract( $personne, 'Personne.id') ) );
?>

<?php
	if( $this->action == 'add'  ) {
		if( Configure::read( 'nom_form_bilan_cg' ) == 'cg66' ) {
			$this->pageTitle = 'Ajout d\'un bilan de parcours';
		}
		else {
			$this->pageTitle = 'Ajout d\'une fiche de saisine';
		}
	}
	else {
		if( Configure::read( 'nom_form_bilan_cg' ) == 'cg66' ) {
			$this->pageTitle = 'Édition du bilan de parcours';
		}
		else {
			$this->pageTitle = 'Édition de la fiche de saisine';
		}
	}

	function radioBilan( $view, $path, $value, $label ) {
		$name = 'data['.implode( '][', explode( '.', $path ) ).']';
		$storedValue = Set::classicExtract( $view->data, $path );
		$checked = ( ( $storedValue == $value ) ? 'checked="checked"' : '' );
		return "<label><input type=\"radio\" id=\"radio{$value}\" name=\"{$name}\" value=\"{$value}\" {$checked} />{$label}</label>";
	}
?>

<div class="with_treemenu">
	<h1><?php echo $this->pageTitle;?></h1>

	<?php
		if( $this->action == 'add' ) {
			echo $form->create( 'Bilanparcours66', array( 'type' => 'post', 'url' => Router::url( null, true ),  'id' => 'Bilan' ) );
		}
		else {
			echo $form->create( 'Bilanparcours66', array( 'type' => 'post', 'url' => Router::url( null, true ), 'id' => 'Bilan' ) );
			echo '<div>';
			echo $form->input( 'Bilanparcours66.id', array( 'type' => 'hidden' ) );
			echo $form->input( 'Pe.Bilanparcours66.id', array( 'type' => 'hidden' ) );
			echo '</div>';
		}
		echo '<div>';
		echo $form->input( 'Bilanparcours66.personne_id', array( 'type' => 'hidden', 'value' => Set::classicExtract( $personne, 'Personne.id') ) );
		echo $form->input( 'Bilanparcours66.nvuser_id', array( 'type' => 'hidden', 'value' => $session->read( 'Auth.User.id' ) ) );
		echo '</div>';
	?>

	<div class="aere">
		<?php
			echo $default->subform(
				array(
					'Bilanparcours66.typeformulaire' => array( 'type' => 'radio', 'value' => $typeformulaire, 'disabled' => true )
				),
				array(
					'options' => $options
				)
			);
			echo $xform->input( 'Bilanparcours66.typeformulaire', array( 'type' => 'hidden', 'value' => $typeformulaire, 'id' => 'Bilanparcours66TypeformulaireHidden' ) );
		?>

<fieldset id="bilanparcourscg">
	<legend>BILAN DU PARCOURS</legend>
		<?php
			echo $default->subform(
				array(
					'Bilanparcours66.orientstruct_id' => array( 'type' => 'hidden' ),
					'Bilanparcours66.structurereferente_id',
					'Bilanparcours66.referent_id',
					'Bilanparcours66.presenceallocataire' => array('required'=>true)
				),
				array(
					'options' => $options
				)
			);
		?>

	<fieldset>
		<legend>Situation de l'allocataire</legend>
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
					<strong>N° Service instructeur : </strong><?php echo Set::classicExtract( $personne, 'Serviceinstructeur.lib_service');?>
					<br />
					<strong>N° demandeur : </strong><?php echo Set::classicExtract( $personne, 'Foyer.Dossier.numdemrsa' );?>
					<br />
					<strong>N° CAF/MSA : </strong><?php echo Set::classicExtract( $personne, 'Foyer.Dossier.matricule' );?>
					<br />
					<strong>Inscrit au Pôle emploi</strong>
					<?php
						$isPoleemploi = Set::classicExtract( $personne, 'Activite.0.act' );
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
					<strong>Adresse : </strong><br /><?php echo Set::classicExtract( $personne, 'Foyer.Adressefoyer.0.Adresse.numvoie' ).' '.Set::enum( Set::classicExtract( $personne, 'Foyer.Adressefoyer.0.Adresse.typevoie' ), $options['typevoie'] ).' '.Set::classicExtract( $personne, 'Foyer.Adressefoyer.0.Adresse.nomvoie' ).'<br /> '.Set::classicExtract( $personne, 'Foyer.Adressefoyer.0.Adresse.codepos' ).' '.Set::classicExtract( $personne, 'Foyer.Adressefoyer.0.Adresse.locaadr' );?>
				</td>
				<td class="mediumSize noborder">
					<?php if( Set::extract( $personne, 'Foyer.Modecontact.0.autorutitel' ) == 'A' ):?>
							<strong>Numéro de téléphone 1 : </strong><?php echo Set::extract( $personne, 'Foyer.Modecontact.0.numtel' );?>
					<?php endif;?>
					<?php if( Set::extract( $personne, 'Foyer.Modecontact.1.autorutitel' ) == 'A' ):?>
							<br />
							<strong>Numéro de téléphone 2 : </strong><?php echo Set::extract( $personne, 'Foyer.Modecontact.1.numtel' );?>
					<?php endif;?>
				</td>
			</tr>
			<tr>
				<td colspan="2" class="mediumSize noborder">
				<?php if( Set::extract( $personne, 'Foyer.Modecontact.0.autorutiadrelec' ) == 'A' ):?>
					<strong>Adresse mail : </strong><?php echo Set::extract( $personne, 'Foyer.Modecontact.0.adrelec' );?> <!-- FIXME -->
				<?php endif;?>
				</td>
			</tr>
		</table>
		<?php
			if( !empty( $personne['Orientstruct'] ) ) {
				if ( $this->action == 'edit' ) {
					$defaultvaluetypeorient_id = $this->data['Bilanparcours66']['nvtypeorient_id'];
					$defaultvaluestructurereferente_id = implode( '_', array( $this->data['Bilanparcours66']['nvtypeorient_id'], $this->data['Bilanparcours66']['nvstructurereferente_id'] ) );

					echo $xhtml->tag(
						'p',
						'<strong>Orientation (au moment de la création du bilan de parcours) : </strong>'.Set::extract( $this->data, 'Orientstruct.Typeorient.lib_type_orient' )
					);
				}
				else {
					$defaultvaluetypeorient_id = $personne['Orientstruct'][0]['typeorient_id'];
					$defaultvaluestructurereferente_id = implode( '_', array( $personne['Orientstruct'][0]['typeorient_id'], $personne['Orientstruct'][0]['structurereferente_id'] ) );

					echo $xhtml->tag(
						'p',
						'<strong>Orientation actuelle : </strong>'.Set::extract( $personne, 'Orientstruct.0.Typeorient.lib_type_orient' )
					);
				}
			}
		
// 		debug( $personne );
		
			echo $default->subform(
				array(
					'Bilanparcours66.sitfam' => array( 'type' => 'radio' )
				),
				array(
					'options' => $options
				)
			);
		?>
	</fieldset>
<fieldset>
	<?php 
		echo $html->tag(
			'h3',
			$form->input(
				'Bilanparcours66.bilanparcoursinsertion',
				array(
					'type'=>'checkbox',
					'label'=> 'Bilan du parcours d\'insertion'
				)
			)
		);//bilanparcoursinsertion
	?>
	<fieldset id="Bilanparcoursinsertion" class="invisible">
	<?php
			echo $default2->subform(
				array(
					'Bilanparcours66.situationperso',
					'Bilanparcours66.situationpro'
				),
				array(
					'options' => $options
				)
			);

			echo $html->tag(
				'p',
				'Bilan du parcours d\'insertion :',
				array(
					'style' => ' font-size: 12px; font-weight:bold;'
				)
			);

			echo $default2->subform(
				array(
					'Bilanparcours66.objinit',
					'Bilanparcours66.objatteint',
					'Bilanparcours66.objnew'
				),
				array(
					'options' => $options
				)
			);
		?>
	</fieldset>
</fieldset>

<fieldset>
		<?php
			echo $html->tag(
				'h3',
				$form->input(
					'Bilanparcours66.motifep',
					array(
						'type'=>'checkbox',
						'label'=> 'Motifs de la saisine de l\'équipe pluridisciplinaire ',
						'style' => 'text-align: center; font-size: 14px; font-weight:bold;'
					)
				)
			);
		?>
		<fieldset id="motifsaisine" class="invisible">
		<?php
			echo $default2->subform(
				array(
					'Bilanparcours66.motifsaisine'
				),
				array(
					'options' => $options
				)
			);
		?>
	</fieldset>
</fieldset>

<script type="text/javascript">
	document.observe("dom:loaded", function() {

		observeDisableFieldsetOnCheckbox(
			'Bilanparcours66Bilanparcoursinsertion',
			'Bilanparcoursinsertion',
			false,
			true
		);

		observeDisableFieldsetOnCheckbox(
			'Bilanparcours66Motifep',
			'motifsaisine',
			false,
			true
		);
	} );
</script>
	<?php
		echo $html->tag(
			'p',
			'Proposition du référent :',
			array(
				'style' => 'text-align: center; font-size: 14px; font-weight:bold;'
			)
		);

		if (isset($this->validationErrors['Bilanparcours66']['proposition'])) {
			echo $xhtml->tag(
				'div',
				$xhtml->tag(
					'div',
					$this->validationErrors['Bilanparcours66']['proposition'],
					array(
						'class' => 'error-message'
					)
				),
				array(
					'class' => 'error'
				)
			);
		}

		if ( $this->action == 'edit' ){
			echo $xform->input( 'Bilanparcours66.proposition', array( 'type' => 'hidden' ) );
			echo $xform->input( 'Bilanparcours66.maintienorientation', array( 'type' => 'hidden' ) );
		}
	?>

	<fieldset>
		<?php
			/// Traitement de l'orientation sans passage en EP locale
			$tmp = radioBilan( $this, 'Bilanparcours66.proposition', 'traitement', 'Traitement de l\'orientation du dossier sans passage en EP Locale' );
			echo $xhtml->tag( 'h3', $tmp );
		?>
		<fieldset id="traitement" class="invisible">
			<fieldset id="cgOrientationActuelle">
				<legend>Maintien de l'orientation SOCIALE</legend>
				<?php

						if ( $this->action == 'edit' ) {
							$defaultvaluetypeorient_id = $this->data['Bilanparcours66']['nvtypeorient_id'];
							$defaultvaluestructurereferente_id = implode( '_', array( $this->data['Bilanparcours66']['nvtypeorient_id'], $this->data['Bilanparcours66']['nvstructurereferente_id'] ) );

							echo $xhtml->tag(
								'p',
								'Orientation SOCIALE (au moment de la création du bilan de parcours) : '.Set::extract( $this->data, 'Orientstruct.Typeorient.lib_type_orient' )
							);
						}
						else {
							if( !empty( $personne['Orientstruct'] ) ) {
								$defaultvaluetypeorient_id = ( isset( $personne['Orientstruct'][0]['typeorient_id'] ) ? $personne['Orientstruct'][0]['typeorient_id'] : null );
								$defaultvaluestructurereferente_id = implode( '_', array( $personne['Orientstruct'][0]['typeorient_id'], $personne['Orientstruct'][0]['structurereferente_id'] ) );

								echo $xhtml->tag(
									'p',
									'Orientation SOCIALE actuelle : '.Set::extract( $personne, 'Orientstruct.0.Typeorient.lib_type_orient' )
								);
							}
							else{
								$defaultvaluestructurereferente_id = $defaultvaluetypeorient_id = null;
							}
						}


					echo $default2->subform(
						array(
							'Bilanparcours66.sansep_typeorientprincipale_id' => array( 'type' => 'radio', 'options' => $options['Bilanparcours66']['typeorientprincipale_id'], 'required' => true, 'value' => ( ( isset( $this->data['Bilanparcours66']['typeorientprincipale_id'] ) ) ? $this->data['Bilanparcours66']['typeorientprincipale_id'] : null ) )
						),
						array(
							'options' => $options,
							'domain' => $domain
						)
					);

					foreach( $options['Bilanparcours66']['typeorientprincipale_id'] as $key => $value ) {
						echo "<div id='maintienOrientSansEp{$key}'>";
						echo $default2->subform(
							array(
								'Bilanparcours66.nvtypeorient_id' => array( 'required'=> true, 'id' => 'Bilanparcours66NvtypeorientIdSansEp'.$key, 'options' => $options['Bilanparcours66']['nvtypeorient_id'][$key], 'value' => $defaultvaluetypeorient_id ),
								'Bilanparcours66.nvstructurereferente_id' => array( 'required'=> true, 'id' => 'Bilanparcours66NvstructurereferenteIdSansEp'.$key, 'options' => $options['Bilanparcours66']['nvstructurereferente_id'], 'value' => $defaultvaluestructurereferente_id )
							),
							array(
								'options' => $options,
								'domain' => $domain
							)
						);
						echo "</div>";
					}

					echo "<div id='cgMaintienOrientSansEpMemeRef' class='aere";
						if ( isset( $this->validationErrors['Bilanparcours66']['changementref'] ) && !empty( $this->validationErrors['Bilanparcours66']['changementref'] ) ) {
							echo " error";
						}
					echo "'>";
						echo $xform->input( 'Bilanparcours66.changementrefsansep', array( 'type' => 'hidden', 'value' => 'N' ) );
						echo "Sans changement de référent.";
						if ( isset( $this->validationErrors['Bilanparcours66']['changementref'] ) && !empty( $this->validationErrors['Bilanparcours66']['changementref'] ) ) {
							echo $xhtml->tag(
								'div',
								$this->validationErrors['Bilanparcours66']['changementref'],
								array(
									'class' => 'error-message'
								)
							);
						}
					echo "</div>";
					echo "<div id='cgMaintienOrientSansEpChangementRef' class='aere'>";
						echo $xform->input( 'Bilanparcours66.changementrefsansep', array( 'type' => 'hidden', 'value' => 'O' ) );
						echo "Avec changement de référent.";
					echo "</div>";
				?>
				<fieldset id="cgContratReconduitSansEp">
					<legend>Reconduction du contrat librement débattu</legend>
					<?php
						echo $default2->subform(
							array(
								'Bilanparcours66.duree_engag' => array( 'required' => true, 'id' => 'Bilanparcours66DureeEngagSansEp' ),
								'Bilanparcours66.ddreconductoncontrat' => array( 'required' => true, 'id' => 'Bilanparcours66DdreconductoncontratSansEp' ),
								'Bilanparcours66.dfreconductoncontrat' => array( 'required' => true, 'id' => 'Bilanparcours66DfreconductoncontratSansEp' )
							),
							array(
								'options' => $options,
								'domain' => $domain
							)
						);
					?>
				</fieldset>
			</fieldset>
		</fieldset>
	</fieldset>
	<fieldset>
		<?php
			/// "Commission Parcours": Examen du dossier avec passage en EP Locale
			$tmp = radioBilan( $this, 'Bilanparcours66.proposition', 'parcours', '"Commission Parcours": Examen du dossier avec passage en EP Locale' );
			echo $xhtml->tag( 'h3', $tmp );

			if( $dossiersepsencours['saisinesbilansparcourseps66'] ) {
				echo $html->tag( 'p', 'Ce dossier est déjà en cours d\'examen par la Commission Parcours', array( 'class' => 'notice' ) );
			}

		?>
		<fieldset id="parcours" class="invisible">
			<?php
				echo $default2->subform(
					array(
						'Bilanparcours66.choixparcours' => array( 'type' => 'radio', 'required' => true )
					),
					array(
						'options' => $options
					)
				);
			?>
			<fieldset id="cgMaintienOrientationAvecEp">
				<legend>Maintien de l'orientation SOCIALE</legend>
				<?php
						if ( $this->action == 'edit' ) {
							$defaultvaluetypeorient_id = $this->data['Bilanparcours66']['nvtypeorient_id'];
							$defaultvaluestructurereferente_id = implode( '_', array( $this->data['Bilanparcours66']['nvtypeorient_id'], $this->data['Bilanparcours66']['nvstructurereferente_id'] ) );

							echo $xhtml->tag(
								'p',
								'Orientation SOCIALE (au moment de la création du bilan de parcours) : '.Set::extract( $this->data, 'Orientstruct.Typeorient.lib_type_orient' )
							);
						}
						else {
							if( !empty( $personne['Orientstruct'] ) ) {
								$defaultvaluetypeorient_id = $personne['Orientstruct'][0]['typeorient_id'];
								$defaultvaluestructurereferente_id = implode( '_', array( $personne['Orientstruct'][0]['typeorient_id'], $personne['Orientstruct'][0]['structurereferente_id'] ) );

								echo $xhtml->tag(
									'p',
									'Orientation SOCIALE actuelle : '.Set::extract( $personne, 'Orientstruct.0.Typeorient.lib_type_orient' )
								);
							}
							else{
								$defaultvaluestructurereferente_id = $defaultvaluetypeorient_id = null;
							}
						}

					echo $default2->subform(
						array(
							'Bilanparcours66.avecep_typeorientprincipale_id' => array( 'type' => 'radio', 'options' => $options['Bilanparcours66']['typeorientprincipale_id'], 'required' => true, 'value' => ( ( isset( $this->data['Bilanparcours66']['typeorientprincipale_id'] ) ) ? $this->data['Bilanparcours66']['typeorientprincipale_id'] : null ) )
						),
						array(
							'options' => $options,
							'domain' => $domain
						)
					);

					foreach( $options['Bilanparcours66']['typeorientprincipale_id'] as $key => $value ) {
						echo "<div id='cgMaintienOrientAvecEp{$key}'>";
						echo $default2->subform(
							array(
								'Bilanparcours66.nvtypeorient_id' => array( 'required'=> true, 'id' => 'Bilanparcours66NvtypeorientIdAvecEp'.$key, 'options' => $options['Bilanparcours66']['nvtypeorient_id'][$key], 'value' => $defaultvaluetypeorient_id ),
								'Bilanparcours66.nvstructurereferente_id' => array( 'required'=> true, 'id' => 'Bilanparcours66NvstructurereferenteIdAvecEp'.$key, 'options' => $options['Bilanparcours66']['nvstructurereferente_id'], 'value' => $defaultvaluestructurereferente_id )
							),
							array(
								'options' => $options,
								'domain' => $domain
							)
						);
						echo "</div>";
					}
					echo "<div id='cgMaintienOrientAvecEpMemeRef' class='aere";
						if ( isset( $this->validationErrors['Bilanparcours66']['changementref'] ) && !empty( $this->validationErrors['Bilanparcours66']['changementref'] ) ) {
							echo " error";
						}
					echo "'>";
						echo $xform->input( 'Bilanparcours66.changementrefavecep', array( 'type' => 'hidden', 'value' => 'N' ) );
						echo "Sans changement de référent.";
						if ( isset( $this->validationErrors['Bilanparcours66']['changementref'] ) && !empty( $this->validationErrors['Bilanparcours66']['changementref'] ) ) {
							echo $xhtml->tag(
								'div',
								$this->validationErrors['Bilanparcours66']['changementref'],
								array(
									'class' => 'error-message'
								)
							);
						}
					echo "</div>";
					echo "<div id='cgMaintienOrientAvecEpChangementRef' class='aere'>";
						echo $xform->input( 'Bilanparcours66.changementrefavecep', array( 'type' => 'hidden', 'value' => 'O' ) );
						echo "Avec changement de référent.";
					echo "</div>";
				?>
				<fieldset id="cgContratReconduitAvecEp">
					<legend>Reconduction du contrat librement débattu</legend>
					<?php
						echo $default2->subform(
							array(
								'Bilanparcours66.duree_engag' => array( 'required' => true, 'id' => 'Bilanparcours66DureeEngagAvecEp' ),
								'Bilanparcours66.ddreconductoncontrat' => array( 'required' => true, 'id' => 'Bilanparcours66DdreconductoncontratAvecEp' ),
								'Bilanparcours66.dfreconductoncontrat' => array( 'required' => true, 'id' => 'Bilanparcours66DfreconductoncontratAvecEp' )
							),
							array(
								'options' => $options,
								'domain' => $domain
							)
						);
					?>
				</fieldset>
			</fieldset>
			<fieldset id="cgReorientationAvecEp">
				<legend>Réorientation du SOCIAL vers le professionnel</legend>
				<?php
					if ( $this->action == 'edit' ) {
						$defaultvaluetypeorient_id = $this->data['Bilanparcours66']['nvtypeorient_id'];
						$defaultvaluestructurereferente_id = implode( '_', array( $this->data['Bilanparcours66']['nvtypeorient_id'], $this->data['Bilanparcours66']['nvstructurereferente_id'] ) );
					}
					else {
						$defaultvaluetypeorient_id = null;
						$defaultvaluestructurereferente_id = null;
					}

					$typeorientprincipale = Configure::read( 'Orientstruct.typeorientprincipale' );
					$typeorientemploiId = $typeorientprincipale['Emploi'][0];

					echo $default->subform(
						array(
							'Bilanparcours66.avecep_typeorientprincipale_id' => array( 'type' => 'hidden', 'value' => $typeorientemploiId ),
							'Bilanparcours66.nvtypeorient_id' => array( 'required' => true, 'options' => $options['Bilanparcours66']['nvtypeorient_id'][$typeorientemploiId], 'value' => $defaultvaluetypeorient_id, 'id' => 'Saisinebilanparcoursep66TypeorientId' ),
							'Bilanparcours66.nvstructurereferente_id' => array( 'required' => true, 'value' => $defaultvaluestructurereferente_id, 'id' => 'Saisinebilanparcoursep66StructurereferenteId' )
						),
						array(
							'options' => $options
						)
					);
					echo "<div id='cgReorientAvecEpChangementRef' class='aere'>";
						echo $xform->input( 'Bilanparcours66.changementrefavecep', array( 'type' => 'hidden', 'value' => 'O' ) );
						echo "Avec changement de référent.";
					echo "</div>";
				?>
			</fieldset>
		</fieldset>
	</fieldset>
	<fieldset>
		<?php
			/// "Commission Audition": Examen du dossier par la commission EP Locale
			$tmp = radioBilan( $this, 'Bilanparcours66.proposition', 'audition', '"Commission Audition": Examen du dossier par la commission EP Locale' );
			echo $xhtml->tag( 'h3', $tmp );
			
			if( $dossiersepsencours['defautsinsertionseps66'] ) {
				echo $html->tag( 'p', 'Ce dossier est déjà en cours d\'examen par la Commission Audition', array( 'class' => 'notice' ) );
			}
		?>
		<fieldset id="audition" class="invisible">
			<?php
				echo $default2->subform(
					array(
						'Bilanparcours66.examenaudition' => array( 'type' => 'radio', 'required' => true )
					),
					array(
						'options' => $options
					)
				);
			?>
		</fieldset>
	</fieldset>

	<fieldset>
		<?php
			/// "Commission Audition PE": Examen du dossier par la commission EP Locale
			$tmp = radioBilan( $this, 'Bilanparcours66.proposition', 'auditionpe', 'Saisine EPL Audition Défaut d\'insertion Public suivi par Pôle Emploi' );
			echo $xhtml->tag( 'h3', $tmp );
		?>
		<fieldset id="auditionpe" class="invisible">
			<?php
				echo $default2->subform(
					array(
						'Bilanparcours66.examenauditionpe' => array( 'type' => 'radio', 'required' => true )
					),
					array(
						'options' => $options
					)
				);
			?>
		</fieldset>
	</fieldset>

		<?php
			echo $default2->subform(
				array(
					'Bilanparcours66.infoscomplementaires'
				),
				array(
					'options' => $options
				)
			);
			echo $html->tag(
				'p',
				'Observations du bénéficiaire :',
				array(
					'style' => 'text-align: center; font-size: 14px; font-weight:bold;'
				)
			);
			echo $default2->subform(
				array(
					'Bilanparcours66.observbenefrealisationbilan',
					'Bilanparcours66.observbenefcompterendu',
					'Bilanparcours66.datebilan' => array( 'dateFormat' => 'DMY', 'maxYear' => date('Y'), 'minYear' => date('Y') - 2, 'empty' => true, 'required' => true ),
				),
				array(
					'options' => $options
				)
			);
		?>
		<div class="submit">
			<?php echo $form->submit( 'Enregistrer', array( 'div' => false ) );?>
			<?php echo $form->submit('Annuler', array( 'name' => 'Cancel', 'div' => false ) );?>
		</div>
</fieldset>

<fieldset id="bilanparcourspe">
	<legend>BILAN DU PARCOURS (Pôle Emploi)</legend>
		<?php
			echo $default->subform(
				array(
					'Pe.Bilanparcours66.orientstruct_id' => array( 'type' => 'hidden' ),
					'Pe.Bilanparcours66.structurereferente_id'
				),
				array(
					'options' => $options
				)
			);

			echo '<div class ="input select';
				if (isset($this->validationErrors['Bilanparcours66']['referent_id'])) echo ' error';
			echo '">';
			echo $default->subform(
				array(
					'Pe.Bilanparcours66.referent_id' => array('div'=>false)
				),
				array(
					'options' => $options
				)
			);
			if (isset($this->validationErrors['Bilanparcours66']['referent_id'])) {
				echo '<div class="error-message">'.$this->validationErrors['Bilanparcours66']['referent_id'].'</div>';
			}
			echo '</div>';

			echo '<div class ="input select';
			if (isset($this->validationErrors['Bilanparcours66']['presenceallocataire'])) echo ' error';
			echo '">';
			echo $default->subform(
				array(
					'Pe.Bilanparcours66.presenceallocataire' => array( 'required' => true, 'div' => false )
				),
				array(
					'options' => $options
				)
			);
			if (isset($this->validationErrors['Bilanparcours66']['presenceallocataire'])) echo '<div class="error-message">'.$this->validationErrors['Bilanparcours66']['presenceallocataire'].'</div>';
			echo '</div>';
		?>

	<fieldset>
		<legend>Situation de l'allocataire</legend>
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
					<strong>N° Service instructeur : </strong><?php echo Set::classicExtract( $personne, 'Serviceinstructeur.lib_service');?>
					<br />
					<strong>N° demandeur : </strong><?php echo Set::classicExtract( $personne, 'Foyer.Dossier.numdemrsa' );?>
					<br />
					<strong>N° CAF/MSA : </strong><?php echo Set::classicExtract( $personne, 'Foyer.Dossier.matricule' );?>
					<br />
					<strong>Inscrit au Pôle emploi</strong>
					<?php
						$isPoleemploi = Set::classicExtract( $personne, 'Activite.0.act' );
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
					<strong>Adresse : </strong><br /><?php echo Set::classicExtract( $personne, 'Foyer.Adressefoyer.0.Adresse.numvoie' ).' '.Set::enum( Set::classicExtract( $personne, 'Foyer.Adressefoyer.0.Adresse.typevoie' ), $options['typevoie'] ).' '.Set::classicExtract( $personne, 'Foyer.Adressefoyer.0.Adresse.nomvoie' ).'<br /> '.Set::classicExtract( $personne, 'Foyer.Adressefoyer.0.Adresse.codepos' ).' '.Set::classicExtract( $personne, 'Foyer.Adressefoyer.0.Adresse.locaadr' );?>
				</td>
				<td class="mediumSize noborder">
					<?php if( Set::extract( $personne, 'Foyer.Modecontact.0.autorutitel' ) == 'A' ):?>
							<strong>Numéro de téléphone 1 : </strong><?php echo Set::extract( $personne, 'Foyer.Modecontact.0.numtel' );?>
					<?php endif;?>
					<?php if( Set::extract( $personne, 'Foyer.Modecontact.1.autorutitel' ) == 'A' ):?>
							<br />
							<strong>Numéro de téléphone 2 : </strong><?php echo Set::extract( $personne, 'Foyer.Modecontact.1.numtel' );?>
					<?php endif;?>
				</td>
			</tr>
			<tr>
				<td colspan="2" class="mediumSize noborder">
				<?php if( Set::extract( $personne, 'Foyer.Modecontact.0.autorutiadrelec' ) == 'A' ):?>
					<strong>Adresse mail : </strong><?php echo Set::extract( $personne, 'Foyer.Modecontact.0.adrelec' );?> <!-- FIXME -->
				<?php endif;?>
				</td>
			</tr>
		</table>
	</fieldset>

	<?php
		echo $default2->subform(
			array(
				'Pe.Bilanparcours66.textbilanparcours',
				'Pe.Bilanparcours66.observbenef',
				'Pe.Bilanparcours66.proposition' => array( 'type' => 'hidden', 'value' => 'parcours' )
			),
			array(
				'options' => $options
			)
		);
	?>

	<fieldset id="peParcours" class="invisible">
		<?php
			echo '<div class ="input radio';
				if (isset($this->validationErrors['Bilanparcours66']['choixparcours'])) echo ' error';
			echo '">';
			echo $default->subform(
				array(
					'Pe.Bilanparcours66.choixparcours' => array( 'div' => false, 'type' => 'radio', 'required' => true )
				),
				array(
					'options' => $options
				)
			);
			if (isset($this->validationErrors['Bilanparcours66']['choixparcours'])) {
				echo '<div class="error-message">'.$this->validationErrors['Bilanparcours66']['choixparcours'].'</div>';
			}
			echo '</div>';
		?>
		<fieldset id="peMaintienOrientationAvecEp">
			<legend>Maintien de l'orientation PROFESSIONNELLE</legend>
			<?php
				if( !empty( $personne['Orientstruct'] ) ) {
					if ( $this->action == 'edit' && !isset( $this->data['Pe']['Bilanparcours66']['nvstructurereferente_id'] ) ) {
						$defaultvaluetypeorient_id = $this->data['Bilanparcours66']['nvtypeorient_id'];
						$defaultvaluestructurereferente_id = implode( '_', array( $this->data['Bilanparcours66']['nvtypeorient_id'], $this->data['Bilanparcours66']['nvstructurereferente_id'] ) );

						echo $xhtml->tag(
							'p',
							'Orientation PROFESSIONNELLE (au moment de la création du bilan de parcours) : '.Set::extract( $this->data, 'Orientstruct.Typeorient.lib_type_orient' )
						);
					}
					elseif ( !isset( $this->data['Pe']['Bilanparcours66']['nvstructurereferente_id'] ) ) {
						$defaultvaluetypeorient_id = $personne['Orientstruct'][0]['typeorient_id'];
						$defaultvaluestructurereferente_id = implode( '_', array( $personne['Orientstruct'][0]['typeorient_id'], $personne['Orientstruct'][0]['structurereferente_id'] ) );

						echo $xhtml->tag(
							'p',
							'Orientation PROFESSIONNELLE actuelle : '.Set::extract( $personne, 'Orientstruct.0.Typeorient.lib_type_orient' )
						);
					}
				}

				$typeorientprincipale = Configure::read( 'Orientstruct.typeorientprincipale' );
				$typeorientemploiId = $typeorientprincipale['Emploi'][0];

				echo '<div class = "input">';
				echo $default->subform(
					array(
						'Pe.Bilanparcours66.avecep_typeorientprincipale_id' => array( 'type' => 'hidden', 'value' => $typeorientemploiId )
					),
					array(
						'options' => $options
					)
				);
				echo '</div>';

				echo '<div class ="input select';
					if (isset($this->validationErrors['Bilanparcours66']['nvtypeorient_id'])) echo ' error';
				echo '">';
				echo $default->subform(
					array(
						'Pe.Bilanparcours66.nvtypeorient_id' => array( 'required' => true, 'options' => $options['Bilanparcours66']['nvtypeorient_id'][$typeorientemploiId], 'value' => $defaultvaluetypeorient_id, 'id' => 'PeSaisinebilanparcoursep66TypeorientId' )
					),
					array(
						'options' => $options
					)
				);
				if (isset($this->validationErrors['Bilanparcours66']['nvtypeorient_id'])) {
					echo '<div class="error-message">'.$this->validationErrors['Bilanparcours66']['nvtypeorient_id'].'</div>';
				}
				echo '</div>';

				echo '<div class ="input select';
					if (isset($this->validationErrors['Bilanparcours66']['nvstructurereferente_id'])) echo ' error';
				echo '">';
				echo $default->subform(
					array(
						'Pe.Bilanparcours66.nvstructurereferente_id' => array( 'required' => true, 'value' => $defaultvaluestructurereferente_id, 'id' => 'PeSaisinebilanparcoursep66StructurereferenteId' )
					),
					array(
						'options' => $options
					)
				);
				if (isset($this->validationErrors['Bilanparcours66']['nvstructurereferente_id'])) {
					echo '<div class="error-message">'.$this->validationErrors['Bilanparcours66']['nvstructurereferente_id'].'</div>';
				}
				echo '</div>';

				echo "<div id='peMaintienOrientAvecEpMemeRef' class='aere";
					if ( isset( $this->validationErrors['Bilanparcours66']['changementref'] ) && !empty( $this->validationErrors['Bilanparcours66']['changementref'] ) ) {
						echo " error";
					}
				echo "'>";
					echo $xform->input( 'Pe.Bilanparcours66.changementrefavecep', array( 'type' => 'hidden', 'value' => 'N' ) );
					echo "Sans changement de référent.";
					if ( isset( $this->validationErrors['Bilanparcours66']['changementref'] ) && !empty( $this->validationErrors['Bilanparcours66']['changementref'] ) ) {
						echo $xhtml->tag(
							'div',
							$this->validationErrors['Bilanparcours66']['changementref'],
							array(
								'class' => 'error-message'
							)
						);
					}
				echo "</div>";
				echo "<div id='peMaintienOrientAvecEpChangementRef' class='aere'>";
					echo $xform->input( 'Pe.Bilanparcours66.changementrefavecep', array( 'type' => 'hidden', 'value' => 'O' ) );
					echo "Avec changement de référent.";
				echo "</div>";
			?>
			<fieldset id="peContratReconduitAvecEp">
				<legend>Reconduction du contrat librement débattu</legend>
				<?php
					echo '<div class ="input select';
						if (isset($this->validationErrors['Bilanparcours66']['duree_engag'])) echo ' error';
					echo '">';
					echo $default->subform(
						array(
							'Pe.Bilanparcours66.duree_engag' => array( 'div' => false, 'required' => true, 'id' => 'PeBilanparcours66DureeEngagAvecEp' )
						),
						array(
							'options' => $options
						)
					);
					if (isset($this->validationErrors['Bilanparcours66']['duree_engag'])) {
						echo '<div class="error-message">'.$this->validationErrors['Bilanparcours66']['duree_engag'].'</div>';
					}
					echo '</div>';

					echo '<div class ="input date';
						if (isset($this->validationErrors['Bilanparcours66']['ddreconductoncontrat'])) echo ' error';
					echo '">';
					echo $default->subform(
						array(
							'Pe.Bilanparcours66.ddreconductoncontrat' => array( 'div' => false, 'required' => true, 'id' => 'PeBilanparcours66DdreconductoncontratAvecEp' )
						),
						array(
							'options' => $options
						)
					);
					if (isset($this->validationErrors['Bilanparcours66']['ddreconductoncontrat'])) {
						echo '<div class="error-message">'.$this->validationErrors['Bilanparcours66']['ddreconductoncontrat'].'</div>';
					}
					echo '</div>';

					echo '<div class ="input date';
						if (isset($this->validationErrors['Bilanparcours66']['dfreconductoncontrat'])) echo ' error';
					echo '">';
					echo $default->subform(
						array(
							'Pe.Bilanparcours66.dfreconductoncontrat' => array( 'div' => false, 'required' => true, 'id' => 'PeBilanparcours66DfreconductoncontratAvecEp' )
						),
						array(
							'options' => $options
						)
					);
					if (isset($this->validationErrors['Bilanparcours66']['dfreconductoncontrat'])) {
						echo '<div class="error-message">'.$this->validationErrors['Bilanparcours66']['dfreconductoncontrat'].'</div>';
					}
					echo '</div>';
				?>
			</fieldset>
		</fieldset>
		<fieldset id="peReorientationAvecEp">
			<legend>Réorientation du PROFESSIONEL vers le SOCIAL</legend>
			<?php
				if ( $this->action == 'edit' ) {
					$defaultvaluetypeorient_id = $this->data['Bilanparcours66']['nvtypeorient_id'];
					$defaultvaluestructurereferente_id = implode( '_', array( $this->data['Bilanparcours66']['nvtypeorient_id'], $this->data['Bilanparcours66']['nvstructurereferente_id'] ) );
				}
				else {
					$defaultvaluetypeorient_id = null;
					$defaultvaluestructurereferente_id = null;
				}

				// INFO: pour les cas où le champ caché n'apparaîtra pas (cf. FormHelper::radio )
				if( isset( $this->data['Pe']['Bilanparcours66']['avecep_typeorientprincipale_id'] ) && !empty( $this->data['Pe']['Bilanparcours66']['avecep_typeorientprincipale_id'] ) ) {
					echo $xform->input( 'Pe.Bilanparcours66.avecep_typeorientprincipale_id', array( 'type' => 'hidden', 'value' => '' ) );
				}

				echo '<div class ="input radio';
					if (isset($this->validationErrors['Bilanparcours66']['avecep_typeorientprincipale_id'])) echo ' error';
				echo '">';
				echo $default->subform(
					array(
						'Pe.Bilanparcours66.avecep_typeorientprincipale_id' => array( 'div' => false, 'options' => $options['Bilanparcours66']['typeorientprincipale_id'], 'type' => 'radio', 'required' => true )
					),
					array(
						'options' => $options
					)
				);
				if (isset($this->validationErrors['Bilanparcours66']['avecep_typeorientprincipale_id'])) {
					echo '<div class="error-message">'.$this->validationErrors['Bilanparcours66']['avecep_typeorientprincipale_id'].'</div>';
				}
				echo '</div>';

				foreach( $options['Bilanparcours66']['typeorientprincipale_id'] as $key => $value ) {
					echo "<div id='peMaintienOrientAvecEp{$key}'>";

						echo '<div class ="input select';
							if (isset($this->validationErrors['Bilanparcours66']['nvtypeorient_id'])) echo ' error';
						echo '">';
						echo $default->subform(
							array(
								'Pe.Bilanparcours66.nvtypeorient_id' => array( 'div' => false, 'required'=> true, 'id' => 'PeBilanparcours66NvtypeorientIdAvecEp'.$key, 'options' => $options['Bilanparcours66']['nvtypeorient_id'][$key], 'value' => $defaultvaluetypeorient_id )
							),
							array(
								'options' => $options
							)
						);
						if (isset($this->validationErrors['Bilanparcours66']['nvtypeorient_id'])) {
							echo '<div class="error-message">'.$this->validationErrors['Bilanparcours66']['nvtypeorient_id'].'</div>';
						}
						echo '</div>';

						echo '<div class ="input select';
							if (isset($this->validationErrors['Bilanparcours66']['nvstructurereferente_id'])) echo ' error';
						echo '">';
						echo $default->subform(
							array(
								'Pe.Bilanparcours66.nvstructurereferente_id' => array( 'div' => false, 'required'=> true, 'id' => 'PeBilanparcours66NvstructurereferenteIdAvecEp'.$key, 'options' => $options['Bilanparcours66']['nvstructurereferente_id'], 'value' => $defaultvaluestructurereferente_id )
							),
							array(
								'options' => $options
							)
						);
						if (isset($this->validationErrors['Bilanparcours66']['nvstructurereferente_id'])) {
							echo '<div class="error-message">'.$this->validationErrors['Bilanparcours66']['nvstructurereferente_id'].'</div>';
						}
						echo '</div>';

					echo "</div>";
				}
				echo "<div id='peReorientAvecEpChangementRef' class='aere'>";
					echo $xform->input( 'Pe.Bilanparcours66.changementrefavecep', array( 'type' => 'hidden', 'value' => 'O' ) );
					echo "Avec changement de référent.";
				echo "</div>";
			?>
		</fieldset>
		<?php
			echo '<div class ="input date';
				if (isset($this->validationErrors['Bilanparcours66']['datebilan'])) echo ' error';
			echo '">';
			echo $default->subform(
				array(
					'Pe.Bilanparcours66.datebilan' => array( 'dateFormat' => 'DMY', 'maxYear' => date('Y'), 'minYear' => date('Y') - 2, 'empty' => true, 'required' => true, 'div' => false )
				),
				array(
					'options' => $options
				)
			);
			if (isset($this->validationErrors['Bilanparcours66']['datebilan'])) {
				echo '<div class="error-message">'.$this->validationErrors['Bilanparcours66']['datebilan'].'</div>';
			}
			echo '</div>';
		?>
	</fieldset>

	<div class="submit">
		<?php echo $form->submit( 'Enregistrer', array( 'div' => false ) );?>
		<?php echo $form->submit('Annuler', array( 'name' => 'Cancel', 'div' => false ) );?>
	</div>
</fieldset>

<?php if ( $this->action == 'edit' && isset( $passagecommissionep['Decisionsaisinebilanparcoursep66'][0]['id'] ) && !empty( $passagecommissionep['Decisionsaisinebilanparcoursep66'][0]['id'] ) ) {
	$avisep = $passagecommissionep['Decisionsaisinebilanparcoursep66'][0];
	echo '<fieldset><legend><strong>AVIS DE L\'EP Locale Commission Parcours du '.date('d/m/Y', strtotime($passagecommissionep['Commissionep']['dateseance'])).'</strong></legend>';
		if ( $avisep['decision'] == 'reorientation' ) {
			echo $xhtml->tag(
				'strong',
				$options['Decisionsaisinebilanparcoursep66']['reorientation'][$avisep['reorientation']]
			);
		}
		elseif ( $avisep['decision'] == 'maintien' ) {
			echo $xhtml->tag(
				'strong',
				'Maintien de l\'orientation SOCIALE : '.$options['Decisionsaisinebilanparcoursep66']['maintienorientparcours'][$avisep['maintienorientparcours']]
			);
			echo $html->tag(
				'p',
				$options['Decisionsaisinebilanparcoursep66']['changementrefparcours'][$avisep['changementrefparcours']]
			);
		}
		echo $html->tag(
			'p',
			"Argumentaire précis (avis motivé) de l'EP Locale :",
			array(
				'style' => 'font-weight:bold; text-decoration:underline'
			)
		);
		echo $html->tag(
			'p',
			$avisep['commentaire']
		);
	echo '</fieldset>';
}
elseif ( $this->action == 'edit' && isset( $passagecommissionep['Decisiondefautinsertionep66'][0]['id'] ) && !empty( $passagecommissionep['Decisiondefautinsertionep66'][0]['id'] ) ) {
	$avisep = $passagecommissionep['Decisiondefautinsertionep66'][0];
	echo '<fieldset><legend><strong>AVIS DE L\'EP Locale Commission Audition du '.date('d/m/Y', strtotime($passagecommissionep['Commissionep']['dateseance'])).'</strong></legend>';
		if ( isset( $passagecommissionep['Decisiondefautinsertionep66'][0]['decisionsup'] ) && !empty( $passagecommissionep['Decisiondefautinsertionep66'][0]['decisionsup'] ) ) {
			echo $html->tag(
				'p',
				$options['Decisiondefautinsertionep66']['decisionsup'][$avisep['decisionsup']]
			);
		}
		echo $html->tag(
			'p',
			$options['Decisiondefautinsertionep66']['decision'][$avisep['decision']]
		);
		echo $html->tag(
			'p',
			"Argumentaire précis (avis motivé) de l'EP Locale :",
			array(
				'style' => 'font-weight:bold; text-decoration:underline'
			)
		);
		echo $html->tag(
			'p',
			$avisep['commentaire']
		);
	echo '</fieldset>';
}

if ( $this->action == 'edit' && isset( $passagecommissionep['Decisionsaisinebilanparcoursep66'][1]['id'] ) && !empty( $passagecommissionep['Decisionsaisinebilanparcoursep66'][1]['id'] ) ) {
	echo $html->tag(
		'p',
		'DECISION DU COORDINATEUR TECHNIQUE',
		array(
			'style' => 'text-align: center; font-size: 14px; font-weight:bold;'
		)
	);
	$decisioncg = $passagecommissionep['Decisionsaisinebilanparcoursep66'][1];
	echo '<fieldset><legend><strong>Suite à l\'avis de l\'EP Locale "Commission Parcours"</strong></legend>';
		if ( $decisioncg['decision'] == 'reorientation' ) {
			echo $xhtml->tag(
				'strong',
				$options['Decisionsaisinebilanparcoursep66']['reorientation'][$decisioncg['reorientation']]
			);
			$accord = ( $avisep['decision'] == $decisioncg['decision'] ) ? 'Oui' : 'Non';
			echo $xhtml->tag(
				'p',
				"En accord avec l'avis de l'EPL commission Parcours : ".$accord
			);
		}
		elseif ( $decisioncg['decision'] == 'maintien' ) {
			echo $xhtml->tag(
				'strong',
				'Maintien de l\'orientation SOCIALE : '.$options['Decisionsaisinebilanparcoursep66']['maintienorientparcours'][$decisioncg['maintienorientparcours']]
			);
			echo $html->tag(
				'p',
				$options['Decisionsaisinebilanparcoursep66']['changementrefparcours'][$decisioncg['changementrefparcours']]
			);
		}
		echo $html->tag(
			'p',
			"Commentaire :",
			array(
				'style' => 'font-weight:bold; text-decoration:underline'
			)
		);
		echo $html->tag(
			'p',
			$avisep['commentaire']
		);
	echo '</fieldset>';
}
elseif ( $this->action == 'edit' && isset( $passagecommissionep['Decisiondefautinsertionep66'][1]['id'] ) && !empty( $passagecommissionep['Decisiondefautinsertionep66'][1]['id'] ) ) {
	$decisioncg = $passagecommissionep['Decisiondefautinsertionep66'][1];
	if ( isset( $passagecommissionep['Decisiondefautinsertionep66'][1]['decisionsup'] ) && !empty( $passagecommissionep['Decisiondefautinsertionep66'][1]['decisionsup'] ) ) {
		echo $html->tag(
			'p',
			'DECISION DU COORDINATEUR TECHNIQUE',
			array(
				'style' => 'text-align: center; font-size: 14px; font-weight:bold;'
			)
		);
		echo '<fieldset><legend><strong>Suite à l\'avis de l\'EP Locale "Commission Audition"</strong></legend>';
			echo $xhtml->tag(
				'p',
				$options['Decisiondefautinsertionep66']['decision'][$decisioncg['decision']]
			);
			$accord = ( $avisep['decision'] == $decisioncg['decision'] ) ? 'Oui' : 'Non';
			echo $xhtml->tag(
				'p',
				"En accord avec l'avis de l'EPL commission Audition : ".$accord
			);
			echo $html->tag(
				'p',
				"Commentaire :",
				array(
					'style' => 'font-weight:bold; text-decoration:underline'
				)
			);
			echo $html->tag(
				'p',
				$avisep['commentaire']
			);
		echo '</fieldset>';
	}

	echo $html->tag(
		'p',
		'DECISION DE LA CGA',
		array(
			'style' => 'text-align: center; font-size: 14px; font-weight:bold;'
		)
	);

	echo '<fieldset><legend>Suite à l\'avis de l\'EP Locale "Commission Audition"</legend>';
		if ( isset( $passagecommissionep['Decisiondefautinsertionep66'][1]['decisionsup'] ) && !empty( $passagecommissionep['Decisiondefautinsertionep66'][1]['decisionsup'] ) ) {
			echo $xhtml->tag(
				'p',
				$options['Decisiondefautinsertionep66']['decisionsup'][$decisioncg['decisionsup']]
			);
			$accord = ( $avisep['decisionsup'] == $decisioncg['decisionsup'] ) ? 'Oui' : 'Non';
			echo $xhtml->tag(
				'p',
				"En accord avec l'avis de l'EPL commission Audition : ".$accord
			);
		}
		else {
			echo $xhtml->tag(
				'p',
				$options['Decisiondefautinsertionep66']['decision'][$decisioncg['decision']]
			);
			$accord = ( $avisep['decision'] == $decisioncg['decision'] || $avisep['decisionsup'] == $decisioncg['decisionsup'] || $avisep['decision'] == $decisioncg['decisionsup'] ) ? 'Oui' : 'Non';
			echo $xhtml->tag(
				'p',
				"En accord avec l'avis de l'EPL commission Audition : ".$accord
			);
		}
	echo '</fieldset>';
} ?>

	</div>
	<?php echo $form->end();?>
</div>
<div class="clearer"><hr /></div>

<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $javascript->link( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}
?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		observeDisableFieldsetOnRadioValue(
			'Bilan',
			'data[Bilanparcours66][typeformulaire]',
			$( 'bilanparcourscg' ),
			'cg',
			false,
			true
		);

		observeDisableFieldsetOnRadioValue(
			'Bilan',
			'data[Bilanparcours66][typeformulaire]',
			$( 'bilanparcourspe' ),
			'pe',
			false,
			true
		);

		
		['traitement', 'parcours', 'audition', 'auditionpe' ].each( function( proposition ) {
			observeDisableFieldsetOnRadioValue(
				'Bilan',
				'data[Bilanparcours66][proposition]',
				$( proposition ),
				proposition,
				false,
				true
			);
		} );

		$( 'Bilan' ).getInputs( 'radio', 'data[Bilanparcours66][sansep_typeorientprincipale_id]' ).each( function ( observeRadio ) {
			$( observeRadio ).observe( 'change', function(event) {
				checkOrientstructTypeorientId( 'data[Bilanparcours66][sansep_typeorientprincipale_id]', 'maintienOrientSansEp' );
			} );
		} );
		checkOrientstructTypeorientId( 'data[Bilanparcours66][sansep_typeorientprincipale_id]', 'maintienOrientSansEp' );

		disableAndHideFormPart( 'cgContratReconduitSansEp' );
		disableAndHideFormPart( 'cgMaintienOrientSansEpChangementRef' );
		disableAndHideFormPart( 'cgMaintienOrientSansEpMemeRef' );
		<?php foreach( $options['Bilanparcours66']['typeorientprincipale_id'] as $key => $value ) { ?>
			dependantSelect( 'Bilanparcours66NvstructurereferenteIdSansEp<?php echo $key ?>', 'Bilanparcours66NvtypeorientIdSansEp<?php echo $key ?>' );
			try { $( 'Bilanparcours66NvstructurereferenteIdSansEp<?php echo $key ?>' ).onchange(); } catch(id) { }
			observeMemeReorientation( 'Bilanparcours66SansepTypeorientprincipaleId<?php echo $key ?>', 'Bilanparcours66NvstructurereferenteIdSansEp<?php echo $key ?>', 'Bilanparcours66NvtypeorientIdSansEp<?php echo $key ?>', 'cgContratReconduitSansEp', 'cgMaintienOrientSansEpMemeRef', 'cgMaintienOrientSansEpChangementRef' );
		<?php } ?>

		dependantSelect( 'Saisinebilanparcoursep66StructurereferenteId', 'Saisinebilanparcoursep66TypeorientId' );
		try { $( 'Saisinebilanparcoursep66StructurereferenteId' ).onchange(); } catch(id) { }

		dependantSelect( 'Bilanparcours66ReferentId', 'Bilanparcours66StructurereferenteId' );

		dependantSelect( 'PeSaisinebilanparcoursep66StructurereferenteId', 'PeSaisinebilanparcoursep66TypeorientId' );
		try { $( 'PeSaisinebilanparcoursep66StructurereferenteId' ).onchange(); } catch(id) { }

		dependantSelect( 'PeBilanparcours66ReferentId', 'PeBilanparcours66StructurereferenteId' );

		$( 'Bilanparcours66DdreconductoncontratSansEpYear' ).observe( 'change', function(event) {
			checkDatesToRefresh( '', 'SansEp' );
		} );
		$( 'Bilanparcours66DdreconductoncontratSansEpMonth' ).observe( 'change', function(event) {
			checkDatesToRefresh( '', 'SansEp' );
		} );
		$( 'Bilanparcours66DdreconductoncontratSansEpDay' ).observe( 'change', function(event) {
			checkDatesToRefresh( '', 'SansEp' );
		} );
		$( 'Bilanparcours66DureeEngagSansEp' ).observe( 'change', function(event) {
			checkDatesToRefresh( '', 'SansEp' );
		} );

		// ---------------------------------------------------------------------

		// Partie en cas de maintien ou  de réorientation
		observeDisableFieldsetOnRadioValue(
			'Bilan',
			'data[Bilanparcours66][choixparcours]',
			$( 'cgMaintienOrientationAvecEp' ),
			'maintien',
			false,
			true
		);

		observeDisableFieldsetOnRadioValue(
			'Bilan',
			'data[Bilanparcours66][choixparcours]',
			$( 'cgReorientationAvecEp' ),
			'reorientation',
			false,
			true
		);

		$( 'Bilan' ).getInputs( 'radio', 'data[Bilanparcours66][avecep_typeorientprincipale_id]' ).each( function ( observeRadio ) {
			$( observeRadio ).observe( 'change', function(event) {
				checkOrientstructTypeorientId( 'data[Bilanparcours66][avecep_typeorientprincipale_id]', 'cgMaintienOrientAvecEp' );
			} );
		} );
		checkOrientstructTypeorientId( 'data[Bilanparcours66][avecep_typeorientprincipale_id]', 'cgMaintienOrientAvecEp' );

		disableAndHideFormPart( 'cgContratReconduitAvecEp' );
		disableAndHideFormPart( 'cgMaintienOrientAvecEpChangementRef' );
		disableAndHideFormPart( 'cgMaintienOrientAvecEpMemeRef' );
		<?php foreach( $options['Bilanparcours66']['typeorientprincipale_id'] as $key => $value ) { ?>
			dependantSelect( 'Bilanparcours66NvstructurereferenteIdAvecEp<?php echo $key ?>', 'Bilanparcours66NvtypeorientIdAvecEp<?php echo $key ?>' );
			try { $( 'Bilanparcours66NvstructurereferenteIdAvecEp<?php echo $key ?>' ).onchange(); } catch(id) { }
			observeMemeReorientation( 'Bilanparcours66AvecepTypeorientprincipaleId<?php echo $key ?>', 'Bilanparcours66NvstructurereferenteIdAvecEp<?php echo $key ?>', 'Bilanparcours66NvtypeorientIdAvecEp<?php echo $key ?>', 'cgContratReconduitAvecEp', 'cgMaintienOrientAvecEpMemeRef', 'cgMaintienOrientAvecEpChangementRef' );
		<?php } ?>
		$( 'Bilanparcours66DdreconductoncontratAvecEpYear' ).observe( 'change', function(event) {
			checkDatesToRefresh( '', 'AvecEp' );
		} );
		$( 'Bilanparcours66DdreconductoncontratAvecEpMonth' ).observe( 'change', function(event) {
			checkDatesToRefresh( '', 'AvecEp' );
		} );
		$( 'Bilanparcours66DdreconductoncontratAvecEpDay' ).observe( 'change', function(event) {
			checkDatesToRefresh( '', 'AvecEp' );
		} );
		$( 'Bilanparcours66DureeEngagAvecEp' ).observe( 'change', function(event) {
			checkDatesToRefresh( '', 'AvecEp' );
		} );

		observeDisableFieldsOnRadioValue(
			'Bilan',
			'data[Bilanparcours66][proposition]',
			[ 
				'Bilanparcours66Observbenefcompterendu'
			],
			['parcours', 'traitement', undefined],
			false,
			true
		);

		// ---------------------------------------------------------------------

		observeDisableFieldsetOnRadioValue(
			'Bilan',
			'data[Pe][Bilanparcours66][choixparcours]',
			$( 'peMaintienOrientationAvecEp' ),
			'maintien',
			false,
			true
		);

		observeDisableFieldsetOnRadioValue(
			'Bilan',
			'data[Pe][Bilanparcours66][choixparcours]',
			$( 'peReorientationAvecEp' ),
			'reorientation',
			false,
			true
		);

		disableAndHideFormPart( 'peContratReconduitAvecEp' );
		disableAndHideFormPart( 'peMaintienOrientAvecEpChangementRef' );
		disableAndHideFormPart( 'peMaintienOrientAvecEpMemeRef' );

		observeMemeReorientation( 'PeBilanparcours66ChoixparcoursMaintien', 'PeSaisinebilanparcoursep66StructurereferenteId', 'PeSaisinebilanparcoursep66TypeorientId', 'peContratReconduitAvecEp', 'peMaintienOrientAvecEpMemeRef', 'peMaintienOrientAvecEpChangementRef' );

		dependantSelect( 'PeSaisinebilanparcoursep66StructurereferenteId', 'PeSaisinebilanparcoursep66TypeorientId' );
		try { $( 'PeSaisinebilanparcoursep66StructurereferenteId' ).onchange(); } catch(id) { }

		$( 'PeBilanparcours66DdreconductoncontratAvecEpYear' ).observe( 'change', function(event) {
			checkDatesToRefresh( 'Pe', 'AvecEp' );
		} );
		$( 'PeBilanparcours66DdreconductoncontratAvecEpMonth' ).observe( 'change', function(event) {
			checkDatesToRefresh( 'Pe', 'AvecEp' );
		} );
		$( 'PeBilanparcours66DdreconductoncontratAvecEpDay' ).observe( 'change', function(event) {
			checkDatesToRefresh( 'Pe', 'AvecEp' );
		} );
		$( 'PeBilanparcours66DureeEngagAvecEp' ).observe( 'change', function(event) {
			checkDatesToRefresh( 'Pe', 'AvecEp' );
		} );

		// ---------------------------------------------------------------------

		$( 'Bilan' ).getInputs( 'radio', 'data[Pe][Bilanparcours66][avecep_typeorientprincipale_id]' ).each( function ( observeRadio ) {
			$( observeRadio ).observe( 'change', function(event) {
				checkOrientstructTypeorientId( 'data[Pe][Bilanparcours66][avecep_typeorientprincipale_id]', 'peMaintienOrientAvecEp' );
			} );
		} );
		checkOrientstructTypeorientId( 'data[Pe][Bilanparcours66][avecep_typeorientprincipale_id]', 'peMaintienOrientAvecEp' );
		<?php foreach( $options['Bilanparcours66']['typeorientprincipale_id'] as $key => $value ) { ?>
			dependantSelect( 'PeBilanparcours66NvstructurereferenteIdAvecEp<?php echo $key ?>', 'PeBilanparcours66NvtypeorientIdAvecEp<?php echo $key ?>' );
			try { $( 'PeBilanparcours66NvstructurereferenteIdAvecEp<?php echo $key ?>' ).onchange(); } catch(id) { }
		<?php } ?>

		// ---------------------------------------------------------------------

		<?php if ( isset( $passagecommissionep ) && !empty( $passagecommissionep ) ) { ?>
			['traitement', 'parcours', 'audition', 'auditionpe' ].each( function( proposition ) {
				$( proposition ).up().getElementsBySelector( 'input', 'select' ).each( function( elmt ) {
					$( elmt ).writeAttribute('disabled', 'disabled');
				} );
			} );
			['Bilanparcours66TypeformulaireCg', 'Bilanparcours66TypeformulairePe', 'Bilanparcours66DatebilanDay', 'Bilanparcours66DatebilanMonth', 'Bilanparcours66DatebilanYear', 'PeSaisinebilanparcoursep66TypeorientId', 'PeSaisinebilanparcoursep66StructurereferenteId', 'PeBilanparcours66DatebilanDay', 'PeBilanparcours66DatebilanMonth', 'PeBilanparcours66DatebilanYear'].each( function ( elmt ) {
				$( elmt ).writeAttribute('disabled', 'disabled');
			} );
		<?php } ?>

		// Cas des dossiers provenant de la recherche par Pôle Emploi -> Radiés / non inscrits
		<?php if ( isset( $this->params['named']['Bilanparcours66__examenauditionpe'] ) && in_array( $this->params['named']['Bilanparcours66__examenauditionpe'], array( 'radiationpe', 'noninscriptionpe' ) ) ) { ?>
			$( 'Bilanparcours66TypeformulaireCg' ).click();
			[ 'radiotraitement', 'radioparcours', 'radioaudition', 'Bilanparcours66TypeformulairePe' ].each( function ( elmt ) {
				$( elmt ).writeAttribute( 'disabled', 'disabled');
			} );
			$( 'radioauditionpe' ).click();
			<?php if ( $this->params['named']['Bilanparcours66__examenauditionpe'] == 'radiationpe' ) { ?>
				$( 'Bilanparcours66ExamenauditionpeRadiationpe' ).click();
				$( 'Bilanparcours66ExamenauditionpeNoninscriptionpe' ).writeAttribute( 'disabled', 'disabled');
			<?php }
			else if ( $this->params['named']['Bilanparcours66__examenauditionpe'] == 'noninscriptionpe' ) { ?>
				$( 'Bilanparcours66ExamenauditionpeNoninscriptionpe' ).click();
				$( 'Bilanparcours66ExamenauditionpeRadiationpe' ).writeAttribute( 'disabled', 'disabled');
			<?php }
		} ?>
		
		// Cas des dossiers provenant de la recherche par Demande de maintien en social
		<?php if ( isset( $this->params['pass'][1]['Bilanparcours66__maintienensocial'] ) ) { ?>
			$( 'Bilanparcours66TypeformulaireCg' ).click();
			[ 'radiotraitement', 'radioaudition', 'radioauditionpe', 'Bilanparcours66TypeformulairePe' ].each( function ( elmt ) {
				$( elmt ).writeAttribute( 'disabled', 'disabled');
			} );
			$( 'Bilanparcours66Motifep' ).click();
			setInputValue( $( 'Bilanparcours66Motifsaisine' ), 'Demande de maintien en social depuis plus de 24 mois' );
			$( 'radioparcours' ).click();
		<?php } ?>
		
		// ----------------------------------------------------------------------------------------------------------
		//On désactive les boutons radio de la commission parcours et audition si un dossier EP 
		// existe déjà (ou est déjà en cours de passage en EP) pour la thématique en question
		<?php if( $dossiersepsencours['saisinesbilansparcourseps66'] ) { ?>
			$( 'radioparcours' ).writeAttribute( 'disabled', 'disabled');
		<?php }?>
		<?php if( $dossiersepsencours['defautsinsertionseps66'] ) { ?>
			$( 'radioaudition' ).writeAttribute( 'disabled', 'disabled');
		<?php }?>
		
		// ----------------------------------------------------------------------------------------------------------
		
	});

	function setInputValue( input, value ) {
		input = $( input );
		if( ( input != undefined ) && ( $F( input ) == '' ) ) {
			$( input ).setValue( value );
		}
	}
	
	function checkDatesToRefresh( prefixe, suffixe ) {
		if( ( $F( prefixe+'Bilanparcours66Ddreconductoncontrat'+suffixe+'Month' ) ) && ( $F( prefixe+'Bilanparcours66Ddreconductoncontrat'+suffixe+'Year' ) ) && ( $F( prefixe+'Bilanparcours66DureeEngag'+suffixe ) ) ) {
			var correspondances = new Array();
			<?php
				foreach( $options['Bilanparcours66']['duree_engag'] as $index => $duree ):?>correspondances[<?php echo $index;?>] = <?php echo str_replace( ' mois', '' ,$duree );?>;<?php endforeach;?>

			setDateIntervalCer(
				prefixe+'Bilanparcours66Ddreconductoncontrat'+suffixe,
				prefixe+'Bilanparcours66Dfreconductoncontrat'+suffixe,
				correspondances[$F( prefixe+'Bilanparcours66DureeEngag'+suffixe )],
				false
			);
		}
	}

	function checkOrientstructTypeorientId( radioName, divFormPartId ) {
		var v = $( 'Bilan' ).getInputs( 'radio', radioName );
		var currentValue = undefined;
		$( v ).each( function( radio ) {
			if( radio.checked ) {
				currentValue = radio.value;
			}
		} );
		<?php foreach( $options['Bilanparcours66']['typeorientprincipale_id'] as $key => $value ) { ?>
			if ( currentValue == <?php echo $key ?> ) {
				enableAndShowFormPart( divFormPartId+'<?php echo $key ?>' );
			}
			else {
				disableAndHideFormPart( divFormPartId+'<?php echo $key ?>' );
			}
		<?php } ?>
	}

	function observeMemeReorientation( radioprecedente, structurereferenteId, typeorientId, contractualisationFieldset, maintienOrientMemeRefDiv, maintienOrientChangementRefDiv ) {
		[ radioprecedente, typeorientId, structurereferenteId ].each( function( elmt ) {
			$( elmt ).observe( 'change', function(event) {
				checkMemeReorientation( structurereferenteId, typeorientId, contractualisationFieldset, maintienOrientMemeRefDiv, maintienOrientChangementRefDiv );
			} );
		} );
		checkMemeReorientation( structurereferenteId, typeorientId, contractualisationFieldset, maintienOrientMemeRefDiv, maintienOrientChangementRefDiv );
	}

	function checkMemeReorientation( structurereferenteId, typeorientId, contractualisationFieldset, maintienOrientMemeRefDiv, maintienOrientChangementRefDiv ) {
		if ( ( $F( structurereferenteId ) != '' || $F( typeorientId ) != '' ) && $( structurereferenteId ).up(1).hasClassName( 'disabled' ) == false ) {
			<?php if ( $this->action == 'edit' && $this->data['Bilanparcours66']['changementref'] == 'O' ) { ?>
				var typeorient_id = '<?php echo @$this->data['Orientstruct']['typeorient_id'] ?>';
				var structurereferente_id = '<?php echo @$this->data['Orientstruct']['structurereferente_id'] ?>';
			<?php } elseif ( $this->action == 'edit' ) { ?>
				var typeorient_id = '<?php echo @$this->data['Bilanparcours66']['nvtypeorient_id'] ?>';
				var structurereferente_id = '<?php echo @$this->data['Bilanparcours66']['nvstructurereferente_id'] ?>';
			<?php } else { ?>
				var typeorient_id = '<?php echo @$personne['Orientstruct'][0]['typeorient_id'] ?>';
				var structurereferente_id = '<?php echo @$personne['Orientstruct'][0]['structurereferente_id'] ?>';
			<?php } ?>
			var explose = $F( structurereferenteId ).split('_');
			if ( explose[1] == structurereferente_id ) {
				enableAndShowFormPart( contractualisationFieldset );
				enableAndShowFormPart( maintienOrientMemeRefDiv );
				disableAndHideFormPart( maintienOrientChangementRefDiv );
			}
			else if ( $F( typeorientId ) != typeorient_id || ( $F( typeorientId ) == typeorient_id && explose[1] != structurereferente_id && $F( structurereferenteId ) != '' ) ) {
				disableAndHideFormPart( contractualisationFieldset );
				enableAndShowFormPart( maintienOrientChangementRefDiv );
				disableAndHideFormPart( maintienOrientMemeRefDiv );
			}
			else {
				disableAndHideFormPart( contractualisationFieldset );
				disableAndHideFormPart( maintienOrientChangementRefDiv );
				disableAndHideFormPart( maintienOrientMemeRefDiv );
			}
		}
		else if ( $( structurereferenteId ).up(1).hasClassName( 'disabled' ) == false ) {
			disableAndHideFormPart( contractualisationFieldset );
			disableAndHideFormPart( maintienOrientChangementRefDiv );
			disableAndHideFormPart( maintienOrientMemeRefDiv );
		}
	}
</script>