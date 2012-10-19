<?php
	$title_for_layout = ( ( $this->action == 'add' ) ? 'Ajout d\'un CER' : 'Modification d\'un CER' );
	$this->set( 'title_for_layout', $title_for_layout );

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}
	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>
<script type="text/javascript">
	document.observe("dom:loaded", function() {
		dependantSelect( 'ContratinsertionReferentId', 'ContratinsertionStructurereferenteId' );
	});
</script>
<script type="text/javascript"> 
	document.observe( "dom:loaded", function() {	
		<?php
			$ref_id = Set::extract( $this->request->data, 'Contratinsertion.referent_id' );
            echo $this->Ajax->remoteFunction(
                array(
                    'update' => 'StructurereferenteRef',
                    'url' => Router::url(
                        array(
                            'action' => 'ajaxstruct',
                            Set::extract( $this->request->data, 'Contratinsertion.structurereferente_id' )
                        ),
                        true
                    )
                )
            ).';';
            echo $this->Ajax->remoteFunction(
                array(
                    'update' => 'ReferentRef',
                    'url' => Router::url(
                        array(
                            'action' => 'ajaxref',
                            Set::extract( $this->request->data, 'Contratinsertion.referent_id' )
                        ),
                        true
                    )
                )
            ).';';
        ?>
    } );
</script>

<div class="with_treemenu">
<?php
	echo $this->Html->tag( 'h1', $title_for_layout );

	echo $this->Xform->create( null, array( 'inputDefaults' => array( 'domain' => 'contratinsertion' ) ) );
//FIXME
// 	$adresseAffichage = $this->Webrsa->blocAdresse( $this->request->data, array( 'separator' => "<br/>", 'options' => $options['Adresse']['typevoie'], 'ville' => true ) );
// 	$adresseFormulaire = $this->Webrsa->blocAdresse( $this->request->data, array( 'separator' => "\n", 'options' => $options['Adresse']['typevoie'], 'ville' => false ) );

	
	echo $this->Xform->inputs(
		array(
			'fieldset' => false,
			'legend' => false,
			'Contratinsertion.id' => array( 'type' => 'hidden' ),
			'Contratinsertion.personne_id' => array( 'type' => 'hidden', 'value' => $personne_id ),
			'Cer93.id' => array( 'type' => 'hidden' ),
			'Cer93.contratinsertion_id' => array( 'type' => 'hidden' ),
			// Champs non sauvegardés mais nécessaires en cas d'erreur et de renvoi du formulaire
			'Contratinsertion.rg_ci' => array( 'type' => 'hidden' ),
			'Personne.sexe' => array( 'type' => 'hidden' ),
			'Cer93.rolepers' => array( 'type' => 'hidden' ),
			'Cer93.numdemrsa' => array( 'type' => 'hidden' ),
			'Cer93.identifiantpe' => array( 'type' => 'hidden' )
		)
	);
?>
<!-- Bloc 1  -->
<fieldset>
    <legend>Service référent désigné par le Département</legend>
    <table class="wide noborder cers93">
        <tr>
            <td class="noborder">
                <?php echo $this->Xform->input( 'Contratinsertion.structurereferente_id', array( 'label' => __d( 'contratinsertion', 'Contratinsertion.structurereferente_id' ), 'type' => 'select', 'options' => $options['Contratinsertion']['structurereferente_id'], /*'selected' => $struct_id,*/ 'empty' => true, 'required' => true ) );?>
                <?php echo $this->Ajax->observeField( 'ContratinsertionStructurereferenteId', array( 'update' => 'StructurereferenteRef', 'url' => Router::url( array( 'action' => 'ajaxstruct' ), true ) ) ); ?>
            </td>
            <td class="noborder">
                <?php echo $this->Xform->input( 'Contratinsertion.referent_id', array( 'label' => __d( 'contratinsertion', 'Contratinsertion.referent_id' ), 'type' => 'select', 'options' => $options['Contratinsertion']['referent_id'], 'empty' => true, 'selected' => ( isset( $this->request->data['Contratinsertion']['structurereferente_id'] ) && isset( $this->request->data['Contratinsertion']['referent_id'] ) ) ? ( $this->request->data['Contratinsertion']['structurereferente_id'].'_'.$this->request->data['Contratinsertion']['referent_id'] ) : null ) );?>
                <?php echo $this->Ajax->observeField( 'ContratinsertionReferentId', array( 'update' => 'ReferentRef', 'url' => Router::url( array( 'action' => 'ajaxref' ), true ) ) ); ?>
            </td>
        </tr>
        <tr>
            <td class="wide noborder"><div id="StructurereferenteRef"></div></td>

            <td class="wide noborder"><div id="ReferentRef"></div></td>
        </tr>
        <tr>
            <td class="wide noborder">
				<?php echo $this->Html->tag( 'p', 'Rang du contrat: '.( !empty( $this->request->data['Contratinsertion']['rg_ci'] ) ? $this->request->data['Contratinsertion']['rg_ci'] : '1' ) ); ?>
			</td>
        </tr>
    </table>
