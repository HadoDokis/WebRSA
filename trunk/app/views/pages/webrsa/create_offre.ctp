<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php $this->pageTitle = 'Création d\'offres';?>


    <div class="">
        <h1>Nouvelle offre</h1><br>

        <form method="post" action="recherche_offre">


        <fieldset>
          <legend>Détails de l'offre</legend>

            <div class="input text">
                <label>Type d'action</label>
                <select>
                    <option value=""></option>
                    <option value="01">Formations</option>
                    <option value="02">Actions de SIAE</option>
                    <option value="03">CUIs (ex contrats aidés)</option>
                    <option value="04">Aides à la création d'entreprise</option>
                    <option value="05">Mesures ASI</option>
                    <option value="06">Actions d'insertion / santé</option>
                    <option value="07">Actions d'insertion / social spécifique</option>
                    <option value="08">Frais annexes à la formation</option>
                    <option value="09">APRE</option>
                </select>
            </div>

            <div class="input text">
                <label style="vertical-align: top;">Thèmes</label>
                <input type="text" >
            </div>


            <div class="input text" >
                <label style="vertical-align: top;"> Public concerné   </label>
                <input type="checkbox" value="1" />Public 1
                <input type="checkbox" value="2" />Public 2
                <input type="checkbox" value="3" />Public 3
                <input type="checkbox" value="4" />Public 4
            </div>

            <div class="input text ">
                <label style="vertical-align: top;">Description</label>
                <textarea cols="50" rows="3" type="textarea" maxlength="250"></textarea>
            </div>
            <div class="input text ">
                <label style="vertical-align: top;">Durée de l'action</label>
                <input type="text" >
            </div>

          <div class="input date">
            <label>Date de début de l'action</label>
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
          <div class="input date">
            <label>Date de fin de l'action</label>
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
              <label style="vertical-align: top;">Partenaires associés</label>
                  <input type="text" >
              </div>
          </fieldset>

            <div class="submit"><input value="Enregistrer offre" type="submit"></div>

        </form></div>

<div class="clearer"><hr></div>            </div>

        </div>

    </body></html>