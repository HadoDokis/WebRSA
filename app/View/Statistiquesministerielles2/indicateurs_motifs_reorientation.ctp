<?php require_once( dirname( __FILE__ ).DS.'search.ctp' ); ?>

<?php if( !empty( $this->request->data ) ): ?>
	<h2>4a. Motifs des réorientations vers une dominante sociale effectuées au cours de l'année</h2>
	<table>
		<caption>Motifs de réorientation vers une dominante sociale (*) (en effectifs)</caption>
		<tbody>
			<tr class="total">
				<th>Nombre de personnes réorientées vers une <em>dominante sociale</em> au cours de l'année :</th>
				<td class="number"><?php echo $this->Locale->number( (int)Hash::get( $results, 'Indicateursocial.total' ) );?></td>
			</tr>
			<tr>
				<th>Orientation initiale inadaptée</th>
				<td class="number"><?php
					$value = Hash::get( $results, "Indicateursocial.orientation_initiale_inadaptee" );
					if( is_null( $value ) ) {
						echo 'N/A';
					}
					else {
						echo $this->Locale->number( $value );
					}
				?></td>
			</tr>
			<tr>
				<th>Changement de situation de la personne (difficultés nouvelles de logement, santé, garde d'enfants, famille, ...)</th>
				<td class="number"><?php
					$value = Hash::get( $results, "Indicateursocial.changement_situation_allocataire" );
					if( is_null( $value ) ) {
						echo 'N/A';
					}
					else {
						echo $this->Locale->number( $value );
					}
				?></td>
			</tr>
			<tr>
				<th>Autres</th>
				<td class="number"><?php echo $this->Locale->number( (int)Hash::get( $results, 'Indicateursocial.autre' ) );?></td>
			</tr>
		</tbody>
	</table>
	<p>(*) Si une personne a été réorientée plusieurs fois au cours de l'année, indiquer uniquement le motif de sa dernière réorientation.</p>

	<h2>4b. Recours à l'article L262-31</h2>
	<table>
		<tbody>
			<tr class="total">
				<th>Nombre de personnes dont le dossier a été examiné par l'équipe pluridisciplinaire dans le cadre de l'article L262-31 (à l'issue du délai de 6 à 12 mois sans réorientation professionnelle) au cours de l'année :</th>
				<td class="number"><?php echo $this->Locale->number( (int)Hash::get( $results, 'Indicateurep.total' ) );?></td>
			</tr>
			<tr>
				<th>Dont maintien en orientation à dominante sociale</th>
				<td class="number"><?php echo $this->Locale->number( (int)Hash::get( $results, 'Indicateurep.maintien' ) );?></td>
			</tr>
			<tr>
				<th>Dont réorientation vers une dominante professionnelle</th>
				<td class="number"><?php echo $this->Locale->number( (int)Hash::get( $results, 'Indicateurep.reorientation' ) );?></td>
			</tr>
		</tbody>
	</table>
<?php endif; ?>