<!-- Bloc 1  -->
<fieldset>
    <legend>Service référent désigné par le Département</legend>
    <table class="wide noborder cers93">
    
        <tr>
			<td class="wide noborder">
				<table class="wide noborder">
					<tr>
						<td class="wide noborder"><strong>Type d'orientation</strong></td>
						<td class="wide noborder"><strong>Nom de la structure</strong></td>
						<td class="wide noborder"><strong>Adresse</strong></td>
					</tr>
					<tr>
						<td class="wide noborder"><?php echo Set::classicExtract( $contratinsertion, 'Structurereferente.Typeorient.lib_type_orient' );?></td>
						<td class="wide noborder"><?php echo Set::classicExtract( $contratinsertion, 'Structurereferente.lib_struc' );?></td>
						<td class="wide noborder"><?php echo Set::classicExtract( $contratinsertion, 'Structurereferente.num_voie').' '.Set::enum( Set::classicExtract( $contratinsertion, 'Structurereferente.type_voie'), $options['Structurereferente']['type_voie'] ).' '.Set::classicExtract( $contratinsertion, 'Structurereferente.nom_voie').'<br /> '.Set::classicExtract( $contratinsertion, 'Structurereferente.code_postal').' '.Set::classicExtract( $contratinsertion, 'Structurereferente.ville');?></td>
					</tr>
				</table>
			</td>
			<td class="wide noborder">
			<?php if( !empty( $contratinsertion['Referent']['nom_complet'] ) ):?>
				<table class="wide noborder">
					<tr>
						<td class="wide noborder"><strong>Nom complet</strong></td>
						<td class="wide noborder"><strong>Fonction</strong></td>
						<td class="wide noborder"><strong>Email</strong></td>
						<td class="wide noborder"><strong>N° téléphone</strong></td>
					</tr>
					<tr>
						<td class="wide noborder"><?php echo Set::classicExtract( $contratinsertion, 'Referent.nom_complet' );?></td>
						<td class="wide noborder"><?php echo Set::classicExtract( $contratinsertion, 'Referent.fonction' );?></td>
						<td class="wide noborder"><?php echo Set::classicExtract( $contratinsertion, 'Referent.email' );?></td>
						<td class="wide noborder"><?php echo Set::classicExtract( $contratinsertion, 'Referent.numero_poste' );?></td>
					</tr>
				</table>
				<?php endif;?>
			</td>
        </tr>
        <tr>
            <td class="wide noborder">
				<?php echo $this->Html->tag( 'p', 'Rang du contrat: '.( !empty( $contratinsertion['Contratinsertion']['rg_ci'] ) ? $contratinsertion['Contratinsertion']['rg_ci'] : '1' ) ); ?>
			</td>
        </tr>
    </table>
</fieldset>
<fieldset>
	<legend>État civil</legend>
	 <table class="wide noborder">
        <tr>
            <td class="mediumSize noborder">
                <strong>Statut de la personne : </strong><?php echo Set::enum( Set::classicExtract( $contratinsertion, 'Cer93.rolepers' ), $options['Prestation']['rolepers'] ); ?>
                <br />
                <strong>Nom : </strong><?php echo Set::enum( Set::classicExtract( $contratinsertion, 'Cer93.qual'), $options['Personne']['qual'] ).' '.Set::classicExtract( $contratinsertion, 'Cer93.nom' );?>
                <br />
                <?php if( $contratinsertion['Cer93']['qual'] != 'MR' ):?>
					<strong>Nom de jeune fille : </strong><?php echo Set::classicExtract( $contratinsertion, 'Cer93.nomnai' );?>
					<br />
                <?php endif;?>
                <strong>Prénom : </strong><?php echo Set::classicExtract( $contratinsertion, 'Cer93.prenom' );?>
                <br />
                <strong>Date de naissance : </strong><?php echo date_short( Set::classicExtract( $contratinsertion, 'Cer93.dtnai' ) );?>
                <br />
                <strong>Adresse : </strong>
				<br /><?php echo nl2br( Set::classicExtract( $contratinsertion, 'Cer93.adresse' ) ).'<br \>'.Set::classicExtract( $contratinsertion, 'Cer93.codepos' ).' '.Set::classicExtract( $contratinsertion, 'Cer93.locaadr' );?>
            </td>
            <td class="mediumSize noborder">
                <strong>N° Service instructeur : </strong>
                <?php
					$libservice = Set::enum( Set::classicExtract( $contratinsertion, 'Suiviinstruction.typeserins' ),  $options['Serviceinstructeur']['typeserins'] );
					if( isset( $libservice ) ) {
						echo $libservice;
					}
					else{
						echo 'Non renseigné';
					}
                ?>
                <br />
                <strong>N° demandeur : </strong><?php echo Set::classicExtract( $contratinsertion, 'Cer93.numdemrsa' );?>
                <br />
                <strong>N° CAF/MSA : </strong><?php echo Set::classicExtract( $contratinsertion, 'Cer93.matricule' );?>
                <br />
                <strong>Inscrit au Pôle emploi</strong>
                <?php echo ( !empty( $contratinsertion['Cer93']['identifiantpe'] ) ? 'Oui' : 'Non' );?>
				<br />
				<strong>N° identifiant : </strong><?php echo Set::classicExtract( $contratinsertion, 'Cer93.identifiantpe' );?>
				<br />
				 <strong>Situation familiale : </strong><?php echo Set::enum( Set::classicExtract( $contratinsertion, 'Cer93.sitfam' ), $options['Foyer']['sitfam'] );?>
                <br />
                <strong>Conditions de logement : </strong><?php echo Set::enum( Set::classicExtract( $contratinsertion, 'Cer93.natlog' ), $options['Dsp']['natlog'] );?>
            </td>
        </tr>
    </table>

