<?php
function musica_aleatoria($pasta){
	if($pasta=="ilha"){
		$min=0;
		$max=3;
		$musica[0]="Ilhas/ilha1";
		$musica[1]="Ilhas/ilha2";
		$musica[2]="Ilhas/ilha3";
		$musica[3]="Ilhas/ilha4";
	}
	else if($pasta=="combate"){
		$min=0;
		$max=0;
		$musica[0]="Combate/combate";
	}
	else{
		$min=0;
		$max=0;
		$musica[0]="Oceano/oceano";
	}
	
	return $musica[rand($min,$max)];
}