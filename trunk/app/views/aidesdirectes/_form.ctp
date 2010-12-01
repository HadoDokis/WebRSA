<fieldset>
    <?php echo $form->input( 'Aidedirecte.typo_aide', array( 'label' => required( __d( 'action', 'Action.typo_aide', true ) ), 'type' => 'select', 'options' => $typo_aide, 'empty' => true )  ); ?>
    <?php echo $form->input( 'Aidedirecte.lib_aide', array( 'label' => required( __d( 'action', 'Action.lib_aide', true ) ), 'type' => 'select', 'options' => $actions, 'empty' => true )  ); ?>
    <?php echo $form->input( 'Aidedirecte.date_aide', array( 'label' => required( __d( 'action', 'Action.date_aide', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 , 'empty' => true)  ); ?>
</fieldset>
