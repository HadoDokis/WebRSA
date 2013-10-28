<?php
	require_once( dirname( __FILE__ ).DS.'search.ctp' );
	$index = 0;
	$annee = Hash::get( $this->request->data, 'Search.annee' );
?>
<?php if( isset( $results ) ): ?>
    <?php
        if( $index == 0 ) {
            $class = 'first';
        }
        else if( $index + 1 == count( $categories ) ) {
            $class = 'last';
        }
        else {
            $class = 'middle';
        }
    ?>
    <table class="tableaud1 <?php echo $class;?>">
        <thead>
            <tr class="main">
                <!-- TODO: traductions -->
                <th rowspan="2"></th>
                <th>Nombre de participants prévisionnel</th>
                <th colspan="3">Report des participants de l'année précédente, le cas échéant</th>
                <th colspan="3">Entrées enregistrées, au titre de la période d'exécution considérée</th>
                <th colspan="3">Sorties enregistrées, au titre de la période d'exécution considérée</th>
                <th colspan="3">Nombre de participants à l'action au 31/12/<?php echo $annee;?></th>
            </tr>
            <tr class="main">
                <th>Total</th>
                <?php for( $i = 0 ; $i < 4 ; $i++ ):?>
                    <th>Total</th>
                    <th>Hommes</th>
                    <th>Femmes</th>
                <?php endfor;?>
            </tr>
        </thead>
        <tbody>
		<?php foreach( $categories as $categorie => $foos ):?>
            <?php if( !in_array( $categorie, array( 'non_scolarise', 'diplomes_etrangers' ) ) ):?>
            <tr class="category">
                <th colspan="14"><?php echo __d( 'tableauxsuivispdvs93', "/Tableauxsuivispdvs93/tableaud1/{$categorie}" );?></th>
            </tr>
            <?php endif;?>
				<?php foreach( $foos as $key => $label ):?>
				<!-- Présentation des lignes "Non scolarisé" et "Diplômes étrangers non reconnus en France" -->
				<?php if( !in_array( $categorie, array( 'non_scolarise', 'diplomes_etrangers' ) ) ):?>
					<tr>
				<?php else: ?>
					<tr class="category">
				<?php endif; ?>
					<?php $lineTotal = array( 'total' => 0, 'homme' => 0, 'femme' => 0 ); ?>
					<?php if( !in_array( $categorie, array( 'non_scolarise', 'diplomes_etrangers' ) ) ):?>
						<th><?php echo __d( 'tableauxsuivispdvs93',  $label );?></th>
					<?php else: ?>
						<th><?php echo __d( 'tableauxsuivispdvs93', "/Tableauxsuivispdvs93/tableaud1/{$categorie}" );?></th>
					<?php endif; ?>
					<?php
						$hasResults = isset( $results[$categorie]['previsionnel'] );
						$total = (int)Hash::get( $results, "{$categorie}.{$key}.previsionnel" );
					?>
					<td class="number"><?php echo ( $hasResults ? $this->Locale->number( $total ) : 'N/C' );?></td>
					<?php foreach( array( 'report', 'entrees', 'sorties' ) as $colonne ):?> <!-- Envoyer depuis le contrôleur -->
						<?php
							// TODO: noms des variables foos/bar/baz
							$bar = Hash::extract( $results, "{$categorie}.{s}.{$colonne}" );
							$baz = Hash::extract( $results, "{$categorie}.{n}.{$colonne}" );
							$hasResults = !empty( $bar ) || !empty( $baz );
							$hommes = (int)Hash::get( $results, "{$categorie}.{$key}.{$colonne}.homme" );
							$femmes = (int)Hash::get( $results, "{$categorie}.{$key}.{$colonne}.femme" );
							$total = $hommes + $femmes;

							if( $colonne != 'sortie' ) {
								$lineTotal['total'] += $total;
								$lineTotal['homme'] += $hommes;
								$lineTotal['femme'] += $femmes;
							}
							else {
								$lineTotal['total'] -= $total;
								$lineTotal['homme'] -= $hommes;
								$lineTotal['femme'] -= $femmes;
							}
						?>
						<td class="number"><?php echo ( $hasResults ? $this->Locale->number( $total ) : 'N/C' );?></td>
						<td class="number"><?php echo ( $hasResults ? $this->Locale->number( $hommes ) : 'N/C' );?></td>
						<td class="number"><?php echo ( $hasResults ? $this->Locale->number( $femmes ) : 'N/C' );?></td>
					<?php endforeach;?>
					<td class="number"><?php echo $this->Locale->number( $lineTotal['total'] );?></td>
					<td class="number"><?php echo $this->Locale->number( $lineTotal['homme'] );?></td>
					<td class="number"><?php echo $this->Locale->number( $lineTotal['femme'] );?></td>
				</tr>
				<?php endforeach;?>
			<?php endforeach;?>
			</tbody>
		</table>
		<?php $index++;?>

	<?php require_once( dirname( __FILE__ ).DS.'footer.ctp' );?>
<?php endif;?>