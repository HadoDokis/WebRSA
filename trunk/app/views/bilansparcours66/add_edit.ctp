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
			echo '</div>';
		}
		echo '<div>';
		echo $form->input( 'Bilanparcours66.personne_id', array( 'type' => 'hidden', 'value' => Set::classicExtract( $personne, 'Personne.id') ) );
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

	if ($this->action == 'edit'){
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
			<?php
				echo $default2->subform(
					array(
						'Bilanparcours66.maintienorientation' => array( 'type' => 'radio', 'required' => true ),
						'Bilanparcours66.changementrefsansep' => array( 'type' => 'radio', 'required' => true )
					),
					array(
						'options' => $options
					)
				);
			?>
			<!--<fieldset id="NvReferent">
				<?php
					echo $default->subform(
						array(
							'Bilanparcours66.nvsansep_referent_id'
						),
						array(
							'options' => $options
						)
					);
				?>
			</fieldset>-->
			<fieldset id="Contratreconduit">
				<legend>Reconduction du contrat librement débattu</legend>
				<?php
					echo $default2->subform(
						array(
							'Bilanparcours66.duree_engag',
							'Bilanparcours66.ddreconductoncontrat',
							'Bilanparcours66.dfreconductoncontrat'
						),
						array(
							'options' => $options,
							'domain' => $domain
						)
					);
				?>
			</fieldset>
			<?php
				echo $default2->subform(
					array(
						'Bilanparcours66.accompagnement' => array( 'type' => 'radio' )
					),
					array(
						'options' => $options,
						'domain' => $domain
					)
				);
			?>
		</fieldset>
	</fieldset>
	<fieldset>
		<?php
			/// "Commission Parcours": Examen du dossier avec passage en EP Locale
			$tmp = radioBilan( $this, 'Bilanparcours66.proposition', 'parcours', '"Commission Parcours": Examen du dossier avec passage en EP Locale' );
			echo $xhtml->tag( 'h3', $tmp );
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
			
			<?php
				//FIXME: Récupération de la dernière orientation en cours pour le maintien d'orientation
				echo $xhtml->tag(
					'p',
					'Orientation SOCIALE actuelle : '.Set::extract( $personne, 'Orientstruct.0.Typeorient.lib_type_orient' ),
					array(
						'id' => 'orientationactuelle'
					)
				);
			?>
			
			
			
			<fieldset id="Maintien" class="invisible">
				<?php
					echo $default2->subform(
						array(
							'Bilanparcours66.maintienorientparcours' => array( 'type' => 'radio', 'required' => true ),
							'Bilanparcours66.changementrefparcours' => array( 'type' => 'radio', 'required' => true )
						),
						array(
							'options' => $options
						)
					);
				?>
				<!--<fieldset id="NvparcoursReferent">
					<?php
						echo $default2->subform(
							array(
								'Bilanparcours66.nvparcours_referent_id'
							),
							array(
								'options' => $options
							)
						);
					?>
				</fieldset>-->
				<fieldset id="TypeAccompagnementSocial" class="invisible">
					<?php
						echo $xhtml->tag( 'h2', 'Pour un accompagnement Social', array( 'class' => 'bilanparcours' ) );
// 		                echo $default2->subform(
// 		                    array(
// 								'Bilanparcours66.accompagnement' => array( 'type' => 'radio' )
// 		                    ),
// 		                    array(
// 		                        'options' => $options
// 		                    )
// 		                );
					?>
				</fieldset>
				<fieldset id="TypeAccompagnementPrepro" class="invisible">
					<?php
						echo $xhtml->tag( 'h2', 'Pour un accompagnement Prépro', array( 'class' => 'bilanparcours' ) );
					?>
				</fieldset>
			</fieldset>
			<fieldset id="Reorientation" class="noborder">
				<?php
					echo $default2->subform(
						array(
							'Bilanparcours66.reorientation' => array ( 'type' => 'radio', 'separator' =>'<br />' )
						),
						array(
							'options' => $options
						)
					);
				?>
			</fieldset>
			<fieldset id="Precoreorient">
				<legend>Préconisation de réorientation</legend>
				<?php
					echo $default->subform(
						array(
							'Saisinebilanparcoursep66.typeorient_id',
							'Saisinebilanparcoursep66.structurereferente_id'
						),
						array(
							'options' => $options
						)
					);
				?>
			</fieldset>
		</fieldset>
	</fieldset>
	<fieldset>
		<?php
			/// "Commission Audition": Examen du dossier par la commission EP Locale
			$tmp = radioBilan( $this, 'Bilanparcours66.proposition', 'audition', '"Commission Audition": Examen du dossier par la commission EP Locale' );
			echo $xhtml->tag( 'h3', $tmp );
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
			if (isset($this->validationErrors['Bilanparcours66']['referent_id'])) echo '<div class="error-message">'.$this->validationErrors['Bilanparcours66']['referent_id'].'</div>';
			echo '</div>';
			
			echo '<div class ="input select';
			if (isset($this->validationErrors['Bilanparcours66']['presenceallocataire'])) echo ' error';
			echo '">';
			echo $default->subform(
				array(
					'Pe.Bilanparcours66.presenceallocataire' => array('required'=>true, 'div'=>false)
				),
				array(
					'options' => $options
				)
			);
			if (isset($this->validationErrors['Bilanparcours66']['presenceallocataire'])) echo '<div class="error-message">'.$this->validationErrors['Bilanparcours66']['presenceallocataire'].'</div>';
			echo '</div>';
		?>

<!--<div class="input select error"><label for="Bilanparcours66Presenceallocataire">Allocataire est-il présent ? <abbr title="Champ obligatoire" class="required">*</abbr></label><select class="form-error" id="Bilanparcours66Presenceallocataire" name="data[Bilanparcours66][presenceallocataire]">
<option value=""></option>
<option value="0">Non</option>
<option value="1">Oui</option>
</select><div class="error-message">Champ obligatoire</div></div>-->

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
				'Pe.Bilanparcours66.observbenef'
			),
			array(
				'options' => $options
			)
		);
	?>
	<fieldset id="Precoreorient">
		<legend>Préconisation de réorientation</legend>
		<?php
			echo $default->subform(
				array(
					'Pe.Saisinebilanparcoursep66.typeorient_id' => array('domain'=>'bilanparcours66'),
					'Pe.Saisinebilanparcoursep66.structurereferente_id' => array('domain'=>'bilanparcours66')
				),
				array(
					'options' => $options
				)
			);
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
		if (isset($this->validationErrors['Bilanparcours66']['datebilan'])) echo '<div class="error-message">'.$this->validationErrors['Bilanparcours66']['datebilan'].'</div>';
		echo '</div>';

		echo $xform->input( 'Pe.Bilanparcours66.proposition', array( 'type' => 'hidden', 'value' => 'parcours' ) );
		echo $xform->input( 'Pe.Bilanparcours66.maintienorientation', array( 'type' => 'hidden', 'value' => true ) );
		echo $xform->input( 'Pe.Bilanparcours66.choixparcours', array( 'type' => 'hidden', 'value' => 'reorientation' ) );
	?>
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
}?>

