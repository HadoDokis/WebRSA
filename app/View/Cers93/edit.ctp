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

	$adresseAffichage = $this->Webrsa->blocAdresse( $personne, array( 'separator' => "<br/>", 'options' => $options['Adresse']['typevoie'], 'ville' => true ) );
	$adresseFormulaire = $this->Webrsa->blocAdresse( $personne, array( 'separator' => "\n", 'options' => $options['Adresse']['typevoie'], 'ville' => false ) );

	
	echo $this->Xform->inputs(
		array(
			'fieldset' => false,
			'legend' => false,
			'Contratinsertion.id' => array( 'type' => 'hidden' ),
			'Contratinsertion.personne_id' => array( 'type' => 'hidden', 'value' => $personne_id ),
			'Cer93.id' => array( 'type' => 'hidden' ),
			'Cer93.contratinsertion_id' => array( 'type' => 'hidden' )
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
				<?php echo $this->Html->tag( 'p', 'Rang du contrat: '.$personne['Contratinsertion']['rangcer'] ); ?>
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
                <strong>Statut de la personne : </strong><?php echo Set::enum( Set::classicExtract( $personne, 'Prestation.rolepers' ), $options['Prestation']['rolepers'] ); ?>
                <br />
                <strong>Nom : </strong><?php echo Set::enum( Set::classicExtract( $personne, 'Personne.qual'), $options['Personne']['qual'] ).' '.Set::classicExtract( $personne, 'Personne.nom' );?>
                <br />
                <?php if( $personne['Personne']['sexe'] == 2 ):?>
					<strong>Nom de jeune fille : </strong><?php echo Set::classicExtract( $personne, 'Personne.nomnai' );?>
					<br />
                <?php endif;?>
                <strong>Prénom : </strong><?php echo Set::classicExtract( $personne, 'Personne.prenom' );?>
                <br />
                <strong>Date de naissance : </strong><?php echo date_short( Set::classicExtract( $personne, 'Personne.dtnai' ) );?>
            </td>
            <td class="mediumSize noborder">
                <strong>N° Service instructeur : </strong>
                <?php
					$libservice = Set::enum( Set::classicExtract( $personne, 'Suiviinstruction.typeserins' ),  $options['Serviceinstructeur']['typeserins'] );
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
                <strong>Adresse : </strong><br /><?php echo $adresseAffichage;?>
            </td>
        </tr>
		<tr>
            <td colspan="2" class="mediumSize noborder">
                <strong>Situation familiale : </strong><?php echo Set::enum( Set::classicExtract( $personne, 'Foyer.sitfam' ), $options['Foyer']['sitfam'] );?>
                <br />
                <strong>Conditions de logement : </strong><?php echo Set::enum( Set::classicExtract( $personne, 'Dsp.natlog' ), $options['Dsp']['natlog'] );?>
            </td>
        </tr>
    </table>

<?php	
	
	// Composition du foyer 
	if( !empty( $composfoyerscers93 ) ) {

		// Sauvegarde des informations
		foreach( $composfoyerscers93 as $index => $compofoyercer93 ) {
			echo $this->Xform->inputs(
				array(
					'fieldset' => false,
					'legend' => false,
					"Compofoyercer93.{$index}.id" => array( 'type' => 'hidden' ),
					"Compofoyercer93.{$index}.cer93_id" => array( 'type' => 'hidden' ),
					"Compofoyercer93.{$index}.qual" => array( 'type' => 'hidden', 'value' => $compofoyercer93['Personne']['qual'] ),
					"Compofoyercer93.{$index}.nom" => array( 'type' => 'hidden', 'value' => $compofoyercer93['Personne']['nom'] ),
					"Compofoyercer93.{$index}.prenom" => array( 'type' => 'hidden', 'value' => $compofoyercer93['Personne']['prenom'] ),
					"Compofoyercer93.{$index}.dtnai" => array( 'type' => 'hidden', 'value' => $compofoyercer93['Personne']['dtnai'] )
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
		foreach( $composfoyerscers93 as $index => $compofoyercer93 ){
			echo $this->Xhtml->tableCells(
				array(
					h( Set::enum( $compofoyercer93['Prestation']['rolepers'], $options['Prestation']['rolepers'] ) ),
					h( Set::enum( $compofoyercer93['Personne']['qual'], $options['Personne']['qual'] ) ),
					h( $compofoyercer93['Personne']['nom'] ),
					h( $compofoyercer93['Personne']['prenom'] ),
					h( $this->Locale->date( 'Date::short', $compofoyercer93['Personne']['dtnai'] ) )
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
			'Cer93.matricule' => array( 'type' => 'hidden', 'value' => $personne['Dossier']['matricule'] ),
			'Cer93.dtdemrsa' => array( 'type' => 'hidden', 'value' => $personne['Dossier']['dtdemrsa'] ),
			'Cer93.qual' => array( 'type' => 'hidden', 'value' => $personne['Personne']['qual'] ),
			'Cer93.nom' => array( 'type' => 'hidden', 'value' => $personne['Personne']['nom'] ),
			'Cer93.nomnai' => array( 'type' => 'hidden', 'value' => $personne['Personne']['nomnai'] ),
			'Cer93.prenom' => array( 'type' => 'hidden', 'value' => $personne['Personne']['prenom'] ),
			'Cer93.dtnai' => array( 'type' => 'hidden', 'value' => $personne['Personne']['dtnai'] ),
			'Cer93.adresse' => array( 'type' => 'hidden', 'value' => $adresseFormulaire ),
			'Cer93.codepos' => array( 'type' => 'hidden', 'value' => $personne['Adresse']['codepos'] ),
			'Cer93.locaadr' => array( 'type' => 'hidden', 'value' => $personne['Adresse']['locaadr'] ),
			'Cer93.sitfam' => array( 'type' => 'hidden', 'value' => $personne['Foyer']['sitfam'] ),
			'Cer93.natlog' => array( 'type' => 'hidden', 'value' => $personne['Dsp']['natlog'] ),
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