<?php if( !empty( $pieces ) ):?>
    <?php
        echo $default->subform(
            array(
                'Pieceaide66.Pieceaide66' => array( 'label' => 'Pièces à fournir', 'multiple' => 'checkbox', 'options' => $pieces, 'empty' => false )
            )
        );
    ?>
<?php endif;?>