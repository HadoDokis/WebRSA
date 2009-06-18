<div class="submit">
    <?php echo $form->button( 'Imprimer', array( 'type' => 'submit' ) );?>
</div>

<?php if( isset( $orients ) ):?>
<h2>Résultats de la recherche</h2>

<?php if( is_array( $orients ) && count( $orients ) > 0  ):?>
        <table>
            <tbody>
                <tr class="even">
                    <th><?php __( 'Numéro dossier' );?></th>
                    <th><?php __( 'Allocataire' );?></th>
                    <th><?php __( 'numtel' );?></th>
                    <th><?php __( 'locaadr' );?></th>
                    <th><?php __( 'Date d\'ouverture droits' );?></th>
                    <th><?php __( 'Date d\'orientation' );?></th>
                    <th><?php __( 'Structure référente' );?></th>
                </tr>
                <?php foreach( $orients as $orient ):?>
                    <tr>
                        <td><?php echo $orient['Dossier']['numdemrsa'];?></td>
                        <td><?php echo $orient['Personne']['qual'].' '.$orient['Personne']['nom'].' '.$orient['Personne']['prenom'];?></td>
                        <td><?php echo $orient['ModeContact']['numtel'];?></td>
                        <td><?php echo $orient['Adresse']['locaadr'];?></td>
                        <td><?php echo $orient['Dossier']['dtdemrsa'];?></td>
                        <td><?php echo $orient['Orientstruct']['date_propo'];?></td>
                        <td><?php echo isset( $sr[$orient['Orientstruct']['structurereferente_id']] ) ? $sr[$orient['Orientstruct']['structurereferente_id']] : null;?></td>
                    </tr>
               <?php endforeach;?>
            </tbody>
        </table>
    <?php endif?>
<?php endif?>
<div class="clearer"><hr /></div>

