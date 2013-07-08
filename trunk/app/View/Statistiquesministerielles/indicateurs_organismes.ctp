<?php
	require_once( dirname( __FILE__ ).DS.'search.ctp' );
	// FIXME: intitulés (traductions), habillage du tableau ...
?>
<?php if( !empty( $this->request->data ) ): ?>
	<h2>2. Bénéficiaires du Rsa dans le champ des Droits et Devoirs (L262-28) au 31 décembre de l'année selon l'organisme de prise en charge où a été désigné le référent unique.</h2>
	<?php
		$annee = Hash::get( $this->request->data, 'Search.annee' );
//		$indicateurs = array( 'age', 'sitfam', 'nivetu', 'anciennete' );
	?>
	<?php // foreach( $indicateurs as $index => $indicateur ):?>
	<table>
		<caption>Organismes de prise en charge, où le référent unique est désigné <em>(en effectifs au 31 décembre de l'année <?php echo $annee;?>)</em></caption>
		<tbody>
			<tr>
				<th><strong>Nombre de personnes dans le champ des Droits et Devoirs (L262-28) au 31 décembre de l'année</strong></th>
				<td><strong><?php echo $this->Locale->number( Hash::get( $results, 'Indicateurorganisme.total' ) );?></strong></td>
			</tr>
			<tr>
				<th colspan="2"><strong>Dont le référent appartient à</strong></th>
			</tr>
			<?php foreach( array_keys( $results['Indicateurorganisme'] ) as $indicateur ):?>
				<?php if( !in_array( $indicateur, array( 'total', 'attente_orient' ) ) ):?>
				<tr>
					<th> - <?php echo __d( 'statistiquesministerielles', "Indicateurorganisme.{$indicateur}" );?></th>
					<td><?php
						$value = Hash::get( $results, "Indicateurorganisme.{$indicateur}" );
						if( is_null( $value ) ) {
							echo 'N/A';
						}
						else {
							echo $this->Locale->number( $value );
						}
					?></td>
				</tr>
				<?php endif;?>
			<?php endforeach;?>
			<tr>
				<th><strong>Nombre de personnes en attente d'orientation (*)</strong></th>
				<td><strong><?php echo $this->Locale->number( Hash::get( $results, 'Indicateurorganisme.attente_orient' ) );?></strong></td>
			</tr>
		</tbody>
	</table>
	<p>(*) Certains bénéficiaires du Rsa peuvent être en attente d'orientation, compte tenu d’une part, du délai de 9 mois accordé par la loi à compter de sa date d'entrée en vigueur, pour examiner l'ensemble des situations des anciens bénéficiaires du RMI et de l'API, ou d’autre part, si la décision d’orientation est en attente de validation par le président du conseil général.</p>
<?php endif;?>