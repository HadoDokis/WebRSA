<?php  $this->pageTitle = 'Modes de contact';?>

<?php echo $this->element( 'dossier_menu', array( 'foyer_id' => $foyer_id ) );?>
<div class="with_treemenu">
    <h1>Modes de contact</h1>

    <?php if( $permissions->check( 'modescontact', 'add' ) ) :?>
        <ul class="actionMenu">
            <?php
                echo '<li>'.$xhtml->addLink(
                    'Ajouter un mode de contact au foyer',
                    array( 'controller' => 'modescontact', 'action' => 'add', $foyer_id )
                ).' </li>';
            ?>
        </ul>
    <?php endif;?>

    <?php if( !empty( $modescontact ) ):?>
        <table class="tooltips">
                <thead>
                    <tr>
                        <th>N° téléphone</th>
                        <th>N° de poste</th>
                        <th>Nature du téléphone </th>
                        <th>Type de téléphone </th>
                        <th>Autorisation utilisation téléphone</th>
                        <th>Adresse électronique</th>
                        <th>Autorisation utilisation email</th>
                        <th colspan="2" class="action">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach( $modescontact as $index => $modecontact ):?>
                    <?php
                    	if (isset( $autorutitel[$modecontact['Modecontact']['autorutitel']] ) && $modecontact['Modecontact']['autorutitel'] != 'R' ) {
	                    	$numtel = h( $modecontact['Modecontact']['numtel'] );
	                    	$numposte = h( $modecontact['Modecontact']['numposte']);
	                    }
	                    else {
	                    	$numtel = null;
	                    	$numposte = null;
	                    }
	                    
	                    if (isset( $autorutiadrelec[$modecontact['Modecontact']['autorutiadrelec']] ) && $modecontact['Modecontact']['autorutiadrelec'] != 'R' )
	                    	$adrelec = h( $modecontact['Modecontact']['adrelec']);
	                    else
	                    	$adrelec = null;
	                    
                        echo $xhtml->tableCells(
                            array(
                                $numtel,
                                $numposte,
                                h( isset( $nattel[$modecontact['Modecontact']['nattel']] ) ? $nattel[$modecontact['Modecontact']['nattel']] : null ),
                                h( isset( $matetel[$modecontact['Modecontact']['matetel']] ) ? $matetel[$modecontact['Modecontact']['matetel']] : null ),
                                h( isset( $autorutitel[$modecontact['Modecontact']['autorutitel']] ) ?  $autorutitel[$modecontact['Modecontact']['autorutitel']] : null ),
                                $adrelec,
                                h( isset( $autorutiadrelec[$modecontact['Modecontact']['autorutiadrelec']] ) ? $autorutiadrelec[$modecontact['Modecontact']['autorutiadrelec']] : null ),
                                $xhtml->viewlink(
                                    'Voir le mode de contact',
                                    array( 'controller' => 'modescontact', 'action' => 'view', $modecontact['Modecontact']['id'] ),
                                    $permissions->check( 'modescontact', 'view' )
                                ),
                                $xhtml->editlink(
                                    'Modifier le mode de contact',
                                    array( 'controller' => 'modescontact', 'action' => 'edit', $modecontact['Modecontact']['id'] ),
                                    $permissions->check( 'modescontact', 'edit' )
                                )
                            ),
                            array( 'class' => 'odd' ),
                            array( 'class' => 'even' )
                        );
                    ?>
                <?php endforeach;?>
            </tbody>
        </table>
        <?php else:?>
            <p class="notice">Ce foyer ne possède actuellement aucun mode de contact.</p>
        <?php endif;?>
</div>

<div class="clearer"><hr /></div>
