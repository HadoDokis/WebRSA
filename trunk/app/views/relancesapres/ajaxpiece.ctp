<?php if( !empty( $piecesapre ) ):?>
<?php
    $piecesPresentesId = Set::classicExtract( $apre, 'Pieceapre.{n}.id' );
    if( empty( $piecesPresentesId ) ) {
        $piecesPresentesId = array_keys( $piecesapre );
    }
?>
<table class="wide noborder">

    <tr>
        <td class="wide noborder"><?php echo $form->input( 'Pieceapre.Pieceapre', array( 'label' => false, 'multiple' => 'checkbox', 'options' => $piecesapre, 'value' => $piecesPresentesId ) );?></td>
    </tr>
</table>
<?php endif;?>