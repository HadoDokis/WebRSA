<?php  $this->pageTitle = 'Référents liés à la personne';?>
<?php  echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id) );?>

<?php
	function value( $array, $index ) {
		$keys = array_keys( $array );
		$index = ( ( $index == null ) ? '' : $index );
		if( @in_array( $index, $keys ) && isset( $array[$index] ) ) {
			return $array[$index];
		}
		else {
			return null;
		}
	}
?>

<div class="with_treemenu">
	<h1>Référents</h1>
		<?php if( empty( $personnes_referents ) ):?>
			<p class="notice">Cette personne ne possède pas encore de référents.</p>
			<ul class="actionMenu">
				<?php
					echo '<li>'.$xhtml->addLink(
						'Ajouter un Référent',
						array( 'controller' => 'personnes_referents', 'action' => 'add', $personne_id )
					).' </li>';
				?>
			</ul>
		<?php endif;?>

		<?php if( !empty( $personnes_referents ) ):?>
		<?php
			$cloture = Set::classicExtract( $pers, 'PersonneReferent.dernier.dfdesignation' );
			$cloture = ( !empty( $cloture ) );
		?>
		<ul class="actionMenu">
			<?php
				echo '<li>'.$xhtml->addLink(
					'Ajouter un Référent',
					array( 'controller' => 'personnes_referents', 'action' => 'add', $personne_id ), $cloture
				).' </li>';
			?>
		</ul>
		<?php endif;?>

	<?php if( !empty( $personnes_referents ) ):?>
	<table class="default2">
		<thead>
			<tr>
				<th>Nom/Prénom Référent</th>
				<th>Fonction</th>
				<th>N° Téléphone</th>
				<th>Email</th>
				<th>Structure référente</th>
				<th>Date de désignation</th>
				<th>Fin de désignation</th>
				<th colspan="4" class="action">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php
				foreach( $personnes_referents as $personne_referent ) {
					$cloture = Set::classicExtract( $personne_referent, 'PersonneReferent.dfdesignation' );
					$cloture = ( !empty( $cloture ) );

					$nbFichiersLies = 0;
					$nbFichiersLies = ( isset( $personne_referent['Fichiermodule'] ) ? count( $personne_referent['Fichiermodule'] ) : 0 );

					echo $xhtml->tableCells(
						array(
							h( Set::classicExtract( $personne_referent, 'Referent.qual' ).' '.Set::classicExtract( $personne_referent, 'Referent.nom' ).' '.Set::classicExtract( $personne_referent, 'Referent.prenom' ) ),
							h( Set::classicExtract( $personne_referent, 'Referent.fonction' ) ),
							h( Set::classicExtract( $personne_referent, 'Referent.numero_poste' ) ),
							h( Set::classicExtract( $personne_referent, 'Referent.email' ) ),
							h( Set::extract( $personne_referent, 'Structurereferente.lib_struc' ) ),
							h( $locale->date( 'Date::short', Set::classicExtract( $personne_referent, 'PersonneReferent.dddesignation' ) ) ),
							h( $locale->date( 'Date::short', Set::classicExtract( $personne_referent, 'PersonneReferent.dfdesignation' ) ) ),
							$default2->button(
								'edit',
								array( 'controller' => 'personnes_referents', 'action' => 'edit',
								$personne_referent['PersonneReferent']['id'] ),
								array( 'enabled' => ( !$cloture && $permissions->check( 'personnes_referents', 'edit' ) ) )
							),
							$default2->button(
								'cloture_referent',
								array( 'controller' => 'personnes_referents', 'action' => 'cloturer',
								$personne_referent['PersonneReferent']['id'] ),
								array( 'enabled' => ( !$cloture && $permissions->check( 'personnes_referents', 'cloturer' ) ) )
							),
							$default2->button(
								'filelink',
								array( 'controller' => 'personnes_referents', 'action' => 'filelink',
								$personne_referent['PersonneReferent']['id'] ),
								array( 'enabled' => $permissions->check( 'personnes_referents', 'filelink' ) )
							),
							h( '('.$nbFichiersLies.')' )
						),
						array( 'class' => 'odd' ),
						array( 'class' => 'even' )
					);
				}
			?>
		</tbody>
	</table>
	<?php  endif;?>

</div>
<div class="clearer"><hr /></div>