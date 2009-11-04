<?php $this->pageTitle = 'APREs';?>
<?php echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>

<?php
    if( $this->action == 'add' ) {
        $this->pageTitle = 'Ajout d\'une APRE';
    }
    else {
        $this->pageTitle = 'APRE ';
        $foyer_id = $this->data['Personne']['foyer_id'];
    }
?>
<div class="with_treemenu">
    <h1><?php echo 'APRE  ';?></h1>


<div id="ficheCI">
        <table>
            <tbody>
                <tr class="even">
                    <th><?php __( 'N° dossier APRE');?></th>
                    <td><?php echo Set::classicExtract( $apre, 'Apre.numeroapre' );?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'Nom / Prénom bénéficiare' );?></th>
                    <td><?php echo ( $apre['Personne']['nom'].' '.$apre['Personne']['prenom'] );?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'Type de demande' );?></th>
                    <td><?php echo Set::classicExtract( $options['typedemandeapre'], Set::classicExtract( $apre, 'Apre.typedemandeapre' ) );?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'Date de demande au CG' );?></th>
                    <td><?php echo date_short( Set::classicExtract( $apre, 'Apre.datedemandeapre' ) );?></td>
                </tr>
            </tbody>
        </table>
</div>
</div>
<div class="clearer"><hr /></div>