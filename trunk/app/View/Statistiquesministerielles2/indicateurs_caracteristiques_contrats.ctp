<?php
	require_once( dirname( __FILE__ ).DS.'search.ctp' );
	// FIXME: intitulés (traductions), habillage du tableau ...
?>
<?php if( !empty( $this->request->data ) ): ?>
	<h2>1. Contrats en cours de validité au 31 décembre de l'année.</h2>
	<?php $annee = Hash::get( $this->request->data, 'Search.annee' ); ?>
	<table>
		<caption>Contrats en cours de validité au 31 décembre <?php echo $annee;?> <em>(en effectifs)</em>.</caption>
		<thead>
			<tr>
				<th></th>
				<th>Total</th>
				<th>Dont personnes dans le champ des Droits et Devoirs (L262-28)</th>
				<th>Dont personnes hors du champ des Droits et Devoirs (L262-28)</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach( array( 'contrat_rmi', 'cer_experimental', 'cer' ) as $categorie ):?>
			<tr>
				<th><strong><?php echo __d( 'statistiquesministerielles2', "Indicateurcaracteristique.{$categorie}" );?></strong></th>
				<td class="number"><strong><?php
					$value = Hash::get( $results, "Indicateurcaracteristique.{$categorie}_total" );
					if( is_null( $value ) ) {
						echo 'N/A';
					}
					else {
						echo $this->Locale->number( $value );
					}
				?></strong></td>
				<td class="number"><strong><?php
					$value = Hash::get( $results, "Indicateurcaracteristique.{$categorie}_droitsdevoirs" );
					if( is_null( $value ) ) {
						echo 'N/A';
					}
					else {
						echo $this->Locale->number( $value );
					}
				?></strong></td>
				<td class="number"><strong><?php
					$value = Hash::get( $results, "Indicateurcaracteristique.{$categorie}_horsdroitsdevoirs" );
					if( is_null( $value ) ) {
						echo 'N/A';
					}
					else {
						echo $this->Locale->number( $value );
					}
				?></strong></td>
			</tr>
			<?php endforeach;?>

			<?php foreach( array( 'ppae', 'cer_pro', 'cer_social_pro' ) as $categorie ):?>
			<tr>
				<th><?php echo __d( 'statistiquesministerielles2', "Indicateurcaracteristique.{$categorie}" );?></th>
				<td class="number"><?php
					$value = Hash::get( $results, "Indicateurcaracteristique.{$categorie}_total" );
					if( is_null( $value ) ) {
						echo 'N/A';
					}
					else {
						echo $this->Locale->number( $value );
					}
				?></td>
				<td class="number"><?php
					$value = Hash::get( $results, "Indicateurcaracteristique.{$categorie}_droitsdevoirs" );
					if( is_null( $value ) ) {
						echo 'N/A';
					}
					else {
						echo $this->Locale->number( $value );
					}
				?></td>
				<td class="number"><?php
					$value = Hash::get( $results, "Indicateurcaracteristique.{$categorie}_horsdroitsdevoirs" );
					if( is_null( $value ) ) {
						echo 'N/A';
					}
					else {
						echo $this->Locale->number( $value );
					}
				?></td>
			</tr>
			<?php endforeach;?>
		</tbody>
	</table>
	<br />
	<table>
		<caption>Contrats en cours de validité au 31 décembre <?php echo $annee;?> <em>(en effectifs)</em>.</caption>
		<thead>
			<tr>
				<th></th>
				<th>Total</th>
				<th>Dont personnes dans le champ des Droits et Devoirs (L262-28)</th>
				<th>Dont personnes hors du champ des Droits et Devoirs (L262-28)</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach( array( 'cer_pro', 'cer_social_pro' ) as $categorie ):?>
				<tr>
					<th><strong><?php echo __d( 'statistiquesministerielles2', "Indicateurcaracteristique.{$categorie}_rappel" );?></strong></th>
					<td><strong><?php echo $this->Locale->number( Hash::get( $results, "Indicateurcaracteristique.{$categorie}_total" ) );?></strong></td>
					<td><strong><?php echo $this->Locale->number( Hash::get( $results, "Indicateurcaracteristique.{$categorie}_droitsdevoirs" ) );?></strong></td>
					<td><strong><?php echo $this->Locale->number( Hash::get( $results, "Indicateurcaracteristique.{$categorie}_horsdroitsdevoirs" ) );?></strong></td>
				</tr>
				<?php foreach( $durees_cers as $duree_cer ):?>
				<tr>
					<th><?php echo __d( 'statistiquesministerielles2', "Indicateurcaracteristique.{$categorie}_{$duree_cer}" );?></th>
					<td><?php echo $this->Locale->number( Hash::get( $results, "Indicateurcaracteristique.{$categorie}_{$duree_cer}_total" ) );?></strong></td>
					<td><?php echo $this->Locale->number( Hash::get( $results, "Indicateurcaracteristique.{$categorie}_{$duree_cer}_droitsdevoirs" ) );?></strong></td>
					<td><?php echo $this->Locale->number( Hash::get( $results, "Indicateurcaracteristique.{$categorie}_{$duree_cer}_horsdroitsdevoirs" ) );?></strong></td>
				</tr>
				<?php endforeach;?>
			<?php endforeach;?>
		</tbody>
	</table>
	<p>(*) Les contrats d’insertion RMI en cours de validité au 31 mai 2009 peuvent se poursuivre au-delà du 1er juin 2009 et au maximum jusqu’au 31 mars 2010. La loi accorde en effet un délai de 9 mois à compter de sa date d’entrée en vigueur, pour examiner l’ensemble des situations des anciens bénéficiaires du RMI et de l’API.</p>
<?php endif; ?>