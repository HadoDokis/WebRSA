<?php
	$title_for_layout = 'Visualisation du CUI';
	$this->set( 'title_for_layout', $title_for_layout );

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>
<?php echo $this->Html->tag( 'h1', $title_for_layout );?>

<fieldset><legend></legend>
	<?php
		echo $this->Xform->fieldValue( 'Cui.typecui', Set::enum( Hash::get( $cui, 'Cui.typecui'), $options['Cui']['typecui']) );
	?>
</fieldset>

<fieldset><legend>Secteur</legend>
	<?php
		echo $this->Xform->fieldValue( 'Cui.secteur', Set::enum( Hash::get( $cui, 'Cui.secteurcui_id'), $secteurscuis ) );
		echo $this->Xform->fieldValue( 'Cui.isaci', Set::enum( Hash::get( $cui, 'Cui.isaci' ), $options['Cui']['isaci'] ) );
		echo $this->Xform->fieldValue( 'Cui.numconvention', $cui['Cui']['numconvention'] );
		echo $this->Xform->fieldValue( 'Cui.numconventionobj', $cui['Cui']['numconventionobj'] );
	?>
</fieldset>
<?php
$codepos = Hash::get( $personne, 'Adresse.codepos' );
$depSplit = substr( $codepos, '0', 2 );
?>
<fieldset>
	<legend>LE SALARIÉ</legend>
	<table class="wide noborder">
		<tr>
			<td class="mediumSize noborder">
				<strong>Nom : </strong><?php echo Set::enum( Hash::get( $personne, 'Personne.qual'), $qual ).' '.Hash::get( $personne, 'Personne.nom' );?>
				<br />
				<?php if(  Hash::get( $personne, 'Personne.qual') != 'MR' ):?>
					<strong>Pour les femmes, nom patronymique : </strong><?php echo Hash::get( $personne, 'Personne.nomnai' );?>
				<?php endif;?>
				<br />
				<strong>Né(e) le : </strong>
					<?php
						echo date_short( Hash::get( $personne, 'Personne.dtnai' ) ).' <strong>à</strong>  '.Hash::get( $personne, 'Personne.nomcomnai' );
					?>
				<br />
				<strong>Adresse : </strong><br />
					<?php
						echo Hash::get( $personne, 'Adresse.numvoie' ).' '.Hash::get( $options['typevoie'], Hash::get( $personne, 'Adresse.typevoie' ) ).' '.Hash::get( $personne, 'Adresse.nomvoie' ).'<br /> '.Hash::get( $personne, 'Adresse.compladr' ).'<br /> '.Hash::get( $personne, 'Adresse.codepos' ).' '.Hash::get( $personne, 'Adresse.locaadr' );
					?>
				<br />
				<!-- Si on n'autorise pas la diffusion de l'email, on n'affiche rien -->
				<?php if( Hash::get( $personne, 'Foyer.Modecontact.0.autorutiadrelec' ) == 'A' ):?>
					<strong>Adresse électronique : </strong><?php echo Hash::get( $personne, 'Foyer.Modecontact.0.adrelec' );?>
				<?php endif;?>
			</td>
			<td class="mediumSize noborder">
				<strong>Prénoms : </strong><?php echo Hash::get( $personne, 'Personne.prenom' );?>
				<br />
				<strong>NIR : </strong><?php echo Hash::get( $personne, 'Personne.nir');?>
				<br />
				<strong>Département : </strong><?php echo Set::extract( $depSplit, $dept );?>
				<br />
				<strong>Canton : </strong><?php echo Hash::get( $personne, 'Canton.canton' );?>
				<br />
				<strong>Nationalité : </strong><?php echo Set::enum( Hash::get( $personne, 'Personne.nati' ), $nationalite );?>
				<br />
				<strong>Date de fin de titre de séjour : </strong><?php echo date_short( Hash::get( $personne, 'Titresejour.dftitsej' ) );?>
				<br />
				<strong>Référent en cours : </strong><?php echo Set::enum( Hash::get( $personne, 'PersonneReferent.referent_id' ), $referents );?>
				<br />
			</td>
		</tr>
		<tr>
			<td class="noborder" colspan="2">
				<strong>Si bénéficiaire RSA, n° allocataire : </strong>
				<?php
					echo Hash::get( $personne, 'Dossier.matricule' ).'  <strong>relève de : </strong> '.Hash::get( $personne, 'Dossier.fonorg' );
				?>
			</td>
		</tr>
	</table>
	<?php
		echo $this->Xform->fieldValue( 'Cui.compofamiliale', Set::enum( Hash::get( $cui, 'Cui.compofamiliale' ), $options['Cui']['compofamiliale']  ) );
		echo $this->Xform->fieldValue( 'Cui.montantrsapercu', $cui['Cui']['montantrsapercu'] );
		echo $this->Xform->fieldValue( 'Cui.nbperscharge', Hash::get( $personne, 'Foyer.nbenfants' ), true, 'text' );

	?>
