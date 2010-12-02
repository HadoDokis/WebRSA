<?php
	echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	echo $this->element( 'dossier_menu', array( 'personne_id' => Set::classicExtract( $personne, 'Personne.id') ) );
?>

<div class="with_treemenu">
	<h1> <?php echo $this->pageTitle = __d( 'bilanparcours66', 'Bilansparcours66::add', true ); ?> </h1>

	<?php echo $javascript->link( 'dependantselect.js' ); ?>
	<script type="text/javascript">
		document.observe("dom:loaded", function() {
			dependantSelect( 'Saisineepbilanparcours66StructurereferenteId', 'Saisineepbilanparcours66TypeorientId' );
			try { $( 'Saisineepbilanparcours66StructurereferenteId' ).onchange(); } catch(id) { }

			dependantSelect( 'Bilanparcours66ReferentId', 'Bilanparcours66StructurereferenteId' );
		});
	</script>

	<?php
		echo $form->create( null, array( 'url' => Router::url( null, true ) ) );

		echo $default->subform(
			array(
				'Bilanparcours66.structurereferente_id',
				'Bilanparcours66.referent_id'
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
					<strong>Adresse : </strong><br /><?php echo Set::classicExtract( $personne, 'Foyer.Adressefoyer.0.Adresse.numvoie' ).' '.Set::enum( Set::classicExtract( $personne, 'Foyer.Adressefoyer.0.Adresse.typevoie' ), $typevoie ).' '.Set::classicExtract( $personne, 'Foyer.Adressefoyer.0.Adresse.nomvoie' ).'<br /> '.Set::classicExtract( $personne, 'Foyer.Adressefoyer.0.Adresse.codepos' ).' '.Set::classicExtract( $personne, 'Foyer.Adressefoyer.0.Adresse.locaadr' );?>
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
		echo $default->subform(
			array(
				'Bilanparcours66.orientstruct_id' => array( 'type' => 'hidden' ),
				/*'Bilanparcours66.typeorient_id',
				'Bilanparcours66.structurereferente_id',
				'Bilanparcours66.motifreorient_id',*/
				'Bilanparcours66.presenceallocataire',
				'Bilanparcours66.situationallocataire',
				'Bilanparcours66.bilanparcours',
				'Bilanparcours66.infoscomplementaires',
	// 			'Bilanparcours66.preconisationpe',
				'Bilanparcours66.observationsallocataire',
	// 			'Bilanparcours66.saisineepparcours',
				'Bilanparcours66.maintienorientation',
	// 			'Bilanparcours66.changereferent',
				'Bilanparcours66.duree_engag',
				'Bilanparcours66.ddreconductoncontrat',
				'Bilanparcours66.dfreconductoncontrat',
				'Saisineepbilanparcours66.typeorient_id',
				'Saisineepbilanparcours66.structurereferente_id'
			),
			array(
				'options' => $options
			)
		);

		echo $form->submit( __( 'Save', true ) );
		echo $form->end();
	?>

	<script type="text/javascript">
		function checkDatesToRefresh() {
			if( ( $F( 'Bilanparcours66DdreconductoncontratMonth' ) ) && ( $F( 'Bilanparcours66DdreconductoncontratYear' ) ) && ( $F( 'Bilanparcours66DureeEngag' ) ) ) {
				var correspondances = new Array();
				<?php
					foreach( $options['Bilanparcours66']['duree_engag'] as $index => $duree ):?>correspondances[<?php echo $index;?>] = <?php echo str_replace( ' mois', '' ,$duree );?>;<?php endforeach;?>

				setDateInterval(
					'Bilanparcours66Ddreconductoncontrat',
					'Bilanparcours66Dfreconductoncontrat',
					correspondances[$F( 'Bilanparcours66DureeEngag' )],
					false
				);
			}
		}

		document.observe( "dom:loaded", function() {
			
			observeDisableFieldsOnValue(
				'Bilanparcours66Maintienorientation',
				[ 'Saisineepbilanparcours66TypeorientId', 'Saisineepbilanparcours66StructurereferenteId' ],
				'1',
				true
			);

			observeDisableFieldsOnValue(
				'Bilanparcours66Maintienorientation',
				[
					'Bilanparcours66DdreconductoncontratYear',
					'Bilanparcours66DdreconductoncontratMonth',
					'Bilanparcours66DdreconductoncontratDay',
					'Bilanparcours66DfreconductoncontratYear',
					'Bilanparcours66DfreconductoncontratMonth',
					'Bilanparcours66DfreconductoncontratDay',
					'Bilanparcours66DureeEngag'
				],
				'0',
				true
			);

			Event.observe( $( 'Bilanparcours66DdreconductoncontratYear' ), 'change', function() {
				checkDatesToRefresh();
			} );
			Event.observe( $( 'Bilanparcours66DdreconductoncontratMonth' ), 'change', function() {
				checkDatesToRefresh();
			} );
			Event.observe( $( 'Bilanparcours66DdreconductoncontratDay' ), 'change', function() {
				checkDatesToRefresh();
			} );

			Event.observe( $( 'Bilanparcours66DureeEngag' ), 'change', function() {
				checkDatesToRefresh();
			} );
		} );
	</script>
</div>
