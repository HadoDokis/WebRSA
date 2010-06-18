<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Recherche par contrats d\'engagement réciproque';?>

<h1>Recherche par CER</h1>

<script type="text/javascript">
    document.observe("dom:loaded", function() {
        observeDisableFieldsetOnCheckbox( 'FiltreDateSaisiCi', $( 'FiltreDateSaisiCiFromDay' ).up( 'fieldset' ), false );
    });
</script>

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

<?php echo $form->create( 'Critereci', array( 'type' => 'post', 'action' => '/index/', 'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) ) ? 'folded' : 'unfolded' ) ) );?>
    <fieldset>
        <legend>Recherche par personne</legend>
        <?php echo $form->input( 'Filtre.nom', array( 'label' => 'Nom ', 'type' => 'text' ) );?>
        <?php echo $form->input( 'Filtre.prenom', array( 'label' => 'Prénom ', 'type' => 'text' ) );?>
        <?php echo $form->input( 'Filtre.nir', array( 'label' => 'NIR ', 'maxlength' => 15 ) );?>
        <?php echo $form->input( 'Filtre.matricule', array( 'label' => 'N° CAF', 'maxlength' => 15 ) );?>
        <?php echo $form->input( 'Filtre.natpf', array( 'label' => 'Nature de la prestation', 'type' => 'select', 'options' => $natpf, 'empty' => true ) );?>
    </fieldset>
    <fieldset>
        <legend>Recherche par CER</legend>
            <?php echo $form->input( 'Filtre.recherche', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );?>
            <?php echo $form->input( 'Filtre.date_saisi_ci', array( 'label' => 'Filtrer par date de saisie du contrat', 'type' => 'checkbox' ) );?>
            <fieldset>
                <legend>Date de saisie du contrat</legend>
                <?php
                    $date_saisi_ci_from = Set::check( $this->data, 'Filtre.date_saisi_ci_from' ) ? Set::extract( $this->data, 'Filtre.date_saisi_ci_from' ) : strtotime( '-1 week' );
                    $date_saisi_ci_to = Set::check( $this->data, 'Filtre.date_saisi_ci_to' ) ? Set::extract( $this->data, 'Filtre.date_saisi_ci_to' ) : strtotime( 'now' );
                ?>
                <?php echo $form->input( 'Filtre.date_saisi_ci_from', array( 'label' => 'Du', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $date_saisi_ci_from ) );?>
                <?php echo $form->input( 'Filtre.date_saisi_ci_to', array( 'label' => 'Au', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $date_saisi_ci_to ) );?>
            </fieldset>
            <?php echo $form->input( 'Filtre.locaadr', array( 'label' => 'Commune de l\'allocataire ', 'type' => 'text' ) );?>
            <!-- <?php echo $form->input( 'Filtre.numcomptt', array( 'label' => 'Numéro de commune au sens INSEE' ) );?> -->
            <?php echo $form->input( 'Filtre.numcomptt', array( 'label' => 'Numéro de commune au sens INSEE', 'type' => 'select', 'options' => $mesCodesInsee, 'empty' => true ) );?>
			<?php
				if( Configure::read( 'CG.cantons' ) ) {
					echo $form->input( 'Canton.canton', array( 'label' => 'Canton', 'type' => 'select', 'options' => $cantons, 'empty' => true ) );
				}
			?>
            <?php echo $form->input( 'Filtre.structurereferente_id', array( 'label' => __( 'lib_struct', true ), 'type' => 'select', 'options' => $struct, 'empty' => true ) ); ?>
            <?php echo $form->input( 'Filtre.referent_id', array( 'label' => __( 'Nom du référent', true ), 'type' => 'select', 'options' => $referents, 'empty' => true ) ); ?>
            <?php echo $ajax->observeField( 'FiltreStructurereferenteId', array( 'update' => 'FiltreReferentId', 'url' => Router::url( array( 'action' => 'ajaxreferent' ), true ) ) );?>
            <?php echo $form->input( 'Filtre.decision_ci', array( 'label' => 'Statut du contrat', 'type' => 'select', 'options' => $decision_ci, 'empty' => true ) ); ?>
            <?php echo $form->input( 'Filtre.datevalidation_ci', array( 'label' => '', 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 , 'empty' => true)  ); ?>

    </fieldset>

    <div class="submit noprint">
        <?php echo $form->button( 'Rechercher', array( 'type' => 'submit' ) );?>
        <?php echo $form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
    </div>

<?php echo $form->end();?>

<!-- Résultats -->
<?php if( isset( $contrats ) ):?>

    <h2 class="noprint">Résultats de la recherche</h2>

    <?php if( is_array( $contrats ) && count( $contrats ) > 0  ):?>

        <?php require( 'index.pagination.ctp' )?>
        <table id="searchResults" class="tooltips">
            <thead>
                <tr>
                    <th><?php echo $paginator->sort( 'Nom de l\'allocataire', 'Personne.nom' );?></th>
                    <th><?php echo $paginator->sort( 'Commune de l\'allocataire', 'Adresse.locaadr' );?></th>
                    <th><?php echo $paginator->sort( 'Référent lié', 'PersonneReferent.referent_id' );?></th>
                    <th><?php echo $paginator->sort( 'N° CAF', 'Dossier.matricule' );?></th>
                    <th><?php echo $paginator->sort( 'Date de saisie du contrat', 'Contratinsertion.date_saisi_ci' );?></th>
                    <th><?php echo $paginator->sort( 'Rang du contrat', 'Contratinsertion.rg_ci' );?></th>
                    <th><?php echo $paginator->sort( 'Décision', 'Contratinsertion.decision_ci' ).$paginator->sort( ' ', 'Contratinsertion.datevalidation_ci' );?></th>
                    <th class="action noprint">Actions</th>
                    <th class="innerTableHeader noprint">Informations complémentaires</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach( $contrats as $index => $contrat ):?>
                    <?php
                        $title = $contrat['Dossier']['numdemrsa'];

                        $innerTable = '<table id="innerTable'.$index.'" class="innerTable">
                            <tbody>
                               <!-- <tr>
                                    <th>Commune de naissance</th>
                                    <td>'.$contrat['Personne']['nomcomnai'].'</td>
                                </tr> -->
                                <tr>
                                    <th>Date de naissance</th>
                                    <td>'.date_short( $contrat['Personne']['dtnai'] ).'</td>
                                </tr>
                                <tr>
                                    <th>Code INSEE</th>
                                    <td>'.$contrat['Adresse']['numcomptt'].'</td>
                                </tr>
                                <tr>
                                    <th>NIR</th>
                                    <td>'.$contrat['Personne']['nir'].'</td>
                                </tr>
                            </tbody>
                        </table>';
//                         debug( $contrat );
                        echo $html->tableCells(
                            array(
                                h( $contrat['Personne']['nom'].' '.$contrat['Personne']['prenom'] ),
                                h( $contrat['Adresse']['locaadr'] ),
                                h( value( $referents, Set::classicExtract( $contrat, 'Contratinsertion.referent_id' ) ) ),
                                h( $contrat['Dossier']['matricule'] ),
                                h( $locale->date( 'Date::short', Set::extract( $contrat, 'Contratinsertion.date_saisi_ci' ) ) ),//date_short( $contrat['Contratinsertion']['date_saisi_ci'] ) ),
                                h( $contrat['Contratinsertion']['rg_ci'] ),
                                h( Set::extract( $decision_ci, Set::extract( $contrat, 'Contratinsertion.decision_ci' ) ).' '.$locale->date( 'Date::short', Set::extract( $contrat, 'Contratinsertion.datevalidation_ci' ) ) ),//date_short($contrat['Contratinsertion']['datevalidation_ci']) ),
                                array(
                                    $html->viewLink(
                                        'Voir le dossier « '.$title.' »',
                                        array( 'controller' => 'contratsinsertion', 'action' => 'index', $contrat['Contratinsertion']['personne_id'] )
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
                    array( 'controller' => 'criteresci', 'action' => 'exportcsv', implode_assoc( '/', ':', array_unisize( $this->data ) ) )
                );
            ?></li>
        </ul>
    <?php  require( 'index.pagination.ctp' )  ?>

    <?php else:?>
        <p>Vos critères n'ont retourné aucun dossier.</p>
    <?php endif?>

<?php endif?>