<?php
    $domain = 'criterecui';
    echo $html->tag(
        'h1',
        $this->pageTitle = __d( 'criterecui', "Criterescuis::{$this->action}", true )
    )
?>

<?php
    if( is_array( $this->data ) ) {
        echo '<ul class="actionMenu"><li>'.$html->link(
            $html->image(
                'icons/application_form_magnify.png',
                array( 'alt' => '' )
            ).' Formulaire',
            '#',
            array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Search' ).toggle(); return false;" )
        ).'</li></ul>';
    }

    echo $xform->create( 'Criterescuis', array( 'type' => 'post', 'action' => '/index/', 'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) ) ? 'folded' : 'unfolded' ) ) );

    ///Formulaire de recherche pour les CUIs
    echo $default->search(
        array(
            'Cui.denomination' => array( 'type' => 'text' ),
            'Cui.date' => array( 'type' => 'date', 'dateFormat' => 'DMY', 'minYear' => date( 'Y' ) - 1, 'maxYear' => date( 'Y' ) + 1 ),
            'Cui.secteur' => array( 'legend' => required( __d( 'cui', 'Cui.secteur', true )  ), 'type' => 'radio', 'options' => $options['secteur'] )
        ),
        array(
            'options' => $options
        )
    );

    echo $xform->end();
?>
<?php $pagination = $xpaginator->paginationBlock( 'Cui', $this->passedArgs ); ?>

    <?php if( isset( $criterescuis ) ):?>
    <br />
    <h2 class="noprint aere">Résultats de la recherche</h2>

    <?php if( is_array( $criterescuis ) && count( $criterescuis ) > 0  ):?>
        <?php echo $pagination;?>
        <table class="tooltips">
            <thead>
                <tr>
                    <th>Nom demandeur</th>
                    <th>Secteur</th>
                    <th>Date du contrat</th>
                    <th>Dénomination</th>
                    <th colspan="4" class="action">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach( $criterescuis as $criterecui ) {
// debug($criterecui);
                        echo $html->tableCells(
                            array(
                                h( Set::enum( Set::classicExtract( $criterecui, 'Personne.qual' ), $qual ).' '.Set::classicExtract( $criterecui, 'Personne.nom' ).' '.Set::classicExtract( $criterecui, 'Personne.prenom' ) ),
                                h( Set::classicExtract( $criterecui, 'Cui.secteur' ) ),
                                h( $locale->date( 'Locale->date',  Set::classicExtract( $criterecui, 'Cui.datecontrat' ) ) ),
                                h( Set::classicExtract( $criterecui, 'Cui.nomemployeur' ) ),
                                $html->viewLink(
                                    'Voir',
                                    array( 'controller' => 'cuis', 'action' => 'index', Set::classicExtract( $criterecui, 'Cui.personne_id' ) )
                                )
                            ),
                            array( 'class' => 'odd' ),
                            array( 'class' => 'even' )
                        );
                    }
                ?>
            </tbody>
        </table>
        <?php echo $pagination;?>
        <ul class="actionMenu">
            <li><?php
                echo $html->printLinkJs(
                    'Imprimer le tableau',
                    array( 'onclick' => 'printit(); return false;', 'class' => 'noprint' )
                );
            ?></li>
            <li><?php
                echo $html->exportLink(
                    'Télécharger le tableau',
                    array( 'controller' => 'criterescuis', 'action' => 'exportcsv', implode_assoc( '/', ':', array_unisize( $this->data ) ) )
                );
            ?></li>
        </ul>
    <?php else:?>
        <p>Vos critères n'ont retourné aucun contrat unique d'insertion.</p>
    <?php endif?>
<?php endif?>

<!-- *********************************************************************** -->