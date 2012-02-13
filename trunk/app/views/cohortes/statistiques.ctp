<h1><?php echo $this->pageTitle = $pageTitle;?></h1>
<?php
	if( !empty( $this->data ) ) {
		echo '<ul class="actionMenu"><li>'.$xhtml->link(
			$xhtml->image(
				'icons/application_form_magnify.png',
				array( 'alt' => '' )
			).' Formulaire',
			'#',
			array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Filtre' ).toggle(); return false;" )
		).'</li></ul>';
	}
?>


<?php require_once( 'filtre.ctp' );?>



<?php if( !empty( $this->data ) ):?>
	<?php if( empty( $statistiques ) ):?>
		<p class="notice">Aucune statistique présente concernant les orientations.</p>
	<?php else:?>
	<?php
		$traductions = array(
			'Orienté' => 'Nombre de personnes orientées',
			'En attente' => 'Nombre de personnes en attente d\'orientation',
			'Non orienté' => 'Nombre de personnes non orientées'
		);
	?>
	<table>
		<tbody>
			<?php foreach( $statistiques as $type => $nombre ):?>
				<tr>
					<th><?php echo h( $traductions[$type] );?></th>
					<td class="number"><?php echo $locale->number( $nombre );?></td>
				</tr>
			<?php endforeach;?>
		</tbody>
	</table>
	<?php endif;?>
<?php endif;?>