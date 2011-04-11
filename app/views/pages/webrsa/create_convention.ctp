<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php $this->pageTitle = 'Création de conventions';?>

<?php
//if( is_array( $this->data ) ) {
echo '<ul class="actionMenu"><li>'.$html->link(
$html->image(
                'icons/application_form_magnify.png',
array( 'alt' => '' )
).' Formulaire',
            '#',
array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Search' ).toggle(); return false;" )
).'</ul>';
//}
?>
<?php
    $typesstructs = array(
        '',
        'Référents',
        'PDV'
    );

    $numconvention = array(
        '',
        '0001',
        '0002',
        '0003'
    );
?>
<?php echo $form->create( 'Convention', array( 'type' => 'post', 'action' => '../pages/display/webrsa/create_convention/', 'id' => 'Search', 'class' => ( !empty( $this->data ) ? 'folded' : 'unfolded' ) ) );?>
<fieldset><legend>Ajout d'une nouvelle convention</legend>

<?php
    echo $form->input( 'Convention.type_struct', array('disabled'=>false, 'label' => 'Choisir votre type de formulaire', 'options' => $typesstructs ) );
?>
</fieldset>
<div class="submit noprint"><?php echo $form->button( 'Rechercher', array( 'type' => 'submit' ) );?>
<?php echo $form->button( 'Réinitialiser', array( 'type'=>'reset' ) );?>
</div>
<?php echo $form->end();?>

<?php if( !empty( $this->data ) ):?>
<!-- Mettre que si on choisit pdv alors le formulaire de dessous doit etre different de si on choisit Referent -->
<?php if( $this->data['Convention']['type_struct'] == '2' ):?>

    <div class="">
        <h1>Nouvelle convention pour les PDVs</h1><br>

        <form method="post" action="liste_convention"> 


        <fieldset>
          <legend>Détails de la convention</legend>
          <div class="input date">
            <label>Date du Conseil Municipal</label>
            <select>
              <option value=""></option>
              <option value="01">1</option>
              <option value="02">2</option>
              <option value="03">3</option>
              <option value="04">4</option>
              <option value="05">5</option>
              <option value="06">6</option>
              <option value="07">7</option>
              <option value="08">8</option>
              <option value="09">9</option>
              <option value="10">10</option>
              <option value="11">11</option>
              <option value="12">12</option>
              <option value="13">13</option>
              <option value="14">14</option>
              <option value="15">15</option>
              <option value="16">16</option>
              <option value="17">17</option>
              <option value="18">18</option>
              <option value="19">19</option>
              <option value="20">20</option>
              <option value="21">21</option>
              <option value="22">22</option>
              <option value="23">23</option>
              <option value="24">24</option>
              <option value="25">25</option>
              <option value="26">26</option>
              <option value="27">27</option>
              <option value="28">28</option>
              <option value="29">29</option>
              <option value="30">30</option>
              <option value="31">31</option>
              </select>-
              <select>
              <option value=""></option>
              <option value="01">janvier</option>
              <option value="02">février</option>
              <option value="03">mars</option>
              <option value="04">avril</option>
              <option value="05">mai</option>
              <option value="06">juin</option>
              <option value="07">juillet</option>
              <option value="08">août</option>
              <option value="09">septembre</option>
              <option value="10">octobre</option>
              <option value="11">novembre</option>
              <option value="12">décembre</option>
              </select>-
              <select>
              <option value=""></option>
              <option value="2010">2010</option>
              <option value="2009">2009</option>
              <option value="2008">2008</option>
              <option value="2007">2007</option>
              <option value="2006">2006</option>
              <option value="2005">2005</option>
              </select>

              </div>


              <div class="input text">
              <label style="vertical-align: top;">Montant global du financement</label>
                  <input type="text" >
              </div>
          </fieldset>

            <div class="submit"><input value="Enregistrer projet" type="submit"></div>
            <div class="submit"><input value="Créer conventions filles / avenants" type="submit"></div>
        </form></div>

