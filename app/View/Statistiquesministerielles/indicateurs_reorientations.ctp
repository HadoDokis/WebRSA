<?php
	require_once( dirname( __FILE__ ).DS.'search.ctp' );
	// FIXME: intitulés (traductions), habillage du tableau ...
?>
<?php if( !empty( $this->request->data ) ): ?>
	<h2>4. Bénéficiaires du RSA réorientées au cours de l'année. </h2>
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
		<caption>Personnes réorientées au cours de l'année <?php echo $annee;?> <em>(en effectifs au 31 décembre de l'année</em>)</caption>
		<thead>
			<tr class="main">
				<th rowspan="2">Catégorie</th>
				<th rowspan="2">Ensemble des personnes réorientées (*)</th>
				<th colspan="3">Dont:</th>
			</tr>
			<tr class="main">
				<th>Orientation à dominante professionnelle vers orientation à dominante sociale</th>
				<th>Orientation à dominante sociale vers orientation à dominante professionnelle</th>
			</tr>
			<tr class="category">
				<th colspan="4"><?php echo __d( 'statistiquesministerielles', $name );?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>Total</th>
				<td><?php echo $this->Locale->number( array_sum( $sdd ) );?></td>
				<td><?php echo $this->Locale->number( array_sum( $orient_pro ) );?></td>
				<td><?php echo $this->Locale->number( array_sum( $orient_sociale ) );?></td>
			</tr>
		</tfoot>
		<tbody>
			<?php foreach( $tranches[$indicateur] as $tranche ):?>
			<tr>
				<th><?php echo __d( 'statistiquesministerielles',  $tranche );?></th>
				<td class="number"><?php echo ( isset( $sdd[$tranche] ) ? $this->Locale->number( $sdd[$tranche] ) : 0 );?></td>
				<td class="number"><?php echo ( isset( $orient_pro[$tranche] ) ? $this->Locale->number( $orient_pro[$tranche] ) : 0 );?></td>
				<td class="number"><?php echo ( isset( $orient_sociale[$tranche] ) ? $this->Locale->number( $orient_sociale[$tranche] ) : 0 );?></td>
			</tr>
			<?php endforeach;?>
		</tbody>
	</table>
	<?php endforeach;?>
	<p>(*) On entend par réorientation, le passage d'une « orientation à dominante sociale » vers une « orientation à dominante professionnelle » ou réciproquement. Un changement d'organisme au sein d'une même orientation (exemple de Pôle emploi vers un organisme privé de placement) n'est pas considéré comme une réorientation.</p>
	<p>(**) L’ancienneté dans le dispositif est mesurée par rapport à la dernière date d’entrée dans le dispositif.</p>
<?php endif; ?>