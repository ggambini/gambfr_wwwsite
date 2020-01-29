<?php
	// Recup biere de la gamme a afficher
	$beer_name = $_GET['beer'];
	if($beer_name == "") {
		echo "Veuillez indiquer un nom de bière";
		exit(0);
	}

	// Recup du JSON
	$beer_json = file_get_contents("gamme/$beer_name.json");
	$beer_data = json_decode($beer_json, TRUE);
	if($beer_data == "") {
		echo "Nom de bière invalide";
                exit(0);
	}

	// Charge le template
	include("template/template.php");
	print_header($beer_data["name"]);
?>
	<div id="content">
		<h1><?php echo($beer_data["name"]); ?></h1>

		<p><?php echo($beer_data["beer_description"]); ?></p>

		<table id="content">
                        <tr valign="top"><td width="260">
				<?php if($beer_data["label"] != "") {
                                        echo("<img src=\"files/".$beer_data["label"]."\" width=\"250px\"/>");
                                } else {
					// etiquette par defaut
                                        echo("<img src=\"files/website_default-label.png\" width=\"250px\"/>");
                                }
                                ?>
			</td><td width"328">
                                <h2>D&eacute;tails</h2>
                                <ul>
					<li>Style : <?php echo($beer_data["style"]); ?></li>
					<li>Alcool : <?php echo($beer_data["alcool"]); ?> %</li>
					<li>Couleur : <?php echo($beer_data["ebc"]); ?> <acronym title="European Brewery Convention">EBC</acronym></li>
                                        <li>Amertume : <?php echo($beer_data["ibu"]); ?> <acronym title="International Bitterness Unit">IBU</acronym></li>
                                </ul>
                        </td></tr>
                </table>

                <p><?php echo($beer_data["recipe_description"]); ?><p/>

		<table id="content">
			<tr valign="top"><td width="396">
				<h2>Recette</h2>
                                <ul>
					<li>Version :  <?php echo($beer_data["version"]); ?></li>
					<li>Malts :<ul>
						<?php foreach($beer_data["recipe"]["malts"] as $malt) {
							echo("<li>$malt</li>");
						} ?>
					</ul></li>
					<li>Houblons :<ul>
						 <?php foreach($beer_data["recipe"]["hops"] as $hop) {
                                                        echo("<li>$hop</li>");
                                                } ?>
					</ul></li>
                                        <li>Levure :  <?php echo($beer_data["recipe"]["yeast"]); ?></li>
					<li>Densité initiale : <?php echo($beer_data["recipe"]["initial_gravity"]); ?><acronym title="Degr&eacute; Plato">°P</acronym></li>
					<li>Densité finale : <?php echo($beer_data["recipe"]["final_gravity"]); ?><acronym title="Degr&eacute; Plato">°P</acronym></li>
                                </ul>
                        </td><td width"260">
				<?php if($beer_data["label2"] != "") {
					echo("<img src=\"files/".$beer_data["label2"]."\"/>");
				} else {
					echo("&nbsp;");
				}
				?>
			</td></tr>
		</table>

		<h2>Ressources</h2>
		<p>
			<a href="http://blog.gamb.fr/index.php?tag/<?php echo($beer_data["blog_keyword"]); ?>">Les brassins</a> : It&eacute;rations de la recette<br/>
		</p>
	</div>
<?php
	print_footer();
?>