<?php else :?>

<div class="">
    <h1>Nouvelle convention pour les référents</h1><br>

    <form method="post" action="liste_convention"> 


    <fieldset>
      <legend>Détails de la convention</legend>
          <div class="input date">
              <label>Type de structure</label>
              <select id="type_struct" name="type_struct">
                <option value=""></option>
                <option value="COM">Commune</option>
                <option value="CCA">CCAS</option>
                <option value="CAG">Communauté d'agglomération</option>
                <option value="ASS">Association</option>
              </select>

          </div>


          <div class="input text">
          <label style="vertical-align: top;">Nom de la structure de convention / ville</label>
              <input type="text" >
          </div>

          <div class="input date">
              <label>Civilité du Maire / du Président</label>
              <select id="civilite" name="civilite">
                <option value=""></option>
                <option value="MLE">Mademoiselle</option>
                <option value="MME">Madame</option>
                <option value="MR">Monsieur</option>
              </select>

          </div>
          <div class="input text">
          <label style="vertical-align: top;">Nom du Maire / du Président</label>
              <input type="text" >
          </div>
          <div class="input text">
          <label style="vertical-align: top;">Prénom du maire / du Président</label>
              <input type="text" >
          </div>
<div class="input date">
        <label>Date de la commission permanente</label>
        <select>
          <option value=""></option>
          <option value="01">1</option>
          <option value="02">2</option>
          <option value="03">3</option>
          <option value="04">4</option>
          <option value="05">5</option>
          <option value="06">6</option>
          <option value="07">7</option>
          <option value="08">8</option>
          <option value="09">9</option>
          <option value="10">10</option>
          <option value="11">11</option>
          <option value="12">12</option>
          <option value="13">13</option>
          <option value="14">14</option>
          <option value="15">15</option>
          <option value="16">16</option>
          <option value="17">17</option>
          <option value="18">18</option>
          <option value="19">19</option>
          <option value="20">20</option>
          <option value="21">21</option>
          <option value="22">22</option>
          <option value="23">23</option>
          <option value="24">24</option>
          <option value="25">25</option>
          <option value="26">26</option>
          <option value="27">27</option>
          <option value="28">28</option>
          <option value="29">29</option>
          <option value="30">30</option>
          <option value="31">31</option>
          </select>-
          <select>
          <option value=""></option>
          <option value="01">janvier</option>
          <option value="02">février</option>
          <option value="03">mars</option>
          <option value="04">avril</option>
          <option value="05">mai</option>
          <option value="06">juin</option>
          <option value="07">juillet</option>
          <option value="08">août</option>
          <option value="09">septembre</option>
          <option value="10">octobre</option>
          <option value="11">novembre</option>
          <option value="12">décembre</option>
          </select>-
          <select>
          <option value=""></option>
          <option value="2010">2010</option>
          <option value="2009">2009</option>
          <option value="2008">2008</option>
          <option value="2007">2007</option>
          <option value="2006">2006</option>
          <option value="2005">2005</option>
          </select>

      <div class="input aere text">
          <label style="vertical-align: top;">N° de la commission permanente</label>
              <input type="text" >
      </div>


      <div class="input aere text">
          <label style="vertical-align: top;">Adresse de la structure</label>
              <input type="text" >
      </div>

      <div class="input aere text">
          <label style="vertical-align: top;">Durée de la convention</label>
              <input type="text" >
      </div>

      <div class="input aere text">
          <label style="vertical-align: top;">RIB</label>
              <input type="text" >
      </div>
      <div class="input aere text">
          <label style="vertical-align: top;">Montant annuel du financement</label>
              <input type="text" >
      </div>
    </fieldset>

 
        <div class="submit"><input value="Enregistrer projet" type="submit"></div>    </form></div>
<?php endif;?>
<div class="clearer"><hr></div>            </div>

        </div>

    </body></html>
<?php endif;?>