</fieldset>
<fieldset>
	<legend>SITUATION DU SALARIE AVANT LA SIGNATURE DE LA CONVENTION </legend>
		<?php
			echo $this->Xform->fieldValue( 'Cui.niveauformation', Set::enum( Hash::get( $cui, 'Cui.niveauformation' ), $options['Cui']['niveauformation']  ) );
			echo $this->Xform->fieldValue( 'Cui.dureesansemploi', Set::enum( Hash::get( $cui, 'Cui.dureesansemploi' ), $options['Cui']['dureesansemploi']  ) );
			echo $this->Xform->fieldValue( 'Cui.isinscritpe', Set::enum( Hash::get( $cui, 'Cui.isinscritpe' ), $options['Cui']['isinscritpe']  ) );
			
			echo $this->Xform->fieldValue( 'Cui.identifiantpe', $cui['Cui']['identifiantpe'] );
			
			if( $cui['Cui']['isinscritpe'] == '1' ) {
				echo $this->Xform->fieldValue( 'Cui.dureeinscritpe', Set::enum( Hash::get( $cui, 'Cui.dureeinscritpe' ), $options['Cui']['dureeinscritpe']  ) );
			}
			
			echo $this->Xform->fieldValue( 'Cui.isbeneficiaire', Set::enum( Hash::get( $cui, 'Cui.isbeneficiaire' ), $options['Cui']['isbeneficiaire']  ) );
			echo $this->Xform->fieldValue( 'Cui.rsadeptmaj', Set::enum( Hash::get( $cui, 'Cui.rsadeptmaj' ), $options['Cui']['rsadeptmaj']  ) );
			if( $cui['Cui']['rsadeptmaj'] == '1' ) {
				echo $this->Xform->fieldValue( 'Cui.dureebenefaide', Set::enum( Hash::get( $cui, 'Cui.dureebenefaide' ), $options['Cui']['dureebenefaide']  ) );
				echo $this->Xhtml->tag( 'p', '( Pour les bénéficiaires du RSA, y compris la période antérieure au 01/06/2009 en RMI ou API )', array( 'class' => 'remarque' ) );
			}
			echo $this->Xform->fieldValue( 'Cui.handicap', Set::enum( Hash::get( $cui, 'Cui.handicap' ), $options['Cui']['handicap']  ) );
		?>
</fieldset>

<!--********************* Le contrat de travail ********************** -->

<fieldset>
	<legend>LE CONTRAT DE TRAVAIL</legend>
	<?php
		echo $this->Xform->fieldValue( 'Cui.typecontrat', Set::enum( Hash::get( $cui, 'Cui.typecontrat' ), $options['Cui']['typecontrat']  ) );
		echo $this->Xform->fieldValue( 'Cui.dateembauche', date_short( $cui['Cui']['dateembauche'] ) );
		if( $cui['Cui']['typecontrat'] == 'CDD' ) {
			echo $this->Xform->fieldValue( 'Cui.datefincontrat', date_short( $cui['Cui']['datefincontrat'] ) );
		}
		
		echo $this->Xform->fieldValue( 'Cui.secteuremploipropose_id', Set::enum( Hash::get( $cui, 'Cui.secteuremploipropose_id' ), $secteursactivites  ) );
		echo $this->Xform->fieldValue( 'Cui.metieremploipropose_id', Set::enum( Hash::get( $cui, 'Cui.secteuremploipropose_id' ).'_'.Hash::get( $cui, 'Cui.metieremploipropose_id' ), $options['Coderomemetierdsp66'] ) );
		echo $this->Xform->fieldValue( 'Cui.salairebrut', $cui['Cui']['salairebrut'] );
		
		echo $this->Xform->fieldValue( 'Cui.dureehebdosalarieheure', $cui['Cui']['dureehebdosalarieheure'].' H '.$cui['Cui']['dureehebdosalarieminute'] );
		
		echo $this->Xform->fieldValue( 'Cui.modulation', Set::enum( Hash::get( $cui, 'Cui.modulation' ), $options['Cui']['modulation']  ) );
	?>
