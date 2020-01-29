<?php
//
// HEADER
//
	function createTabGamme() {
		// Creation menu gauche liste des bieres de la gamme
                $listGamme = ["la_commune","poele","propagamb","grosspils","helles_yeah","saison"];
                $tabGamme = "";

                foreach($listGamme as $beer_name) {
                        // Recup des infos concernant la biere courante
                        $beer_json = file_get_contents("gamme/$beer_name.json");
                        $beer_data = json_decode($beer_json, TRUE);

                        if($beer_data == "") {
                                echo "Template-listGamme : Nom de bière invalide";
                        	exit(0);
                        }

                        $tabGamme .= "<p><table><tr>";
                        $tabGamme .= "<td><img src=\"files/nrw_blank.png\" height=50/></td>";
                        $tabGamme .= "<td><a href=\"gamme.php?beer=$beer_name\"><b>".$beer_data["name"]."</b></a><br/>";
                        $tabGamme .= "<span style=\"background-color:#".$beer_data["ebc_html"]."\">&nbsp;&nbsp;&nbsp;</span> ".$beer_data["style"]."<br/>".$beer_data["alcool"]." % / ".$beer_data["ibu"]." IBU</td>";
                	$tabGamme .= "</tr></table></p>\n";
                }
		return $tabGamme;
	}

	function createTabExternalLinks () {
		// Création liste des liens externes dans menu de droite
		$tabExternalLinks = "<table id=\"right_menu_table\">";
		$links_json = file_get_contents("external_links.json");
		$links_data = json_decode($links_json, TRUE);

		foreach($links_data as $link) {
			$tabExternalLinks .= "<tr><td>";
			$tabExternalLinks .= "<a href=\"".$link["link"]."\">".$link["label"]." ";
			$tabExternalLinks .= "<span style=\"background-color:#".$link["color"]."\">&nbsp;&nbsp;&nbsp;</span></a></td></tr>";
		}

		$tabExternalLinks .= "</table>";

		return $tabExternalLinks;
	}

	function print_header($subtitle) {
		// Constantes
			$header_file = "template/header.html";

		// Ouverture du fichier
                        $fp = fopen($header_file, "r");
                        if(!$fp) {
                                $error = "Erreur lors de l'ouverture du header";
				echo $error;
                                exit(0);
                        }
                // Lecture du fichier
                        $header_html = fread($fp, filesize($header_file));
                        if(!$header_html) {
                                $error = "Erreur lors de la lecture du header";
                                echo $error;
                                exit(0);
                        }
                // Action reussie
                        fclose($fp);

		// Modif du template
			// Sous-titre du header html
			$header_html = str_replace("{SUBTITLE}", $subtitle, $header_html);

		// Ajout tabGamme dans header
			$tabGamme = createTabGamme();
			$header_html = str_replace("{TABLE_GAMME}", $tabGamme, $header_html);

		// Ajout tabExternalLinks dans header
			$tabExternalLinks = createTabExternalLinks();
			$header_html = str_replace("{TABLE_EXTERNAL_LINKS}", $tabExternalLinks, $header_html);

		// Creation tableau avec flux RSS blog
			$tabRSS = "<table id=\"right_menu_table\">";
			$i = 0;
			$xmlRSS = simplexml_load_file("http://blog.gamb.fr/index.php?feed/rss2");

                	foreach($xmlRSS->channel->item as $article) {
                	        $tabRSS .= "<tr><td><a href='".$article->link."'>".substr($article->title, 0, 26)." ";
				$tabRSS .= "<span style=\"background-color:#ff0000\">&nbsp;&nbsp;&nbsp;</span></a></td></tr>";
				$i++;
				// On limite a x articles
				if($i >= 10) {
					break;
				}
                	}
			$tabRSS .= "</table>";
			// Ajout tabRSS dans header
			$header_html = str_replace("{TABLE_RSS}", $tabRSS, $header_html);

		// Affichage
			echo $header_html;
			return true;
	}

// FOOTER
	function print_footer() {
                // Constantes
                        $footer_file = "template/footer.html";

                // Ouverture du fichier
                        $fp = fopen($footer_file, "r");
                        if(!$fp) {
                                $error = "Erreur lors de l'ouverture du footer";
                                echo $error;
                                exit(0);
                        }
                // Lecture du fichier
                        $footer_html = fread($fp, filesize($footer_file));
                        if(!$footer_html) {
                                $error = "Erreur lors de la lecture du footer";
                                echo $error;
                                exit(0);
                        }
                // Action reussie
                        fclose($fp);

                // Modif du template

                // Affichage
                        echo $footer_html;
                        return true;
        }

?>
