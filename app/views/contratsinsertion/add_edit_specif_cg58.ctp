<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'CER';?>

<?php echo $this->element( 'dossier_menu', array( 'personne_id' => Set::classicExtract( $personne, 'Personne.id' ) ) );?>

<?php
	if( $this->action == 'add' ) {
		$this->pageTitle = 'Ajout d\'un CER';
	}
	else {
		$this->pageTitle = 'Édition d\'un CER';
	}
?>
<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $javascript->link( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}
?>
<script type="text/javascript">
	document.observe("dom:loaded", function() {
		dependantSelect( 'ContratinsertionReferentId', 'ContratinsertionStructurereferenteId' );
	});
</script>

<script type="text/javascript">
	function checkDatesToRefresh() {
		if( ( $F( 'ContratinsertionDdCiMonth' ) ) && ( $F( 'ContratinsertionDdCiYear' ) ) && ( $F( 'ContratinsertionDureeEngag' ) ) ) {
			var correspondances = new Array();

			<?php
				$duree_engag = 'duree_engag_'.Configure::read( 'nom_form_ci_cg' );
				foreach( $$duree_engag as $index => $duree ):?>correspondances[<?php echo $index;?>] = <?php echo str_replace( ' mois', '' ,$duree );?>;<?php endforeach;?>

			setDateIntervalCer( 'ContratinsertionDdCi', 'ContratinsertionDfCi', correspondances[$F( 'ContratinsertionDureeEngag' )], false );
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

		Event.observe( $( 'ContratinsertionDureeEngag' ), 'change', function() {
			checkDatesToRefresh();
		} );
	});

</script>
<script type="text/javascript">
	document.observe( "dom:loaded", function() {
		<?php
		$ref_id = Set::extract( $this->data, 'Contratinsertion.referent_id' );
			echo $ajax->remoteFunction(
				array(
					'update' => 'StructurereferenteRef',
					'url' => Router::url(
						array(
							'action' => 'ajaxstruct',
							Set::extract( $this->data, 'Contratinsertion.structurereferente_id' )
						),
						true
					)
				)
			).';';
			echo $ajax->remoteFunction(
				array(
					'update' => 'ReferentRef',
					'url' => Router::url(
						array(
							'action' => 'ajaxref',
							Set::extract( $this->data, 'Contratinsertion.referent_id' )
						),
						true
					)
				)
			).';';
		?>
	} );
</script>

<?php
	function value( $array, $index ) {
		$keys = array_keys( $array );
		$index = ( ( $index == null ) ? '' : $index );
		if( @in_array( $index, $keys ) && isset( $array[$index] ) ) {
			return $array[$index];
		}
		else {
			return null;
		}
	}
?>
<div class="with_treemenu">
	<h1><?php echo $this->pageTitle;?></h1>

	<?php
		if( $this->action == 'add' ) {
			echo $form->create( 'Contratinsertion', array( 'type' => 'post', 'id' => 'testform', 'url' => Router::url( null, true ) ) );
			echo '<div>';
			echo $form->input( 'Contratinsertion.id', array( 'type' => 'hidden', 'value' => '' ) );

			echo $form->input( 'Contratinsertion.personne_id', array( 'type' => 'hidden', 'value' => Set::classicExtract( $personne, 'Personne.id' ) ) );
			echo $form->input( 'Contratinsertion.rg_ci', array( 'type' => 'hidden') );
			echo '</div>';
		}
		else {
			echo $form->create( 'Contratinsertion', array( 'type' => 'post', 'id' => 'testform', 'url' => Router::url( null, true ) ) );
			echo '<div>';
			echo $form->input( 'Contratinsertion.id', array( 'type' => 'hidden' ) );

			echo $form->input( 'Contratinsertion.personne_id', array( 'type' => 'hidden', 'value' => Set::classicExtract( $personne, 'Personne.id' ) ) );
			echo '</div>';
		}
	?>
