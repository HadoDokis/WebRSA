<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Appel à projets';?>
<div class="">
    <h1>Formulaire de candidature pour organisme de formation</h1><br>
<form method="post" action="saisie_candidature">
    <fieldset class="">
        <div class="input date">
            <label>Intitulé de l'action</label>
            <select id="offre" name="offre">
                <option value=""></option>
                <option value="01" selected>Formation linguistique </option>
                <option value="02">Remise à niveau</option>
                <option value="03">Redynamisation</option>
                <option value="04">Préqualification</option>
                <option value="05">Qualification</option>
            </select>
        </div>
        <div class="input date">
            <label>Date prévisionnelle de début de l'action</label>
                <select>
                    <option value=""></option>
                    <option value="01" selected>1</option>
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
                    <option value="12" selected>décembre</option>
                </select>-
                <select>
                    <option value=""></option>
                    <option value="2010" selected>2010</option>
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
                    <option value="22">22</option>
                    <option value="23">23</option>
                    <option value="24">24</option>
                    <option value="25">25</option>
                    <option value="26">26</option>
                    <option value="27">27</option>
                    <option value="28">28</option>
                    <option value="29">29</option>
                    <option value="30">30</option>
                    <option value="31" selected>31</option>
                </select>-
                <select>
                    <option value=""></option>
                    <option value="01">janvier</option>
                    <option value="02">février</option>
                    <option value="03" selected>mars</option>
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
                    <option value="2011" selected>2011</option>
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
                <label style="vertical-align: top;">Organisme bénéficiaire</label>
                <input type="text" value="Organisme de formation 1" >
            </div>

                <div class="input date">
                    <label>Civilité du responsable</label>
                    <select id="civilite" name="civilite">
                      <option value=""></option>
                      <option value="MLE">Mademoiselle</option>
                      <option value="MME">Madame</option>
                      <option value="MR" selected>Monsieur</option>
                    </select>

                </div>
                <div class="input text">
                <label style="vertical-align: top;">Nom du responsable</label>
                    <input type="text" value="AUZOLAT">
                </div>
                <div class="input text">
                <label style="vertical-align: top;">Prénom du responsable</label>
                    <input type="text" value="Arnaud">
                </div>
                <div class="input text">
                <label style="vertical-align: top;">N° de téléphone du responsable</label>
                    <input type="text" maxlength="10" value="0467659647">
                </div>
                <div class="input text">
                <label style="vertical-align: top;">N° de fax</label>
                    <input type="text" maxlength="10" value="0467659696">
                </div>
                <div class="input text">
                <label style="vertical-align: top;">Adresse mail</label>
                    <input type="text" maxlength="10" value="arnaud.auzolat@adullact-projet.coop">
                </div>
      </fieldset>
      <fieldset>

                <div class="input text">
                <label style="vertical-align: top;">Coût total de l'action</label>
                    <input type="text" value="1500€" >
                </div>
                <div class="input text">
                <label style="vertical-align: top;">Montant total de la subvention sollicitée (CG et FSE)</label>
                    <input type="text" value="1500€">
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
                      <option value="MR" selected>Monsieur</option>
                    </select>

                </div>
                <div class="input text">
                <label style="vertical-align: top;">Nom du responsable de l'action</label>
                    <input type="text" value="BUFFIN">
                </div>
                <div class="input text">
                <label style="vertical-align: top;">Prénom du responsable de l'action</label>
                    <input type="text" value="Christian">
                </div>
                <div class="input text">
                <label style="vertical-align: top;">N° du téléphone du responsable de l'action</label>
                    <input type="text" maxlength="10" value="0467659647">
                </div>
                <div class="input text">
                <label style="vertical-align: top;">Nom de la personne à contacter pour le recrutement des stagiaires</label>
                    <input type="text" maxlength="10" value="DEMARETZ François">
                </div>

        <label> Type d'action :   </label>
            <div class="multiselect text" id="type_action_1">
              <label><input type="checkbox" value="1" />Alpha - A1.1 - A1 </label>
              <label><input type="checkbox" value="2" />Alpha - A2 </label>
              <label><input type="checkbox" value="3" checked/>Français Langue Etrangère - A1</label>
              <label><input type="checkbox" value="4" checked/>Français Langue Etrangère - A2</label>
              <label><input type="checkbox" value="5" checked/>Linguistique B1 CECR</label>
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
              <input type="text" value="20">
          </div>
      </fieldset>


          <fieldset>
            <legend>Durée</legend>

            <div class="input text">
                <label style="vertical-align: top;">Nombre total d'heures de l'action</label>
                    <input type="text" maxlength="5" value="60">
            </div>

            <label style="vertical-align: top;">Nombre total d'heures par stagiaires :</label>
            <div class="input text" style="  margin-left:2em;">
                <label style="vertical-align: top;">En centre :</label>
                    <input type="text" maxlength="5" value="40">
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
                        <input typetype="text" maxlength="10" value="3">
                    </div>

            </fieldset>
            <fieldset id="Moyentechnique" class="invisible">
                <?php
                    $tmp = radioApre( $this, "", 'Moyentechnique', 'Définir les moyens techniques' );
                    echo $html->tag( 'h3', $tmp );
                ?>
                    <div class="input text">
                        <label >Capacité de la salle</label>
                        <input typetype="text" maxlength="10" value="30">
                    </div>
                    <div class="input text">
                        <label style="vertical-align: top;">Equipements</label>
                        <input typetype="text" maxlength="10" value="Chaises, 10 ordinateurs, documents de travail">
                    </div>
                    <div class="input text">
                        <label style="vertical-align: top;">Matériel informatique</label>
                        <input typetype="text" maxlength="10" value="Ordinateur portable, Vidéo-projecteur, Paper-board,...">
                    </div>

            </fieldset>
        </fieldset>
        <fieldset><legend>Liste des pièces à transmettre</legend>

            <div class="">
                <label>Un exemplaire des statuts régulièrement déposés, du récepissé en Préfecture ou de la parution au JO</label><input checked="checked" style="position: absolute; left: 1000px;" type="checkbox"><br><br>
                <label>La composition du bureau (fonctions et professions) et du conseil d'administration</label><input checked="checked" style="position: absolute; left: 1000px;" type="checkbox"><br><br>
                <label>Si le présent dossier n'est pas signé par le représentant légal de l'association, le pouvoir de ce dernier au signataire</label><input checked="checked" style="position: absolute; left: 1000px;" type="checkbox"><br><br>
                <label>Un relevé d'identité bancaire ou postal de l'association</label><input checked="checked" style="position: absolute; left: 1000px;" type="checkbox"><br><br>

            </div>
        </fieldset>
    <div class="submit">
        <a href="candidature_appel_a_projet"><input value="Précédent"  type="submit"></a>
        <input value="Enregistrer" name="Enregistrer" type="submit">

    </div>    </form></div>

<div class="clearer"><hr></div>            </div>
    </body></html>