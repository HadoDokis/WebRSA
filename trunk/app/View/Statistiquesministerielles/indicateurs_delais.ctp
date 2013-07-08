<?php
	require_once( dirname( __FILE__ ).DS.'search.ctp' );
	// FIXME: intitulés (traductions), habillage du tableau ...
?>
<?php if( !empty( $this->request->data ) ): ?>
	<h2>3. Délais entre les différentes étapes de l'orientation au cours de l'année.</h2>
	<?php
		$annee = Hash::get( $this->request->data, 'Search.annee' );
	?>
	<table>
		<caption>Délais entre les différentes étapes de l'orientation (en jours).</caption>
		<tbody>
			<tr>
				<th><strong>a. Délai moyen entre la date d'ouverture de droit, tel qu'enregistré par les organismes chargés du service de l'allocation (Caf, Msa), et la décision d'orientation validée par le président du conseil général (*)</strong></th>
				<td><strong><?php echo $this->Locale->number( Hash::get( $results, 'Indicateurdelai.delai_moyen_orientation' ) );?></strong></td>
			</tr>
			<tr>
				<th><strong>b. Délai moyen entre la décision d'orientation et la signature d'un contrat</strong></th>
				<td><strong><?php echo $this->Locale->number( Hash::get( $results, 'Indicateurdelai.delai_moyen_signature' ) );?></strong></td>
			</tr>
			<?php foreach( $types_cers as $type_cer => $delais ):?>
				<tr>
					<th><strong><?php echo __d( 'statistiquesministerielles', "Indicateurdelai.{$type_cer}_delai_moyen" );?></strong></th>
					<td><strong><?php
						$value = Hash::get( $results, "Indicateurdelai.{$type_cer}_delai_moyen" );
						$value = ( is_null( $value ) ? 'N/A' : $this->Locale->number( $value ) );
						echo $value;
					?></strong></td>
				</tr>
				<tr>
					<th><?php echo __d( 'statistiquesministerielles', "Indicateurdelai.{$type_cer}_nombre_moyen" );?></th>
					<td><?php
						$value = Hash::get( $results, "Indicateurdelai.{$type_cer}_nombre_moyen" );
						$value = ( is_null( $value ) ? 'N/A' : $this->Locale->number( $value ) );
						echo $value;
					?></td>
				</tr>
				<tr>
					<th><?php echo __d( 'statistiquesministerielles', "Indicateurdelai.{$type_cer}_delai_{$delais['nbMoisTranche1']}_mois" );?></th>
					<td><?php
						$value = Hash::get( $results, "Indicateurdelai.{$type_cer}_delai_{$delais['nbMoisTranche1']}_mois" );
						$value = ( is_null( $value ) ? 'N/A' : $this->Locale->number( $value ) );
						echo $value;
					?></td>
				</tr>
				<tr>
					<th><?php echo __d( 'statistiquesministerielles', "Indicateurdelai.{$type_cer}_delai_{$delais['nbMoisTranche1']}_{$delais['nbMoisTranche2']}_mois" );?></th>
					<td><?php
						$value = Hash::get( $results, "Indicateurdelai.{$type_cer}_delai_{$delais['nbMoisTranche1']}_{$delais['nbMoisTranche2']}_mois" );
						$value = ( is_null( $value ) ? 'N/A' : $this->Locale->number( $value ) );
						echo $value;
					?></td>
				</tr>
				<tr>
					<th><?php echo __d( 'statistiquesministerielles', "Indicateurdelai.{$type_cer}_delai_plus_{$delais['nbMoisTranche2']}_mois" );?></th>
					<td><?php
						$value = Hash::get( $results, "Indicateurdelai.{$type_cer}_delai_plus_{$delais['nbMoisTranche2']}_mois" );
						$value = ( is_null( $value ) ? 'N/A' : $this->Locale->number( $value ) );
						echo $value;
					?></td>
				</tr>
			<?php endforeach;?>
		</tbody>
	</table>
	<p>(*) On considère que la date d’ouverture de droit correspond à la date de dépôt de la demande, c’est-à-dire le premier jour du mois du dépôt de la demande.</p>
	<p>(**) Il serait souhaitable, qu’à terme, les flux d’échanges entre Pôle emploi et les Conseils généraux permettent de recueillir ces informations sur le PPAE.</p>
<?php endif;?>