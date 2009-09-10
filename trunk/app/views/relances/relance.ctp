<h1><?php echo $this->pageTitle = $pageTitle;?></h1>

<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<script type="text/javascript">
    document.observe("dom:loaded", function() {
        observeDisableFieldsetOnCheckbox( 'RelanceDaterelance', $( 'RelanceDaterelanceFromDay' ).up( 'fieldset' ), false );
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

<?php echo $form->create( 'Relance', array( 'url'=> Router::url( null, true ) ) );?>
    <?php echo $form->input( 'Relance.daterelance', array( 'label' => 'Filtrer par date de relance', 'type' => 'checkbox' ) );?>
   <fieldset>
        <legend>Date de Relance</legend>
        <?php
            $daterelance_from = Set::check( $this->data, 'Relance.daterelance_from' ) ? Set::extract( $this->data, 'Relance.daterelance_from' ) : strtotime( '-1 week' );
            $daterelance_to = Set::check( $this->data, 'Relance.daterelance_to' ) ? Set::extract( $this->data, 'Relance.daterelance_to' ) : strtotime( 'now' );
        ?>
        <?php echo $form->input( 'Relance.daterelance_from', array( 'label' => 'Du', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $daterelance_from ) );?>
        <?php echo $form->input( 'Relance.daterelance_to', array( 'label' => 'Au', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $daterelance_to ) );?>
    </fieldset>

    <div class="submit">
        <?php echo $form->button( 'Filtrer', array( 'type' => 'submit' ) );?>
        <?php echo $form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
    </div>
<?php echo $form->end();?>

<?php if( !empty( $this->data ) ):?>
    <?php if( empty( $orientsstructs ) ):?>
        <p class="notice">Aucun dossier relancé n'est présente.</p>
    <?php else:?>
        <table class="tooltips_oupas">
            <thead>
                <tr>
                    <th>N° Dossier</th>
                    <th>N° CAF</th>
                    <th>Nom / Prénom Allocataire</th>
                    <th>Date orientation</th>
                    <th>Date de relance</th>
                    <th>Statut relance</th>
                    <th class="action">Action</th>
                    <th class="innerTableHeader">Informations complémentaires</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach( $orientsstructs as $index => $orientstruct ):?>
                    <?php
                        $innerTable = '<table id="innerTable'.$index.'" class="innerTable">
                            <tbody>
                                <tr>
                                    <th>N° de dossier</th>
                                    <td>'.h( $orientstruct['Dossier']['numdemrsa'] ).'</td>
                                </tr>
                                <tr>
                                    <th>Date naissance</th>
                                    <td>'.h( date_short( $orientstruct['Personne']['dtnai'] ) ).'</td>
                                </tr>
                                <tr>
                                    <th>Numéro CAF</th>
                                    <td>'.h( $orientstruct['Dossier']['matricule'] ).'</td>
                                </tr>
                                <tr>
                                    <th>NIR</th>
                                    <td>'.h( $orientstruct['Personne']['nir'] ).'</td>
                                </tr>
                            </tbody>
                        </table>';

                        echo $html->tableCells(
                            array(
                                h( $orientstruct['Dossier']['numdemrsa'] ),
                                h( $orientstruct['Dossier']['matricule'] ),
                                h( $orientstruct['Personne']['nom'].' '.$orientstruct['Personne']['prenom'] ),
                                h( date_short( $orientstruct['Orientstruct']['date_valid'] ) ),
                                h( date_short( $orientstruct['Orientstruct']['daterelance'] ) ),
                                h( value( $statutrelance, Set::extract( $orientstruct, 'Orientstruct.statutrelance' ) ) ),
                                $html->printLink(
                                    'Imprimer la notification',
                                    array( 'controller' => 'gedooos', 'action' => 'notification_structure', $orientstruct['Personne']['id'] ),
                                    $permissions->check( 'gedooos', 'notification_structure' )
                                ),
                                array( $innerTable, array( 'class' => 'innerTableCell' ) ),
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
                echo $html->printCohorteLink(
                    'Imprimer la cohorte',
                    Set::merge(
                        array(
                            'controller' => 'gedooos',
                            'action'     => 'notifications_cohortes'
                        ),
                        array_unisize( $this->data )
                    )
                );
            ?></li>

           <!-- <li><?php
                echo $html->exportLink(
                    'Télécharger le tableau',
                    Set::merge(
                        array(
                            'controller' => 'cohortes',
                            'action' => 'exportcsv'
                        ),
                        array_unisize( $this->data )
                    )
                );
            ?></li> -->
        </ul>
    <?php endif;?>
<?php endif;?>