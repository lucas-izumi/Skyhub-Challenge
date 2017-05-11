<?php
	ini_set("allow_url_fopen", 1); //Permite a captura de links
			
	final class ResizePhotos
	{
		private $endereco;

		/* Redimensiona uma imagem com a extensão .jpg
		Função retirada de: http://stackoverflow.com/a/39083723 */
		private function resize_imagejpg($file, $w, $h) {
   			list($width, $height) = getimagesize($file);
   			$src = imagecreatefromjpeg($file);
   			$dst = imagecreatetruecolor($w, $h);
   			imagecopyresampled($dst, $src, 0, 0, 0, 0, $w, $h, $width, $height);
   			return $dst;
		}

		/* Inicializa a variavel de endereço e chama a função que faz o redimensionamento */
		public function set_endereco($endereco) {
			$this->JSONvalido($endereco);
			$this->endereco = $endereco;
			$this->resize_photos($this->endereco);
		}
				
		/* Lança as exceções para argumentos invalidos */	
		private function JSONvalido($endereco) {
			if (is_null($endereco))	{
				throw new InvalidArgumentException(
                	sprintf('O endereço passado é inválido')
            	);
			}
			else {
				$componentes = pathinfo($endereco);
				if ($componentes['extension'] != 'json') {
					throw new InvalidArgumentException(
                		sprintf('"%s" não é uma extensão JSON válida', $componentes['extension'])
            		);
				}
			}
		}

		private function resize_photos($endereco) {
			$json = file_get_contents($endereco); //Obtem o objeto JSON do link
			$obj = json_decode($json, true); //Transforma em uma array do PHP
			$numImagens = count($obj["images"]); //Descobre o tamanho da array
			$newJSON['images'] = [];
			$newJSON = json_encode($newJSON, JSON_PRETTY_PRINT);
			$tempArray = json_decode($newJSON, true);

			/* Percorre todas as imagens da array */
			for($i = 0; $i < $numImagens; $i++)
			{
				$fileName = pathinfo($obj["images"][$i]["url"]); //Obtem os componentes do link
				copy($obj["images"][$i]["url"], "images/".$fileName['basename']); //Copia a imagem atual para posteriormente converte-la

				/* Converte a imagem para tres tamanhos diferentes */
				$imgSmall = $this->resize_imagejpg("images/".$fileName['basename'], 320, 240);
				imagejpeg($imgSmall, "images/".$fileName['filename']."_small.".$fileName['extension']);

				$imgMedium = $this->resize_imagejpg("images/".$fileName['basename'], 384, 288);
				imagejpeg($imgMedium, "images/".$fileName['filename']."_medium.".$fileName['extension']);

				$imgLarge = $this->resize_imagejpg("images/".$fileName['basename'], 640, 480);
				imagejpeg($imgLarge, "images/".$fileName['filename']."_large.".$fileName['extension']);

				/* Guarda o endereço de todas as imagens no vetor $novoElemento */
				$novoElemento['url'] = "images/".$fileName['basename'];
				$novoElemento['url_small'] = "images/".$fileName['filename']."_small.".$fileName['extension'];
				$novoElemento['url_medium'] = "images/".$fileName['filename']."_medium.".$fileName['extension'];
				$novoElemento['url_large'] = "images/".$fileName['filename']."_large.".$fileName['extension'];
				array_push($tempArray['images'], $novoElemento); //Adiciona o novo elemento no array que representa o objeto JSON
			}
			
			$newJSON = json_encode($tempArray, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); //Faz o encode do objeto
			file_put_contents('images.json', $newJSON); //Cria o arquivo
			header("Location: images.json"); //Redireciona para a página JSON
		}
	}


	$classObj = new ResizePhotos();
	$classObj->set_endereco($_GET['link']);
?>