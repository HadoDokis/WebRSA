<?php
	$title_for_layout = 'Visualisation du Bilan de parcours';
	$this->set( 'title_for_layout', $title_for_layout );

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}
?>
<?php echo $this->Html->tag( 'h1', $title_for_layout );?>

<fieldset><legend>BILAN DU PARCOURS</legend>
	<?php

		echo $this->Xform->fieldValue( 'Bilanparcours66.typeformulaire', Set::enum( Set::classicExtract( $bilanparcours66, 'Bilanparcours66.typeformulaire'), $options['Bilanparcours66']['typeformulaire'] ) );
		echo $this->Xform->fieldValue( 'Bilanparcours66.serviceinstructeur_id', Set::classicExtract( $bilanparcours66, 'Serviceinstructeur.lib_service' ) );
		echo $this->Xform->fieldValue( 'Bilanparcours66.structurereferente_id', $bilanparcours66['Structurereferente']['lib_struc'] );
		echo $this->Xform->fieldValue( 'Bilanparcours66.referent_id', $bilanparcours66['Referent']['nom_complet'] );
		echo $this->Xform->fieldValue( 'Bilanparcours66.presenceallocataire', Set::enum( Set::classicExtract( $bilanparcours66, 'Bilanparcours66.presenceallocataire'), $options['Bilanparcours66']['presenceallocataire'] ) );
	?>
	<fieldset>
		<legend>Situation de l'allocataire</legend>
		<table class="wide noborder">
			<tr>
				<td class="mediumSize noborder">
					<strong>Statut de la personne : </strong><?php echo Set::enum( Set::extract( $bilanparcours66, 'Prestation.rolepers' ), $options['Prestation']['rolepers'] ); ?>
					<br />
					<strong>Nom : </strong><?php echo Set::enum( Set::classicExtract( $bilanparcours66, 'Personne.qual') , $options['Personne']['qual'] ).' '.Set::classicExtract( $bilanparcours66, 'Personne.nom' );?>
					<br />
					<strong>Prénom : </strong><?php echo Set::classicExtract( $bilanparcours66, 'Personne.prenom' );?>
					<br />
					<strong>Date de naissance : </strong><?php echo date_short( Set::classicExtract( $bilanparcours66, 'Personne.dtnai' ) );?>
				</td>
				<td class="mediumSize noborder">
					<strong>N° demandeur : </strong><?php echo Set::classicExtract( $bilanparcours66, 'Dossier.numdemrsa' );?>
					<br />
					<strong>N° CAF/MSA : </strong><?php echo Set::classicExtract( $bilanparcours66, 'Dossier.matricule' );?>
					<br />
					<strong>Inscrit au Pôle emploi</strong>
					<?php
						$isPoleemploi = Set::classicExtract( $bilanparcours66, 'Historiqueetatpe.etat' );
						if( $isPoleemploi == 'inscription' )
							echo 'Oui';
						else
							echo 'Non';
					?>
					<br />
					<strong>N° identifiant : </strong><?php echo Set::classicExtract( $bilanparcours66, 'Historiqueetatpe.identifiantpe' );?>
				</td>
			</tr>
			<tr>
				<td class="mediumSize noborder">
					<strong>Adresse : </strong><br /><?php echo Set::classicExtract( $bilanparcours66, 'Adresse.numvoie' ).' '.Set::enum( Set::classicExtract( $bilanparcours66, 'Adresse.typevoie' ), $options['Adresse']['typevoie'] ).' '.Set::classicExtract( $bilanparcours66, 'Adresse.nomvoie' ).'<br /> '.Set::classicExtract( $bilanparcours66, 'Adresse.codepos' ).' '.Set::classicExtract( $bilanparcours66, 'Adresse.locaadr' );?>
				</td>
			</tr>
		</table>
		<?php
			echo $this->Xhtml->tag(
				'p',
				'<strong>Orientation actuelle (au moment de la saisie du bilan) : </strong>'.Set::extract( $bilanparcours66, 'Typeorientorigine.lib_type_orient' )
			);

			echo $this->Xform->fieldValue( 'Bilanparcours66.sitfam', Set::enum( Set::classicExtract( $bilanparcours66, 'Bilanparcours66.sitfam'), $options['Bilanparcours66']['sitfam'] ) );

		?>
	</fieldset>
	<?php if( $bilanparcours66['Bilanparcours66']['bilanparcoursinsertion'] != '0' ) :?>
		<fieldset><legend>Bilan du parcours d'insertion</legend>
			<?php
				echo $this->Xform->fieldValue( 'Bilanparcours66.situationperso', Set::classicExtract( $bilanparcours66, 'Bilanparcours66.situationperso' ) );
				echo $this->Xform->fieldValue( 'Bilanparcours66.situationpro', Set::classicExtract( $bilanparcours66, 'Bilanparcours66.situationpro' ) );
				echo $this->Xform->fieldValue( 'Bilanparcours66.objinit', Set::classicExtract( $bilanparcours66, 'Bilanparcours66.objinit' ) );
				echo $this->Xform->fieldValue( 'Bilanparcours66.objatteint', Set::classicExtract( $bilanparcours66, 'Bilanparcours66.objatteint' ) );
				echo $this->Xform->fieldValue( 'Bilanparcours66.objnew', Set::classicExtract( $bilanparcours66, 'Bilanparcours66.objnew' ) );
			?>
		</fieldset>
	<?php endif;?>
	<?php if( $bilanparcours66['Bilanparcours66']['motifep'] != '0' ) :?>
		<fieldset><legend>Motif de la saisine</legend>
			<?php
				echo $this->Xform->fieldValue( 'Bilanparcours66.motifsaisine', Set::classicExtract( $bilanparcours66, 'Bilanparcours66.motifsaisine' ) );
			?>
		</fieldset>
	<?php endif;?>
	<fieldset>
		<?php
			echo $this->Xhtml->tag(
				'p',
				'Proposition du référent :',
				array(
					'style' => 'text-align: center; font-size: 14px; font-weight:bold;'
				)
			);

			$structureOrigine = Set::classicExtract( $bilanparcours66, 'Typeorientorigine.lib_type_orient' );
			$structureNouvelle = Set::classicExtract( $bilanparcours66, 'Structurereferentenouvelle.lib_struc' );

			echo $this->Xform->fieldValue( 'Bilanparcours66.proposition', Set::enum( Set::classicExtract( $bilanparcours66 , 'Bilanparcours66.proposition' ), $options['Bilanparcours66']['proposition'] ) );

			// Affichage selon la proposition
			if( $bilanparcours66['Bilanparcours66']['proposition'] == 'aucun' ) {
				echo '';
			}
			else if( $bilanparcours66['Bilanparcours66']['proposition'] == 'traitement' ) {
				echo $this->Xform->fieldValue( 'Bilanparcours66.avecep_typeorientprincipale_id', Set::classicExtract( $bilanparcours66 , 'Typeorientprincipale.lib_type_orient' ) );
				echo $this->Xform->fieldValue( 'Bilanparcours66.nvtypeorient_id', Set::classicExtract( $bilanparcours66 , 'NvTypeorient.lib_type_orient' ) );
				echo $this->Xform->fieldValue( 'Bilanparcours66.nvstructurereferente_id', Set::classicExtract( $bilanparcours66 , 'NvStructurereferente.lib_struc' ) );

				$avecSansChangementRef = $bilanparcours66['Bilanparcours66']['changementrefsansep'];
				if( $avecSansChangementRef == 'N' ) {
					echo '<div class="input text"><span class="label">&nbsp;</span><span class="input">Sans changement de référent</span></div>';
				}
				else {
					echo '<div class="input text"><span class="label">&nbsp;</span><span class="input">Avec changement de référent</span></div>';
				}

				//En cas de maintien au sein de la même structure
				if( $structureNouvelle == $structureOrigine ) {
					echo '<div class="aere">';
					echo '<fieldset><legend>Reconduction du contrat librement débattu</legend>';
						// Reconduction du contrat librement débattu
						echo $this->Xform->fieldValue( 'Bilanparcours66.duree_engag', Set::enum( Set::classicExtract( $bilanparcours66 , 'Bilanparcours66.duree_engag' ), $options['Bilanparcours66']['duree_engag'] ) );
						echo $this->Xform->fieldValue( 'Bilanparcours66.ddreconductoncontrat', date_short( Set::classicExtract( $bilanparcours66 , 'Bilanparcours66.ddreconductoncontrat' ) ) );
						echo $this->Xform->fieldValue( 'Bilanparcours66.dfreconductoncontrat', date_short( Set::classicExtract( $bilanparcours66 , 'Bilanparcours66.dfreconductoncontrat' ) ) );
					echo '</fieldset>';
					echo '</div>';
				}

			}
			else if( $bilanparcours66['Bilanparcours66']['proposition'] == 'parcours' ) {
				echo $this->Xform->fieldValue( 'Bilanparcours66.choixparcours', Set::enum( Set::classicExtract( $bilanparcours66 , 'Bilanparcours66.choixparcours' ), $options['Bilanparcours66']['choixparcours'] ) );
				//Pour un accompagnement
				echo $this->Xform->fieldValue( 'Bilanparcours66.avecep_typeorientprincipale_id', Set::classicExtract( $bilanparcours66 , 'Typeorientprincipale.lib_type_orient' ) );
				echo $this->Xform->fieldValue( 'Bilanparcours66.nvtypeorient_id', Set::classicExtract( $bilanparcours66 , 'NvTypeorient.lib_type_orient' ) );
				echo $this->Xform->fieldValue( 'Bilanparcours66.nvstructurereferente_id', Set::classicExtract( $bilanparcours66 , 'NvStructurereferente.lib_struc' ) );

				$avecSansChangementRef = $bilanparcours66['Bilanparcours66']['changementrefsansep'];
				$choixparcours = $bilanparcours66['Bilanparcours66']['choixparcours'];
				if( $avecSansChangementRef == 'N' && $choixparcours == 'maintien' ) {
					echo '<div class="input text"><span class="label">&nbsp;</span><span class="input">Sans changement de référent</span></div>';
				}
				else {
					echo '<div class="input text"><span class="label">&nbsp;</span><span class="input">Avec changement de référent</span></div>';
				}
			}
			else if( $bilanparcours66['Bilanparcours66']['proposition'] == 'audition' ) {
				echo $this->Xform->fieldValue( 'Bilanparcours66.examenaudition', Set::enum( Set::classicExtract( $bilanparcours66 , 'Bilanparcours66.examenaudition' ), $options['Bilanparcours66']['examenaudition'] ) );

				echo $this->Xform->fieldValue( 'Bilanparcours66.observbenefcompterendu', Set::classicExtract( $bilanparcours66, 'Bilanparcours66.observbenefcompterendu' ) );
			}
		?>
	</fieldset>
	<fieldset>
		<?php
			echo $this->Xform->fieldValue( 'Bilanparcours66.infoscomplementaires', Set::classicExtract( $bilanparcours66, 'Bilanparcours66.infoscomplementaires' ) );
			echo $this->Xform->fieldValue( 'Bilanparcours66.observbenefrealisationbilan', Set::classicExtract( $bilanparcours66, 'Bilanparcours66.observbenefrealisationbilan' ) );

			echo $this->Xform->fieldValue( 'Bilanparcours66.datebilan', date_short( Set::classicExtract( $bilanparcours66, 'Bilanparcours66.datebilan' ) ) );
		?>
	</fieldset>
</fieldset>
<?php
	echo $this->Default->button(
		'back',
		array(
			'controller' => 'bilansparcours66',
			'action'     => 'index',
			$bilanparcours66['Bilanparcours66']['personne_id']
		),
		array(
			'id' => 'Back'
		)
	);
?>