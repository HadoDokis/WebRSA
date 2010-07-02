        <?php echo $form->input( 'Adresse.numvoie', array( 'label' =>   __( 'numvoie', true ) ) );?>
        <?php echo $form->input( 'Adresse.typevoie', array( 'label' =>  required( __( 'typevoie', true ) ), 'type' => 'select', 'options' => $typevoie, 'empty' => true ) );?>
        <?php echo $form->input( 'Adresse.nomvoie', array( 'label' =>  required( __( 'nomvoie', true ) ) ) );?>
        <?php echo $form->input( 'Adresse.complideadr', array( 'label' =>  __( 'complideadr', true ) ) );?>
        <?php echo $form->input( 'Adresse.compladr', array( 'label' =>  __( 'compladr', true ) ) );?>
        <?php echo $form->input( 'Adresse.lieudist', array( 'label' =>  __( 'lieudist', true ) ) );?>
        <?php echo $form->input( 'Adresse.numcomrat', array( 'label' =>  __( 'numcomrat', true ) ) );?>
        <?php echo $form->input( 'Adresse.numcomptt', array( 'label' =>  required( __( 'numcomptt', true ) ) ) );?>
        <?php echo $form->input( 'Adresse.codepos', array( 'label' =>  required( __( 'codepos', true ) ) ) );?>
        <?php echo $form->input( 'Adresse.locaadr', array( 'label' =>  required( __( 'locaadr', true ) ) ) );?>
        <?php echo $form->input( 'Adresse.pays', array( 'label' =>  required( __( 'pays', true ) ), 'type' => 'select', 'options' => $pays, 'empty' => true ) );?>
        <?php echo $form->input( 'Adresse.canton', array( 'label' =>  __( 'canton', true ) ) );?>

        <?php if( $this->name == 'Adressesfoyers' ):?>
            <?php echo $form->input( 'Adressefoyer.rgadr', array( 'label' => required( __( 'rgadr', true ) ), 'type' => 'select', 'options' => $rgadr, 'empty' => true ) );?>
        <?php endif;?>
        <?php echo $form->input( 'Adressefoyer.dtemm', array( 'label' =>  __( 'dtemm', true ), 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => ( date( 'Y' ) - 100 ), 'empty' => true ) );?>
        <?php echo $form->input( 'Adressefoyer.typeadr', array( 'label' => required( __( 'typeadr', true ) ), 'type' => 'select', 'options' => $typeadr, 'empty' => true ) );?>
