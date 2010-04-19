<!--/************************************************************************/ -->
    <?php echo $javascript->link( 'dependantselect.js' ); ?>
    <script type="text/javascript">
//         document.observe("dom:loaded", function() {
//             dependantSelect(
//                 'Aideapre66Typeaideapre66Id',
//                 'Aideapre66Themeapre66Id'
//             );
//         });
    </script>
<!--/************************************************************************/ -->


<fieldset>
    <legend>Aide demandée</legend>
    <?php

        $Aideapre66Id = Set::classicExtract( $this->data, 'Aideapre66.id' );
        $ApreId = Set::classicExtract( $this->data, "{$this->modelClass}.id" );
// debug($this->data);

        if( $this->action == 'edit' && !empty( $Aideapre66Id ) ) {
            echo $form->input( 'Aideapre66.id', array( 'type' => 'hidden' ) );
            echo $form->input( 'Aideapre66.apre_id', array( 'type' => 'hidden', 'value' => $ApreId ) );

        }

        echo $default->subform(
            array(
                'Aideapre66.apre_id' => array( 'type' => 'hidden', 'value' => Set::classicExtract( $this->data, "{$this->modelClass}.id" ) ),
                'Aideapre66.themeapre66_id' => array( 'options' => $themes ),
                'Aideapre66.typeaideapre66_id' => array( 'options' => $typesaides ),
                'Aideapre66.motivdem',
                'Aideapre66.montantaide' => array( 'rows' => 1 ),
                'Pieceaide66.Pieceaide66' => array( 'label' => 'Pièces à fournir', 'multiple' => 'checkbox' , 'options' => $pieceliste, 'empty' => false )
            ),
            array(
                'options' => $options
            )
        );
//         debug($options);
    ?>
</fieldset>

<!-- 
<fieldset>
        <?php if( Configure::read( 'nom_form_apre_cg' ) == 'cg66' ):?>
            <legend>Prescripteur</legend>
        <?php elseif( Configure::read( 'nom_form_apre_cg' ) == 'cg93' ):?>
            <legend>Structure référente</legend>
        <?php endif;?>
            <table class="wide noborder">
                <tr>
                    <td class="noborder">
                        <strong>Nom de l'organisme</strong>
                        <?php echo $xform->input( 'Aideapre66.themeapre66_id', array( 'domain' => 'apre', 'label' => false, 'type' => 'select', 'options' => $themes, 'empty' => true ) );?>
                    </td>
                    <td class="noborder">
                        <strong>Nom du référent</strong>
                        <?php echo $xform->input( 'Aideapre66.typeaideapre66_id', array( 'domain' => 'apre', 'label' => false, 'type' => 'select', 'options' => $typesaides, 'empty' => true ) );?>
                    </td>
                </tr>
                <tr>
                    <td class="wide noborder"><div id="StructurereferenteRef"></div></td>

                    <td class="wide noborder"><div id="ReferentRef"></div></td>
                </tr>
            </table>
        </fieldset>
        -->