</fieldset>

<script type="text/javascript">
    Event.observe( $( 'ContratinsertionStructurereferenteId' ), 'change', function( event ) {
        $( 'ReferentRef' ).update( '' );
    } );
</script>
<fieldset>
	<legend>État civil</legend>
	 <table class="wide noborder">
        <tr>
            <td class="mediumSize noborder">
                <strong>Statut de la personne : </strong><?php echo Set::enum( Set::classicExtract( $this->request->data, 'Cer93.rolepers' ), $options['Prestation']['rolepers'] ); ?>
                <br />
                <strong>Nom : </strong><?php echo Set::enum( Set::classicExtract( $this->request->data, 'Cer93.qual'), $options['Personne']['qual'] ).' '.Set::classicExtract( $this->request->data, 'Cer93.nom' );?>
                <br />
                <?php if( $this->request->data['Personne']['sexe'] == 2 ):?>
					<strong>Nom de jeune fille : </strong><?php echo Set::classicExtract( $this->request->data, 'Cer93.nomnai' );?>
					<br />
                <?php endif;?>
                <strong>Prénom : </strong><?php echo Set::classicExtract( $this->request->data, 'Cer93.prenom' );?>
                <br />
                <strong>Date de naissance : </strong><?php echo date_short( Set::classicExtract( $this->request->data, 'Cer93.dtnai' ) );?>
                <br />
                <strong>Adresse : </strong>
                <br /><?php /*echo $adresseAffichage;*/ echo Set::classicExtract( $this->request->data, 'Cer93.adresse' ); /* FIXME*/ ?>
            </td>
            <td class="mediumSize noborder">
                <strong>N° Service instructeur : </strong>
                <?php
					$libservice = Set::enum( Set::classicExtract( $this->request->data, 'Suiviinstruction.typeserins' ),  $options['Serviceinstructeur']['typeserins'] );
					if( isset( $libservice ) ) {
						echo $libservice;
					}
					else{
						echo 'Non renseigné';
					}
                ?>
                <br />
                <strong>N° demandeur : </strong><?php echo Set::classicExtract( $this->request->data, 'Cer93.numdemrsa' );?>
                <br />
                <strong>N° CAF/MSA : </strong><?php echo Set::classicExtract( $this->request->data, 'Cer93.matricule' );?>
                <br />
                <strong>Inscrit au Pôle emploi</strong>
                <?php echo ( !empty( $this->request->data['Cer93']['identifiantpe'] ) ? 'Oui' : 'Non' );?>
				<br />
				<strong>N° identifiant : </strong><?php echo Set::classicExtract( $this->request->data, 'Cer93.identifiantpe' );?>
				<br />
				 <strong>Situation familiale : </strong><?php echo Set::enum( Set::classicExtract( $this->request->data, 'Cer93.sitfam' ), $options['Foyer']['sitfam'] );?>
                <br />
                <strong>Conditions de logement : </strong><?php echo Set::enum( Set::classicExtract( $this->request->data, 'Cer93.natlog' ), $options['Dsp']['natlog'] );?>
            </td>
        </tr>
    </table>

