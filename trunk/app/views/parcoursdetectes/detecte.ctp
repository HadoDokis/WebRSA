<?php
    echo $html->tag(
        'h1',
        $this->pageTitle = __d( 'parcoursdetecte', "Parcoursdetectes::{$this->action}", true )
    );


    echo $html->tag(
        'p',
        'Nb de nouveaux parcours détectés : '.$compteur,
        array(
            'class' => 'notice'
        )
    );

?>
<?php if( !empty( $orientsstructs ) ):?>
    <table class="aere">
        <thead>
            <tr>
                <!--<th>Id de l'orientation</th>-->
                <th>Nom personne</th>
                <th>Type d'orientation</th>
                <th>Structure référente liée</th>
                <th>Date de validation</th>
                <th>Statut orientation</th>
                <th>Age</th>
                <th class="action">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
                    foreach( $orientsstructs as $i => $value ) {

                        echo $html->tableCells(
                            array(

//                                 h( Set::classicExtract( $value, '0.id' ) ),
				h( Set::enum( Set::classicExtract( $value, '0.qual' ), $qual ).' '.Set::classicExtract( $value, '0.nom' ).' '.Set::classicExtract( $value, '0.prenom' ) ),
                                h( Set::enum( Set::classicExtract( $value, '0.typeorient_id' ), $typeorient ) ),
                                h( Set::enum( Set::classicExtract( $value, '0.structurereferente_id' ), $struct )  ),
                                h( $locale->date( 'Date::short', Set::classicExtract( $value, '0.date_valid' ) ) ),
                                h( Set::classicExtract( $value, '0.statut_orient' ) ),
                                h( Set::classicExtract( $value, '0.age' ) ),
                                $html->link(
                                    'Voir',
                                    array( 'controller' => 'orientsstructs', 'action' => 'index', Set::classicExtract( $value, '0.id' ) )
                                ),
                            ),
                            array( 'class' => 'odd' ),
                            array( 'class' => 'even' )
                        );
                    }

            ?>
        </tbody>
    </table>
    <?php endif;?>
<?php
    echo $html->tag(
        'h2',
        'Parcours existants'
    );
    $nbParcours = Set::extract( $parcoursdetectes, '/Parcoursdetecte/id' );

    echo $html->tag(
        'p',
        'Nb de parcours présents : '.count( $nbParcours ),
        array(
            'class' => 'notice'
        )
    );
// debug($parcoursdetectes);
    echo $default->index(
        $parcoursdetectes,
        array(
            'Orientstruct.Personne.nom_complet',
            'Parcoursdetecte.signale',
            'Parcoursdetecte.commentaire',
            'Parcoursdetecte.created',
            'Parcoursdetecte.datetransref',
            'Ep.name'/* => array( 'input' => 'select' )*/,
//             'Parcoursdetecte.osnv_id' => array( 'input' => 'select' ),
        ),
        array(
            'actions' => array(
                'Parcoursdetecte.view' => array( 'controller' => 'parcoursdetectes', 'action' => 'index' )
            ),
            'options' => $options
        )
    );

    echo $default->button(
        'back',
        array(
            'controller' => 'parcoursdetectes',
            'action'     => 'index'
        ),
        array(
            'id' => 'Back'
        )
    );

?>