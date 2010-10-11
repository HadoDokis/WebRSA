<?php $this->pageTitle = 'Paramétrage des Tiers prestataires APRE';?>
<?php echo $xform->create( 'Tiersprestataireapre' );?>
<div>
    <h1><?php echo 'Visualisation de la table tiers prestataire APRE ';?></h1>

    <ul class="actionMenu">
        <?php
            echo '<li>'.$html->addLink(
                'Ajouter',
                array( 'controller' => 'tiersprestatairesapres', 'action' => 'add' )
            ).' </li>';
        ?>
    </ul>
    <?php if( empty( $tiersprestatairesapres ) ):?>
        <p class="notice">Aucun tiers prestataire présent pour le moment.</p>
    <?php else:?>
    <div>
        <h2>Table des Tiers prestataires APRE</h2>
        <table>
        <thead>
            <tr>
                <th>Nom organisme</th>
                <th>N° Siret  </th>
                <th>Adresse</th>
                <th>N° de téléphone</th>
                <th>Email</th>
                <th>Formation liée</th>
                <!-- <th>Etat Banque</th>
                <th>Guichet</th>
                <th>N° Compte Banque</th>
                <th>Clé RIB</th> -->
                <th colspan="2" class="action">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach( $tiersprestatairesapres as $tiersprestataire ):?>
                <?php echo $html->tableCells(
                            array(
                                h( Set::classicExtract( $tiersprestataire, 'Tiersprestataireapre.nomtiers' ) ),
                                h( Set::classicExtract( $tiersprestataire, 'Tiersprestataireapre.siret' ) ),
                                h(
                                    Set::classicExtract( $tiersprestataire, 'Tiersprestataireapre.numvoie' ).' '.Set::enum( Set::classicExtract( $tiersprestataire, 'Tiersprestataireapre.typevoie' ), $typevoie ).' '.Set::classicExtract( $tiersprestataire, 'Tiersprestataireapre.nomvoie' ).' '.Set::classicExtract( $tiersprestataire, 'Tiersprestataireapre.codepos' ).' '.Set::classicExtract( $tiersprestataire, 'Tiersprestataireapre.ville' )
                                ),
                                h( Set::classicExtract( $tiersprestataire, 'Tiersprestataireapre.numtel' ) ),
                                h( Set::classicExtract( $tiersprestataire, 'Tiersprestataireapre.adrelec' ) ),
                                h( Set::enum( Set::classicExtract( $tiersprestataire, 'Tiersprestataireapre.aidesliees' ), $natureAidesApres ) ),
                                /*h( Set::classicExtract( $tiersprestataire, 'Tiersprestataireapre.etaban' ) ),
                                h( Set::classicExtract( $tiersprestataire, 'Tiersprestataireapre.guiban' ) ),
                                h( Set::classicExtract( $tiersprestataire, 'Tiersprestataireapre.numcomptban' ) ),
                                h( Set::classicExtract( $tiersprestataire, 'Tiersprestataireapre.clerib' ) ),*/
                                $html->editLink(
                                    'Éditer le tiers prestataire APRE ',
                                    array( 'controller' => 'tiersprestatairesapres', 'action' => 'edit', $tiersprestataire['Tiersprestataireapre']['id'] )
                                ),
                                $html->deleteLink(
                                    'Supprimer le tiers prestataire APRE ',
                                    array( 'controller' => 'tiersprestatairesapres', 'action' => 'delete', $tiersprestataire['Tiersprestataireapre']['id'] ),
                                    $permissions->check( 'tiersprestatairesapres', 'delete' ) && Set::classicExtract( $tiersprestataire, 'Tiersprestataireapre.deletable' )
                                )
                            ),
                            array( 'class' => 'odd' ),
                            array( 'class' => 'even' )
                        );
                ?>
            <?php endforeach;?>
            </tbody>
        </table>
    </div>
    <?php endif;?>
    <div class="submit">
        <?php
            echo $form->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) );
        ?>
    </div>
</div>
<div class="clearer"><hr /></div>
<?php echo $form->end();?>