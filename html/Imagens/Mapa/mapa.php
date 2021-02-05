<table style="border-collapse: collapse">
	<? for($y=1;$y<201;$y++){ ?>
		<tr>
			<? for($x=1;$x<201;$x++){ ?>
				<td style="padding: 0px;">
					<img src="Mapa_Oceano/Mapa_<? echo str_pad($x,2,'0', STR_PAD_LEFT) ?>_<? echo str_pad($y,2,'0', STR_PAD_LEFT) ?>.jpg" title="<? echo str_pad($x,2,'0', STR_PAD_LEFT) ?>_<? echo str_pad($y,2,'0', STR_PAD_LEFT) ?>" width="10px" />
				</td>
			<? } ?>
		</tr>
	<? } ?>
</table>