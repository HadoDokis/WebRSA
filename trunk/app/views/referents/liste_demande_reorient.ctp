<?php
    echo $xhtml->tag(
        'h1',
        $this->pageTitle = __d( 'referent', "Referents::{$this->action}", true )
    )
?>
<?php
    if( is_array( $this->data ) ) {
        echo '<ul class="actionMenu"><li>'.$xhtml->link(
            $xhtml->image(
                'icons/application_form_magnify.png',
                array( 'alt' => '' )
            ).' Formulaire',
            '#',
            array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Search' ).toggle(); return false;" )
        ).'</li></ul>';
    }

    echo $xform->create( 'Referents', array( 'type' => 'post', 'action' => '/liste_demande_reorient/', 'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) ) ? 'folded' : 'unfolded' ) ) );

    ///Formulaire de recherche de référents
    echo $default->search(
        array(
            'Referent.id' => array( 'options' => $referent )
        ),
        array(
            'options' => $options
        )
    );

    echo $xform->end();
?>

<?php if( isset( $referents ) ): ?>
    <h2 class="noprint">Résultats de la recherche</h2>
        <?php if( is_array( $referents ) && count( $referents ) > 0 ):?>
        <?php
            echo $default->index(
                $referents,
                array(
                    'Referent.qual' => array( 'options' => $qual ),
                    'Referent.nom',
                    'Referent.prenom',
                    'Referent.fonction',
                    'Referent.numero_poste',
                    'Referent.email'
                ),
                array(
                    'actions' => array(
                        'Referent.liste_demande_reorient' => array( 'controller' => 'referents', 'action' => 'demandes_reorient' )
                    )
                )
            );


        ?>
        <?php else:?>
            <p>Vos critères n'ont retourné aucune demande.</p>
        <?php endif?>
<?php endif?>