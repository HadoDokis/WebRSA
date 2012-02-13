
<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Demande de remboursement';?>

<div class="">
    <h1>Demande de remboursement</h1><br>

    <form method="post" action="">
    <fieldset><legend> Type demande </legend>
  <div class="input radio">
Demande de remboursement <input name="" value="E" checked="checked" type="radio">
Demande de prise en charge <input name="" value="E" checked="checked" type="radio">

	</div>
    </fieldset>
    <fieldset><legend> Détails </legend>
	<div class="input date">
	    <label>Date demande</label>
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
	    <label>Motif remboursement</label>
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
	      </select>
	</div>
	<div class="input text">
	<label style="vertical-align: top;">Montant remboursement</label>
	    <input typetype="text" maxlength="10">
	</div>
	<div class="input text">
	<label style="vertical-align: top;">Commentaires</label>
	    <textarea cols="50" rows="3" type="textarea" maxlength="250"></textarea>
	</div>
	<div class="input text">
<label style="vertical-align: top;">Justificatifs</label>
	<input type="file">
	</div>

    </fieldset>
        <div class="submit"><input value="Enregistrer" type="submit"></div>    </form></div>

<div class="clearer"><hr></div>            </div>

        </div>

            <div id="pageFooter">
    webrsa v. 1.0.4.398 (CakePHP v. 1.2.2.8120) - 2009@Adullact.
    Page construite en 0,34 secondes.    $LastChangedDate: 2009-07-27 17:51:26 +0200 (lun., 27 juil. 2009)$
</div>

    </body></html>