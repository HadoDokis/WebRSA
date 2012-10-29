<?php if( !empty( $referent ) ): ?>
	<fieldset>
		<legend>Structure référente</legend>
		<table class="wide noborder">
			<tr>
				<td class="wide noborder" style="width: 400px"><strong>Nom de la structure liée au référent : </strong></td>
				<td class="wide noborder">
					<?php echo Set::classicExtract( $referent, 'Structurereferente.lib_struc' ); ?>
				</td>
			</tr>
		</table>
	</fieldset>
<?php endif;?>