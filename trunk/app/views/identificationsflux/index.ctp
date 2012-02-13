<?php  $this->pageTitle = 'Identification du flux';?>

	<h1><?php echo $this->pageTitle;?></h1>

	<?php if( empty( $identflux ) ):?>
		<p class="notice">Aucun flux reçu.</p>
	<?php else: ?>
		<table class="tooltips">
			<thead>
				<tr>
					<th>Type de totalisation </th>
					<th>Montant total Rsa socle</th>
					<th>Montant total Rsa socle majoré</th>
					<th>Montant total Rsa local</th>
					<th>Montant total</th>
				</tr>
			</thead>
			<tbody>
					<?php
						echo $xhtml->tableCells(
							array(
								h( $identflux['Identificationflux']['applieme'] ),
								h( $identflux['Identificationflux']['numversionapplieme'] ),
								h( $identflux['Identificationflux']['typeflux'] ),
								h( $identflux['Identificationflux']['natflux'] ),
								h( date_short( $identflux['Identificationflux']['dtcreaflux'] ) ) ,
								h( date_short( $identflux['Identificationflux']['heucreaflux'] ) ) ,
								h( date_short( $identflux['Identificationflux']['dtref'] ) ) ,
							),
							array( 'class' => 'odd' ),
							array( 'class' => 'even' )
						);
					?>
			</tbody>
		</table>
	<?php  endif;?>
<div class="clearer"><hr /></div>