</fieldset>


<!--********************* Les actions d'accompagnement et de formation prévues ********************** -->
<fieldset>
	<legend>LES ACTIONS D'ACCOMPAGNEMENT ET DE FORMATION PRÉVUES</legend>
	<?php
		echo $this->Xform->fieldValue( 'Cui.tuteur', $cui['Cui']['tuteur'] );
		echo $this->Xform->fieldValue( 'Cui.fonctiontuteur', $cui['Cui']['fonctiontuteur'] );
		echo $this->Xform->fieldValue( 'Cui.orgsuivi_id', Set::enum( Hash::get( $cui, 'Cui.orgsuivi_id' ), $organismes  ) );
		echo $this->Xform->fieldValue( 'Cui.referent_id', Set::enum( Hash::get( $cui, 'Cui.orgsuivi_id' ).'_'.Hash::get( $cui, 'Cui.referent_id' ), $prestataires ) );
		echo $this->Xform->fieldValue( 'Cui.isaas', Set::enum( Hash::get( $cui, 'Cui.isaas' ), $options['Cui']['isaas']  ) );

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
					echo $this->Xform->fieldValue( 'Cui.remobilisation', Set::enum( Hash::get( $cui, 'Cui.remobilisation' ), $options['Cui']['remobilisation']  ) );
					echo $this->Xform->fieldValue( 'Cui.aidereprise', Set::enum( Hash::get( $cui, 'Cui.aidereprise' ), $options['Cui']['aidereprise']  ) );
					echo $this->Xform->fieldValue( 'Cui.elaboprojetpro', Set::enum( Hash::get( $cui, 'Cui.elaboprojetpro' ), $options['Cui']['elaboprojetpro']  ) );
					echo $this->Xform->fieldValue( 'Cui.evaluation', Set::enum( Hash::get( $cui, 'Cui.evaluation' ), $options['Cui']['evaluation']  ) );
					echo $this->Xform->fieldValue( 'Cui.aiderechemploi', Set::enum( Hash::get( $cui, 'Cui.aiderechemploi' ), $options['Cui']['aiderechemploi']  ) );
					echo $this->Xform->fieldValue( 'Cui.autre', Hash::get( $cui, 'Cui.autre' ) );
					
				?>
			</td>
			<td class="cui5 noborder">
				<?php
					echo $this->Xhtml->tag( 'p', 'Type d\'actions : ' );
					echo $this->Xform->fieldValue( 'Cui.adaptation', Set::enum( Hash::get( $cui, 'Cui.adaptation' ), $options['Cui']['adaptation']  ) );
					echo $this->Xform->fieldValue( 'Cui.remiseniveau', Set::enum( Hash::get( $cui, 'Cui.remiseniveau' ), $options['Cui']['remiseniveau']  ) );
					echo $this->Xform->fieldValue( 'Cui.prequalification', Set::enum( Hash::get( $cui, 'Cui.prequalification' ), $options['Cui']['prequalification']  ) );
					echo $this->Xform->fieldValue( 'Cui.nouvellecompetence', Set::enum( Hash::get( $cui, 'Cui.nouvellecompetence' ), $options['Cui']['nouvellecompetence']  ) );
					echo $this->Xform->fieldValue( 'Cui.formqualif', Set::enum( Hash::get( $cui, 'Cui.formqualif' ), $options['Cui']['formqualif']  ) );
					echo $this->Xform->fieldValue( 'Cui.formation', Set::enum( Hash::get( $cui, 'Cui.formation' ), $options['Cui']['formation']  ) );
					echo $this->Xform->fieldValue( 'Cui.isperiodepro', Set::enum( Hash::get( $cui, 'Cui.isperiodepro' ), $options['Cui']['isperiodepro']  ) );
					echo $this->Xform->fieldValue( 'Cui.niveauqualif', Set::enum( Hash::get( $cui, 'Cui.niveauqualif' ), $options['Cui']['niveauformation']  ) );
					echo $this->Xform->fieldValue( 'Cui.validacquis', Set::enum( Hash::get( $cui, 'Cui.validacquis' ), $options['Cui']['validacquis']  ) );
				?>
			</td>
		</tr>
	</table>
