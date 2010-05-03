<?php if( !empty( $part ) ):?>
    <table class="wide noborder">
        <tr>
            <td class="wide noborder"><strong>Partenaire</strong></td>
            <td class="wide noborder"><strong>Nom du correspondant</strong></td>
        </tr>
        <tr>
            <td class="wide noborder">
                <?php
                    echo Set::enum( Set::classicExtract( $part, 'ActioncandidatPartenaire.partenaire_id' ), $parts );
                ?>
            </td>
            <td class="wide noborder">
                <?php
                    echo Set::classicExtract( $contact, 'Contactpartenaire.qual' ).' '.Set::classicExtract( $contact, 'Contactpartenaire.nom' ).' '.Set::classicExtract( $contact, 'Contactpartenaire.prenom' );
                ?>
            </td>
        </tr>

    </table>
<?php endif;?>