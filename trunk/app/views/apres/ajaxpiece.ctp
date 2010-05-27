<div class="aere">
    <?php if( !empty( $typeaideapre ) ):?>
    <?php
        $tmp = array(
            'Typeaideapre66.objetaide' => Set::classicExtract( $typeaideapre, 'Typeaideapre66.objetaide' ),
            'Typeaideapre66.plafond' => Set::classicExtract( $typeaideapre, 'Typeaideapre66.plafond' )
        );
        echo $default->view(
            Xset::bump( $tmp ),
            array(
                'Typeaideapre66.objetaide',
                'Typeaideapre66.plafond' => array( 'type' => 'money' )
            ),
            array(
                'class' => 'inform'
            )
        );
    ?>
    <?php endif;?>
    <?php if( !empty( $pieces ) ):?>
        <?php
            echo $default->subform(
                array(
                    'Pieceaide66.Pieceaide66' => array( 'label' => 'Pièces fournies', 'multiple' => 'checkbox', 'options' => $pieces, 'empty' => false )
                )
            );
        ?>
    <?php endif;?>
</div>