<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php
    $this->pageTitle = 'Recherche par Bilans de parcours';
?>

<h1><?php echo $this->pageTitle;?></h1>

<?php
    echo '<ul class="actionMenu"><li>'.$xhtml->link(
        $xhtml->image(
            'icons/application_form_magnify.png',
            array( 'alt' => '' )
        ).' Formulaire',
        '#',
        array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Search' ).toggle(); return false;" )
    ).'</li></ul>';
?>
<?php echo $javascript->link( 'dependantselect.js' ); ?>
<script type="text/javascript">
    document.observe("dom:loaded", function() {
        observeDisableFieldsetOnCheckbox( 'Bilanparcours66Datebilan', $( 'Bilanparcours66DatebilanFromDay' ).up( 'fieldset' ), false );

        dependantSelect( 'Bilanparcours66ReferentId', 'Bilanparcours66StructurereferenteId' );
    });
</script>

<?php echo $xform->create( 'Criterebilanparcours66', array( 'type' => 'post', 'action' => $this->action, 'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) ) ? 'folded' : 'unfolded' ) ) );?>

    <fieldset>
            <?php echo $xform->input( 'Bilanparcours66.indexparams', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );?>

            <fieldset>
                <legend>Filtrer par Bilan de parcours</legend>
                <?php
                    echo $default2->subform(
                        array(
                            'Bilanparcours66.proposition' => array( 'type' => 'select', 'options' => $options['proposition'] ),
                            'Bilanparcours66.choixparcours' => array( 'type' => 'select', 'options' => $options['choixparcours'] ),
                            'Bilanparcours66.examenaudition' => array( 'type' => 'select', 'options' => $options['examenaudition'] ),
                            'Bilanparcours66.maintienorientation' => array( 'type' => 'select', 'options' => $options['maintienorientation'] ),
                            'Bilanparcours66.structurereferente_id' => array( 'type' => 'select', 'options' => $struct ),
                            'Bilanparcours66.referent_id' => array( 'type' => 'select', 'options' => $referents ),
                        ),
                        array(
                            'options' => $options
                        )
                    );

                ?>
            </fieldset>

            <?php echo $xform->input( 'Bilanparcours66.datebilan', array( 'label' => 'Filtrer par date de Bilan de parcours', 'type' => 'checkbox' ) );?>
            <fieldset>
                <legend>Filtrer par période</legend>
                <?php
                    $datebilan_from = Set::check( $this->data, 'Bilanparcours66.datebilan_from' ) ? Set::extract( $this->data, 'Bilanparcours66.datebilan_from' ) : strtotime( '-1 week' );
                    $datebilan_to = Set::check( $this->data, 'Bilanparcours66.datebilan_to' ) ? Set::extract( $this->data, 'Bilanparcours66.datebilan_to' ) : strtotime( 'now' );
                ?>
                <?php echo $xform->input( 'Bilanparcours66.datebilan_from', array( 'label' => 'Du', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 1, 'minYear' => date( 'Y' ) - 10, 'selected' => $datebilan_from ) );?>
                <?php echo $xform->input( 'Bilanparcours66.datebilan_to', array( 'label' => 'Au', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 1, 'minYear' => date( 'Y' ) - 10, 'selected' => $datebilan_to ) );?>
            </fieldset>

    </fieldset>

    <div class="submit noprint">
        <?php echo $xform->button( 'Rechercher', array( 'type' => 'submit' ) );?>
        <?php echo $xform->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
    </div>

<?php echo $xform->end();?>
<?php $pagination = $xpaginator->paginationBlock( 'Bilanparcours66', $this->passedArgs ); ?>
<?php echo $pagination;?>
<?php if( isset( $bilansparcours66 ) ):?>
    <?php if( is_array( $bilansparcours66 ) && count( $bilansparcours66 ) > 0  ):?>
        <?php
            echo '<table><thead>';
                echo '<tr>
                    <th>'.$xpaginator->sort( __d( 'bilanparcours66', 'Bilanparcours66.datebilan', true ), 'Bilanparcours66.datebilan' ).'</th>
                    <th>'.$xpaginator->sort( __d( 'personne', 'Personne.nom_complet', true ), 'Personne.nom_complet' ).'</th>
                    <th>'.$xpaginator->sort( __d( 'structurereferente', 'Structurereferente.lib_struc', true ), 'Structurereferente.lib_struc' ).'</th>
                    <th>'.$xpaginator->sort( __d( 'referent', 'Referent.nom_complet', true ), 'Referent.nom_complet' ).'</th>
                    <th>'.$xpaginator->sort( __d( 'bilanparcours66', 'Bilanparcours66.proposition', true ), 'Bilanparcours66.proposition' ).'</th>
                    <th>'.$xpaginator->sort( __d( 'bilanparcours66', 'Bilanparcours66.choixparcours', true ), 'Bilanparcours66.choixparcours' ).'</th>
                    <th>Saisine EP</th>
                    <th>Actions</th>
                </tr></thead><tbody>';
            foreach( $bilansparcours66 as $bilanparcour66 ) {

                $isSaisine = '0';
                if( isset( $bilanparcour66['Dossierep']['etapedossierep'] ) ){
                    $isSaisine = '1';
                }

                $motif = null;
                if (empty($bilanparcour66['Bilanparcours66']['choixparcours']) && !empty($bilanparcour66['Bilanparcours66']['examenaudition'])) {
                    $motif = Set::classicExtract( $options['examenaudition'], $bilanparcour66['Bilanparcours66']['examenaudition'] );
                }
                elseif (empty($bilanparcour66['Bilanparcours66']['choixparcours']) && empty($bilanparcour66['Bilanparcours66']['examenaudition'])) {
                    if ($bilanparcour66['Bilanparcours66']['maintienorientation']=='0') {
                        $motif = 'Réorientation';
                    }
                    else {
                        $motif = 'Maintien';
                    }
                }
                else {
                    $motif = Set::classicExtract( $options['choixparcours'], $bilanparcour66['Bilanparcours66']['choixparcours'] );
                }

                echo '<tr>
                    <td>'.h( $locale->date( 'Date::short', $bilanparcour66['Bilanparcours66']['datebilan'] ) ).'</td>
                    <td>'.h( $bilanparcour66['Personne']['nom_complet'] ).'</td>
                    <td>'.h( $bilanparcour66['Structurereferente']['lib_struc'] ).'</td>
                    <td>'.h( $bilanparcour66['Referent']['nom_complet'] ).'</td>
                    <td>'.h( Set::classicExtract( $options['proposition'], $bilanparcour66['Bilanparcours66']['proposition'] ) ).'</td>
                    <td>'.h( $motif ).'</td>'.
                    $default2->Type2->format( $isSaisine, 'Dossierep.etapedossierep', array( 'type' => 'boolean', 'tag' => 'td' ) ).
                    '<td>'.$xhtml->link( 'Voir', array( 'controller' => 'bilansparcours66', 'action' => 'index', $bilanparcour66['Personne']['id'] ) ).'</td>
                </tr>';
            }
            echo '</tbody></table>';
    ?>
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
                array( 'controller' => 'criteresbilansparcours66', 'action' => 'exportcsv', implode_assoc( '/', ':', array_unisize( $this->data ) ) )
            );
        ?></li>
    </ul>
<?php echo $pagination;?>

    <?php else:?>
        <?php echo $xhtml->tag( 'p', 'Aucun résultat ne correspond aux critères choisis.', array( 'class' => 'notice' ) );?>
    <?php endif;?>
<?php endif;?>