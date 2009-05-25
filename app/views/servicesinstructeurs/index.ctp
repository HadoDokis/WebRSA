<?php $this->pageTitle = 'Paramétrage des Services instructeurs';?>

<div>
    <h1><?php echo 'Visualisation de la table  ';?></h1>

    <ul class="actionMenu">
        <?php
            echo '<li>'.$html->addLink(
                'Ajouter',
                array( 'controller' => 'servicesinstructeurs', 'action' => 'add' )
            ).' </li>';
        ?>
    </ul>
    <div>
        <h2>Table Serviceinstructeures d'utilisateurs</h2>
        <table>
        <thead>
            <tr>
                 <th>Nom du service</th>
		 <th>N° de rue</th>
                 <th>Nom de rue</th>
		 <th>Complément d'adresse</th>
                 <th>Code INSEE</th>
		 <th>Code postal</th>
                 <th>Ville</th>
		 <th colspan="1" class="action">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach( $servicesinstructeurs as $serviceinstructeur ):?>
                <?php echo $html->tableCells(
                            array(
                                h( $serviceinstructeur['Serviceinstructeur']['lib_service'] ),
				h( $serviceinstructeur['Serviceinstructeur']['num_rue'] ),
                                h( $serviceinstructeur['Serviceinstructeur']['nom_rue'] ),
				h( $serviceinstructeur['Serviceinstructeur']['complement_adr'] ),
                                h( $serviceinstructeur['Serviceinstructeur']['code_insee'] ),
				h( $serviceinstructeur['Serviceinstructeur']['code_postal'] ),
                                h( $serviceinstructeur['Serviceinstructeur']['ville'] ),
                                $html->editLink(
                                    'Éditer le contrat d\'insertion ',
                                    array( 'controller' => 'servicesinstructeurs', 'action' => 'edit', $serviceinstructeur['Serviceinstructeur']['id'] )
                                ),
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