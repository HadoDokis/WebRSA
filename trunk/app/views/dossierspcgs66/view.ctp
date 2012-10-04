<?php
	$this->pageTitle =  __d( 'dossierpcg66', "Dossierspcgs66::{$this->action}", true );

	echo $this->element( 'dossier_menu', array( 'foyer_id' => $foyer_id ) );
?>

<div class="with_treemenu">
	<?php
		echo $xhtml->tag( 'h1', $this->pageTitle );
		echo $form->create( 'Dossierpcg66', array( 'type' => 'post', 'id' => 'dossierpcg66form', 'url' => Router::url( null, true ) ) );

		echo $default2->view(
			$dossierpcg66,
			array(
				'Dossierpcg66.datereceptionpdo',
				'Typepdo.libelle',
				'Originepdo.libelle',
				'Dossierpcg66.orgpayeur',
				'Serviceinstructeur.lib_service',
				'Dossierpcg66.iscomplet',
				'Dossierpcg66.user_id' => array( 'value' => '#User.nom# #User.prenom#' ),
				'Dossierpcg66.etatdossierpcg'
			),
			array(
				'class' => 'aere',
				'options' => $options
			)
		);
    ?>
    <h2>Décisions du dossier</h2>
    <?php if( !empty( $dossierpcg66['Decisiondossierpcg66'] ) ):?>
        <table class="tooltips aere">
            <thead>
                <tr>
                    <th>Proposition</th>
                    <th>Date de la proposition</th>
                    <th>Validation</th>
                    <th>Date de validation</th>
                    <th>Commentaire du technicien</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach( $dossierpcg66['Decisiondossierpcg66'] as $decision ){      
                        echo $xhtml->tableCells(
                            array(
                                h( Set::classicExtract( $decisionpdo, Set::classicExtract( $decision, 'decisionpdo_id' ) ) ),
                                h( date_short( Set::classicExtract( $decision, 'datepropositiontechnicien' ) ) ),
                                h( value( $options['Decisiondossierpcg66']['validationproposition'], Set::classicExtract( $decision, 'validationproposition' ) ) ),
                                h( date_short( Set::classicExtract( $decision, 'datevalidation' ) ) ),
                                h( Set::classicExtract( $decision, 'commentairetechnicien' ) )
                            ),
                            array(
                                'options' => $options
                            )
                        );
                    }
                ?>
            </tbody>
        </table>
        <?php else:?>
            <p class="notice">Aucune décision émise pour ce dossier</p>
        <?php endif;?>
        <?php
            echo "<h2>Pièces jointes</h2>";
            echo $fileuploader->results( Set::classicExtract( $dossierpcg66, 'Fichiermodule' ) );
        ?>
</div>
	<div class="submit">
		<?php
			echo $form->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) );
		?>
	</div>
	<?php echo $form->end();?>
<div class="clearer"><hr /></div>