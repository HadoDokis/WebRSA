<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Recherche par Rendez-vous';?>

<h1>Recherche par Rendez-vous</h1>

<?php
    function value( $array, $index ) {
        $keys = array_keys( $array );
        $index = ( ( $index == null ) ? '' : $index );
        if( @in_array( $index, $keys ) && isset( $array[$index] ) ) {
            return $array[$index];
        }
        else {
            return null;
        }
    }
?>
<?php
//     if( isset( $rdvs ) ) {
//         $xpaginator->options( array( 'url' => $this->passedArgs ) );
//         $params = array( 'format' => 'Résultats %start% - %end% sur un total de %count%.' );
//         $pagination = $xhtml->tag( 'p', $xpaginator->counter( $params ) );
// 
//         $pages = $xpaginator->first( '<<' );
//         $pages .= $xpaginator->prev( '<' );
//         $pages .= $xpaginator->numbers();
//         $pages .= $xpaginator->next( '>' );
//         $pages .= $xpaginator->last( '>>' );
// 
//         $pagination .= $xhtml->tag( 'p', $pages );
//     }
//     else {
//         $pagination = '';
//     }
    $pagination = $xpaginator->paginationBlock( 'Rendezvous', $this->passedArgs );
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

?>
<script type="text/javascript">
    document.observe("dom:loaded", function() {
        observeDisableFieldsetOnCheckbox( 'CritererdvDaterdv', $( 'CritererdvDaterdvFromDay' ).up( 'fieldset' ), false );
//         dependantSelect( 'CritererdvReferentId', 'CritererdvStructurereferenteId' );
    });
</script>

