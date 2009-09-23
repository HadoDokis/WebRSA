<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
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

?>
<script type="text/javascript">
    document.observe("dom:loaded", function() {
        observeDisableFieldsetOnCheckbox( 'CritererdvDaterdv', $( 'CritererdvDaterdvFromDay' ).up( 'fieldset' ), false );
    });
</script>

<?php echo $form->create( 'Critererdv', array( 'type' => 'post', 'action' => '/index/', 'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) ) ? 'folded' : 'unfolded' ) ) );?>
    <fieldset>
        <legend>Recherche par Contrat d'insertion</legend>
            <?php echo $form->input( 'Critererdv.recherche', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );?>
            <?php echo $form->input( 'Critererdv.locaadr', array( 'label' => __( 'locaadr', true ), 'type' => 'text' ) );?>
            <?php echo $form->input( 'Critererdv.numcomptt', array( 'label' => 'Numéro de commune au sens INSEE', 'type' => 'text' ) );?>
            <?php echo $form->input( 'Critererdv.statutrdv', array( 'label' => __( 'statutrdv', true ), 'type' => 'select' , 'options' => $statutrdv, 'empty' => true ) );?>
            <?php echo $form->input( 'Critererdv.structurereferente_id', array( 'label' => __( 'lib_struct', true ), 'type' => 'select', 'options' => $struct, 'empty' => true ) ); ?>
            <?php echo $form->input( 'Critererdv.typerdv_id', array( 'label' => __( 'lib_rdv', true ), 'type' => 'select', 'options' => $typerdv, 'empty' => true ) ); ?>
            <?php echo $form->input( 'Critererdv.daterdv', array( 'label' => 'Filtrer par date de RDV', 'type' => 'checkbox' ) );?>
            <fieldset>
                <legend>Date de Rendez-vous</legend>
                <?php
                    $daterdv_from = Set::check( $this->data, 'Critererdv.daterdv_from' ) ? Set::extract( $this->data, 'Critererdv.daterdv_from' ) : strtotime( '-1 week' );
                    $daterdv_to = Set::check( $this->data, 'Critererdv.daterdv_to' ) ? Set::extract( $this->data, 'Critererdv.daterdv_to' ) : strtotime( 'now' );
                ?>
                <?php echo $form->input( 'Critererdv.daterdv_from', array( 'label' => 'Du', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $daterdv_from ) );?>
                <?php echo $form->input( 'Critererdv.daterdv_to', array( 'label' => 'Au', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $daterdv_to ) );?>
            </fieldset>
        <fieldset>
            <legend>Recherche par demandeur</legend>
            <?php echo $form->input( 'Critererdv.nom', array( 'label' => 'Nom' ) );?>
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

        <?php /*require( 'index.pagination.ctp' )*/?>
        <table id="searchResults" class="tooltips_oupas">
            <thead>
                <tr>
                    <th><?php echo $paginator->sort( 'Nom de l\'allocataire', 'Personne.nom' );?></th>
                    <th><?php echo $paginator->sort( 'Commune de l\'allocataire', 'Adresse.locaadr' );?></th>
                    <th><?php echo $paginator->sort( 'Structure référente', 'Rendezvous.structurereferente_id' );?></th>
                    <th><?php echo $paginator->sort( 'Type de RDV', 'Rendezvous.typerdv_id' );?></th>
                    <th><?php echo $paginator->sort( 'Statut du RDV', 'Rendezvous.statutrdv' );?></th>
                    <th><?php echo $paginator->sort( 'Date du RDV', 'Rendezvous.daterdv' );?></th>
                    <th>Objet du RDV</th>
                    <th>Commentaire suite au RDV</th>
                    <th class="action noprint">Actions</th>
                    <th class="innerTableHeader noprint">Informations complémentaires</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach( $rdvs as $index => $rdv ):?>
                    <?php
                        $title = $rdv['Dossier']['numdemrsa'];

                        $innerTable = '<table id="innerTable'.$index.'" class="innerTable">
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
                            </tbody>
                        </table>';
                        echo $html->tableCells(
                            array(
                                h( $rdv['Personne']['nom'].' '.$rdv['Personne']['prenom'] ),
                                h( Set::extract( $rdv, 'Adresse.locaadr' ) ),
                                h( value( $struct, Set::extract( $rdv, 'Rendezvous.structurereferente_id' ) ) ),
                                h( value( $typerdv, Set::extract( $rdv, 'Rendezvous.typerdv_id' ) ) ),
                                h( value( $statutrdv, Set::extract( $rdv, 'Rendezvous.statutrdv' ) ) ),
                                h( date_short( $rdv['Rendezvous']['daterdv'] ) ),
                                h( Set::extract( $rdv, 'Rendezvous.objetrdv' ) ),
                                h( Set::extract( $rdv, 'Rendezvous.commentairerdv' ) ),
                                array(
                                    $html->viewLink(
                                        'Voir le dossier « '.$title.' »',
                                        array( 'controller' => 'rendezvous', 'action' => 'index', $rdv['Rendezvous']['personne_id'] )
                                    ),
                                    array( 'class' => 'noprint' )
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
                echo $html->printLinkJs(
                    'Imprimer le tableau',
                    array( 'onclick' => 'printit(); return false;', 'class' => 'noprint' )
                );
            ?></li>
            <li><?php
                echo $html->exportLink(
                    'Télécharger le tableau',
                    array( 'controller' => 'criteresrdv', 'action' => 'exportcsv', implode_assoc( '/', ':', array_unisize( $this->data ) ) )
                );
            ?></li>
        </ul>
    <?php  /*require( 'index.pagination.ctp' )*/  ?>

    <?php else:?>
        <p>Vos critères n'ont retourné aucun dossier.</p>
    <?php endif?>

<?php endif?>