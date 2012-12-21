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

	echo $this->Xform->create( null, array( 'inputDefaults' => array( 'domain' => 'contratinsertion' ), 'id' => 'contratinsertion' ) );

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
			'Cer93.identifiantpe' => array( 'type' => 'hidden' ),
			'Cer93.user_id' => array( 'type' => 'hidden' ),
			'Cer93.nomutilisateur' => array( 'type' => 'hidden' ),
			'Cer93.structureutilisateur' => array( 'type' => 'hidden' ),
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
                <?php echo $this->Xform->input( 'Contratinsertion.referent_id', array( 'label' => __d( 'contratinsertion', 'Contratinsertion.referent_id' ), 'type' => 'select', 'options' => $options['Contratinsertion']['referent_id'], 'empty' => true, 'selected' => ( isset( $this->request->data['Contratinsertion']['structurereferente_id'] ) && isset( $this->request->data['Contratinsertion']['referent_id'] ) ) ? ( $this->request->data['Contratinsertion']['structurereferente_id'].'_'.suffix( $this->request->data['Contratinsertion']['referent_id'] ) ) : null ) );?>
                <?php echo $this->Ajax->observeField( 'ContratinsertionReferentId', array( 'update' => 'ReferentRef', 'url' => Router::url( array( 'action' => 'ajaxref' ), true ) ) ); ?>
            </td>
        </tr>
        <tr>
            <td class="wide noborder"><div id="StructurereferenteRef"></div></td>

            <td class="wide noborder"><div id="ReferentRef"></div></td>
        </tr>
        <tr>
            <td class="wide noborder">
				<?php
					echo $this->Html->tag( 'p', 'Rang du contrat: '.$this->request->data['Contratinsertion']['rg_ci'] );
				?>
			</td>
        </tr>
    </table>
</fieldset>

<script type="text/javascript">
    function checkDatesToRefresh() {
        if( ( $F( 'ContratinsertionDdCiMonth' ) ) && ( $F( 'ContratinsertionDdCiYear' ) ) && ( radioValue( 'contratinsertion', 'data[Cer93][duree]' ) !== undefined ) ) {
            var correspondances = new Array();
            <?php

                $duree_engag = $options['Cer93']['duree'];
                foreach( $duree_engag as $index => $duree ):?>correspondances[<?php echo $index;?>] = <?php echo str_replace( ' mois', '' ,$duree );?>;<?php endforeach;?>

            setDateIntervalCer( 'ContratinsertionDdCi', 'ContratinsertionDfCi', correspondances[radioValue( 'contratinsertion', 'data[Cer93][duree]' )], false );

        }
    }

    document.observe( "dom:loaded", function() {
        Event.observe( $( 'ContratinsertionDdCiDay' ), 'change', function() {
            checkDatesToRefresh();
        } );
        Event.observe( $( 'ContratinsertionDdCiMonth' ), 'change', function() {
            checkDatesToRefresh();
        } );
        Event.observe( $( 'ContratinsertionDdCiYear' ), 'change', function() {
            checkDatesToRefresh();
        } );

		<?php foreach( $options['Cer93']['duree'] as $duree ): ?>
        Event.observe( $( 'Cer93Duree<?php echo str_replace( ' mois', '' ,$duree );?>' ), 'change', function() {
            checkDatesToRefresh();
        } );
		<?php endforeach;?>
	});
</script>
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
				<br /><?php echo nl2br( Set::classicExtract( $this->request->data, 'Cer93.adresse' ) ).'<br />'.Set::classicExtract( $this->request->data, 'Cer93.codepos' ).' '.Set::classicExtract( $this->request->data, 'Cer93.locaadr' );?>
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
                <strong>Date d'ouverture de droit : </strong><?php echo date_short( Set::classicExtract( $this->request->data, 'Cer93.dtdemrsa' ) );?>
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
			'Cer93.adresse' => array( 'type' => 'hidden' ),
			'Cer93.codepos' => array( 'type' => 'hidden' ),
			'Cer93.locaadr' => array( 'type' => 'hidden' ),
			'Cer93.sitfam' => array( 'type' => 'hidden' ),
			'Cer93.natlog' => array( 'type' => 'hidden' ),
			'Cer93.prevupcd' => array( 'type' => 'hidden' ),
			'Cer93.sujetpcd' => array( 'type' => 'hidden' ),
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
			'Cer93.inscritpe' => array( 'domain' => 'cer93', 'type' => 'radio', 'options' => $options['Cer93']['inscritpe'], 'empty' => false ),
			'Cer93.cmu' => array( 'domain' => 'cer93', 'type' => 'radio', 'options' => $options['Cer93']['cmu'], 'empty' => false ),
			'Cer93.cmuc' => array( 'domain' => 'cer93', 'type' => 'radio', 'options' => $options['Cer93']['cmuc'], 'empty' => false )
		)
	);


