<?php  $this->pageTitle = 'Orientation de la personne';?>
<?php  echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id) );?>


<div class="with_treemenu">
	<h1>Orientation</h1>

	<?php if( empty( $orientstructs ) ):?>
		<p class="notice">Cette personne ne possède pas encore d'orientation.</p>
	<?php endif;?>

	<?php if( $permissions->check( 'orientsstructs', 'add' ) ):?>
		<ul class="actionMenu">
			<?php
				echo '<li>'.$xhtml->addLink(
					'Préconiser une orientation',
					array( 'controller' => 'orientsstructs', 'action' => 'add', $personne_id )
				).' </li>';
			?>
		</ul>
	<?php endif;?>

	<?php if( !empty( $orientstructs ) ):?>
	<table class="tooltips">
		<thead>
			<tr>
				<th>Nom</th>
				<th>Prénom</th>
				<th>Date de la demande</th>
				<th>Date d'orientation</th>
				<th>Préconisation d'orientation</th>
				<th>Structure référente</th>
				<?php if( Configure::read( 'nom_form_ci_cg' ) == 'cg58' ):?><th>Etat de l'orientation</th><?php endif;?>
				<th colspan="3" class="action">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php

				foreach( $orientstructs as $i => $orientstruct ) {

					$isOrient = false;
					if( isset( $orientstruct['Orientstruct']['date_propo'] ) ){
						$isOrient = true;
					}

					$cells = array(
						h( $orientstruct['Personne']['nom']),
						h( $orientstruct['Personne']['prenom'] ),
						h( date_short( $orientstruct['Orientstruct']['date_propo'] ) ),
						h( date_short( $orientstruct['Orientstruct']['date_valid'] ) ),
						h( Set::classicExtract( $orientstruct, 'Typeorient.lib_type_orient' ) ) ,
						h( $orientstruct['Structurereferente']['lib_struc']  ),
					);

					if( Configure::read( 'nom_form_ci_cg' ) == 'cg58' ) {
						$cells[] = h( Set::enum( $orientstruct['Orientstruct']['etatorient'], $options['etatorient'] ) ) ;
					}

					$saisineTexte = ( ( Configure::read( 'Ep.departement') == 93 ) ? 'Saisine EP' : 'Bilan de parcours' );
					$lienSaisineEp = $xhtml->saisineEpLink(
						$saisineTexte,
						array(
							'controller' => Inflector::tableize( Configure::read( 'Ep.declencheursaisine' ) ),
							'action' => 'add',
							( ( Configure::read( 'Ep.departement') == 93 ) ? $orientstruct['Orientstruct']['id'] : $orientstruct['Orientstruct']['personne_id'] )
						),
						// FIXME; droits + pas de saisine si non terminée ?
						(
							( $i == 0 ) // La dernière orientation en date
							&& !empty( $orientstruct['Orientstruct']['structurereferente_id'] )
							&& $orientstruct['Personne']['Calculdroitrsa']['toppersdrodevorsa']
						)
					);

					array_push(
						$cells,
						$xhtml->editLink(
							'Editer l\'orientation',
							array( 'controller' => 'orientsstructs', 'action' => 'edit', $orientstruct['Orientstruct']['id'] ),
							$permissions->check( 'orientsstructs', 'edit' )
						),
						$xhtml->printLink(
							'Imprimer la notification',
							array( 'controller' => 'gedooos', 'action' => 'orientstruct', $orientstruct['Orientstruct']['id'] ),
							$permissions->check( 'gedooos', 'orientstruct' ) && $orientstruct['Orientstruct']['imprime']
						),
						$lienSaisineEp
					);

					echo $xhtml->tableCells( $cells, array( 'class' => 'odd' ), array( 'class' => 'even' ) );
				}
			?>
		</tbody>
	</table>

	<?php
		/*echo $default2->index(
			$orientstructs,
			array(
				'Personne.nom',
				'Personne.prenom',
				'Orientstruct.date_propo',
				'Orientstruct.date_valid',
				'Typeorient.lib_type_orient',
				'Structurereferente.lib_struc'
			),
			array(
				'actions' => array(
					'Orientsstructs::edit',
					'Orientsstructs::print' => array(
						'url' => array( 'controller' => 'gedooos', 'action' => 'orientstruct', '#Orientstruct.id#' )
					),
					'Orientsstructs::saisine' => array(
						'url' => array( 'controller' => Inflector::tableize( Configure::read( 'Ep.declencheursaisine' ) ), 'action' => 'add', '#Orientstruct.id#' ),
						'disabled' => 'empty( "#Orientstruct.structurereferente_id#" ) || empty( "#Personne.Calculdroitrsa.toppersdrodevorsa#" )'
					),
				)
			)
		);
		debug( $orientstructs );*/
	?>
	<?php  endif;?>
</div>
<div class="clearer"><hr /></div>