<?php $this->pageTitle = 'Paramétrage des référents APRE';?>

<div>
    <h1><?php echo 'Visualisation de la table  ';?></h1>

    <ul class="actionMenu">
        <?php
            echo '<li>'.$html->addLink(
                'Ajouter',
                array( 'controller' => 'referentsapre', 'action' => 'add' )
            ).' </li>';
        ?>
    </ul>
    <div>
        <h2>Table des Référents APRE</h2>
        <table>
        <thead>
            <tr>
                <th>Civilité</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Adresse</th>
                <th>N° de téléphone</th>
                <th>Email</th>
                <th>Fonction</th>
                <th>Organisme</th>
                <th>Employé au Service pour le Pôle Emploi ?</th>
                <th colspan="2" class="action">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach( $referentsapre as $referentapre ):?>
                <?php echo $html->tableCells(
                            array(
                                h( Set::classicExtract( $qual, Set::classicExtract( $referentapre, 'Referentapre.qual' ) ) ),
                                h( Set::classicExtract( $referentapre, 'Referentapre.nom' ) ),
                                h( Set::classicExtract( $referentapre, 'Referentapre.prenom' ) ),
                                h( Set::classicExtract( $referentapre, 'Referentapre.adresse' ) ),
                                h( Set::classicExtract( $referentapre, 'Referentapre.numtel' ) ),
                                h( Set::classicExtract( $referentapre, 'Referentapre.email' ) ),
                                h( Set::classicExtract( $referentapre, 'Referentapre.fonction' ) ),
                                h( Set::classicExtract( $referentapre, 'Referentapre.organismeref' ) ),
                                h( Set::classicExtract( $options['spe'], Set::classicExtract( $referentapre, 'Referentapre.spe', 'enum' ) ) ),
                                $html->editLink(
                                    'Éditer le référent APRE ',
                                    array( 'controller' => 'referentsapre', 'action' => 'edit', $referentapre['Referentapre']['id'] )
                                ),
                                $html->deleteLink(
                                    'Supprimer le référent APRE ',
                                    array( 'controller' => 'referentsapre', 'action' => 'delete', $referentapre['Referentapre']['id'] )
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
</div>
<div class="clearer"><hr /></div>