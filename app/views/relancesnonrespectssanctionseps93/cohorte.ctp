<?php
	echo $default2->form(
		array(
			'Personne.nom' => array( 'type' => 'text' ),
			'Personne.nomnai' => array( 'type' => 'text' ),
			'Personne.prenom' => array( 'type' => 'text' ),
			'Personne.nir' => array( 'type' => 'text' ),
			// TODO
			/*'Adresse.numcomptt' => array( 'type' => 'text' ),
			'Serviceinstructeur.id',// suiviinstruction
			'Dossier.matricule' => array( 'type' => 'text' ),
			'Dossiercaf.nomtitulaire' => array( 'type' => 'text' ),
			'Dossiercaf.prenomtitulaire' => array( 'type' => 'text' ),*/
			'Relance.numrelance' => array( 'type' => 'radio', 'options' => array( 1 => 'Première relance', 2 => 'Seconde relance', 3 => 'Troisième relance' ), 'value' => ( isset( $this->data['Relance']['numrelance'] ) ? @$this->data['Relance']['numrelance'] : 1 ) ),
		),
		array(
			'submit' => 'Rechercher'
		)
	);

	if( isset( $results ) ) {
		echo $xform->create( null, array( 'id' => 'Relancenonrespectsanctionep93Form' ) );

		foreach( Set::flatten( $this->data ) as $key => $data ) {
			if( !preg_match( '/^Relancenonrespectsanctionep93\./', $key ) && !( trim( $data ) == '' ) ) {
				echo $xform->input( $key, array( 'type' => 'hidden', 'value' => $data ) );
			}
		}

		echo '<table class="tooltips" style="width: 100%;">
			<thead>
				<tr>
					<th>N° CAF</th>
					<th>Nom / Prénom Allocataire</th>
					<th>NIR</th>
					<th>Nom de commune</th>
					<th>Date d\'orientation</th>
					<th>'.__d( 'orientstruct', 'Orientstruct.nbjours', true ).'</th>
					'.( ( $this->data['Relance']['numrelance'] == 2 ) ? '<th>Date de première relance</th>' : '' ).'
					'.( ( $this->data['Relance']['numrelance'] == 3 ) ? '<th>Date de seconde relance</th>' : '' ).'
					<th style="width: 19em;">'.__d( 'relancenonrespectsanctionep93', 'Relancenonrespectsanctionep93.daterelance', true ).'</th>
					<th style="width: 8em;">'.__d( 'relancenonrespectsanctionep93', 'Relancenonrespectsanctionep93.arelancer', true ).'</th>
					<th class="innerTableHeader">Informations complémentaires</th>
				</tr>
			</thead>
			<tbody>';
			foreach( $results as $index => $result ) {
				$innerTable = '<table id="innerTable'.$index.'" class="innerTable">
					<tbody>
						<tr>
							<th>Date naissance</th>
							<td>'.h( date_short( @$result['Personne']['dtnai'] ) ).'</td>
						</tr>
					</tbody>
				</table>';

				$row = array(
					h( @$result['Personne']['Foyer']['Dossier']['matricule'] ),
					h( @$result['Personne']['nom'].' '.@$result['Personne']['prenom'] ),
					h( @$result['Personne']['nir'] ),
					h( @$result['Personne']['Foyer']['Adressefoyer']['0']['Adresse']['locaadr'] ),
					h( date_short( @$result['Orientstruct']['date_valid'] ) ),
					h( @$result['Orientstruct']['nbjours'] )
				);

				if( $this->data['Relance']['numrelance'] == 2 ) {
					$row[] = date_short( @$result['Nonrespectsanctionep93'][0]['Relancenonrespectsanctionep93'][0]['daterelance'] );
				}
				else if( $this->data['Relance']['numrelance'] == 3 ) {
					$row[] = date_short( @$result['Nonrespectsanctionep93'][0]['Relancenonrespectsanctionep93'][0]['daterelance'] );
				}

				$row = Set::merge(
					$row,
					array(
						( ( @$this->data['Relance']['numrelance'] > 1 ) ? $xform->input( "Relancenonrespectsanctionep93.{$index}.nonrespectsanctionep93_id", array( 'type' => 'hidden', 'value' => @$result['Nonrespectsanctionep93'][0]['id'] ) ) : '' ).
						$xform->input( "Relancenonrespectsanctionep93.{$index}.numrelance", array( 'type' => 'hidden', 'value' => @$this->data['Relance']['numrelance'] ) ).
						$xform->input( "Relancenonrespectsanctionep93.{$index}.orientstruct_id", array( 'type' => 'hidden', 'value' => @$result['Orientstruct']['id'] ) ).
						$xform->input( "Relancenonrespectsanctionep93.{$index}.daterelance", array( 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 5, 'minYear' => date( 'Y' ) - 5, 'label' => false, 'div' => false ) ),
						$xform->input( "Relancenonrespectsanctionep93.{$index}.arelancer", array( 'type' => 'radio', 'options' => array( 'R' => 'Relancer', 'E' => 'En attente' ), 'legend' => false, 'div' => false, 'separator' => '<br />', 'value' => ( isset( $this->data['Relancenonrespectsanctionep93'][$index]['arelancer'] ) ? @$this->data['Relancenonrespectsanctionep93'][$index]['arelancer'] : 'E' ) ) ),
						array( $innerTable, array( 'class' => 'innerTableCell' ) )
					)
				);

				echo $xhtml->tableCells(
					$row,
					array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
					array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
				);
			}
		echo '</tbody></table>';
		echo $xform->end( __( 'Save', true ) );
	}
?>
<?php if( isset( $results ) ):?>
	<script type="text/javascript">
		<?php foreach( $results as $index => $result ):?>
		observeDisableFieldsOnRadioValue(
			'Relancenonrespectsanctionep93Form',
			'data[Relancenonrespectsanctionep93][<?php echo $index;?>][arelancer]',
			[
				'Relancenonrespectsanctionep93<?php echo $index;?>DaterelanceDay',
				'Relancenonrespectsanctionep93<?php echo $index;?>DaterelanceMonth',
				'Relancenonrespectsanctionep93<?php echo $index;?>DaterelanceYear'
			],
			'E',
			false
		);
		<?php endforeach;?>
	// 	( form, radioName, fieldsIds, value, condition )
	</script>
<?php endif;?>