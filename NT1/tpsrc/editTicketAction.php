<?php
	if(empty($_REQUEST["applicationOther"])){
		$application=$_REQUEST["application"];
	}
	else{
		$application=$_REQUEST["applicationOther"];
	}
	if(isset($_REQUEST["priority"])){
		$priorite=$_REQUEST["priority"];
	}
	if(isset($_REQUEST["oneLiner"])){
		$resume=$_REQUEST["oneLiner"];
	}
	if(isset($_REQUEST["contents"])){
		$description = substr($_REQUEST["contents"],0,40);
	}
	if(isset($_REQUEST["type"])){
		$type=$_REQUEST["type"];
	}
?>
<html>
<head>

  <link rel="stylesheet" href="../styles/styles.css" type="text/css" media="screen">
  <title>Saisie Anomalie</title>
<head>

<span style="font-style: italic;">(Demande
envoy&eacute;e par l'adresse IP <?php echo $_SERVER['REMOTE_ADDR'];?>)</span><br />

<table id="buglist" border="1" cellspacing="1">

  <tbody>

    <tr>

      <th>Application</th>

      <th>Priorit&eacute;</th>

      <th>Type</th>

      <th>Date</th>

      <th>R&eacute;sum&eacute;</th>

      <th>Description</th>

    </tr>
	<?php
			if($priorite==1){
				 $priorite = "Très faible";
				echo  '<tr class="tab_bg_1">';
			}
			if($priorite==2){
				$priorite = "Faible";
				echo  '<tr class="tab_bg_2">';
			}
			if($priorite==3){
				$priorite = "Moyenne";
				echo  '<tr class="tab_bg_yellow">';
			}
			if($priorite==4){
				$priorite = "Urgente";
				echo  '<tr class="tab_bg_orange">';
			}
			if($priorite==5){
				$priorite = "Très urgente";
				echo  '<tr class="tab_bg_red">';
			}
	?>

      <td class="center"><?php echo $application?></td>

      <td class="center"><?php echo $priorite?></td>

      <td class="center">
		<?php echo $type ?>
	  </td>

      <td class="center"><?php echo date('d F Y'); ?></td>

      <td class="left"><?php echo $resume?></td>

      <td class="left"><?php echo $description?></td>

    </tr>

  </tbody>
</table>
</html>
