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
//                 'Typeaideapre66.objetaide',
                'Typeaideapre66.plafond' => array( 'type' => 'money' )
            ),
            array(
                'class' => 'inform'
            )
        );
    ?>
    <?php endif;?>
    <table class="wide noborder">
        <tr>
            <td class="noborder">
                <?php if( !empty( $piecesadmin ) ):?>
                    <?php
                        echo $default->subform(
                            array(
                                'Pieceaide66.Pieceaide66' => array( 'label' => 'Pièces administratives', 'multiple' => 'checkbox', 'options' => $pieceadmin, 'empty' => false )
                            )
                        );
                    ?>
                <?php endif;?>
            </td>

            <td class="noborder">
            <?php debug($piecescomptable);?>
                <?php if( !empty( $piecescomptable ) ):?>
                    <?php
                        echo $default->subform(
                            array(
                                'Piececomptable66.Piececomptable66' => array( 'label' => 'Pièces comptables', 'multiple' => 'checkbox', 'options' => $piececomptable, 'empty' => false )
                            )
                        );
                    ?>
                <?php endif;?>
            </td>
        </tr>
    </table>
</div>