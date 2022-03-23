
<P>
<B>DEBUTTTTTT DU PROCESSUS :</B>
<BR>
<?php echo " ", date ("h:i:s"); ?>
</P>
<?php

// fixe le délai d'expiration du programme
set_time_limit (500);
$path= "docs";

// appel de la fonction récursive qui va nous permettre d'analyser le répertoire docs
explorerDir($path);

function explorerDir($path)
{
	// Pour ouvrir le dossier du chemin $path 
	$folder = opendir($path);
	
	// on récupère le nom de la prochaine entrée du dossier et si elle n'est pas vide on entre dans la boucle
	while($entree = readdir($folder))
	{		
		// on ne touche pas aux repertoires . et .. (l'une désigne le repertoire courant l'autre le repertoire parent)
		if($entree != "." && $entree != "..")
		{
			// test si le fichier est un dossier
			if(is_dir($path."/".$entree))
			{
				// Afficher les répertoires
				echo  "</br>" . "Dossier : " . $entree . "</br>";
				// pour garder le repertoire courant de notre recherche actuelle
				$sav_path = $path;
				// ajoute le nouveau repertoire trouvé
				$path .= "/".$entree;
				// rappelle la fonction sur ce nouveau repertoire
				explorerDir($path);
				// pour revenir sur le parent
				$path = $sav_path;
			}
			else
			{
				// on récupère le chemin entier en plus du fichier
				$path_source = $path."/".$entree;				
				
				// On récupère les informations du fichier
				$fileInfos = new SplFileInfo($entree);
				$extentionAllowed = array("png", "jpeg", "jpg");
				$extension = $fileInfos->getExtension();
				$nameFile = $fileInfos->getBasename('.'.$extension);
				$pathToSave =  getcwd() . '/' . $path_source;
				$size =  filesize($path_source);
				//Si c'est un .png ou un .jpeg		
				//Alors je ferais quoi ? Devinez !
				//...

				if(in_array(strtolower($extension), $extentionAllowed)) // On affiche le nom du fichier avec son image
					echo 'Fichier :'. $entree . ' <img style="width: 150px;" src="' . $path_source.'" /></br>';
					
				else // On affiche le nom du fichier
					echo "Fichier : " . $entree . "</br>";


				$port="3306";
				$db="lecturerecursive";
				$user='user';
				$pass='my-secret-pw';
				$connect = "mysql:host=localhost:$port;dbname=$db";
				$database = new PDO($connect, $user, $pass);

				// Si le fichier n'est pas déjà enregistré dans la bdd, on l'insère
				$sql = $database->query("SELECT COUNT(*) AS nbImages FROM `imagesData` WHERE `name` = '$nameFile' AND `type` = '$extension' AND `size` = '$size' AND `path` = '$pathToSave'")->fetch();
				if((int) $sql['nbImages'] == 0){
					$database->query("INSERT INTO `imagesData`(`name`, `type`, `size`, `path`) VALUES ('$nameFile', '$extension', '$size', '$pathToSave')");
				}
				$database = null;
			}
		}
	}
	closedir($folder);
}
?>
<P>
<B>FINNNNNN DU PROCESSUS :</B>
<BR>
<?php echo " ", date ("h:i:s"); ?>
</P>
