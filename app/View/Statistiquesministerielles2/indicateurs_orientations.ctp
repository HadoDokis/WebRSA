<?php
	require_once( dirname( __FILE__ ).DS.'search.ctp' );
	// FIXME: intitulés (traductions), habillage du tableau ...
?>
<?php if( !empty( $this->request->data ) ): ?>
	<h2>1. Bénéficiaires du RSA dans le champ des Droits et Devoirs (L262-28) au 31 décembre de l'année, selon le parcours d'insertion envisagé par le Conseil général.</h2>
	<?php
		$annee = Hash::get( $this->request->data, 'Search.annee' );
		$indicateurs = array( 'age', 'sitfam', 'nivetu', 'anciennete' );
	?>
	<?php foreach( $indicateurs as $index => $indicateur ):?>
	<?php
		$name = "Indicateur{$indicateur}";
		$sdd = (array)Hash::get( $results, "{$name}.sdd" );
		$orient_pro = (array)Hash::get( $results, "{$name}.orient_pro" );
		$orient_sociale = (array)Hash::get( $results, "{$name}.orient_sociale" );
		$attente_orient = (array)Hash::get( $results, "{$name}.attente_orient" );

		if( $index == 0 ) {
			$class = 'first';
		}
		else if( $index + 1 == count( $indicateurs ) ) {
			$class = 'last';
		}
		else {
			$class = 'middle';
		}
	?>
	<table class="<?php echo $class;?>">
		<caption>Orientation des personnes dans le champ des Droits et Devoirs au cours de l'année <?php echo $annee;?> <em>(en effectifs au 31 décembre de l'année</em>)</caption>
		<thead>
			<tr class="main">
				<th rowspan="2">Catégorie</th>
				<th rowspan="2">Personnes dans le champ des Droits et Devoirs L262-28</th>
				<th colspan="3">Dont:</th>
			</tr>
			<tr class="main">
				<th>Orientation à dominante professionnelle</th>
				<th>Orientation à dominante sociale</th>
				<th>En attente d'orientation (*)</th>
			</tr>
			<tr class="category">
				<th colspan="5"><?php echo __d( 'statistiquesministerielles2', $name );?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>Total</th>
				<td><?php echo $this->Locale->number( array_sum( $sdd ) );?></td>
				<td><?php echo $this->Locale->number( array_sum( $orient_pro ) );?></td>
				<td><?php echo $this->Locale->number( array_sum( $orient_sociale ) );?></td>
				<td><?php echo $this->Locale->number( array_sum( $attente_orient ) );?></td>
			</tr>
		</tfoot>
		<tbody>
			<?php foreach( $tranches[$indicateur] as $tranche ):?>
			<tr>
				<th><?php echo __d( 'statistiquesministerielles2',  $tranche );?></th>
				<td class="number"><?php echo ( isset( $sdd[$tranche] ) ? $this->Locale->number( $sdd[$tranche] ) : 0 );?></td>
				<td class="number"><?php echo ( isset( $orient_pro[$tranche] ) ? $this->Locale->number( $orient_pro[$tranche] ) : 0 );?></td>
				<td class="number"><?php echo ( isset( $orient_sociale[$tranche] ) ? $this->Locale->number( $orient_sociale[$tranche] ) : 0 );?></td>
				<td class="number"><?php echo ( isset( $attente_orient[$tranche] ) ? $this->Locale->number( $attente_orient[$tranche] ) : 0 );?></td>
			</tr>
			<?php endforeach;?>
		</tbody>
	</table>
	<?php endforeach;?>
	<p>(*) Certains bénéficiaires du Rsa peuvent être en attente d'orientation, compte tenu d’une part, du délai de 9 mois accordé par la loi à compter de sa date d'entrée en vigueur, pour examiner l'ensemble des situations des anciens bénéficiaires du RMI et de l'API, ou d’autre part, si la décision d’orientation est en attente de validation par le président du conseil général.</p>
	<p>(**) L’ancienneté dans le dispositif est mesurée par rapport à la dernière date d’entrée dans le dispositif. </p>
<?php endif; ?>