?>
<fieldset>
	<legend>Formation et expérience</legend>
	<?php
		// bloc 4 : Formation et expérience
		echo $this->Xform->input( 'Cer93.nivetu', array( 'domain' => 'cer93', 'type' => 'select', 'empty' => true, 'options' => $options['Cer93']['nivetu'] ) );
	?>

	<fieldset>
		<legend>Diplômes (scolaires, universitaires et/ou professionnels)</legend>
		<ul class="actionMenu">
			<li><a href="#" onclick="addDynamicTrInputs( 'Diplomecer93', gabaritDiplomecer93 ); return false;">Ajouter</a></li>
		</ul>
		<table id="Diplomecer93">
			<thead>
				<tr>
					<th>Intitulé du diplôme</th>
					<th>Année d'obtention</th>
					<th>Précisez si obtenu à l'étranger</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php
					if( !empty( $this->request->data['Diplomecer93'] ) ) {
						foreach( $this->request->data['Diplomecer93'] as $index => $diplomecer93 ) {
							echo $this->Html->tableCells(
								array(
									$this->Xform->input( "Diplomecer93.{$index}.id", array( 'type' => 'hidden', 'label' => false ) )
									.$this->Xform->input( "Diplomecer93.{$index}.cer93_id", array( 'type' => 'hidden', 'label' => false ) )
									.$this->Xform->input( "Diplomecer93.{$index}.name", array( 'type' => 'text', 'label' => false ) ),
									$this->Xform->input( "Diplomecer93.{$index}.annee", array( 'type' => 'select', 'label' => false, 'options' => array_range( date( 'Y' ), 1960 ), 'empty' => true ) ),
									$this->Xform->input( "Diplomecer93.{$index}.isetranger", array( 'type' => 'checkbox', 'label' => false, 'empty' => true ) ),
									$this->Html->link( 'Supprimer', '#', array( 'onclick' => "deleteDynamicTrInputs( 'Diplomecer93', {$index} );return false;" ) ),
								)
							);
						}
					}
				?>
			</tbody>
		</table>
	</fieldset>

	<fieldset>
		<legend>Expériences professionnelles significatives</legend>
		<ul class="actionMenu">
			<li><a href="#" onclick="addDynamicTrInputs( 'Expprocer93', gabaritExpprocer93 ); return false;">Ajouter</a></li>
		</ul>
		<table id="Expprocer93">
			<thead>
				<tr>
					<th>Métier exercé</th>
					<th>Secteur d'activité</th>
					<th>Année de début</th>
					<th>Durée</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php
					if( !empty( $this->request->data['Expprocer93'] ) ) {
						foreach( $this->request->data['Expprocer93'] as $index => $expprocer93 ) {
							echo $this->Html->tableCells(
								array(
									$this->Xform->input( "Expprocer93.{$index}.id", array( 'type' => 'hidden', 'label' => false ) )
									.$this->Xform->input( "Expprocer93.{$index}.cer93_id", array( 'type' => 'hidden', 'label' => false ) )
									.$this->Xform->input( "Expprocer93.{$index}.metierexerce_id", array( 'type' => 'select', 'label' => false, 'options' => $options['Expprocer93']['metierexerce_id'], 'empty' => true ) ),
									$this->Xform->input( "Expprocer93.{$index}.secteuracti_id", array( 'type' => 'select', 'label' => false, 'options' => $options['Expprocer93']['secteuracti_id'], 'empty' => true ) ),
									$this->Xform->input( "Expprocer93.{$index}.anneedeb", array( 'type' => 'select', 'label' => false, 'options' => array_range( date( 'Y' ), 1960 ), 'empty' => true ) ),
									$this->Xform->input( "Expprocer93.{$index}.duree", array( 'type' => 'text', 'label' => false ) ),
									$this->Html->link( 'Supprimer', '#', array( 'onclick' => "deleteDynamicTrInputs( 'Expprocer93', {$index} );return false;" ) ),
								)
							);
						}
					}
				?>
			</tbody>
		</table>
	</fieldset>
	<?php
		echo $this->Xform->inputs(
			array(
				'fieldset' => false,
				'legend' => false,
				'Cer93.autresexps' => array( 'domain' => 'cer93', 'type' => 'textarea' ),
				'Cer93.isemploitrouv' => array( 'legend' => required( __d( 'cer93', 'Cer93.isemploitrouv' ) ), 'domain' => 'cer93', 'type' => 'radio', 'options' => $options['Cer93']['isemploitrouv'] )
			)
		);
	?>
	<fieldset id="emploitrouv" class="noborder">
	<?php
		echo $this->Xform->inputs(
			array(
				'fieldset' => true,
				'legend' => 'Si oui, veuillez préciser :',
				'Cer93.secteuracti_id' => array( 'domain' => 'cer93', 'type' => 'select', 'options' => $options['Expprocer93']['secteuracti_id'], 'empty' => true, 'required' => true ),
				'Cer93.metierexerce_id' => array( 'domain' => 'cer93', 'type' => 'select', 'options' => $options['Expprocer93']['metierexerce_id'], 'empty' => true, 'required' => true ),
				'Cer93.dureehebdo' => array( 'domain' => 'cer93', 'type' => 'select', 'options' => $options['dureehebdo'], 'empty' => true, 'required' => true ),
				'Cer93.naturecontrat_id' => array( 'domain' => 'cer93', 'type' => 'select', 'options' => $options['Naturecontrat']['naturecontrat_id'], 'empty' => true, 'required' => true )
			)
		);

		echo $this->Xform->input( 'Cer93.dureecdd', array( 'domain' => 'cer93', 'type' => 'select', 'empty' => true, 'options' => $options['dureecdd'], 'required' => true ) );
	?>
	</fieldset>
	<script type="text/javascript">
		document.observe( "dom:loaded", function() {
			observeDisableFieldsetOnRadioValue(
				'contratinsertion',
				'data[Cer93][isemploitrouv]',
				$( 'emploitrouv' ),
				'O',
				false,
				true
			);
			<?php if( !empty( $naturecontratDuree ) ):?>
				observeDisableFieldsOnValue(
					'Cer93NaturecontratId',
					[ 'Cer93Dureecdd' ],
					[ '<?php echo implode( "', '", $naturecontratDuree ); ?>' ],
					false,
					true
				);
			<?php endif;?>
		});
	</script>
	<!-- Fin bloc 4 -->
