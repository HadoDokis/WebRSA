<?php $this->pageTitle = 'Préconisation d\'orientation';?>
<?php echo $this->element( 'dossier_menu', array( 'id' => $details['Dossier']['id'] ) );?>

<div class="with_treemenu">

<h1>Préconisation d'orientation</h1>

<table class="tooltips">
	<thead>
		<tr>
			<th>Nom</th>
			<th>Prénom</th>
			<th>Date de la demande</th>
			<th>Date d'orientation</th>
			<th>Statut de l'orientation</th>
			<th>Préconisation d'orientation</th>
			<th>Structure référente</th>
			<th colspan="2" class="action">Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php
			foreach( $personnes as $personne ) {
				echo $xhtml->tableCells(
					array(
						h( $personne['Personne']['nom'] ),
						h( $personne['Personne']['prenom'] ),
						h( $locale->date( 'Date::short', $details['Dossier']['dtdemrsa'] ) ),
						h( $locale->date( 'Date::short', $personne['Orientstruct']['date_valid'] ) ),
						h( $personne['Orientstruct']['statut_orient'] ),
						h( Set::enum( Set::classicExtract( $personne, 'Structurereferente.typeorient_id' ), $typeorient ) ) ,
						h( Set::classicExtract( $personne, 'Structurereferente.lib_struc' )  ),
						$xhtml->editLink(
							'Editer l\'orientation',
							array( 'controller' => 'dossierssimplifies', 'action' => 'edit', $personne['Personne']['id'] )
						),
						$xhtml->printLink(
							'Imprimer la notification',
							array( 'controller' => 'orientsstructs', 'action' => 'impression', $personne['Orientstruct']['id'] ),
							!empty( $personne['Orientstruct']['typeorient_id'] )
						),
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
			}
		?>
	</tbody>
</table>
</div>
<div class="clearer"><hr /></div>