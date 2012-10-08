<?php
	$this->pageTitle = '1. Affectation d\'un référent - référents déjà affectés';
	echo $html->tag( 'h1', $this->pageTitle );

	require_once( dirname( __FILE__ ).DS.'filtre.ctp' );

	if( isset( $personnes_referents ) ) {
		if( empty( $personnes_referents ) ) {
			echo $html->tag( 'p', 'Aucun résultat', array( 'class' => 'notice' ) );
		}
		else {
			$pagination = $xpaginator->paginationBlock( 'Personne', $this->passedArgs );
			echo $pagination;

			echo '<table>';
			echo '<thead>
					<tr>
						<th>Commune</th>
						<th>Date de demande</th>
						<th>Date d\'orientation</th>
						<th>Date de naissance</th>
						<th>Soumis à droits et devoirs</th>
						<th>Présence d\'une DSP</th>
						<th>Rang CER</th>
						<th>Nom, prénom</th>
						<th>Date d\'affectation</th>
						<th>Affectation</th>
						<th>Détails</th>
					</tr>
				</thead>';
			echo '<tbody>';
			foreach( $personnes_referents as $index => $personne_referent ) {
				echo $html->tableCells(
					array(
						$personne_referent['Adresse']['locaadr'],
						date_short( $personne_referent['Dossier']['dtdemrsa'] ),
						date_short( $personne_referent['Orientstruct']['date_valid'] ),
						date_short( $personne_referent['Personne']['dtnai'] ),
						$xhtml->boolean( $personne_referent['Calculdroitrsa']['toppersdrodevorsa'] ),
						$xhtml->boolean( $personne_referent['Dsp']['exists'] ),
						$personne_referent['Contratinsertion']['rg_ci'],
						$personne_referent['Personne']['nom_complet_court'],
						date_short( $personne_referent['PersonneReferent']['dddesignation'] ),
						$personne_referent['Referent']['nom_complet'],
						$xhtml->viewLink( 'Voir', array( 'controller' => 'personnes_referents', 'action' => 'index', $personne_referent['Personne']['id'] ) ),
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
			}
			echo '</tbody>';
			echo '</table>';

			echo $pagination;
		}
	}
?>
<?php if( isset( $personnes_referents ) ):?>
<ul class="actionMenu">
	<li><?php
		echo $xhtml->exportLink(
			'Télécharger le tableau',
			array( 'action' => 'exportcsv' ) + Set::flatten( $this->data, '__' )
		);
	?></li>
</ul>
<?php endif;?>