<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Candidature';?>

      <div class="">
          <h1>Sélection individuelle de l'action proposée par la structure</h1><br>

          <form method="post" action="suivi_structure_candidate"> 

      <fieldset class="">
          <div class="input date">
              <label>Intitulé de l'action</label>
              <select id="offre" name="offre">
                <option value=""></option>
                <option value="01">Chantier école espaces verts</option>
                <option value="02" selected="Stage d'alphabétisation">Stage d'alphabétisation</option>
                <option value="03">Stage linguistique : niveau 1 - anglais</option>
                <option value="04">Stage linguistique : niveau 2 - anglais</option>
              </select>
          </div>
      <div class="input date">
              <label>Date prévisionnelle de début de l'action</label>
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
                <option value="21" selected="21">21</option>
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
                <option value="10" selected="octobre">octobre</option>
                <option value="11">novembre</option>
                <option value="12">décembre</option>
                </select>-
                <select>
                <option value=""></option>
                <option value="2010" selected="2010">2010</option>
                <option value="2009">2009</option>
                <option value="2008">2008</option>
                <option value="2007">2007</option>
                <option value="2006">2006</option>
                <option value="2005">2005</option>
                </select>
          </div>
      <div class="input date">
              <label>Date prévisionnelle de fin de l'action</label>
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
                <option value="22" selected="22">22</option>
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
                <option value="10" selected="octobre">octobre</option>
                <option value="11">novembre</option>
                <option value="12">décembre</option>
                </select>-
                <select>
                <option value=""></option>
                <option value="2011" selected="2011">2011</option>
                <option value="2010">2010</option>
                <option value="2009">2009</option>
                <option value="2008">2008</option>
                <option value="2007">2007</option>
                <option value="2006">2006</option>
                <option value="2005">2005</option>
                </select>
          </div>
      </fieldset>

      <fieldset>

                <div class="input text">
                <label style="vertical-align: top;">Coût total de l'action</label>
                    <input type="text" value="2400 €" >
                </div>
                <div class="input text">
                <label style="vertical-align: top;">Montant total de la subvention sollicitée (CG et FSE)</label>
                    <input type="text" value="1000 €" >
                </div>

      </fieldset>

      <fieldset class="">
          <legend>Type d'action</legend>
                <div class="input date">
                    <label>Civilité du responsable de l'action</label>
                    <select id="civilite" name="civilite">
                      <option value=""></option>
                      <option value="MLE">Mademoiselle</option>
                      <option value="MME">Madame</option>
                      <option value="MR" selected="Monsieur">Monsieur</option>
                    </select>

                </div>
                <div class="input text">
                <label style="vertical-align: top;">Nom du responsable de l'action</label>
                    <input type="text" value="BUFFIN">
                </div>
                <div class="input text">
                <label style="vertical-align: top;">Prénom du responsable de l'action</label>
                    <input type="text" value="Christian" >
                </div>
                <div class="input text">
                <label style="vertical-align: top;">N° du téléphone du responsable de l'action</label>
                    <input type="text" maxlength="10" value="0404040404">
                </div>
                <div class="input text">
                <label style="vertical-align: top;">Nom de la personne à contacter pour le recrutement des stagiaires</label>
                    <input type="text" value="MR FEYDEL Pascal">
                </div>

        <label> Type d'action :   </label>
            <div class="multiselect text" id="type_action_1">
              <label><input type="checkbox" value="1" checked="Alpha - A1.1 - A1" />Alpha - A1.1 - A1 </label>
              <label><input type="checkbox" value="2" checked="Alpha - A2 " />Alpha - A2 </label>
              <label><input type="checkbox" value="3" />Français Langue Etrangère - A1</label>
              <label><input type="checkbox" value="4" />Français Langue Etrangère - A2</label>
              <label><input type="checkbox" value="5" />Linguistique B1 CECR</label>
              <label><input type="checkbox" value="6" />RAN</label>

              <label><input type="checkbox" value="7" />Redynamisation</label>
              <label><input type="checkbox" value="8" />Luttre contre l'illétrisme</label>
              <label><input type="checkbox" value="9" />Pré qualifiant</label>
              <label><input type="checkbox" value="10" />Qualifiant</label>
              <label><input type="checkbox" value="11" />Pré qualifiant/Qualifiant + Linguistique</label>
          <div>
      <br />
          <div class="input text">
          <label style="vertical-align: top;">Lieux de l'action</label>
              <input type="text" value="Bobigny">
          </div>
          <div class="input text">
          <label style="vertical-align: top;">Nombre de places disponibles</label>
              <input type="text" value="15">
          </div>
      </fieldset>


          <fieldset>
            <legend>Durée</legend>

            <div class="input text">
                <label style="vertical-align: top;">Nombre total d'heures de l'action</label>
                    <input type="text" maxlength="5" value="48">
            </div>

            <label style="vertical-align: top;">Nombre total d'heures par stagiaires :</label>
            <div class="input text" style="  margin-left:2em;">
                <label style="vertical-align: top;">En centre :</label>
                    <input type="text" maxlength="5" value="28">
                <label style="vertical-align: top;">En entreprise :</label>
                    <input type="text" maxlength="5" value="20">
            </div>
      </fieldset>
        <fieldset type="checkbox">
            <?php
                function radioApre( $view, $path, $value, $label ) {
                    $name = 'data['.implode( '][', explode( '.', $path ) ).']';
                    $notEmptyValues = Set::filter( Set::classicExtract( $view->data, $value ) );
                    $checked = ( ( !empty( $notEmptyValues ) ) ? 'checked="checked"' : 'checked' );

                    return "<label><input type=\"checkbox\" name=\"{$name}\" value=\"{$value}\" {$checked} />{$label}</label>";
                }
            ?>

            <fieldset id="Moyenhumain" class="invisible">
                <?php
                    $tmp = radioApre( $this, "", 'Moyenhumain', 'Définir les moyens humains' );
                    echo $html->tag( 'h3', $tmp );
                ?>
                    <div class="input text">
                        <label >Nombre de chefs de projets</label>
                        <input typetype="text" maxlength="10" value="2">
                    </div>
                    <div class="input text">
                        <label style="vertical-align: top;">Nombre de formateurs</label>
                        <input typetype="text" maxlength="10" value="1">
                    </div>

            </fieldset>
            <fieldset id="Moyentechnique" class="invisible">
                <?php
                    $tmp = radioApre( $this, "", 'Moyentechnique', 'Définir les moyens techniques');

                    echo $html->tag( 'h3', $tmp );
                ?>
                    <div class="input text">
                        <label >Capacité de la salle</label>
                        <input typetype="text" maxlength="10" value="35 personnes">
                    </div>
                    <div class="input text">
                        <label style="vertical-align: top;">Equipements</label>
                        <input typetype="text" maxlength="10" value="Rétro-projecteur, Paper-board">
                    </div>
                    <div class="input text">
                        <label style="vertical-align: top;">Matériel informatique</label>
                        <input typetype="text" maxlength="10" value="Ordinateur portable">
                    </div>

            </fieldset>
        </fieldset>

        <fieldset><legend>PROPOSITION DE L'INSTRUCTEUR</legend>

            <div class="input date">
                <label>Décision</label>
                <select name="organisme">
                    <option value=""></option>
                    <option value="retenu">Action retenue</option>
                    <option value="rejete">Action ajournée</option>
                    <option value="rejete">Action rejetée</option>
                </select>
            </div>

            <div class="input date">
                <label>Type de motif</label>
                <select name="motif" <!--disabled="disabled"--> >
                    <option value=""></option>
                    <option value="1">Manque de pièces</option>
                    <option value="2">...</option>
                </select>
            </div>


            <div class="input text">
                <label style="vertical-align: top;">Motif détaillé</label>
                <input typetype="text" maxlength="10" value=""  >
            </div>
        </fieldset>


        <div class="submit">
            <input value="Enregistrer projet" type="submit">
<!--            <input value="Favorable" type="submit">
            <input value="Défavorable" type="submit">
            <input value="Ajourné" type="submit">
            <input value="Rejeter" type="submit">-->
        </div>
    </form></div>

<div class="clearer"><hr></div>            </div>

        </div>

    </body></html>