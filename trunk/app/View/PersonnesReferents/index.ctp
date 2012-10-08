<?php  $this->pageTitle = 'Référents liés à la personne';?>
<?php  echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id) );?>

<div class="with_treemenu">
	<h1>Référents</h1>
		<?php if( empty( $personnes_referents ) ):?>
			<p class="notice">Cette personne ne possède pas encore de référents.</p>
			<ul class="actionMenu">
				<?php
					echo '<li>'.$this->Xhtml->addLink(
						'Ajouter un Référent',
						array( 'controller' => 'personnes_referents', 'action' => 'add', $personne_id ),
						$this->Permissions->check( 'personnes_referents', 'add' )
					).' </li>';
				?>
			</ul>
		<?php endif;?>

		<?php if( !empty( $personnes_referents ) ):?>
		<ul class="actionMenu">
			<?php
				echo '<li>'.$this->Xhtml->addLink(
					'Ajouter un Référent',
					array(
						'controller' => 'personnes_referents',
						'action' => 'add',
						$personne_id
					),
					( $ajoutPossible && $this->Permissions->check( 'personnes_referents', 'add' ) )
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
					$cloture = ( !empty( $personne_referent['PersonneReferent']['dfdesignation'] ) );

					echo $this->Xhtml->tableCells(
						array(
							h( Set::classicExtract( $personne_referent, 'Referent.qual' ).' '.Set::classicExtract( $personne_referent, 'Referent.nom' ).' '.Set::classicExtract( $personne_referent, 'Referent.prenom' ) ),
							h( Set::classicExtract( $personne_referent, 'Referent.fonction' ) ),
							h( Set::classicExtract( $personne_referent, 'Referent.numero_poste' ) ),
							h( Set::classicExtract( $personne_referent, 'Referent.email' ) ),
							h( Set::extract( $personne_referent, 'Structurereferente.lib_struc' ) ),
							h( $this->Locale->date( 'Date::short', Set::classicExtract( $personne_referent, 'PersonneReferent.dddesignation' ) ) ),
							h( $this->Locale->date( 'Date::short', Set::classicExtract( $personne_referent, 'PersonneReferent.dfdesignation' ) ) ),
							$this->Default2->button(
								'edit',
								array( 'controller' => 'personnes_referents', 'action' => 'edit',
								$personne_referent['PersonneReferent']['id'] ),
								array( 'enabled' => ( !$cloture && $this->Permissions->check( 'personnes_referents', 'edit' ) ) )
							),
							$this->Default2->button(
								'cloture_referent',
								array( 'controller' => 'personnes_referents', 'action' => 'cloturer',
								$personne_referent['PersonneReferent']['id'] ),
								array( 'enabled' => ( !$cloture && $this->Permissions->check( 'personnes_referents', 'cloturer' ) ) )
							),
							$this->Default2->button(
								'filelink',
								array( 'controller' => 'personnes_referents', 'action' => 'filelink',
								$personne_referent['PersonneReferent']['id'] ),
								array( 'enabled' => $this->Permissions->check( 'personnes_referents', 'filelink' ) )
							),
							h( "({$personne_referent['Fichiermodule']['nombre']})" )
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