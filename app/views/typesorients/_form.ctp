<fieldset>
    <?php echo $form->input( 'Typeorient.id', array( 'label' => 'id', 'type' => 'hidden' ) );?>
    <?php echo $form->input( 'Typeorient.lib_type_orient', array( 'label' =>  __( 'lib_type_orient', true ), 'type' => 'text' ) );?>
    <?php echo $form->input( 'Typeorient.parentid', array( 'label' => __( 'parentid', true ), 'type' => 'select', 'options' => $parentid, 'empty' => true )  );?>
    <?php echo $form->input( 'Typeorient.modele_notif', array( 'label' => __( 'modele_notif', true ), 'type' => 'text' )  );?>   
   <?php /*echo $form->input( 'Typeorient.modele_notif', array( 'label' => __( 'modele_notif', true ), 'type' => 'select' , 'options' => $notif, 'empty' => true ) );*/?> 
</fieldset>
<table>
<thead>
    <tr>
        <th>ID</th>
        <th>Type d'orientation</th>
        <th>Parent</th>
        <th>Modèle de notification</th>
    </tr>
</thead>
<tbody>
    <?php foreach( $typesorients as $typeorient ):?>
        <?php echo $html->tableCells(
                    array(
                        h( $typeorient['Typeorient']['id'] ),
                        h( $typeorient['Typeorient']['lib_type_orient'] ),
                        h( $typeorient['Typeorient']['parentid'] ),
                        h( $typeorient['Typeorient']['modele_notif'] ),
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
        ?>
    <?php endforeach;?>
    </tbody>
</table>