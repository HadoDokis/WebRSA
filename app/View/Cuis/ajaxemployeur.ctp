<?php if( !empty( $partenaire ) ): ?>
<fieldset>
	<legend>Employeur</legend>
		<table class="wide noborder">
			<tr>
				<td class="mediumSize noborder">
					<fieldset>
						<?php
							//Partenaire
							echo $this->Xform->fieldValue( 'Partenaire.libstruc', Set::classicExtract( $partenaire, 'Partenaire.libstruc' ) );
							echo $this->Xform->fieldValue( 'Partenaire.adresse', Set::classicExtract( $partenaire, 'Partenaire.adresse' ) );
							echo $this->Xform->fieldValue( 'Partenaire.canton', Set::classicExtract( $partenaire, 'Partenaire.canton' ) );
							echo $this->Xform->fieldValue( 'Partenaire.email', Set::classicExtract( $partenaire, 'Partenaire.email' ) );
							
							// Contact partenaire
							echo $this->Xform->fieldValue( 'Contactpartenaire.nom_candidat', Set::classicExtract( $partenaire, 'Contactpartenaire.nom_candidat' ) );
							echo $this->Xform->fieldValue( 'Contactpartenaire.numtel', Set::classicExtract( $partenaire, 'Contactpartenaire.numtel' ) );
							echo $this->Xform->fieldValue( 'Contactpartenaire.numfax', Set::classicExtract( $partenaire, 'Contactpartenaire.numfax' ) );
							
							echo $this->Xform->fieldValue( 'Referent.nom_complet', Set::classicExtract( $partenaire, 'Referent.nom_complet' ) );

						?>
					</fieldset>
				</td>
				<td class="mediumSize noborder">
					<fieldset>
						<?php
							echo $this->Xform->fieldValue( 'Partenaire.statut', Set::enum( Set::classicExtract( $partenaire, 'Partenaire.statut' ), $options['Cui']['statutemployeur'] ) );
							echo $this->Xform->fieldValue( 'Partenaire.secteuractivitepartenaire_id', Set::enum( Set::classicExtract( $partenaire, 'Partenaire.secteuractivitepartenaire_id' ), $secteursactivites ) );
							echo $this->Xform->fieldValue( 'Partenaire.raisonsocialepartenairecui66_id', Set::enum( Set::classicExtract( $partenaire, 'Partenaire.raisonsocialepartenairecui66_id' ), $options['Cui']['raisonsocialepartenairecui66_id'] ) );
							
							echo $this->Xform->fieldValue( 'Partenaire.nomtiturib', Set::classicExtract( $partenaire, 'Partenaire.nomtiturib' ) );
							echo $this->Xform->fieldValue( 'Partenaire.codeban', Set::classicExtract( $partenaire, 'Partenaire.codeban' ) );
							echo $this->Xform->fieldValue( 'Partenaire.guiban', Set::classicExtract( $partenaire, 'Partenaire.guiban' ) );
							echo $this->Xform->fieldValue( 'Partenaire.numcompt', Set::classicExtract( $partenaire, 'Partenaire.numcompt' ) );
							echo $this->Xform->fieldValue( 'Partenaire.nometaban', Set::classicExtract( $partenaire, 'Partenaire.nometaban' ) );
							echo $this->Xform->fieldValue( 'Partenaire.clerib', Set::classicExtract( $partenaire, 'Partenaire.clerib' ) );
							
							echo $this->Xform->fieldValue( 'Partenaire.orgrecouvcotis', Set::enum( Set::classicExtract( $partenaire, 'Partenaire.orgrecouvcotis' ), $options['Cui']['orgrecouvcotis'] ) );
						?>
					</fieldset>
				</td>
			</tr>
		</table>
</fieldset>

<?php if( !empty( $actioncandidat ) ) :?>
	<fieldset>
		<legend>Action</legend>
		<table class="noborder">
			<tr>
				<td class="cui1 noborder">
					<?php
						// Action
						echo $this->Xform->fieldValue( 'Actioncandidat.codeaction', Set::classicExtract( $actioncandidat, 'Actioncandidat.codeaction' ) );

						echo $this->Xform->fieldValue( 'Referent.nom_correspondant', Set::classicExtract( $referent, 'Referent.nom_complet' ) );
					?>
				</td>
			</tr>
			<tr>
				<td class="cui1 noborder">
					<?php
						echo $this->Xform->label( 'Fichiers liés :' );
						echo $this->Fileuploader->results( Set::classicExtract( $actioncandidat, 'Fichiermodule' ) );
					?>
				</td>
			</tr>
		</table>
	</fieldset>
<?php endif;?>

<script type="text/javascript">
	<!--//--><![CDATA[//><!--
	function setInputValue( input, value ) {
		input = $( input );
		if( ( input != undefined ) && ( $F( input ) == '' ) ) {
			$( input ).setValue( value );
		}
	}
	setInputValue( 'ActioncandidatPersonneLieurdvpartenaire', '<?php echo str_replace( "'", "\\'", Set::classicExtract( $partenaire, 'Partenaire.libstruc' ) );?>' );
	setInputValue( 'ActioncandidatPersonnePersonnerdvpartenaire', '<?php echo str_replace( "'", "\\'", Set::classicExtract( $partenaire, 'Contactpartenaire.nom_candidat' ) );?>' );
	
	
	//--><!]]>
</script> 
<?php endif;?>