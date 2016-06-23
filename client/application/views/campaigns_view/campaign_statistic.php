
<div class="row page-title">
	<div class="container  ">
		<div class="col-xs-9">
			<h2><i class="fa fa-bar-chart"></i> Statistique</h2>
		</div>
		<div class="col-xs-3">

		</div>
	</div>
</div>
<br>
<div class="container">
	<div class="row" id="stats-panels">

		<div class="col-md-3">

			<div class="panel panel-default tooltip-help" data-placement="bottom" data-toggle="tooltip"  data-container="body">

				<div class="panel-heading">Date d'envoi</div>
				<div class="panel-body text-center stat-value text-success" id="clicks_percentage"><b class="static"><?php echo $request['date_envoi'] ;?></b></div>

			</div>

		</div><!-- .col -->

		<div class="col-md-3">

			<div class="panel panel-default tooltip-help" data-placement="bottom" data-toggle="tooltip"  data-container="body">

				<div class="panel-heading">Total d'envois</div>
				<div class="panel-body text-center stat-value"><b class="static"><?php echo $request['nbEnvoi'] ;?></b></div>

			</div>

		</div><!-- .col -->

		<div class="col-md-3">

			<div class="panel panel-default tooltip-help" data-placement="bottom" data-toggle="tooltip"  data-container="body">

				<div class="panel-heading">Total réçus</div>
				<div class="panel-body text-center stat-value text-warning" id="unsub_percentage" style="color: #00CE00;"><b class="static"><?php echo $request['nbReceved'] ;?></b></div>

			</div>

		</div><!-- .col -->


		<div class="col-md-3">

			<div class="panel panel-default tooltip-help" data-placement="bottom" data-toggle="tooltip"  data-container="body">

				<div class="panel-heading">Total réfusés</div>
				<div class="panel-body text-center stat-value text-info" id="viewed_percentage" style="color: red;"><b class="static"><?php echo $request['nbRefu'] ;?></b></div>

			</div>

		</div><!-- .col -->

	</div>
	
	<div class="row">
		<center><button class="btn btn-primary" id="nbrefus" >Afficher les numeros réfusés</button></center>
	</div>
	<br>
	<div class=" panel panel-default col-md-4 col-md-push-4" id="nums" style='padding-top: 10px; padding-bottom: 10px;'>		<?php 
		$DetailDlr = $request['DetailDlr'];
        $nbrNum = $request['nbEnvoi']; 
        $nums = '';
        $msg = 'Aucun numero réfusé';
        for ($i=0; $i < $nbrNum; $i++) { 
            if($DetailDlr[$i]['dlr'] !== "1"){                    
                if($i === 0){
                	$msg = "<p><li>".substr($DetailDlr[$i]['numreceiver'],-8)."</li>";
                	if($DetailDlr[$i]['dlr'] === '2'){ $msg .= "Echec de l'envoi par l'operateur</p>";}
                	if($DetailDlr[$i]['dlr'] === '16'){ $msg .= "SMS rejeté par l'operateur</p>";}
                    $nums .= substr($DetailDlr[$i]['numreceiver'],-8);
                }else{
                	$msg = $msg."<p><li>".substr($DetailDlr[$i]['numreceiver'],-8)."</li>";
                	if($DetailDlr[$i]['dlr'] === '2'){ $msg .= "Echec de l'envoi par l'operateur</p>";}
                	if($DetailDlr[$i]['dlr'] === '16'){ $msg .= "SMS rejeté par l'operateur</p>";}
                    $nums .= ','.substr($DetailDlr[$i]['numreceiver'],-8);
                }
            }
        }
        if($msg !== 'Aucun numero réfusé'){
        	/*$msg .= "
            <input type='hidden' value=".$id_envoie." name='id_envoi' id='id_envoi'>
            <input type='hidden' value=".$nums." name='nums' id='nums'>
            <button id='envoi' class='btn btn-success' style='float: right;'>Réssayer</button"*/;
        }
        echo $msg;
		?>
	</div>
</div>