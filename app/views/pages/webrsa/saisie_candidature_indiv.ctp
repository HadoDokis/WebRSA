<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Candidature';?>

      <div class="">
          <h1>Sélection individuelle d'une structure</h1><br>

          <form method="post" action="saisie_candidature_action"> 

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
                <label style="vertical-align: top;">Organisme bénéficiaire</label>
                    <input type="text" value="SIAE">
                </div>

                <div class="input date">
                    <label>Civilité du responsable</label>
                    <select id="civilite" name="civilite">
                      <option value=""></option>
                      <option value="MLE">Mademoiselle</option>
                      <option value="MME">Madame</option>
                      <option value="MR" selected="Monsieur">Monsieur</option>
                    </select>

                </div>
                <div class="input text">
                <label style="vertical-align: top;">Nom du responsable</label>
                    <input type="text" value="AUZOLAT">
                </div>
                <div class="input text">
                <label style="vertical-align: top;">Prénom du responsable</label>
                    <input type="text" value="Arnaud" >
                </div>
                <div class="input text">
                <label style="vertical-align: top;">N° de téléphone du responsable</label>
                    <input type="text" maxlength="10" value="0467659647">
                </div>
                <div class="input text">
                <label style="vertical-align: top;">N° de fax</label>
                    <input type="text" maxlength="10">
                </div>
                <div class="input text">
                <label style="vertical-align: top;">Adresse mail</label>
                    <input type="text" maxlength="10" value="arnaud.auzolat@adullact-projet.coop">
                </div>
      </fieldset>

        <fieldset><legend>Liste des pièces à transmettre</legend>

            <div class="">
                <label>Un exemplaire des statuts régulièrement déposés, du récepissé en Préfecture ou de la parution au JO</label><input checked="checked" style="position: absolute; left: 1000px;" type="checkbox"><br><br>
                <label>La composition du bureau (fonctions et professions) et du conseil d'administration</label><input checked="checked" style="position: absolute; left: 1000px;" type="checkbox"><br><br>
                <label>Si le présent dossier n'est pas signé par le représentant légal de l'association, le pouvoir de ce dernier au signataire</label><input checked="checked" style="position: absolute; left: 1000px;" type="checkbox"><br><br>
                <label>Un relevé d'identité bancaire ou postal de l'association</label><input checked="checked" style="position: absolute; left: 1000px;" type="checkbox"><br><br>

            </div>
        </fieldset>
        <fieldset><legend>PROPOSITION DE L'INSTRUCTEUR</legend>

            <div class="input date">
                <label>Décision</label>
                <select id="decisionorganisme" name="organisme">
                    <option value=""></option>
                    <option value="retenu">Organisme retenu</option>
                    <option value="rejete">Organisme rejeté</option>
                </select>
            </div>

<?php

//     if() {
//         $disabled = "enabled";
//     }

?>
            <div class="input date">
                <label>Type de motif</label>
                <select name="motif">
                    <option value=""></option>
                    <option value="1">Objet de la formation inadéquate</option>
                    <option value="2">...</option>
                </select>
            </div>

            <div class="input text">
                <label style="vertical-align: top;">Motif détaillé</label>
                <input typetype="text" maxlength="10" value="" >
            </div>
        </fieldset>

<script type="text/javascript">
</script>

        <div class="submit">
            <input value="Enregistrer l'organisme" type="submit" href="saisie_candidature_action">
            <input value="Rejeter" type="submit">
        </div>
    </form></div>

<div class="clearer"><hr></div>            </div>

        </div>

    </body></html>