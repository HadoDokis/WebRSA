<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php $this->pageTitle = 'Suivi des étapes / pièces (CG) ou par structure';?>
<ul class="actionMenu">
<?php

        echo '<li>'.$xhtml->link(
            $xhtml->image(
                'icons/application_form_magnify.png',
                array( 'alt' => '' )
            ).' Formulaire',
            '#',
            array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Search' ).toggle(); return false;" )
        ).'</li>';

?>
</ul>
<?php
    $etapes = array(
        '',
        'Etape 1',
        'Etape 2',
        'Etape 3',
        'Etape 4',
        'Etape 5',
        'Etape 6',
        'Etape 7',
        'Etape 8',
        'Etape 9'
    );

    $pieces = array(
        '',
        'Piece 1',
        'Piece 2',
        'Piece 3',
        'Piece 4',
        'Piece 5',
        'Piece 6',
        'Piece 7',
        'Piece 8',
        'Piece 9'
    );

    $nomstruct = array(
        '',
        'SIAE',
        'Organisme de formation',
        '...'
    );

    $etatpiece = array(
        'En retard',
        'Reçue',
        'Complète',
        'Relancée'
    );
?>
<?php echo $form->create( 'Structure', array( 'type' => 'post', 'action' => '../pages/display/webrsa/suivi_etapes_pieces/', 'id' => 'Search', 'class' => ( !empty( $this->data ) ? 'folded' : 'unfolded' ) ) );?>
<fieldset>

<?php
    echo $form->input( 'Structure.typestruct', array('disabled'=>false, 'label' => 'Type de structure', 'options' => $nomstruct ) );
    echo $form->input( 'Structure.nomstruct', array('disabled'=>false, 'label' => 'Nom de la structure' ) );
    echo $form->input( 'Structure.etapes', array('disabled'=>false, 'label' => 'Etapes', 'options' => $etapes ) );
    echo $form->input( 'Structure.pieces', array('disabled'=>false, 'label' => 'Pièces', 'options' => $pieces ) );
    echo $form->input( 'Structure.etatpiece', array('disabled'=>false, 'legend' => 'Etat de la pièce', 'type' => 'radio', 'options' => $etatpiece ) );

?>

</fieldset>
<div class="submit noprint"><?php echo $form->button( 'Rechercher', array( 'type' => 'submit' ) );?>

<?php echo $form->button( 'Réinitialiser', array( 'type'=>'reset' ) );?>
</div>
<?php echo $form->end();?>

<?php if( !empty( $this->data ) ):?>

    <div class="">
    <h1>Suivi des étapes / des pièces</h1>
        <table class="tooltips_oupas">
            <thead>
                <tr>
                    <th>Type de structure</th>
                    <th>Structure</th>
                    <th>Pièce / Etape</th>
                    <th>Date butoir</th>
                    <th>Date réception</th>
                    <th>Date dossier complet</th>
                    <th>Nb de relances</th>
                    <th>Date dernière relance</th>
                    <th>Actions</th>
                    <th onchange="allCheckboxes( true ); return false;"><input type="checkbox"/>Tout cocher</th>
                </tr>
            </thead>
            <tbody>
                <tr class="odd dynamic" id="innerTableTrigger0">
                    <td>PDV Drancy</td>
                    <td>Drancy</td>
                    <td>Pièce 2</td>
                    <td>15/11/2010</td>
                    <td>14/11/2010</td>
                    <td>14/11/2010</td>
                    <td>0</td>
                    <td></td>
                    <td>
                        <a href="../webrsa/saisie_candidature_indiv" title=""><img src="liste_appels_a_projet_fichiers/disk.png">Voir</a>
                    </td>
                    <td>
                        <input type="checkbox"  class = "checkbox"/>
                    </td>

                </tr>
                <tr class="even dynamic" id="innerTableTrigger0">
                    <td>PDV Bobigny</td>
                    <td>Bobigny</td>
                    <td>Etape 2</td>
                    <td>15/11/2010</td>
                    <td></td>
                    <td></td>
                    <td>1</td>
                    <td>15/12/2010</td>
                    <td>
                        <a href="../webrsa/saisie_candidature_indiv" title=""><img src="liste_appels_a_projet_fichiers/disk.png">Voir</a>
                    </td>
                    <td>
                        <input type="checkbox" class = "checkbox"/>
                    </td>

                </tr>
            </tbody>
        </table>


    <br>

    <div class="submit">
        <input value="Marquer complet les pièces sélectionnées" type="submit">
        <input value="Relancer les pièces sélectionnées" type="submit">
    </div>

       <ul class="actionMenu">
            <?php

                echo '<li>'.$xhtml->exportLink(
                    'Télécharger le tableau',
                    array(  'action' => '#' )
                ).' </li>';
                echo '<li>'.$xhtml->printLink(
                    'Imprimer',
                    array(  'action' => '#' )
                ).' </li>';
            ?>
        </ul>
</div>
    <div class="clearer"><hr></div>

</body></xhtml>
<?php endif;?>
<script type="text/javascript">
//<![CDATA[
    function allCheckboxes( checked ) {
        $$('input.checkbox').each( function ( checkbox ) {
                $( checkbox ).checked = checked;
        } );
        return false;
    }
//]]>
</script>