<?php if ( $this->action == 'edit' && isset( $passagecommissionep['Decisionsaisinebilanparcoursep66'][1]['id'] ) && !empty( $passagecommissionep['Decisionsaisinebilanparcoursep66'][1]['id'] ) ) {
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
		dependantSelect( 'Saisinebilanparcoursep66StructurereferenteId', 'Saisinebilanparcoursep66TypeorientId' );
		try { $( 'Saisinebilanparcoursep66StructurereferenteId' ).onchange(); } catch(id) { }

		dependantSelect( 'Bilanparcours66ReferentId', 'Bilanparcours66StructurereferenteId' );

		dependantSelect( 'PeSaisinebilanparcoursep66StructurereferenteId', 'PeSaisinebilanparcoursep66TypeorientId' );
		try { $( 'PeSaisinebilanparcoursep66StructurereferenteId' ).onchange(); } catch(id) { }

		dependantSelect( 'PeBilanparcours66ReferentId', 'PeBilanparcours66StructurereferenteId' );
		
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

		$( 'Bilanparcours66DdreconductoncontratYear' ).observe( 'change', function(event) {
			checkDatesToRefresh();
		} );
		$( 'Bilanparcours66DdreconductoncontratMonth' ).observe( 'change', function(event) {
			checkDatesToRefresh();
		} );
		$( 'Bilanparcours66DdreconductoncontratDay' ).observe( 'change', function(event) {
			checkDatesToRefresh();
		} );
		$( 'Bilanparcours66DureeEngag' ).observe( 'change', function(event) {
			checkDatesToRefresh();
		} );

		// Javascript pour les aides liées à l'APRE
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

		// Partie en cas de changement ou non du référent
		/*observeDisableFieldsetOnRadioValue(
			'Bilan',
			'data[Bilanparcours66][changementrefsansep]',
			$( 'NvReferent' ),
			'O',
			false,
			true
		);*/

		observeDisableFieldsetOnRadioValue(
			'Bilan',
			'data[Bilanparcours66][changementrefsansep]',
			$( 'Contratreconduit' ),
			'N',
			false,
			true
		);

		// Partie en cas de maintien ou  de réorientation
		observeDisableFieldsetOnRadioValue(
			'Bilan',
			'data[Bilanparcours66][choixparcours]',
			$( 'Maintien' ),
			'maintien',
			false,
			true
		);

		// Partie en cas de maintien ou  de réorientation
		observeDisableFieldsetOnRadioValue(
			'Bilan',
			'data[Bilanparcours66][choixparcours]',
			$( 'orientationactuelle' ),
			'maintien',
			false,
			true
		);
		
		observeDisableFieldsetOnRadioValue(
			'Bilan',
			'data[Bilanparcours66][choixparcours]',
			$( 'Reorientation' ),
			'reorientation',
			false,
			true
		);

		observeDisableFieldsetOnRadioValue(
			'Bilan',
			'data[Bilanparcours66][choixparcours]',
			$( 'Precoreorient' ),
			'reorientation',
			false,
			true
		);




		// Partie en cas de maintien en social ou prépro
		observeDisableFieldsetOnRadioValue(
			'Bilan',
			'data[Bilanparcours66][maintienorientparcours]',
			$( 'TypeAccompagnementSocial' ),
			'social',
			false,
			true
		);
		// Partie en cas de maintien en social ou prépro
		observeDisableFieldsetOnRadioValue(
			'Bilan',
			'data[Bilanparcours66][maintienorientparcours]',
			$( 'TypeAccompagnementPrepro' ),
			'prepro',
			false,
			true
		);


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


		/*observeDisableFieldsetOnRadioValue(
			'Bilan',
			'data[Bilanparcours66][changementrefparcours]',
			$( 'NvparcoursReferent' ),
			'O',
			false,
			true
		);*/

// 		observeDisableFieldsetOnRadioValue(
// 		    'Bilan',
// 		    'data[Bilanparcours66][changementrefparcours]',
// 		    $( 'TypeAccompagnement' ),
// 		    'N',
// 		    false,
// 		    true
// 		);
		
		<?php if ($this->action=='edit') { ?>
			['traitement', 'parcours', 'audition', 'auditionpe' ].each( function( proposition ) {
				$( proposition ).up().getElementsBySelector( 'input', 'select' ).each( function( elmt ) {
					$( elmt ).writeAttribute('disabled', 'disabled');
				} );
			} );
			['Bilanparcours66TypeformulaireCg', 'Bilanparcours66TypeformulairePe', 'Bilanparcours66DatebilanDay', 'Bilanparcours66DatebilanMonth', 'Bilanparcours66DatebilanYear', 'PeSaisinebilanparcoursep66TypeorientId', 'PeSaisinebilanparcoursep66StructurereferenteId', 'PeBilanparcours66DatebilanDay', 'PeBilanparcours66DatebilanMonth', 'PeBilanparcours66DatebilanYear'].each( function ( elmt ) {
				$( elmt ).writeAttribute('disabled', 'disabled');
			} );
		<?php } ?>
		
		<?php if ( isset( $this->params['named']['Bilanparcours66__examenauditionpe'] ) && in_array( $this->params['named']['Bilanparcours66__examenauditionpe'], array( 'radiationpe', 'noninscriptionpe' ) ) ) { ?>
			$( 'Bilanparcours66TypeformulaireCg' ).click();
			[ 'radiotraitement', 'radioparcours', 'radioaudition', 'Bilanparcours66TypeformulairePe', 'Bilanparcours66Bilanparcoursinsertion', 'Bilanparcours66Motifep' ].each( function ( elmt ) {
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
	
	});
	
	function checkDatesToRefresh() {
		if( ( $F( 'Bilanparcours66DdreconductoncontratMonth' ) ) && ( $F( 'Bilanparcours66DdreconductoncontratYear' ) ) && ( $F( 'Bilanparcours66DureeEngag' ) ) ) {
			var correspondances = new Array();
			<?php
				foreach( $options['Bilanparcours66']['duree_engag'] as $index => $duree ):?>correspondances[<?php echo $index;?>] = <?php echo str_replace( ' mois', '' ,$duree );?>;<?php endforeach;?>

			setDateIntervalCer(
				'Bilanparcours66Ddreconductoncontrat',
				'Bilanparcours66Dfreconductoncontrat',
				correspondances[$F( 'Bilanparcours66DureeEngag' )],
				false
			);
		}
		
	}
</script>
