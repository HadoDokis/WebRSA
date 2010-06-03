<?php
    echo $html->tag(
        'h1',
        $this->pageTitle = __d( 'parcoursdetecte', "Parcoursdetectes::{$this->action}", true )
    );

	echo $default->index(
		$parcoursdetectes,
		array(
			'Orientstruct.Personne.nom_complet',
			'Parcoursdetecte.created',
			'Parcoursdetecte.datetransref',
			'Parcoursdetecte.signale' => array( 'input' => 'checkbox' ),
			'Parcoursdetecte.commentaire' => array( 'input' => 'text' ),
// 			'Parcoursdetecte.ep_id' => array( 'input' => 'select' ),
		),
		array(
			'actions' => array(
				'Orientstruct.view' => array( 'controller' => 'orientsstructs', 'action' => 'edit' )
			),
			'options' => $options,
			/*'groupColumns' => array(
				'Nom a et nom b' => array( 2, 3 ),
				'Item.version' => array( 4, 5 ),
				'Item.modifiable' => array( 6, 7 ),
			),*/
			/// FIXME: indexActions
			/*'tooltip' => array(
				'Item.description_a',
				'Item.description_b',
				'Item.date_a',
				'Item.date_b',
				'Item.category_id',
				'Item.foo',
				'Item.bar',
				'Item.tel',
				'Item.fax',
				'Item.montant'
			),*/
			'cohorte' => true,
			'hidden' => array(
				'Parcoursdetecte.id'
			)
		)
	);

    echo $default->button(
        'back',
        array(
            'controller' => 'eps',
            'action'     => 'liste',
            'Search__active' => 1
        ),
        array(
            'id' => 'Back'
        )
    );

?>