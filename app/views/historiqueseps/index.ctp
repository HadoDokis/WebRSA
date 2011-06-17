<?php  echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id) );?>

<div class="with_treemenu">
	<h1><?php echo $this->pageTitle = 'Historique des passages en EP';?></h1>
	<?php
		echo $default2->search(
			array(
				'Dossierep.themeep' => array( 'domain' => 'historiqueep' )
			),
			array(
				'options' => $options
			)
		);

		if( empty( $passages ) ) {
			echo '<p class="notice">'.__d( 'historiqueep', 'Historiqueep::index::empty', true ).'</p>';
		}
		else {
			$pagination = $xpaginator2->paginationBlock( 'Passagecommissionep', Set::merge( $this->params['pass'], $this->params['named'] ) );

			echo $pagination;

			echo '<table class="default2"><thead>';
			echo str_replace(
				'</tr>',
				'<th colspan="2">Actions</th></tr>',
				$xhtml->tableHeaders(
					array(
						$xpaginator2->sort( __d( 'ep', 'Ep.identifiant', true ), 'Commissionep.Ep.identifiant' ),
						$xpaginator2->sort( __d( 'commissionep', 'Commissionep.identifiant', true ), 'Commissionep.identifiant' ),
						$xpaginator2->sort( __d( 'commissionep', 'Commissionep.dateseance', true ), 'Commissionep.dateseance' ),
						$xpaginator2->sort( __d( 'passagecommissionep', 'Passagecommissionep.etatdossierep', true ), 'Passagecommissionep.etatdossierep' ),
						$xpaginator2->sort( __d( 'dossierep', 'Dossierep.themeep', true ), 'Dossierep.themeep' ),
						$xpaginator2->sort( __d( 'dossierep', 'Dossierep.created', true ), 'Dossierep.created' ),
					)
				)
			);
			echo '</thead><tbody>';
			foreach( $passages as $passsage ) {
				echo $xhtml->tableCells(
					array(
						$type2->format( $passsage, 'Commissionep.Ep.identifiant' ),
						$type2->format( $passsage, 'Commissionep.identifiant' ),
						$type2->format( $passsage, 'Commissionep.dateseance' ),
						$type2->format( $passsage, 'Passagecommissionep.etatdossierep', array( 'options' => $options ) ),
						$type2->format( $passsage, 'Dossierep.themeep', array( 'options' => $options ) ),
						$type2->format( $passsage, 'Dossierep.created' ),
						$xhtml->link( 'Passage', array( 'controller' => 'historiqueseps', 'action' => 'view_passage', $passsage['Passagecommissionep']['id'] ), array( 'class' => 'button view', 'enabled' => $permissions->check( 'historiqueseps', 'view_passage' ) ) ),
						// FIXME
// 						$xhtml->link( 'Dossier', array( 'controller' => 'historiqueseps', 'action' => 'view_dossier', $passsage['Dossierep']['id'] ), array( 'class' => 'button view', 'enabled' => $permissions->check( 'historiqueseps', 'view_dossier' ) ) ),
						$xhtml->link( 'Commission', array( 'controller' => 'commissionseps', 'action' => 'decisioncg', $passsage['Commissionep']['id'] ), array( 'class' => 'button view', 'enabled' => $permissions->check( 'commissionseps', 'decisioncg' ) ) ),
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