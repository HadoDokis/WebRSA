<?php
	echo $this->Default3->titleForLayout( $personne );

	echo $this->element( 'ancien_dossier' );

	echo $this->Default3->actions(
		array(
			"/Questionnairesd1pdvs93/add/{$personne_id}" => array(
				'disabled' => !$this->Permissions->checkDossier( 'Questionnairesd1pdvs93', 'add', $dossierMenu )
						|| !$add_enabled
			),
		)
	);
?>

<?php if( !empty( $historiquesdroit ) ):?>
   <caption>Historique du droit</caption>
   <table class="aere">
       <thead>
       <tr>
           <th>Etat(s) du dossier RSA</th>
           <th>Soumis à droit et devoir</th>
           <th>Modifié le</th>
       </tr>
       </thead>
       <tbody>
        <?php
            $listeEtat = null;
            $listeSoumis = null;
            $dateChangement = null;

            foreach( $historiquesdroit as $key => $histo ) {
                if( !empty( $histo ) ) {
                    $listeEtat = value( $options['Situationallocataire']['etatdosrsa'], $histo['Historiquedroit']['etatdosrsa'] );
                    @$listeSoumis = value( $options['Situationallocataire']['toppersdrodevorsa'], $histo['Historiquedroit']['toppersdrodevorsa'] );
                    $dateChangement = $histo['Historiquedroit']['created'];

                    echo $this->Xhtml->tableCells(
						array(
                            h( $listeEtat ),
                            h( @$listeSoumis ),
                            h( $this->Locale->date( 'Datetime::full', $dateChangement ) )
                        )
                    );
                }
            }
        ?>
        </tbody>
    </table>
    <?php else :?>
        <p class="notice">Aucun historique trouvé pour cet allocataire</p>
    <?php endif;?>

<?php
	// A-t'on des messages à afficher à l'utilisateur ?
	echo $this->Default3->messages( $messages );

	echo $this->Default2->index(
		$questionnairesd1pdvs93,
		array(
			'Structurereferente.lib_struc' => array( 'domain' => 'questionnairesd1pdvs93' ),
			'Rendezvous.daterdv',
			'Statutrdv.libelle',
			'Questionnaired1pdv93.date_validation' => array( 'domain' => 'questionnairesd1pdvs93' ),
            'Historiquedroit.etatdosrsa' => array( 'domain' => 'historiquedroit' ),
            'Historiquedroit.toppersdrodevorsa' => array( 'domain' => 'historiquedroit', 'type' => 'boolean' ),
            'Historiquedroit.modified' => array(  'domain' => 'historiquedroit' )
		),
        array(
            'actions' => array(
                'Questionnairesd1pdvs93::view' => array(
                    'disabled' => !$this->Permissions->check( 'Questionnairesd1pdvs93', 'view' )
                ),
                'Questionnairesd1pdvs93::delete' => array(
                    'confirm' => true,
                    'disabled' => '!'.WebrsaPermissions::checkD1D2(
						'#Structurereferente.id#',
						$this->Permissions->check( 'Questionnairesd1pdvs93', 'delete' ),
						true
					)
                )
            ),
            'options' => $options
        )
	);
?>