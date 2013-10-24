<?php
	require_once( dirname( __FILE__ ).DS.'search.ctp' );
	$annee = Hash::get( $this->request->data, 'Search.annee' );
?>
<?php if( isset( $results ) ): ?>
	<table class="tableaud2">
		<thead>
			<tr>
				<th colspan="3"></th>
				<th>Nombre de personnes</th>
				<th>En %</th>
				<th>Dont hommes</th>
				<th>En %</th>
				<th>Dont femmes</th>
				<th>En %</th>
				<th>Dont couvert par un CER = Objectif "SORTIE"</th>
				<th>En %</th>
			</tr>
		</thead>
		<tbody>
			<!-- TODO: total participants, total sorties => dans le Helper -->
			<?php echo $this->Tableaud2->line1Categorie( 'maintien', $results );?>
			<?php echo $this->Tableaud2->line3Categorie( 'sortie_obligation', $results, $categories );?>
			<?php echo $this->Tableaud2->line1Categorie( 'abandon', $results );?>
			<?php echo $this->Tableaud2->line1Categorie( 'reorientation', $results );?>
			<?php echo $this->Tableaud2->line2Categorie( 'changement_situation', $results, $categories );?>
		</tbody>
	</table>

	<?php require_once( dirname( __FILE__ ).DS.'footer.ctp' );?>
<?php endif;?>