<?php

	// Bloc 2 : Composition du foyer
	if( !empty( $contratinsertion['Cer93']['Compofoyercer93'] ) ) {
		// Affichage des informations sous forme de tableau
		echo '<table class="mediumSize aere">
			<thead>
				<tr>
					<th>Rôle</th>
					<th>Civilité</th>
					<th>Nom</th>
					<th>Prénom</th>
					<th>Date de naissance</th>
			</thead>
		<tbody>';
		foreach( $contratinsertion['Cer93']['Compofoyercer93'] as $index => $compofoyercer93 ){
			echo $this->Xhtml->tableCells(
				array(
					h( Set::enum( $compofoyercer93['rolepers'], $options['Prestation']['rolepers'] ) ),
					h( Set::enum( $compofoyercer93['qual'], $options['Personne']['qual'] ) ),
					h( $compofoyercer93['nom'] ),
					h( $compofoyercer93['prenom'] ),
					h( $this->Locale->date( 'Date::short', $compofoyercer93['dtnai'] ) )
				),
				array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
				array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
			);
		}
		echo '</tbody></table>';
	}


	echo $this->Xform->fieldValue( 'Cer93.incoherencesetatcivil', Set::classicExtract( $contratinsertion, 'Cer93.incoherencesetatcivil' ) );
?>
</fieldset>

<fieldset>
	<legend>Vérification des droits</legend>
	<?php
		echo $this->Xform->fieldValue( 'Cer93.inscritpe', Set::enum( Set::classicExtract( $contratinsertion, 'Cer93.inscritpe' ), $options['Cer93']['inscritpe'] ) );
		echo $this->Xform->fieldValue( 'Cer93.cmu', Set::enum( Set::classicExtract( $contratinsertion, 'Cer93.cmu' ), $options['Cer93']['cmu'] ) );
		echo $this->Xform->fieldValue( 'Cer93.cmuc', Set::enum( Set::classicExtract( $contratinsertion, 'Cer93.cmuc' ), $options['Cer93']['cmuc'] ) );
	?>
</fieldset>
<fieldset>
	<legend>Formation et expérience</legend>
	<?php
		echo $this->Xform->fieldValue( 'Cer93.nivetu', Set::enum( Set::classicExtract( $contratinsertion, 'Cer93.nivetu' ), $options['Cer93']['nivetu'] ) );
	?>

<table class="wide aere noborder">
	<tr>
		<td style="width:48%" class="noborder">
		<h3>Diplômes (scolaires, universitaires et/ou professionnels)</h3>
			<table id="Diplomecer93">
				<thead>
					<tr>
						<th>Intitulé du diplôme</th>
						<th>Année d'obtention</th>
					</tr>
				</thead>
				<tbody>
					<?php
						if( !empty( $contratinsertion['Cer93']['Diplomecer93'] ) ) {
							foreach( $contratinsertion['Cer93']['Diplomecer93'] as $index => $diplomecer93 ) {
								echo $this->Xhtml->tableCells(
									array(
										h( $diplomecer93['name'] ),
										h( $diplomecer93['annee'] ),
									),
									array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
									array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
								);
								
							}
						}
					?>
				</tbody>
			</table>
		</td>
		<td class="noborder">
			<h3>Expériences professionnelles significatives</h3>
				<table>
					<thead>
						<tr>
							<th>Métier exercé</th>
							<th>Secteur d'activité</th>
							<th>Année de début</th>
							<th>Durée</th>
						</tr>
					</thead>
					<tbody>
						<?php
							if( !empty( $contratinsertion['Cer93']['Expprocer93'] ) ) {
								foreach( $contratinsertion['Cer93']['Expprocer93'] as $index => $expprocer93 ) {
									echo $this->Html->tableCells(
										array(
											h( Set::enum( $expprocer93['metierexerce_id'], $options['Expprocer93']['metierexerce_id'] ) ),
											h( Set::enum( $expprocer93['secteuracti_id'], $options['Expprocer93']['secteuracti_id'] ) ),
											h( $expprocer93['anneedeb'] ),
											h( $expprocer93['duree'] ),
										),
										array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
										array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
									);
								}
							}
						?>
					</tbody>
				</table>
			</td>
		</tr>
	</table>
	<?php
		echo $this->Xform->fieldValue( 'Cer93.autresexps', Set::classicExtract( $contratinsertion, 'Cer93.autresexps') );
		echo $this->Xform->fieldValue( 'Cer93.isemploitrouv', Set::enum( Set::classicExtract( $contratinsertion, 'Cer93.isemploitrouv'), $options['Cer93']['isemploitrouv'] ) );
		if( $contratinsertion['Cer93']['isemploitrouv'] == 'O' ) {
			echo $this->Xform->fieldValue( 'Cer93.secteuracti_id', Set::enum( Set::classicExtract( $contratinsertion, 'Cer93.secteuracti_id'), $options['Expprocer93']['secteuracti_id'] ) );
			echo $this->Xform->fieldValue( 'Cer93.metierexerce_id', Set::enum( Set::classicExtract( $contratinsertion, 'Cer93.metierexerce_id'), $options['Expprocer93']['metierexerce_id'] ) );
			echo $this->Xform->fieldValue( 'Cer93.dureehebdo', Set::enum( Set::classicExtract( $contratinsertion, 'Cer93.dureehebdo'), $options['dureehebdo'] ) );
			echo $this->Xform->fieldValue( 'Cer93.naturecontrat_id', Set::enum( Set::classicExtract( $contratinsertion, 'Cer93.naturecontrat_id'), $options['Naturecontrat']['naturecontrat_id'] ) );
			
			if( !empty( $contratinsertion['Cer93']['dureecdd'] ) ) {
				echo $this->Xform->fieldValue( 'Cer93.dureecdd', Set::enum( Set::classicExtract( $contratinsertion, 'Cer93.dureecdd'), $options['dureecdd'] ) );
			}
		}
	?>
	<!-- Fin bloc 4 -->
