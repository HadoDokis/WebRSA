
<fieldset>
    <?php
        echo $default->subform(
            array(
                'Aideapre66.typeaideapre66_id' => array( 'options' => $typesaides ),
                'Aideapre66.plafond' => array( 'rows' => 1 ),
                'Pieceaide66.Pieceaide66' => array( 'label' => 'Pièces à fournir', 'multiple' => 'checkbox' , 'options' => $pieceliste, 'empty' => false )
            ),
            array(
                'options' => $options
            )
        );
//         debug($options);
    ?>
</fieldset>