<fieldset>

	<fieldset>
		<legend>RÉFÉRENT UNIQUE</legend>
		<table class="wide noborder">
			<tr>
				<td class="noborder">
					<strong>Organisme chargé de l'instruction du dossier :</strong>
					<?php echo $xform->input( 'Contratinsertion.structurereferente_id', array( 'label' => false, 'type' => 'select', 'options' => $structures, 'selected' => $struct_id, 'empty' => true ) );?>
					<?php echo $ajax->observeField( 'ContratinsertionStructurereferenteId', array( 'update' => 'StructurereferenteRef', 'url' => Router::url( array( 'action' => 'ajaxstruct' ), true ) ) ); ?>
				</td>
				<td class="noborder">
					<strong>Nom du référent unique :</strong>
					<?php echo $xform->input( 'Contratinsertion.referent_id', array('label' => false, 'type' => 'select', 'options' => $referents, 'empty' => true, 'selected' => $struct_id.'_'.$referent_id ) );?>
					<?php echo $ajax->observeField( 'ContratinsertionReferentId', array( 'update' => 'ReferentRef', 'url' => Router::url( array( 'action' => 'ajaxref' ), true ) ) ); ?>
				</td>
			</tr>
			<tr>
				<td class="wide noborder"><div id="StructurereferenteRef"></div></td>
				<td class="wide noborder"><div id="ReferentRef"></div></td>
			</tr>
		</table>
	</fieldset>

	<fieldset>
		<legend>CARACTÉRISTIQUES DU PRÉSENT CONTRAT</legend>

		<?php
			if ( isset( $avenant_id ) && !empty( $avenant_id ) ) {
				echo $xhtml->tag(
					'div',
					$xform->label( __d( 'contratinsertion', 'Contratinsertion.num_contrat', true ) ).
					'Avenant',
					array(
						'class' => 'input select'
					)
				);
				echo $xform->input( 'Contratinsertion.num_contrat', array( 'type' => 'hidden', 'value' => $tc ) );
			}
			else {
				echo $xform->input( 'Contratinsertion.num_contrat', array( 'label' => 'Type de contrat' , 'type' => 'select', 'options' => $options['num_contrat'], 'empty' => true, 'value' => $tc ) );
			}
		?>

		<table class="nbrCi wide noborder">
			<tr class="nbrCi">
				<td class="noborder">Nombre de renouvellements </td>
				<td class="noborder"> <?php echo $nbrCi;?> </td>
			</tr>
		</table>

		<?php echo $xform->input( 'Contratinsertion.dd_ci', array( 'label' => __d( 'contratinsertion', 'Contratinsertion.dd_ci', true ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+2, 'minYear'=>date('Y')-2 , 'empty' => false ) );?>
		<?php echo $xform->input( 'Contratinsertion.duree_engag', array( 'label' => __d( 'contratinsertion', 'Contratinsertion.duree_engag', true ), 'type' => 'select', 'options' => $duree_engag_cg58, 'empty' => true ) );?>
		<?php echo $xform->input( 'Contratinsertion.df_ci', array( 'label' => __d( 'contratinsertion', 'Contratinsertion.df_ci', true ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+2, 'minYear'=>date('Y')-2 , 'empty' => true ) ) ;?>

	</fieldset>
		<?php echo $xform->input( 'Contratinsertion.date_saisi_ci', array( 'label' => __d( 'contratinsertion', 'Contratinsertion.date_saisi_ci', true ), 'type' => 'hidden'  ) ) ;?>
</fieldset>

	<div class="submit">
		<?php echo $form->submit( 'Enregistrer', array( 'div' => false ) );?>
		<?php echo $form->submit('Annuler', array( 'name' => 'Cancel', 'div' => false ) );?>
	</div>
	<?php echo $form->end();?>
</div>

<div class="clearer"><hr /></div>
<script type="text/javascript">
	Event.observe( $( 'ContratinsertionDdCiDay' ), 'change', function( event ) {
		$( 'ContratinsertionDateSaisiCiDay' ).value = $F( 'ContratinsertionDdCiDay' );
	} );
	Event.observe( $( 'ContratinsertionDdCiMonth' ), 'change', function( event ) {
		$( 'ContratinsertionDateSaisiCiMonth' ).value = $F( 'ContratinsertionDdCiMonth' );
	} );
	Event.observe( $( 'ContratinsertionDdCiYear' ), 'change', function( event ) {
		$( 'ContratinsertionDateSaisiCiYear' ).value = $F( 'ContratinsertionDdCiYear' );
	} );
</script>