<?php echo $form->create( 'Critererdv', array( 'type' => 'post', 'action' => '/index/', 'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) ) ? 'folded' : 'unfolded' ) ) );?>
    <fieldset>
        <legend>Recherche par personne</legend>
        <?php echo $form->input( 'Critererdv.nom', array( 'label' => 'Nom ', 'type' => 'text' ) );?>
        <?php echo $form->input( 'Critererdv.prenom', array( 'label' => 'Prénom ', 'type' => 'text' ) );?>
        <?php echo $form->input( 'Critererdv.nir', array( 'label' => 'NIR ', 'maxlength' => 15 ) );?>
        <?php echo $form->input( 'Critererdv.matricule', array( 'label' => 'N° CAF ', 'maxlength' => 15 ) );?>
        <?php echo $form->input( 'Critererdv.natpf', array( 'label' => 'Nature de la prestation', 'type' => 'select', 'options' => $natpf, 'empty' => true ) );?>
        <?php
            $valueDossierDernier = isset( $this->data['Dossier']['dernier'] ) ? $this->data['Dossier']['dernier'] : true;
            echo $form->input( 'Dossier.dernier', array( 'label' => 'Uniquement la dernière demande RSA pour un même allocataire', 'type' => 'checkbox', 'checked' => $valueDossierDernier ) );
        ?>
    </fieldset>
    <fieldset>
        <legend>Recherche par RDV</legend>
            <?php echo $form->input( 'Critererdv.recherche', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );?>
            <?php echo $form->input( 'Critererdv.locaadr', array( 'label' => __d( 'adresse', 'Adresse.locaadr', true ), 'type' => 'text' ) );?>
            <!-- <?php echo $form->input( 'Critererdv.numcomptt', array( 'label' => 'Numéro de commune au sens INSEE' ) );?> -->
            <?php echo $form->input( 'Critererdv.numcomptt', array( 'label' => 'Numéro de commune au sens INSEE', 'type' => 'select', 'options' => $mesCodesInsee, 'empty' => true ) );?>
			<?php
				if( Configure::read( 'CG.cantons' ) ) {
					echo $form->input( 'Canton.canton', array( 'label' => 'Canton', 'type' => 'select', 'options' => $cantons, 'empty' => true ) );
				}
			?>
            <?php echo $form->input( 'Critererdv.statutrdv_id', array( 'label' => __d( 'rendezvous', 'Rendezvous.statutrdv', true ), 'type' => 'select' , 'options' => $statutrdv, 'empty' => true ) );?>
            <?php echo $form->input( 'Critererdv.structurereferente_id', array( 'label' => __d( 'rendezvous', 'Rendezvous.lib_struct', true ), 'type' => 'select', 'options' => $struct, 'empty' => true ) ); ?>

            <?php echo $form->input( 'Critererdv.referent_id', array( 'label' => __( 'Nom du référent', true ), 'type' => 'select', 'options' => $referents, 'empty' => true ) ); ?>
            <?php /*echo $ajax->observeField( 'CritererdvStructurereferenteId', array( 'update' => 'CritererdvReferentId', 'url' => Router::url( array( 'action' => 'ajaxreferent' ), true ) ) );*/?>

            <!--  Ajout d'une permanence liée à une structurereferente  -->
            <?php
                echo $form->input( 'Critererdv.permanence_id', array( 'label' => 'Permanence liée à la structure', 'type' => 'select', 'options' => $permanences, 'empty' => true ) );
//                 echo $ajax->observeField( 'CritererdvStructurereferenteId', array( 'update' => 'CritererdvPermanenceId', 'url' => Router::url( array( 'action' => 'ajaxperm' ), true ) ) );
            ?>
            <?php echo $form->input( 'Critererdv.typerdv_id', array( 'label' => __d( 'rendezvous', 'Rendezvous.lib_rdv', true ), 'type' => 'select', 'options' => $typerdv, 'empty' => true ) ); ?>
            <?php echo $form->input( 'Critererdv.daterdv', array( 'label' => 'Filtrer par date de RDV', 'type' => 'checkbox' ) );?>
            <fieldset>
                <legend>Date de Rendez-vous</legend>
                <?php
                    $daterdv_from = Set::check( $this->data, 'Critererdv.daterdv_from' ) ? Set::extract( $this->data, 'Critererdv.daterdv_from' ) : strtotime( '-1 week' );
                    $daterdv_to = Set::check( $this->data, 'Critererdv.daterdv_to' ) ? Set::extract( $this->data, 'Critererdv.daterdv_to' ) : strtotime( 'now' );
                ?>
                <?php echo $form->input( 'Critererdv.daterdv_from', array( 'label' => 'Du (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $daterdv_from ) );?>
                <?php echo $form->input( 'Critererdv.daterdv_to', array( 'label' => 'Au (exclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'maxYear' => date( 'Y' ) + 5,  'selected' => $daterdv_to ) );?>
            </fieldset>
    </fieldset>

    <div class="submit noprint">
        <?php echo $form->button( 'Rechercher', array( 'type' => 'submit' ) );?>
        <?php echo $form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
    </div>

<?php echo $form->end();?>

<!-- Résultats -->
<?php if( isset( $rdvs ) ):?>

    <h2 class="noprint">Résultats de la recherche</h2>

    <?php if( is_array( $rdvs ) && count( $rdvs ) > 0  ):?>

        <?php echo $pagination;?>
        <table id="searchResults" class="tooltips">
            <thead>
                <tr>
                    <th><?php echo $xpaginator->sort( 'Nom de l\'allocataire', 'Personne.nom' );?></th>
                    <th><?php echo $xpaginator->sort( 'Commune de l\'allocataire', 'Adresse.locaadr' );?></th>
                    <th><?php echo $xpaginator->sort( 'Structure référente', 'Rendezvous.structurereferente_id' );?></th>
                    <th>Référent</th>
                    <th><?php echo $xpaginator->sort( 'Objet du RDV', 'Rendezvous.typerdv_id' );?></th>
                    <th><?php echo $xpaginator->sort( 'Date du RDV', 'Rendezvous.daterdv' );?></th>
                    <th>Heure du RDV</th>
                    <th><?php echo $xpaginator->sort( 'Statut du RDV', 'Rendezvous.statutrdv_id' );?></th>

                    <!--<th>Objet du RDV</th>
                    <th>Commentaire suite au RDV</th>-->
                    <th colspan="2" class="action noprint">Actions</th>
                    <th class="innerTableHeader noprint">Informations complémentaires</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach( $rdvs as $index => $rdv ):?>
                    <?php
                        $title = $rdv['Dossier']['numdemrsa'];

                        $innerTable = '<table id="innerTablesearchResults'.$index.'" class="innerTable">
                            <tbody>
                               <!-- <tr>
                                    <th>Commune de naissance</th>
                                    <td>'.$rdv['Personne']['nomcomnai'].'</td>
                                </tr> -->
                                <tr>
                                    <th>Date de naissance</th>
                                    <td>'.date_short( $rdv['Personne']['dtnai'] ).'</td>
                                </tr>
                                <tr>
                                    <th>Code INSEE</th>
                                    <td>'.$rdv['Adresse']['numcomptt'].'</td>
                                </tr>
                                <tr>
                                    <th>NIR</th>
                                    <td>'.$rdv['Personne']['nir'].'</td>
                                </tr>
                            </tbody>
                        </table>';
// debug($rdv);
                        echo $xhtml->tableCells(
                            array(
                                h( $rdv['Personne']['nom'].' '.$rdv['Personne']['prenom'] ),
                                h( Set::extract( $rdv, 'Adresse.locaadr' ) ),
                                h( Set::extract( $rdv, 'Structurereferente.lib_struc' ) ),
                                h( Set::classicExtract( $rdv, 'Referent.qual' ) ),
                                h( Set::enum( Set::extract( $rdv, 'Rendezvous.typerdv_id' ), $typerdv ) ),
                                h( $locale->date( 'Date::short', $rdv['Rendezvous']['daterdv'] ) ),
                                h( $locale->date( 'Time::short', $rdv['Rendezvous']['heurerdv'] ) ),
                                h( Set::enum( Set::extract( $rdv, 'Rendezvous.statutrdv_id' ), $statutrdv ) ),

//                                 h( Set::extract( $rdv, 'Rendezvous.objetrdv' ) ),
//                                 h( Set::extract( $rdv, 'Rendezvous.commentairerdv' ) ),
                                array(
                                    $xhtml->viewLink(
                                        'Voir le dossier « '.$title.' »',
                                        array( 'controller' => 'rendezvous', 'action' => 'index', $rdv['Rendezvous']['personne_id'] )
                                    ),
                                    array( 'class' => 'noprint' )
                                ),
                                $xhtml->printLink(
                                    'Imprimer la notification',
                                    array( 'controller' => 'rendezvous', 'action' => 'gedooo', $rdv['Rendezvous']['id'] )
                                ),
                                array( $innerTable, array( 'class' => 'innerTableCell noprint' ) ),
                            ),
                            array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
                            array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
                        );
                    ?>
                <?php endforeach;?>
            </tbody>
        </table>
        <ul class="actionMenu">
            <li><?php
                echo $xhtml->printLinkJs(
                    'Imprimer le tableau',
                    array( 'onclick' => 'printit(); return false;', 'class' => 'noprint' )
                );
            ?></li>
            <li><?php
                echo $xhtml->exportLink(
                    'Télécharger le tableau',
                    array( 'controller' => 'criteresrdv', 'action' => 'exportcsv', implode_assoc( '/', ':', array_unisize( $this->data ) ) )
                );
            ?></li>
            <li><?php
//                 echo $xhtml->printCohorteLink(
//                     'Imprimer la cohorte',
//                     Set::merge(
//                         array(
//                             'controller' => 'gedooos',
//                             'action'     => 'notifications_relances'
//                         ),
//                         array_unisize( $this->data )
//                     )
//                 );
            ?></li>
        </ul>
     <?php echo $pagination;?>

    <?php else:?>
        <p>Vos critères n'ont retourné aucun dossier.</p>
    <?php endif?>

<?php endif?>