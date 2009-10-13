<?php $this->pageTitle = 'Rendez-vous';?>
<?php echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>

<div class="with_treemenu">
    <h1><?php echo 'Rendez-vous';?></h1>

    <div id="ficheCI">
        <table>
            <tbody>
                <tr class="even">
                    <th><?php __( 'lib_struc' );?></th>
                    <td><?php echo Set::classicExtract( $rendezvous, 'Structurereferente.lib_struc' );?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'Référent' );?></th>
                    <td><?php echo Set::classicExtract( $referent, Set::classicExtract( $rendezvous, 'Rendezvous.referent_id' ) );?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'Fonction du référent' );?></th>
                    <td><?php echo Set::classicExtract( $referentFonction, Set::classicExtract( $rendezvous, 'Rendezvous.referent_id' ) );?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'Type de RDV' );?></th>
                    <td><?php echo Set::classicExtract( $typerdv, Set::classicExtract( $rendezvous, 'Rendezvous.typerdv_id' ) );?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'statutrdv' );?></th>
                    <td><?php echo Set::classicExtract( $statutrdv, Set::classicExtract( $rendezvous, 'Rendezvous.statutrdv' ) );?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'daterdv' );?></th>
                    <td><?php echo date_short( Set::classicExtract( $rendezvous, 'Rendezvous.daterdv' ) );?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'heurerdv' );?></th>
                    <td><?php echo Set::classicExtract( $rendezvous, 'Rendezvous.heurerdv' );?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'objetrdv' );?></th>
                    <td><?php echo Set::classicExtract( $rendezvous, 'Rendezvous.objetrdv' );?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'commentairerdv' );?></th>
                    <td><?php echo Set::classicExtract( $rendezvous, 'Rendezvous.commentairerdv' );?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="clearer"><hr /></div>