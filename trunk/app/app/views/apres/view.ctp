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

<?php
    $montantrestant = null;
    $montantaverser = Set::classicExtract( $apre, 'Apre.montantaverser' );
    $montantdejaverse = Set::classicExtract( $apre, 'Apre.montantdejaverse' );
    $montantrestant = ( $montantaverser - $montantdejaverse );
?>

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
                <tr class="even">
                    <th><?php __( 'Montant aide complémentaire demandée');?></th>
                    <td><?php echo Set::classicExtract( $apre, 'Apre.montantaverser' );?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'Avis du comité d\'examen' );?></th>
                    <td><?php echo Set::classicExtract( Set::classicExtract( $aprecomiteapre, 'ApreComiteapre.decisioncomite' ), $optionsaprecomite['decisioncomite'] );?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'Montant aide complémentaire accordée' );?></th>
                    <td><?php echo Set::classicExtract( $aprecomiteapre, 'ApreComiteapre.montantattribue' );?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'Montant aide complémentaire déjà versé' );?></th>
                    <td><?php echo Set::classicExtract( $apre, 'Apre.montantdejaverse' );?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'Date du versement' );?></th>
                    <td><?php echo date_short( Set::classicExtract( $apre, 'Comiteapre.decisioncomite' ) );?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'Montant restant à payer' );?></th>
                    <td><?php echo $montantrestant;?></td>
                </tr>
            </tbody>
        </table>
</div>

</div>
<div class="clearer"><hr /></div>