<?php	
	
	// Bloc 2 : Composition du foyer 
	if( !empty( $this->request->data['Compofoyercer93'] ) ) {

		// Sauvegarde des informations
		foreach( $this->request->data['Compofoyercer93'] as $index => $compofoyercer93 ) {
			echo $this->Xform->inputs(
				array(
					'fieldset' => false,
					'legend' => false,
					"Compofoyercer93.{$index}.id" => array( 'type' => 'hidden' ),
					"Compofoyercer93.{$index}.cer93_id" => array( 'type' => 'hidden' ),
					"Compofoyercer93.{$index}.qual" => array( 'type' => 'hidden' ),
					"Compofoyercer93.{$index}.nom" => array( 'type' => 'hidden' ),
					"Compofoyercer93.{$index}.prenom" => array( 'type' => 'hidden' ),
					"Compofoyercer93.{$index}.dtnai" => array( 'type' => 'hidden' ),
					"Compofoyercer93.{$index}.rolepers" => array( 'type' => 'hidden' ),
				)
			);
		}
		
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
		foreach( $this->request->data['Compofoyercer93'] as $index => $compofoyercer93 ){
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
	
	echo $this->Xform->inputs(
		array(
			'fieldset' => false,
			'legend' => false,
			// Bloc 2: état cvil
			'Cer93.matricule' => array( 'type' => 'hidden' ),
			'Cer93.dtdemrsa' => array( 'type' => 'hidden' ),
			'Cer93.qual' => array( 'type' => 'hidden' ),
			'Cer93.nom' => array( 'type' => 'hidden' ),
			'Cer93.nomnai' => array( 'type' => 'hidden' ),
			'Cer93.prenom' => array( 'type' => 'hidden' ),
			'Cer93.dtnai' => array( 'type' => 'hidden' ),
			'Cer93.adresse' => array( 'type' => 'hidden' ),//FIXME virtual fiuelds adresse.php
			'Cer93.codepos' => array( 'type' => 'hidden' ),
			'Cer93.locaadr' => array( 'type' => 'hidden' ),
			'Cer93.sitfam' => array( 'type' => 'hidden' ),
			'Cer93.natlog' => array( 'type' => 'hidden' ),
			'Cer93.incoherencesetatcivil' => array( 'domain' => 'cer93', 'type' => 'textarea' )
		)
	);
?>
</fieldset>
	
<?php
	//Bloc 3 : Vérification des droits

	echo $this->Xform->inputs(
		array(
			'fieldset' => true,
			'legend' => 'Vérification des droits',
			'Cer93.inscritpe' => array( 'domain' => 'cer93', 'type' => 'select', 'options' => $options['Cer93']['inscritpe'], 'empty' => true ),
			'Cer93.cmu' => array( 'domain' => 'cer93', 'type' => 'select', 'options' => $options['Cer93']['cmu'], 'empty' => true ),
			'Cer93.cmuc' => array( 'domain' => 'cer93', 'type' => 'select', 'options' => $options['Cer93']['cmuc'], 'empty' => true )
		)
	);


?>
<fieldset>
	<legend>Formation et expérience</legend>
	<?php
		// bloc 4 : Formation et expérience
		echo $this->Xform->input( 'Cer93.nivetu', array( 'domain' => 'cer93', 'type' => 'select', 'empty' => true, 'options' => $options['Cer93']['nivetu'] ) );
		
		// Diplômes (scolaires, universitaires et/ou professionnels
// 		echo '<p>Diplômes (scolaires, universitaires et/ou professionnels)</p>';
// 		echo $this->Default2->index(
// 			$diplomescers93,
// 			array(
// 				'Diplomecer93.name',
// 				'Diplomecer93.annee'
// 			),
// 			array(
// 				'actions' => array(
// 					'Diplomescers93::edit',
// 					'Diplomescers93::delete'
// 				),
// 				'add' => array( 'Diplomecer93.add' ),
// 				'options' => $options
// 			)
// 		);
// 		
	?>
</fieldset>
<?php
	echo $this->Xform->inputs(
		array(
			'fieldset' => false,
			'legend' => false,
			'Contratinsertion.dd_ci' => array( 'type' => 'date', 'empty' => true, 'dateFormat' => 'DMY' ),
			'Contratinsertion.df_ci' => array( 'type' => 'date', 'empty' => true, 'dateFormat' => 'DMY' ),
			'Contratinsertion.date_saisi_ci' => array( 'type' => 'date', 'empty' => true, 'dateFormat' => 'DMY' )
		)
	);
?>

<?php
	echo $this->Html->tag(
		'div',
		 $this->Xform->button( 'Enregistrer', array( 'type' => 'submit' ) )
		.$this->Xform->button( 'Annuler', array( 'type' => 'submit', 'name' => 'Cancel' ) ),
		array( 'class' => 'submit noprint' )
	);

	echo $this->Xform->end();
?>
</div>
<div class="clearer"><hr /></div>