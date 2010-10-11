<?php  $this->pageTitle = 'Grossesses de la personne';?>

<?php  echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>


<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

    <?php if( empty( $grossesse ) ):?>
        <p class="notice">Cette personne n'a pas eu de grossesses.</p>

    <?php else:?>
            <table class="tooltips">
                <thead>
                    <tr>
                        <th>Date de début de grossesse</th>
                        <th>Date de fin de grossesse</th>
                        <th>Date de déclaration de grossesse</th>
                        <th>Nature de l'interruption</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                        echo $html->tableCells(
                            array(
                                h( date_short( $grossesse['Grossesse']['ddgro'] ) ),
                                h( date_short( $grossesse['Grossesse']['dfgro'] ) ),
                                h( date_short( $grossesse['Grossesse']['dtdeclgro'] ) ),
                                h( $natfingro[$grossesse['Grossesse']['natfingro']] ),
                            ),
                            array( 'class' => 'odd' ),
                            array( 'class' => 'even' )
                        );
                    ?>
                </tbody>
            </table>
    <?php endif;?>
</div>
<div class="clearer"><hr /></div>
