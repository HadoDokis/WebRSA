<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php  $this->pageTitle = 'Versement d\'acompte RSA';?>

<h1><?php echo $this->pageTitle;?></h1>

<?php
	if( is_array( $this->data ) ) {
		echo '<ul class="actionMenu"><li>'.$xhtml->link(
			$xhtml->image(
				'icons/application_form_magnify.png',
				array( 'alt' => '' )
			).' Formulaire',
			'#',
			array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Search' ).toggle(); return false;" )
		).'</li></ul>';
	}

	function thead( $pct = null ) {
		return '<thead>
				<tr>
					<th style="width: '.$pct.'%;"></th>
					<th style="width: '.$pct.'%;"></th>
					<th style="width: '.$pct.'%;"></th>
				</tr>
			</thead>';
	}
?>

<?php echo $form->create( 'Totalisationsacomptes', array( 'type' => 'post', 'action' => '/index/', 'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) ) ? 'folded' : 'unfolded' ) ) );?>
		<fieldset>
			<?php echo $form->input( 'Filtre.dtcreaflux', array( 'label' => 'Recherche des versements pour le mois de ', 'type' => 'date', 'dateFormat' => 'MY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 1 ) );?>
	</fieldset>

	<div class="submit noprint">
		<?php echo $form->button( 'Rechercher', array( 'type' => 'submit' ) );?>
		<?php echo $form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
	</div>
<?php echo $form->end();?>

<!-- Résultats -->
<?php if( isset( $totsacoms ) ):?>

<?php $mois = strftime('%B %Y', strtotime( $this->data['Filtre']['dtcreaflux']['year'].'-'.$this->data['Filtre']['dtcreaflux']['month'].'-01' ) ); ?>

	<h2 class="noprint">Liste des versements d'allocation pour le mois de <?php echo isset( $mois ) ? $mois : null ;?> </h2>

	<?php if( is_array( $totsacoms ) && count( $totsacoms ) > 0  ):?>
		<?php $sommeFlux = $sommeCalculee = 0; ?>

		<table id="searchResults" class="tooltips">
			<?php foreach( $totsacoms as $totacom ) :?>
				<tbody>
					<tr class="even">
						<th><?php echo Set::classicExtract( $type_totalisation, Set::classicExtract( $totacom, 'Totalisationacompte.type_totalisation' )  );?></th>
						<th>Total acomptes transmis (CAF/MSA)</th>
					</tr>
					<tr class="odd">
						<td>RSA socle</td>
						<td class="number"><?php echo $locale->money( $totacom['Totalisationacompte']['mttotsoclrsa'] );?></td>
					</tr>
					<tr class="even">
						<td>RSA socle majoré</td>
						<td class="number"><?php echo $locale->money( $totacom['Totalisationacompte']['mttotsoclmajorsa'] );?></td>
					</tr>
					<tr class="odd">
						<td>RSA local</td>
						<td class="number"><?php echo $locale->money( $totacom['Totalisationacompte']['mttotlocalrsa'] );?></td>
					</tr>
					<tr class="even">
						<td>RSA socle total</td>
						<td class="number"><?php echo $locale->money( $totacom['Totalisationacompte']['mttotrsa'] );?></td>
					</tr>
				</tbody>
			<?php endforeach; ?>
		</table>

		<ul class="actionMenu">
			<li><?php
				echo $xhtml->printLinkJs(
					'Imprimer le tableau',
					array( 'onclick' => 'printit(); return false;' )
				);
			?></li>

			<li><?php
				echo $xhtml->exportLink(
					'Télécharger le tableau',
					array( 'controller' => 'totalisationsacomptes', 'action' => 'exportcsv', implode_assoc( '/', ':', array_unisize( $this->data ) ) )
				);
			?></li>
		</ul>
	<?php else:?>
		<p>Vos critères n'ont retourné aucun dossier.</p>
	<?php endif?>

<?php endif?>