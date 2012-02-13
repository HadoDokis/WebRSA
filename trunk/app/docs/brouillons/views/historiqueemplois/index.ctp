<?php
	$this->pageTitle = __d( 'historiqueemploi', 'Historiqueemplois::index', true );
	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>
<div class="with_treemenu">
	<h1><?php echo $this->pageTitle;?></h1>
	<ul class="actions">
		<li class="add"><?php echo $xhtml->link( 'Ajouter', array( 'action' => 'add', $personne_id ), array( 'class' => 'button add', 'enabled' => $permissions->check( 'historiqueemplois', 'add' ) ) );?></li>
	</ul>
	<?php
		if( empty( $historiqueemplois ) ) {
			echo '<p class="notice">'.__d( 'historiqueemploi', 'Historiqueemplois::index::empty', true ).'</p>';
		}
		else {
			$pagination = $xpaginator2->paginationBlock( 'Historiqueemploi', /*Set::merge( $this->params['pass'], */$this->params['named']/* )*/ );

			echo $pagination;

			echo '<table class="default2"><thead>';
			echo str_replace(
				'</tr>',
				'<th colspan="2">Actions</th></tr>',
				$xhtml->tableHeaders(
					array(
						$xpaginator2->sort( __d( 'historiqueemploi', 'Historiqueemploi.datedebut', true ), 'Historiqueemploi.datedebut' ),
						$xpaginator2->sort( __d( 'historiqueemploi', 'Historiqueemploi.datefin', true ), 'Historiqueemploi.datefin' ),
						$xpaginator2->sort( __d( 'historiqueemploi', 'Historiqueemploi.secteuractivite', true ), 'Historiqueemploi.secteuractivite' ),
						$xpaginator2->sort( __d( 'historiqueemploi', 'Historiqueemploi.emploi', true ), 'Historiqueemploi.emploi' ),
						$xpaginator2->sort( __d( 'historiqueemploi', 'Historiqueemploi.dureehebdomadaire', true ), 'Historiqueemploi.dureehebdomadaire' ),
						$xpaginator2->sort( __d( 'historiqueemploi', 'Historiqueemploi.naturecontrat', true ), 'Historiqueemploi.naturecontrat' ),
						$xpaginator2->sort( __d( 'historiqueemploi', 'Historiqueemploi.dureecdd', true ), 'Historiqueemploi.dureecdd' ),
					)
				)
			);
			echo '</thead><tbody>';

			foreach( $historiqueemplois as $historiqueemploi ) {
				echo $xhtml->tableCells(
					array(
						$type2->format( $historiqueemploi, 'Historiqueemploi.datedebut' ),
						$type2->format( $historiqueemploi, 'Historiqueemploi.datefin' ),
						$type2->format( $historiqueemploi, 'Historiqueemploi.secteuractivite', array( 'options' => $options ) ),
						$type2->format( $historiqueemploi, 'Historiqueemploi.emploi', array( 'options' => $options ) ),
						$type2->format( $historiqueemploi, 'Historiqueemploi.dureehebdomadaire' ),
						$type2->format( $historiqueemploi, 'Historiqueemploi.naturecontrat', array( 'options' => $options ) ),
						$type2->format( $historiqueemploi, 'Historiqueemploi.dureecdd', array( 'options' => $options ) ),
						$xhtml->link( 'Modifier', array( 'action' => 'edit', $historiqueemploi['Historiqueemploi']['id'] ), array( 'class' => 'button edit', 'enabled' => $permissions->check( 'historiqueemplois', 'edit' ) ) ),
						$xhtml->link( 'Supprimer', array( 'action' => 'delete', $historiqueemploi['Historiqueemploi']['id'] ), array( 'class' => 'button delete', 'enabled' => $permissions->check( 'historiqueemplois', 'delete' ) ), sprintf( 'Voulez-vous vraiment supprimer ce métier exercé ?' ) ),
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
			}

			echo '</tbody></table>';
			echo $pagination;
		}
	?>
</div>
<div class="clearer"><hr /></div>