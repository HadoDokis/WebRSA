<?php
    $this->pageTitle = 'Impression des APREs pour l\'état liquidatif';

    echo $xhtml->tag( 'h1', $this->pageTitle );

    ///Fin pagination


    if( empty( $apres ) ) {
        echo $xhtml->tag( 'p', 'Aucune APRE à sélectionner.', array( 'class' => 'notice' ) );
    }
    else {
        $paginator->options( array( 'url' => $this->passedArgs ) );
        $params = array( 'format' => 'Résultats %start% - %end% sur un total de %count%.' );
        $pagination = $xhtml->tag( 'p', $paginator->counter( $params ) );

        $pages = $paginator->first( '<<' );
        $pages .= $paginator->prev( '<' );
        $pages .= ' '.$paginator->numbers().' ';
        $pages .= $paginator->next( '>' );
        $pages .= $paginator->last( '>>' );

        $pagination .= $xhtml->tag( 'p', $pages );

        $headers = array(
            $paginator->sort( 'N° Dossier', 'Dossier.numdemrsa' ),
            $paginator->sort( 'N° APRE', 'Apre.numeroapre' ),
            $paginator->sort( 'Date de demande APRE', 'Apre.datedemandeapre' ),
            $paginator->sort( 'Montant forfaitaire', 'Apre.mtforfait' ),
            $paginator->sort( 'Nb enfant - 12ans', 'Apre.nbenf12' ),
            $paginator->sort( 'Nom bénéficiaire', 'Personne.nom' ),
            $paginator->sort( 'Prénom bénéficiaire', 'Personne.prenom' ),
            $paginator->sort( 'Adresse', 'Adresse.locaadr' ),
            'Formation',
            'Bénéficiaire',
            'Tiers prestataire',

        );

        ///
        $thead = $xhtml->tag( 'thead', $xhtml->tableHeaders( $headers ) );

        echo $xform->create( 'Etatliquidatif' );
        // FIXME
        echo '<div>'.$xform->input( 'Etatliquidatif.id', array( 'type' => 'hidden', 'value' => $this->params['pass'][0] ) ).'</div>';

        /// Corps du tableau
        $rows = array();

        foreach( $apres as $i => $apre ) {
            if( $typeapre == 'F' ) {
                $apre['Apre']['allocation'] = $apre['Apre']['mtforfait'];
                $isTiers = false;
                $libelleNatureaide = null;
                $dest = null;
            }
            else if( $typeapre == 'C' ) {

                $apre['Apre']['allocation'] = $apre['ApreEtatliquidatif']['montantattribue'];
                $aidesApre = array();
                $isTiers = false;
                $modelsFormation = array( 'Formqualif', 'Formpermfimo', 'Permisb', 'Actprof' );
                $modelLie = Set::classicExtract( $apre, 'Apre.Natureaide' );
                foreach( $modelLie as $natureaide => $nombre ) {
                    if( $nombre > 0 ) {
                        $aidesApre = $natureaide;
                        if( in_array( $natureaide, $modelsFormation ) ){
                            $dest = 'tiersprestataire';
                            $isTiers = true;
                            $tmpNatureaide = array_flip( Set::classicExtract( $apre, 'Apre.Natureaide' ) );
                            if( isset( $tmpNatureaide['1'] ) ) {
                                $libelleNatureaide = __d( 'apre', $tmpNatureaide['1'], true ); // FIXME: traduction
                            }
                        }
                        else{
                            $dest = 'beneficiaire';
                            $isTiers = false;
                            $libelleNatureaide = 'Hors formation';
                        }
                    }
                }
            }
            else {
                $this->cakeError( 'error500' );
            }
////////////////////////////////////////////////

// 		}
////////////////////////////////////////////////
            $apre_id = Set::classicExtract( $apre, 'Apre.id' );
            $rows[] = array(
                Set::classicExtract( $apre, 'Dossier.numdemrsa' ),
                Set::classicExtract( $apre, 'Apre.numeroapre' ),
                $locale->date( 'Date::short', Set::classicExtract( $apre, 'Apre.datedemandeapre' ) ),
                $locale->money( Set::classicExtract( $apre, 'Apre.allocation' ) ),
                Set::classicExtract( $apre, 'Apre.nbenf12' ),
                Set::classicExtract( $apre, 'Personne.nom' ),
                Set::classicExtract( $apre, 'Personne.prenom' ),
                Set::classicExtract( $apre, 'Adresse.locaadr' ),
                $libelleNatureaide,
                $theme->button( 'print', array( 'controller' => 'etatsliquidatifs', 'action' => 'impressiongedoooapres', Set::classicExtract( $apre, 'Apre.id' ), $this->params['pass'][0], 'dest' => 'beneficiaire' ) /*array( 'enabled' =>  !$isTiers )*/ ),
                $theme->button( 'print', array( 'controller' => 'etatsliquidatifs', 'action' => 'impressiongedoooapres', Set::classicExtract( $apre, 'Apre.id' ), $this->params['pass'][0], 'dest' => 'tiersprestataire' ), array( 'enabled' =>  $isTiers ) ),
            );
        }
        $tbody = $xhtml->tag( 'tbody', $xhtml->tableCells( $rows, array( 'class' => 'odd' ), array( 'class' => 'even' ) ) );


        echo $pagination;
        echo $xhtml->tag( 'table', $thead.$tbody );
        echo $pagination;

        echo $xform->end();
    }
?>
<?php if( $typeapre == 'F' ) :?>


<ul class="actionMenu">
    <li><?php
        echo $xhtml->printCohorteLink(
            'Imprimer la cohorte',
            Set::merge(
                array(
                    'controller' => 'etatsliquidatifs',
                    'action'     => 'impressioncohorte',
                    $this->params['pass'][0],
                    'page' => $this->params['paging']['Apre']['page']
                ),
                array_unisize( $this->data )
            )
        );
    ?></li>
</ul>
<?php endif;?>