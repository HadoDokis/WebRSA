<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Préconisation d\'orientation RSA';?>

<h1><?php echo $this->pageTitle;?></h1>

<?php echo $form->create( 'Dossiersimplifie',array( 'url' => Router::url( null, true ) ) );?>
<!--
le NIR (N° Sécu) et le N° National de la demande d'instruction à 9
chiffres, le nom, le prénom, l'adresse du bénéficiaire(elle permettra de
déterminer le territoire et donc l'adresse de la structure en charge du
suivi), la date de naissance ainsi que le type d'orientation et
l'organisme vers lequel il y aura orientation (si ouverture du droit).
-->
    <h2>Dossier RSA</h2>
    <?php echo $form->input( 'Dossier.numdemrsa', array( 'label' => required( 'Numéro de demande RSA' ) ) );?>
    <?php echo $form->input( 'Dossier.dtdemrsa', array( 'label' => required( 'Date de demande' ), 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 1, 'minYear' => date( 'Y' ) - 1 ) );?>

    <h2>Demandeur</h2>
    <div><?php echo $form->input( 'Personne.0.rolepers', array( 'label' => required( __( 'rolepers', true ) ), 'value' => 'DEM', 'type' => 'hidden') );?></div>
    <?php echo $form->input( 'Personne.0.qual', array( 'label' => required( __( 'qual', true ) ), 'type' => 'select', 'options' => $qual, 'empty' => true ) );?>
    <?php echo $form->input( 'Personne.0.nom', array( 'label' => required( __( 'nom', true ) ) ) );?>
    <?php echo $form->input( 'Personne.0.prenom', array( 'label' => required( __( 'prenom', true ) ) ) );?>
    <?php echo $form->input( 'Personne.0.nir', array( 'label' => required( __( 'nir', true ) ) ) );?>
    <?php echo $form->input( 'Personne.0.dtnai', array( 'label' => required( __( 'dtnai', true ) ), 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => ( date( 'Y' ) - 100 ), 'empty' => true ) );?>
    <?php echo $form->input( 'Personne.0.toppersdrodevorsa', array(  'label' =>  required( __( 'toppersdrodevorsa', true ) ), 'options' => $toppersdrodevorsa, 'type' => 'select', 'empty' => 'Non défini'  ) );?>
    <h3>Orientation</h3>
    <?php echo $form->input( 'Typeorient.0.id', array( 'label' => required( __( 'lib_type_orient', true ) ), 'type' => 'select', 'options' => $typesOrient, 'empty' => true ) );?>
    <?php echo $form->input( 'TypeStruct.0.id', array( 'label' => required(__( 'lib_struc', true  )), 'type' => 'select', 'options' => $typesStruct, 'empty' => true ) );?>
    <?php echo $form->input( 'Orientstruct.0.structurereferente_id', array( 'label' => required(__( 'structure_referente', true  )), 'type' => 'select', 'options' => $structsReferentes, 'empty' => true ) );?>


    <h2>Conjoint</h2>
    <div> <?php echo $form->input( 'Personne.1.rolepers', array( 'label' =>  __( 'rolepers', true ) , 'value' => 'CJT', 'type' => 'hidden') );?></div>
    <?php echo $form->input( 'Personne.1.qual', array( 'label' =>  __( 'qual', true ) , 'type' => 'select', 'options' => $qual, 'empty' => true ) );?>
    <?php echo $form->input( 'Personne.1.nom', array( 'label' =>  __( 'nom', true )  ) );?>
    <?php echo $form->input( 'Personne.1.prenom', array( 'label' =>  __( 'prenom', true  ) ) );?>
    <?php echo $form->input( 'Personne.1.nir', array( 'label' =>  __( 'nir', true ) ) );?>
    <?php echo $form->input( 'Personne.1.dtnai', array( 'label' =>  __( 'dtnai', true  ), 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => ( date( 'Y' ) - 100 ), 'empty' => true ) );?>
    <?php echo $form->input( 'Personne.1.toppersdrodevorsa', array(  'label' =>   __( 'toppersdrodevorsa', true ), 'options' => $toppersdrodevorsa, 'type' => 'select', 'empty' => 'Non défini'  ) );?> 
    <h3>Orientation</h3>
    <?php echo $form->input( 'Typeorient.1.id', array( 'label' => required( __( 'lib_type_orient', true ) ), 'type' => 'select', 'options' => $typesOrient, 'empty' => true ) );?>
    <?php echo $form->input( 'TypeStruct.1.id', array( 'label' => required(__( 'lib_struc', true  )), 'type' => 'select', 'options' => $typesStruct, 'empty' => true ) );?>
    <?php echo $form->input( 'Orientstruct.1.structurereferente_id', array( 'label' => required(__( 'structure_referente', true  )), 'type' => 'select', 'options' => $structsReferentes, 'empty' => true ) );?>
     <!--<h2>Adresse</h2>
    <?php echo $form->input( 'Adresse.numvoie', array( 'label' =>   __( 'numvoie', true ) ) );?>
    <?php echo $form->input( 'Adresse.typevoie', array( 'label' =>  required( __( 'typevoie', true ) ) ) );?>
    <?php echo $form->input( 'Adresse.nomvoie', array( 'label' =>  required( __( 'nomvoie', true ) ) ) );?>
    <?php echo $form->input( 'Adresse.complideadr', array( 'label' =>  __( 'complideadr', true ) ) );?>
    <?php echo $form->input( 'Adresse.compladr', array( 'label' =>  __( 'compladr', true ) ) );?>
    <?php echo $form->input( 'Adresse.codepos', array( 'label' =>  required( __( 'codepos', true ) ) ) );?>
    <?php echo $form->input( 'Adresse.locaadr', array( 'label' =>  required( __( 'locaadr', true ) ) ) );?>
    <?php echo $form->input( 'Adresse.pays', array( 'label' =>  required( __( 'pays', true ) ), 'type' => 'select', 'options' => $pays, 'empty' => true ) );?>
   <?php echo $form->input( 'Adresse.canton', array( 'label' =>  __( 'canton', true ) , 'type' => 'select', 'empty' => true ) );?> -->

    <?php echo $form->submit( 'Enregistrer' );?>
<?php echo $form->end();?>