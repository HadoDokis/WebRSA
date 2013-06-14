<h1><?php echo $this->pageTitle = 'Historique des passages en EP';?></h1>
<?php
	echo $this->Default2->search(
		array(
			'Dossierep.themeep' => array( 'domain' => 'historiqueep' )
		),
		array(
			'options' => $options
		)
	);

	if( empty( $passages ) ) {
		echo '<p class="notice">'.__d( 'historiqueep', 'Historiqueep::index::empty' ).'</p>';
	}
	else {
		$pagination = $this->Xpaginator2->paginationBlock( 'Passagecommissionep', Set::merge( $this->request->params['pass'], $this->request->params['named'] ) );

		echo $pagination;

		echo '<table class="default2"><thead>';
		echo str_replace(
			'</tr>',
			'<th colspan="2">Actions</th></tr>',
			$this->Xhtml->tableHeaders(
				array(
					$this->Xpaginator2->sort( __d( 'ep', 'Ep.identifiant' ), 'Commissionep.Ep.identifiant' ),
					$this->Xpaginator2->sort( __d( 'commissionep', 'Commissionep.identifiant' ), 'Commissionep.identifiant' ),
					$this->Xpaginator2->sort( __d( 'commissionep', 'Commissionep.dateseance' ), 'Commissionep.dateseance' ),
					$this->Xpaginator2->sort( __d( 'passagecommissionep', 'Passagecommissionep.etatdossierep' ), 'Passagecommissionep.etatdossierep' ),
					$this->Xpaginator2->sort( __d( 'dossierep', 'Dossierep.themeep' ), 'Dossierep.themeep' ),
					$this->Xpaginator2->sort( __d( 'dossierep', 'Dossierep.created' ), 'Dossierep.created' ),
				)
			)
		);

		$actionDecisionsCommission = 'decisioncg';
		if( Configure::read( 'Cg.departement' ) == 58 ) {
			$actionDecisionsCommission = 'decisionep';
		}

		echo '</thead><tbody>';
		foreach( $passages as $passsage ) {
			echo $this->Xhtml->tableCells(
				array(
					$this->Type2->format( $passsage, 'Commissionep.Ep.identifiant' ),
					$this->Type2->format( $passsage, 'Commissionep.identifiant' ),
					$this->Type2->format( $passsage, 'Commissionep.dateseance' ),
					$this->Type2->format( $passsage, 'Passagecommissionep.etatdossierep', array( 'options' => $options ) ),
					$this->Type2->format( $passsage, 'Dossierep.themeep', array( 'options' => $options ) ),
					$this->Type2->format( $passsage, 'Dossierep.created' ),
					$this->Xhtml->link( 'Passage', array( 'controller' => 'historiqueseps', 'action' => 'view_passage', $passsage['Passagecommissionep']['id'] ), array( 'class' => 'button view', 'enabled' => $this->Permissions->checkDossier( 'historiqueseps', 'view_passage', $dossierMenu ) ) ),
					$this->Xhtml->link( 'Commission', array( 'controller' => 'commissionseps', 'action' => $actionDecisionsCommission, $passsage['Commissionep']['id'] ), array( 'class' => 'button view', 'enabled' => $this->Permissions->check( 'commissionseps', $actionDecisionsCommission ) ) ),
				),
				array( 'class' => 'odd' ),
				array( 'class' => 'even' )
			);
		}
		echo '</tbody></table>';

		echo $pagination;
	}
?>