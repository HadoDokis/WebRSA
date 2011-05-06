<?php if( !empty( $actioncandidatPartenaire ) ): ?>
<fieldset>
	<legend>Partenaire</legend>
    <table class="wide noborder">
        <tr>
            <td class="wide noborder"><strong>Nom : </strong></td>
            <td class="wide noborder">
                <?php
                	if( !is_null($referent) )
                    	echo Set::classicExtract( $referent, 'Referent.nom_complet' );
                    else
                    	echo 'Aucun référent saisi';
                ?>
            </td>
        </tr>
        <tr>
        	<td class="wide noborder"><strong>Tél. : </strong></td>
            <td class="wide noborder"><?php echo Set::classicExtract( $actioncandidatPartenaire, 'Partenaire.numtel' ); ?></td>
        </tr>  
        <tr>
        	<td class="wide noborder"><strong>Fax : </strong></td>
            <td class="wide noborder"><?php echo Set::classicExtract( $actioncandidatPartenaire, 'Partenaire.numfax' ); ?></td>
        </tr>
        <tr>
        	<td class="wide noborder"><strong>Code action : </strong></td>
            <td class="wide noborder"><?php echo Set::classicExtract( $actioncandidatPartenaire, 'Actioncandidat.codeaction' ); ?></td>
        </tr>  
        <tr>
        	<td class="wide noborder"><strong>Correspondant de l'action : </strong></td>
            <td class="wide noborder">
                <?php
                    echo Set::classicExtract( $actioncandidatPartenaire, 'Partenaire.libstruc' );
                ?>
            </td>        
        </tr>                    

    </table>
</fieldset>    
<?php endif;?>