</fieldset>
<fieldset id="bilanpcd"><legend>Bilan du contrat précédent</legend>
	<?php
		//Bloc 5 : Bilan du précédent contrat
		echo $this->Xform->fieldValue( 'Cer93.bilancerpcd', Set::classicExtract( $contratinsertion, 'Cer93.bilancerpcd') );

		// Bloc 6 : Projet pour ce nouveau contrat
		echo $this->Xform->fieldValue( 'Cer93.prevu', Set::classicExtract( $contratinsertion, 'Cer93.prevu') );

	?>
		<table>
			<thead>
				<tr>
					<th>Sujet du CER</th>
					<th>Sous sujet</th>
					<th>Si autre, commentaire</th>
				</tr>
			</thead>
			<tbody>
				<?php
					if( !empty( $contratinsertion['Cer93']['Sujetcer93'] ) ) {
						foreach( $contratinsertion['Cer93']['Sujetcer93'] as $index => $sujetcer93 ) {
							echo $this->Html->tableCells(
								array(
									h( $sujetcer93['name'] ),
									h( $sujetcer93['Cer93Sujetcer93']['Soussujetcer93']['name'] ),
									h( $sujetcer93['Cer93Sujetcer93']['commentaireautre'] )
								),
								array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
								array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
							);
						}
					}
				?>
			</tbody>
		</table>
</fieldset>
<?php
	//Bloc 7 : Durée proposée
	echo $this->Xform->fieldValue( 'Cer93.duree', Set::enum( Set::classicExtract( $contratinsertion, 'Cer93.duree'), $options['Cer93']['duree'] ) );

	//Bloc 8 : Projet pour ce nouveau contrat
	echo $this->Xform->fieldValue( 'Cer93.pointparcours', Set::enum( Set::classicExtract( $contratinsertion, 'Cer93.pointparcours'), $options['Cer93']['pointparcours'] ) );
	if( !empty( $contratinsertion['Cer93']['datepointparcours'] ) ) {
		echo $this->Xform->fieldValue( 'Cer93.datepointparcours', date_short( Set::classicExtract( $contratinsertion, 'Cer93.datepointparcours') ) );
	}
?>
<?php


	//Bloc 9 : Partie réservée au professionnel en charge du contrat
	echo $this->Xform->fieldValue( 'Cer93.structureutilisateur', Set::classicExtract( $contratinsertion, 'Cer93.structureutilisateur' ) );
	echo $this->Xform->fieldValue( 'Cer93.nomutilisateur', Set::classicExtract( $contratinsertion, 'Cer93.nomutilisateur' ) );

	echo $this->Xform->fieldValue( 'Cer93.pourlecomptede', Set::classicExtract( $contratinsertion, 'Cer93.pourlecomptede' ) );
	echo $this->Xform->fieldValue( 'Cer93.observpro', Set::classicExtract( $contratinsertion, 'Cer93.observpro' ) );

	echo $this->Xform->fieldValue( 'Contratinsertion.dd_ci', date_short( Set::classicExtract( $contratinsertion, 'Contratinsertion.dd_ci') ) );
	echo $this->Xform->fieldValue( 'Contratinsertion.df_ci', date_short( Set::classicExtract( $contratinsertion, 'Contratinsertion.df_ci') ) );
	echo $this->Xform->fieldValue( 'Contratinsertion.date_saisi_ci', date_short( Set::classicExtract( $contratinsertion, 'Contratinsertion.date_saisi_ci') ) );
?>