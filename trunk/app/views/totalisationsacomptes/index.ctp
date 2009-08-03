<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php  $this->pageTitle = 'Versement d\'acompte RSA';?>

<h1><?php echo $this->pageTitle;?></h1>

<?php
    if( is_array( $this->data ) ) {
        echo '<ul class="actionMenu"><li>'.$html->link(
            $html->image(
                'icons/application_form_magnify.png',
                array( 'alt' => '' )
            ).' Formulaire',
            '#',
            array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Search' ).toggle(); return false;" )
        ).'</li></ul>';
    }

    function thead( $pct = null ) {
        return '<thead>
                <tr>
                    <th style="width: '.$pct.'%;"></th>
                    <th style="width: '.$pct.'%;"></th>
                    <th style="width: '.$pct.'%;"></th>
                </tr>
            </thead>';
    }
?>

<?php echo $form->create( 'Totalisationsacomptes', array( 'type' => 'post', 'action' => '/index/', 'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) ) ? 'folded' : 'unfolded' ) ) );?>
        <fieldset>
            <?php echo $form->input( 'Filtre.dtref', array( 'label' => 'Recherche des versements pour le mois de ', 'type' => 'date', 'dateFormat' => 'MY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) ) );?>
    </fieldset>

    <div class="submit noprint">
        <?php echo $form->button( 'Rechercher', array( 'type' => 'submit' ) );?>
        <?php echo $form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
    </div>
<?php echo $form->end();?>

<!-- Résultats -->
<?php if( isset( $totsacoms ) ):?>

   <?php $mois = strftime('%B %Y', strtotime( $this->data['Filtre']['dtref']['year'].'-'.$this->data['Filtre']['dtref']['month'].'-01' ) ); ?>

    <h2 class="noprint">Liste des versements d'allocation pour le mois de <?php echo isset( $mois ) ? $mois : null ; ?> </h2>

    <?php if( is_array( $totsacoms ) && count( $totsacoms ) > 0  ):?>

        <table id="searchResults" class="tooltips_oupas">
        <?php echo thead( 40 );?>
            <?php foreach( $totsacoms as $totacom ) :?>
                <tbody>
                    <tr class="even">
                        <th><?php echo $type_totalisation[$totacom['Totalisationacompte']['type_totalisation']];?></th>
                        <th>Total acomptes transmis (CAF/MSA)</th>
                        <th>Total acomptes calculés</th>
                    </tr>
                    <tr class="odd">
                        <td>RSA socle</td>
                        <td><?php echo $totacom['Totalisationacompte']['mttotsoclrsa'];?></td>
                        <td></td>
                    </tr>
                    <tr class="even">
                        <td>RSA socle majoré</td>
                        <td><?php echo $totacom['Totalisationacompte']['mttotsoclmajorsa'];?></td>
                        <td></td>
                    </tr>
                    <tr class="odd">
                        <td>RSA local</td>
                        <td><?php echo $totacom['Totalisationacompte']['mttotlocalrsa'];?></td>
                        <td></td>
                    </tr>
                </tbody>
            <?php endforeach; ?>
            <!-- <?php /*foreach( $totsacoms as $totacom ) :*/?> -->
                <tr class="even">
                    <th>Soit un total de versement de </th>
                    <td>
                        <?php echo $totsacoms[0]['Totalisationacompte']['mttotsoclrsa'] + $totsacoms[0]['Totalisationacompte']['mttotsoclmajorsa'] + $totsacoms[0]['Totalisationacompte']['mttotlocalrsa']+$totsacoms[1]['Totalisationacompte']['mttotsoclrsa'] + $totsacoms[1]['Totalisationacompte']['mttotsoclmajorsa'] + $totsacoms[1]['Totalisationacompte']['mttotlocalrsa'];?> 
                    </td>
                    <td><!--<?php ?>--> </td>
                </tr>
            <!-- <?php /*endforeach;*/?> -->
        </table>
       <!-- <ul class="actionMenu">
            <?php
                echo $html->printLink(
                    'Imprimer le tableau',
                    array( 'controller' => 'gedooos', 'action' => 'notifications_cohortes' )
                );
            ?>

            <?php
                echo $html->exportLink(
                    'Télécharger le tableau',
                    array( 'controller' => 'cohortes', 'action' => 'exportcsv' )
                );
            ?>
        </ul> -->
    <?php else:?>
        <p>Vos critères n'ont retourné aucun dossier.</p>
    <?php endif?>

<?php endif?>
