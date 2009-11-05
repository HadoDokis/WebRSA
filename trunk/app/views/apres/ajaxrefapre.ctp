<?php if( !empty( $refapre ) ):?>
<table class="wide noborder">
    <tr>
        <td class="wide noborder"><strong>Organisme</strong></td>
        <td class="wide noborder"><strong>Email</strong></td>
        <td class="wide noborder"><strong>Adresse</strong></td>
        <td class="wide noborder"><strong>N° téléphone</strong></td>
    </tr>
    <tr>
        <td class="wide noborder"><?php echo $refapre['Referentapre']['organismeref'];?></td>
        <td class="wide noborder"><?php echo $refapre['Referentapre']['email'];?></td>
        <td class="wide noborder"><?php echo $refapre['Referentapre']['adresse'];?></td>
        <td class="wide noborder"><?php echo $refapre['Referentapre']['numtel'];?></td>
    </tr>
</table>
<?php endif;?>