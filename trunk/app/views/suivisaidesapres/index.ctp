<?php echo $xform->create( 'Suiviaideapre' );?>
<div>

<h1><?php echo $this->pageTitle = 'Paramétrage des personnes chargés du suivi des Aides APREs';?></h1>

    <ul class="actionMenu">
        <?php
            echo '<li>'.$xhtml->addLink(
                'Ajouter',
                array( 'controller' => 'suivisaidesapres', 'action' => 'add' )
            ).' </li>';
        ?>
    </ul>
    <?php if( empty( $suivisaidesapres ) ):?>
        <p class="notice">Aucune personne présente pour le moment.</p>
    <?php else:?>
    <div>
        <h2>Table des Personnes chargés du suivi des Aides APREs</h2>
        <!--<table>
        <thead>
            <tr>
                <th>Civilité</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>N° de téléphone</th>
                <th colspan="2" class="action">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach( $suivisaidesapres as $suiviaideapre ):?>
                <?php echo $xhtml->tableCells(
                            array(
                                h( Set::classicExtract( $qual, Set::classicExtract( $suiviaideapre, 'Suiviaideapre.qual' ) ) ),
                                h( Set::classicExtract( $suiviaideapre, 'Suiviaideapre.nom' ) ),
                                h( Set::classicExtract( $suiviaideapre, 'Suiviaideapre.prenom' ) ),
                                h( Set::classicExtract( $suiviaideapre, 'Suiviaideapre.numtel' ) ),
                                $xhtml->editLink(
                                    'Éditer la personne ',
                                    array( 'controller' => 'suivisaidesapres', 'action' => 'edit', $suiviaideapre['Suiviaideapre']['id'] )
                                ),
                                $xhtml->deleteLink(
                                    'Supprimer la personne ',
                                    array( 'controller' => 'suivisaidesapres', 'action' => 'delete', $suiviaideapre['Suiviaideapre']['id'] )
                                )
                            ),
                            array( 'class' => 'odd' ),
                            array( 'class' => 'even' )
                        );
                ?>
            <?php endforeach;?>
            </tbody>
        </table>-->
		<?php
			echo $default->index(
				$suivisaidesapres,
				array(
					'Suiviaideapre.qual' => array( 'options' => $qual ),
					'Suiviaideapre.nom',
					'Suiviaideapre.prenom',
					'Suiviaideapre.numtel' => array( 'type' => 'phone' ),
				),
				array(
					'actions' => array(
						'Suiviaideapre.edit',
						'Suiviaideapre.delete'
					)
				)
			);
		?>

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