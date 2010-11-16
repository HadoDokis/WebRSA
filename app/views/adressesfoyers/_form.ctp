        <?php echo $form->input( 'Adresse.numvoie', array( 'label' =>   __d( 'adresse', 'Adresse.numvoie', true ) ) );?>
        <?php echo $form->input( 'Adresse.typevoie', array( 'label' =>  required( __d( 'adresse', 'Adresse.typevoie', true ) ), 'type' => 'select', 'options' => $typevoie, 'empty' => true ) );?>
        <?php echo $form->input( 'Adresse.nomvoie', array( 'label' =>  required( __d( 'adresse', 'Adresse.nomvoie', true ) ) ) );?>
        <?php echo $form->input( 'Adresse.complideadr', array( 'label' =>  __d( 'adresse', 'Adresse.complideadr', true ) ) );?>
        <?php echo $form->input( 'Adresse.compladr', array( 'label' =>  __d( 'adresse', 'Adresse.compladr', true ) ) );?>
        <?php echo $form->input( 'Adresse.lieudist', array( 'label' =>  __d( 'adresse', 'Adresse.lieudist', true ) ) );?>
        <?php echo $form->input( 'Adresse.numcomrat', array( 'label' =>  __d( 'adresse', 'Adresse.numcomrat', true ) ) );?>
        <?php echo $form->input( 'Adresse.numcomptt', array( 'label' =>  required( __d( 'adresse', 'Adresse.numcomptt', true ) ) ) );?>
        <?php echo $form->input( 'Adresse.codepos', array( 'label' =>  required( __d( 'adresse', 'Adresse.codepos', true ) ) ) );?>
        <?php echo $form->input( 'Adresse.locaadr', array( 'label' =>  required( __d( 'adresse', 'Adresse.locaadr', true ) ) ) );?>
        <?php echo $form->input( 'Adresse.pays', array( 'label' =>  required( __d( 'adresse', 'Adresse.pays', true ) ), 'type' => 'select', 'options' => $pays, 'empty' => true ) );?>
        <?php echo $form->input( 'Adresse.canton', array( 'label' =>  __d( 'adresse', 'Adresse.canton', true ) ) );?>

        <?php if( $this->name == 'Adressefoyers' ):?>
            <?php echo $form->input( 'Adressefoyer.rgadr', array( 'label' => required( __d( 'adressefoyer', 'Adressefoyer.rgadr', true ) ), 'type' => 'select', 'options' => $rgadr, 'empty' => true ) );?>
        <?php endif;?>
        <?php echo $form->input( 'Adressefoyer.dtemm', array( 'label' =>  __d( 'adressefoyer', 'Adressefoyer.dtemm', true ), 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => ( date( 'Y' ) - 100 ), 'empty' => true ) );?>
        <?php echo $form->input( 'Adressefoyer.typeadr', array( 'label' => required( __d( 'adressefoyer', 'Adressefoyer.typeadr', true ) ), 'type' => 'select', 'options' => $typeadr, 'empty' => true ) );?>
