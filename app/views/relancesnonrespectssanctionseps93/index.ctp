<?php
	echo $default2->form(
		array(
			'Personne.nom' => array( 'type' => 'text' ),
			'Personne.nomnai' => array( 'type' => 'text' ),
			'Personne.prenom' => array( 'type' => 'text' ),
			'Personne.nir' => array( 'type' => 'text' ),
			'Adresse.numcomptt' => array( 'type' => 'text' ),
			'Serviceinstructeur.id',// suiviinstruction
			'Dossier.matricule' => array( 'type' => 'text' ),
			'Dossiercaf.nomtitulaire' => array( 'type' => 'text' ),
			'Dossiercaf.prenomtitulaire' => array( 'type' => 'text' ),
			'Relance.numrelance' => array( 'type' => 'radio', 'options' => array( 1 => 'Première relance', 2 => 'Seconde relance', 3 => 'Troisième relance' ) ),
		),
		array(
			'submit' => 'Rechercher'
		)
	);

	if( isset( $results ) ) {
		echo $xform->create( null );
		echo '<table class="tooltips" style="width: 100%;">
			<thead>
				<tr>
					<th>N° CAF</th>
					<th>Nom / Prénom Allocataire</th>
					<th>NIR</th>
					<th>Nom de commune</th>
					<th>Date d\'orientation</th>
					<th>Orientstruct.nbjours</th>
					<th>Relancenonrespectsanctionep93.daterelance</th>
					<th>Relancenonrespectsanctionep93.arelancer</th>
					<th class="innerTableHeader">Informations complémentaires</th>
				</tr>
			</thead>
			<tbody>';
			foreach( $results as $index => $result ) {
				$innerTable = '<table id="innerTable'.$index.'" class="innerTable">
					<tbody>
						<tr>
							<th>N° de dossier</th>
							<td>'.h( @$result['Dossier']['numdemrsa'] ).'</td>
						</tr>
						<!--<tr>
							<th>Date naissance</th>
							<td>'.h( date_short( @$result['Personne']['dtnai'] ) ).'</td>
						</tr>
						<tr>
							<th>Numéro CAF</th>
							<td>'.h( @$result['Dossier']['matricule'] ).'</td>
						</tr>
						<tr>
							<th>NIR</th>
							<td>'.h( @$result['Personne']['nir'] ).'</td>
						</tr>
						<tr>
							<th>Code postal</th>
							<td>'.h( @$result['Adresse']['codepos'] ).'</td>
						</tr>
						<tr>
							<th>Date fin CER</th>
							<td>'.h( @$result['Contratinsertion']['df_ci'] ).'</td>
						</tr>-->

					</tbody>
				</table>';

				/*$statutRelance = Set::extract( $result, 'Orientstruct.'.$index.'.statutrelance' );
				$result_id = Set::extract( $result, 'Orientstruct.id');*/

				echo $xhtml->tableCells(
					array(
						h( @$result['Personne']['Foyer']['Dossier']['matricule'] ),
						h( @$result['Personne']['nom'].' '.@$result['Personne']['prenom'] ),
						h( @$result['Personne']['nir'] ),
						h( @$result['Personne']['Foyer']['Adressefoyer']['0']['Adresse']['locaadr'] ),
						h( date_short( @$result['Orientstruct']['date_valid'] ) ),
						h( @$result['Orientstruct']['nbjours'] ),
						$xform->input( "Relancenonrespectsanctionep93.{$index}.orientstruct_id", array( 'type' => 'hidden', 'value' => @$result['Orientstruct']['id'] ) ).
						$xform->input( "Relancenonrespectsanctionep93.{$index}.daterelance", array( 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 5, 'minYear' => date( 'Y' ) - 5, 'label' => false, 'div' => false ) ),
						$xform->input( "Relancenonrespectsanctionep93.{$index}.arelancer", array( 'type' => 'radio', 'options' => array( 'R' => 'Relancer', 'E' => 'En attente' ), 'legend' => false, 'div' => false, 'separator' => '<br />' ) )
// 								array( $innerTable, array( 'class' => 'innerTableCell' ) ),
					),
					array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
					array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
				);
				}
		echo '</tbody></table>';
		echo $xform->end( __( 'Save', true ) );
	}
?>
<?php if( isset( $results ) ):?>
<?php endif;?>