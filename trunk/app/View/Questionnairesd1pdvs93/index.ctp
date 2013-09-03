<?php
	echo $this->Default3->titleForLayout( $personne );

	echo $this->Default3->actions(
		array(
			"/Questionnairesd1pdvs93/add/{$personne_id}" => array(
				'disabled' => !$this->Permissions->check( 'Questionnairesd1pdvs93', 'add' )
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
            $dateModif = null;

            foreach( $historiquesdroit as $key => $histo ) {
                if( !empty( $histo ) ) {
                    $listeEtat = value( $options['Situationallocataire']['etatdosrsa'], $histo['Historiquedroit']['etatdosrsa'] );
                    @$listeSoumis = value( $options['Situationallocataire']['toppersdrodevorsa'], $histo['Historiquedroit']['toppersdrodevorsa'] );
                    $dateModif = $histo['Historiquedroit']['modified'];

                    echo $this->Xhtml->tableCells(
						array(
                            h( $listeEtat ),
                            h( @$listeSoumis ),
                            h( $this->Locale->date( 'Datetime::full', $dateModif ) )
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
	if( !empty( $messages ) ) {
		foreach( $messages as $message => $class ) {
			echo $this->Html->tag( 'p', __d( 'questionnairesd1pdvs93', $message ), array( 'class' => "message {$class}" ) );
		}
	}

	echo $this->Default2->index(
		$questionnairesd1pdvs93,
		array(
			'Rendezvous.daterdv',
			'Statutrdv.libelle',
			'Questionnaired1pdv93.date_validation' => array( 'domain' => 'questionnairesd1pdvs93' ),
            'Historiquedroit.etatdosrsa' => array( 'domain' => 'historiquedroit' ),
            'Historiquedroit.toppersdrodevorsa' => array( 'domain' => 'historiquedroit' ),
            'Historiquedroit.modified' => array(  'domain' => 'historiquedroit' )
		),
        array(
            'actions' => array(
                'Questionnairesd1pdvs93::view' => array(
                    'disabled' => !$this->Permissions->check( 'Questionnairesd1pdvs93', 'view' )
                ),
                'Questionnairesd1pdvs93::delete' => array(
                    'confirm' => true,
                    'disabled' => !$this->Permissions->check( 'Questionnairesd1pdvs93', 'delete' )
                )
            ),
            'options' => $options
        )
	);
?>