</fieldset>
<fieldset>
	<legend>Accompagnements</legend>
	<?php
		echo $this->Xform->fieldValue( 'Cui.iscae', Set::enum( Hash::get( $cui, 'Cui.iscae' ), $options['Cui']['iscae']  ) );
	?>
</fieldset>

<!--********************* La prise en charge (cadre réservé au prescripteur) ********************** -->
<fieldset>
	<legend>LA PRISE EN CHARGE (CADRE RÉSERVÉ AU PRESCRIPTEUR)</legend>
	<?php
		echo $this->Xform->fieldValue( 'Cui.datedebprisecharge', date_short( Hash::get( $cui, 'Cui.datedebprisecharge' ) ) );
		echo $this->Xform->fieldValue( 'Cui.dureeprisecharge', Set::enum( Hash::get( $cui, 'Cui.dureeprisecharge' ), $options['Cui']['dureeprisecharge']  ) );
		echo $this->Xform->fieldValue( 'Cui.datefinprisecharge', date_short( Hash::get( $cui, 'Cui.datefinprisecharge' ) ) );

		echo $this->Xform->fieldValue( 'Cui.dureehebdoretenueheure', $cui['Cui']['dureehebdoretenueheure'].' H '.$cui['Cui']['dureehebdoretenueminute'] );
		
		echo $this->Xform->fieldValue( 'Cui.opspeciale', Hash::get( $cui, 'Cui.opspeciale' ) );
		
		echo $this->Xform->fieldValue( 'Cui.tauxfixe', Hash::get( $cui, 'Cui.tauxfixe' ) );

		echo $this->Xhtml->tag( 'hr /');

		echo $this->Xhtml->tag( 'p','Dans le cas d\'un contrat prescrit par le Conseil Général ou pour son compte (sur la base d\'une convention d\'objectifs et de moyens)', array( 'class' => 'aere' ) );
		
		echo $this->Xform->fieldValue( 'Cui.tauxprisencharge', Hash::get( $cui, 'Cui.tauxprisencharge' ) );
		
		echo $this->Xform->fieldValue( 'Cui.financementexclusif', Set::enum( Hash::get( $cui, 'Cui.financementexclusif' ), $options['Cui']['financementexclusif']  ) );
		
		echo $this->Xform->fieldValue( 'Cui.tauxfinancementexclusif', Hash::get( $cui, 'Cui.tauxfinancementexclusif' ) );
		
		if( Configure::read( 'nom_form_cui_cg' ) == 'cg93' ){
			echo $this->Xform->fieldValue( 'Cui.orgapayeur', Set::enum( Hash::get( $cui, 'Cui.orgapayeur' ), $options['Cui']['orgapayeur']  ) );
			echo $this->Xform->fieldValue( 'Cui.organisme', Hash::get( $cui, 'Cui.organisme' ) );
			echo $this->Xform->fieldValue( 'Cui.adresseorganisme', Hash::get( $cui, 'Cui.adresseorganisme' ) );
		}
	?>
</fieldset>
<fieldset>
	<legend></legend>
	<?php
		echo $this->Xform->fieldValue( 'Cui.datecontrat', date_short( Hash::get( $cui, 'Cui.datecontrat' ) ) );
	?>
</fieldset>

<?php
	echo $this->Default->button(
		'back',
		array(
			'controller' => 'cuis',
			'action'     => 'index',
			$cui['Cui']['personne_id']
		),
		array(
			'id' => 'Back'
		)
	);
?>