</fieldset>
<!--  Bloc 5 -->
<?php if( $this->request->data['Contratinsertion']['rg_ci'] <= 1 ): ?>
	<p class="notice">Ce CER est le premier.</p>
<?php else: ?>
<fieldset id="bilanpcd"><legend>Bilan du contrat précédent</legend>

	<h4>Le précédent contrat portait sur </h4>
		<?php if( !empty( $this->request->data['Cer93']['sujetpcd'] ) ):?>
		<table class="aere">
			<thead>
				<tr>
					<th>Sujet du CER</th>
					<th>Sous sujet</th>
					<th>Si autre, commentaire</th>
				</tr>
			</thead>
			<tbody>
				<?php
					// Le précédent CER portait sur (liste des cases à cocher)
					$sujetscers93 = unserialize( $this->request->data['Cer93']['sujetpcd'] );
					foreach( $sujetscers93['Sujetcer93']  as $index => $sujetcer93 ) {
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
				?>
			</tbody>
		</table>
	<?php else:?>
		<p class="notice">Aucune information renseignée</p>
	<?php endif;?>

	<?php
		//Il a été prévu (champ prevu du bloc 6)
		echo $this->Xform->fieldValue( 'Cer93.prevupcd', Set::classicExtract( $this->request->data, 'Cer93.prevupcd' ), true, 'textarea' );

		//Bloc 5 : Bilan du précédent contrat
		echo $this->Xform->input( 'Cer93.bilancerpcd', array( 'domain' => 'cer93', 'type' => 'textarea' ) );
	?>
</fieldset>
<?php endif; ?>
<!--  Fin bloc 5 -->

<fieldset><legend>Projet pour ce nouveau contrat</legend>
		<?php
		// Bloc 6 : Projet pour ce nouveau contrat
		echo $this->Xform->input( 'Cer93.prevu', array( 'domain' => 'cer93', 'type' => 'textarea', 'required' => true ) );

		// HABTM spécial, avec des select liés aux cases à cocher
		echo '<fieldset><legend>Votre contrat porte sur</legend>';
		//$selectedSujetcer93 = Set::extract( '/Sujetcer93/Sujetcer93/sujetcer93_id', $this->request->data );
		$selectedSujetcer93 = array();
		if( !empty( $this->request->data['Sujetcer93']['Sujetcer93'] ) ) {
			$selectedSujetcer93 = Set::extract( '/Sujetcer93/Sujetcer93/sujetcer93_id', $this->request->data );
			$keys = array_keys( $this->request->data['Sujetcer93']['Sujetcer93'] );
			$selectedSujetcer93 = array_combine( $keys, $selectedSujetcer93 );
		}
		echo $this->Xform->input( "Sujetcer93.Sujetcer93", array( 'type' => 'hidden', 'value' => '' ) );
		$i = 0;

		echo '<ul class="liste_sujets">';
		foreach( $options['Sujetcer93']['sujetcer93_id'] as $idSujet => $nameSujet ) {
			$array_key = array_search( $idSujet, $selectedSujetcer93 );
			$checked = ( ( $array_key !== false ) ? 'checked' : '' );
			$soussujetcer93_id = null;
			$commentaireautre = null;

			$valeurparsoussujetcer93_id = null;

			if( $checked ) {
				if( isset( $this->request->data['Sujetcer93']['Sujetcer93'][$array_key]['soussujetcer93_id'] ) ) {
					$soussujetcer93_id = $this->request->data['Sujetcer93']['Sujetcer93'][$array_key]['soussujetcer93_id'];
				}
				else if( isset( $this->request->data['Sujetcer93']['Sujetcer93'][$array_key]['commentaireautre'] ) ) {
					$commentaireautre = $this->request->data['Sujetcer93']['Sujetcer93'][$array_key]['commentaireautre'];
				}

				if( isset( $this->request->data['Sujetcer93']['Sujetcer93'][$array_key]['valeurparsoussujetcer93_id'] ) ) {
					$valeurparsoussujetcer93_id = suffix( $this->request->data['Sujetcer93']['Sujetcer93'][$array_key]['valeurparsoussujetcer93_id'] );
					$valeurparsoussujetcer93_id = "{$soussujetcer93_id}_{$valeurparsoussujetcer93_id}";
				}
			}
			// TODO: sur la même ligne ?
			echo '<li>'; // Niveau 1
			echo $this->Xform->input( "Sujetcer93.Sujetcer93.{$idSujet}.sujetcer93_id", array( 'name' => "data[Sujetcer93][Sujetcer93][{$i}][sujetcer93_id]", 'label' => $nameSujet, 'type' => 'checkbox', 'value' => $idSujet, 'hiddenField' => false, 'checked' => $checked ) );

			// Niveau 2
			if( !empty( $soussujetscers93[$idSujet] ) ) {
				echo '<ul><li>'; // Niveau 2
				echo $this->Xform->input( "Sujetcer93.Sujetcer93.{$idSujet}.soussujetcer93_id", array( 'name' => "data[Sujetcer93][Sujetcer93][{$i}][soussujetcer93_id]", 'label' => false, 'type' => 'select', 'options' => $soussujetscers93[$idSujet], 'empty' => true, 'value' => $soussujetcer93_id ) );

				$interSoussujet = array_intersect( array_keys( $soussujetscers93[$idSujet] ), $autresoussujet_isautre_id );
				if( !empty( $interSoussujet ) ) {
					$autresoussujet = null;
					if( isset( $this->request->data['Sujetcer93']['Sujetcer93'][$array_key]['autresoussujet'] ) ) {
						$autresoussujet = $this->request->data['Sujetcer93']['Sujetcer93'][$array_key]['autresoussujet'];
					}
					echo $this->Xform->input( "Sujetcer93.Sujetcer93.{$idSujet}.autresoussujet", array( 'name' => "data[Sujetcer93][Sujetcer93][{$i}][autresoussujet]", 'label' => false, 'type' => 'text', 'value' => $autresoussujet ) );
				}

				// Niveau 3
				if( !empty( $valeursparsoussujetscers93[$idSujet] ) ) {
					$correspondChilParent[$this->Html->domId( "Sujetcer93.Sujetcer93.{$idSujet}.valeurparsoussujetcer93_id" )] = $this->Html->domId( "Sujetcer93.Sujetcer93.{$idSujet}.soussujetcer93_id" );
					echo '<ul><li>'; // Niveau 3
					echo $this->Xform->input( "Sujetcer93.Sujetcer93.{$idSujet}.valeurparsoussujetcer93_id", array( 'name' => "data[Sujetcer93][Sujetcer93][{$i}][valeurparsoussujetcer93_id]", 'label' => false, 'type' => 'select', 'options' => $valeursparsoussujetscers93[$idSujet], 'empty' => true, 'value' => $valeurparsoussujetcer93_id ) );
					// Ajout
					$inter = array_intersect( array_keys( $valeursparsoussujetscers93[$idSujet] ), $autrevaleur_isautre_id );
					if( !empty( $inter ) ) {
						$autrevaleur = null;
						if( isset( $this->request->data['Sujetcer93']['Sujetcer93'][$array_key]['autrevaleur'] ) ) {
							$autrevaleur = $this->request->data['Sujetcer93']['Sujetcer93'][$array_key]['autrevaleur'];
						}
						echo $this->Xform->input( "Sujetcer93.Sujetcer93.{$idSujet}.autrevaleur", array( 'name' => "data[Sujetcer93][Sujetcer93][{$i}][autrevaleur]", 'label' => false, 'type' => 'text', 'value' => $autrevaleur ) );
					}
					echo '</li></ul>'; // Niveau 3
				}
				echo '</li></ul>'; // Niveau 2
			}
			else {
				echo $this->Xform->input( "Sujetcer93.Sujetcer93.{$idSujet}.commentaireautre", array( 'name' => "data[Sujetcer93][Sujetcer93][{$i}][commentaireautre]", 'label' => false, 'type' => 'text', 'value' => $commentaireautre ) );
			}
			echo '</li>'; // Niveau 1
			$i++;
		}
		echo '</ul>';
		echo '</fieldset>';
	?>
</fieldset>
<!-- Javascript pour les sujetscers93 -->
<script type="text/javascript">
//<![CDATA[
	<?php foreach( array_keys( $options['Sujetcer93']['sujetcer93_id'] ) as $key ) :?>
	observeDisableFieldsOnCheckbox(
		'Sujetcer93Sujetcer93<?php echo $key;?>Sujetcer93Id',
		['Sujetcer93Sujetcer93<?php echo $key;?>Soussujetcer93Id', 'Sujetcer93Sujetcer93<?php echo $key;?>Valeurparsoussujetcer93Id', 'Sujetcer93Sujetcer93<?php echo $key;?>Commentaireautre', 'Sujetcer93Sujetcer93<?php echo $key;?>Autrevaleur'],
		false,
		true
	);
	<?php endforeach;?>

	<?php if( !empty( $correspondChilParent ) ):?>
		<?php foreach( $correspondChilParent as $childId => $parentId ):?>
			dependantSelect( '<?php echo $childId;?>', '<?php echo $parentId;?>' );
		<?php endforeach;?>
	<?php endif;?>
//]]>
</script>
<!-- Javascript pour les soussujetscers93 -->
<script type="text/javascript">
//<![CDATA[
	document.observe( "dom:loaded", function() {
		<?php foreach( array_keys( $options['Sujetcer93']['sujetcer93_id'] ) as $key ):?>
			<?php if( in_array( $key, $sujets_ids_soussujets_autres ) ):?>
			observeDisableFieldsOnValue(
				'Sujetcer93Sujetcer93<?php echo $key;?>Soussujetcer93Id',
				['Sujetcer93Sujetcer93<?php echo $key;?>Autresoussujet'],
				['<?php echo implode( "', '", $autresoussujet_isautre_id );?>'],
				false,
				true
			);
			<?php endif;?>
		<?php endforeach;?>
	});
//]]>
</script>
<!-- Javascript pour les valeursparsoussujetscers93 -->
<script type="text/javascript">
//<![CDATA[
	document.observe( "dom:loaded", function() {
		<?php foreach( array_keys( $options['Sujetcer93']['sujetcer93_id'] ) as $key ):?>
			<?php if( in_array( $key, $sujets_ids_valeurs_autres ) ):?>
			observeDisableFieldsOnValue(
				'Sujetcer93Sujetcer93<?php echo $key;?>Valeurparsoussujetcer93Id',
				['Sujetcer93Sujetcer93<?php echo $key;?>Autrevaleur'],
				['<?php echo implode( "', '", $autrevaleur_isautre_id );?>'],
				false,
				true
			);
			<?php endif;?>
		<?php endforeach;?>
	});
//]]>
</script>

<!-- Ajout -->

<?php
	//Bloc 7 : Durée proposée
	echo $this->Xform->input( 'Cer93.duree', array( 'legend' => required( 'Ce contrat est proposé pour une durée de ' ), 'domain' => 'cer93', 'type' => 'radio', 'options' => $options['Cer93']['duree'] ) );

	//Bloc 8 : Projet pour ce nouveau contrat
	echo $this->Xform->input( 'Cer93.pointparcours', array( 'domain' => 'cer93', 'type' => 'select', 'options' => $options['Cer93']['pointparcours'], 'empty' => true, 'required' => true ) );

	echo $this->Xform->input( 'Cer93.datepointparcours', array( 'domain' => 'cer93', 'type' => 'date', 'dateFormat' => 'DMY', 'empty' => true ) );
?>
<script type="text/javascript">
	document.observe( "dom:loaded", function() {
		observeDisableFieldsOnValue(
			'Cer93Pointparcours',
			[
				'Cer93DatepointparcoursDay',
				'Cer93DatepointparcoursMonth',
				'Cer93DatepointparcoursYear'
			],
			[ 'aladate' ],
			false,
			true
		);
	});
</script>
<?php


	//Bloc 9 : Partie réservée au professionnel en charge du contrat
	echo $this->Xform->fieldValue( 'Cer93.structureutilisateur', Set::classicExtract( $this->request->data, 'Cer93.structureutilisateur' ) );
	echo $this->Xform->fieldValue( 'Cer93.nomutilisateur', Set::classicExtract( $this->request->data, 'Cer93.nomutilisateur' ) );

	echo $this->Xform->input( 'Cer93.pourlecomptede', array( 'domain' => 'cer93', 'type' => 'text' ) );
	echo $this->Xform->input( 'Cer93.observpro', array( 'domain' => 'cer93', 'type' => 'textarea' ) );

	echo $this->Xform->inputs(
		array(
			'fieldset' => false,
			'legend' => false,
			'Contratinsertion.dd_ci' => array( 'domain' => 'contratinsertion', 'type' => 'date', 'empty' => true, 'dateFormat' => 'DMY', 'required' => true ),
			'Contratinsertion.df_ci' => array( 'domain' => 'contratinsertion','type' => 'date', 'empty' => true, 'dateFormat' => 'DMY', 'required' => true ),
			'Contratinsertion.date_saisi_ci' => array( 'label' => 'Contrat saisi le', 'type' => 'date', 'dateFormat' => 'DMY', 'required' => true )
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


<script type="text/javascript">
	<!--//--><![CDATA[//><!--
		var gabaritDiplomecer93 = '<tr><td><?php
			$fields = $this->Xform->input( 'Diplomecer93.%line%.id', array( 'type' => 'hidden', 'label' => false ) )
				.$this->Xform->input( 'Diplomecer93.%line%.cer93_id', array( 'type' => 'hidden', 'label' => false ) )
				.$this->Xform->input( 'Diplomecer93.%line%.name', array( 'type' => 'text', 'label' => false ) );
			echo str_replace( "'", "\\'", $fields );
		?></td><td><?php
			$fields = $this->Xform->input( 'Diplomecer93.%line%.annee', array( 'type' => 'select', 'options' => array_range( date( 'Y' ), 1960 ), 'label' => false, 'empty' => true ) );
			echo str_replace( "'", "\\'", preg_replace( '/[[:space:]]+/', ' ', $fields ) );
		?></td><td><?php
			$fields = $this->Xform->input( 'Diplomecer93.%line%.isetranger', array( 'type' => 'checkbox', 'label' => false ) );
			echo str_replace( "'", "\\'", preg_replace( '/[[:space:]]+/', ' ', $fields ) );
		?></td><td><a href="#" onclick="deleteDynamicTrInputs( \'Diplomecer93\', %line% );return false;">Supprimer</a></td></tr>';

		var gabaritExpprocer93 = '<tr><td><?php
			$fields = $this->Xform->input( 'Expprocer93.%line%.id', array( 'type' => 'hidden', 'label' => false ) )
				.$this->Xform->input( 'Expprocer93.%line%.cer93_id', array( 'type' => 'hidden', 'label' => false ) )
				.$this->Xform->input( 'Expprocer93.%line%.metierexerce_id', array( 'type' => 'select', 'label' => false, 'options' => $options['Expprocer93']['metierexerce_id'], 'empty' => true ) );
			echo str_replace( "'", "\\'", preg_replace( '/[[:space:]]+/', ' ', $fields ) );
		?></td><td><?php
			$fields = $this->Xform->input( 'Expprocer93.%line%.secteuracti_id', array( 'type' => 'select', 'label' => false, 'options' => $options['Expprocer93']['secteuracti_id'], 'empty' => true ) );
			echo str_replace( "'", "\\'", preg_replace( '/[[:space:]]+/', ' ', $fields ) );
		?></td><td><?php
			$fields = $this->Xform->input( 'Expprocer93.%line%.anneedeb', array( 'type' => 'select', 'label' => false, 'options' => array_range( date( 'Y' ), 1960 ), 'empty' => true ) );
			echo str_replace( "'", "\\'", preg_replace( '/[[:space:]]+/', ' ', $fields ) );
		?></td><td><?php
			$fields = $this->Xform->input( 'Expprocer93.%line%.duree', array( 'type' => 'text', 'label' => false ) );
			echo str_replace( "'", "\\'", preg_replace( '/[[:space:]]+/', ' ', $fields ) );
		?></td><td><a href="#" onclick="deleteDynamicTrInputs( \'Expprocer93\', %line% );return false;">Supprimer</a></td></tr>';
	//--><!]]>
</script>

<script type="text/javascript">
	<!--//--><![CDATA[//><!--
		function addDynamicTrInputs( tableId, gabarit ) {
			var index = 0;
			$$( '#' + tableId + ' tbody tr > td:nth-child(1) > input:nth-child(1)' ).each( function( input ) {
				var i = parseInt( input.name.replace( new RegExp( '^.*\\]\\[([0-9]+)\\]\\[.*$', 'gi' ), '$1' ) );
				if( i >= index ) {
					index = i + 1;
				}
			} );
			var line = gabarit.replace( new RegExp( '%line%', 'gi' ), index );
			$$( '#' + tableId + ' tbody' )[0].insert( { 'top': line } );
		}

		function deleteDynamicTrInputs( tableId, index ) {
			var lineNr = -1;
			$$( '#' + tableId + ' tbody tr > td:nth-child(1) > input:nth-child(1)' ).each( function( input, l ) {
				console.log( l );
				var i = parseInt( input.name.replace( new RegExp( '^.*\\]\\[([0-9]+)\\]\\[.*$', 'gi' ), '$1' ) );
				if( i == index ) {
					lineNr = l;
				}
			} );

			if( lineNr != -1 ) {
				$$( '#' + tableId + ' tbody tr' )[parseInt(lineNr)].remove();
			}
		}
	//--><!]]>
</script>