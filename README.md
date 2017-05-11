# Skyhub-Challenge
Resolução do desafio "Resize photos – SkyHub challenge" (disponível no arquivo skyhub_second_challenge.pdf)

# Funcionamento
Para rodar o script PHP, insira o arquivo **skyhub.php** no seu servidor e certifique-se de criar uma pasta chamada **images** no mesmo diretório.
Para executá-lo basta acessar a página, enviando o endereço desejado:

```skyhub.php?link=endereco```

**Exemplo**: skyhub.php?link=http://teste/images.json

# Implementação
A programação do desafio foi feita na linguagem **PHP**, por ser uma linguagem que possuo mais intimidade e que me facilitou lidar com objetos JSON.

O script PHP recebe um webservice endpoint com um JSON de imagens. Os atributos deste objeto são acessados e copiados para a pasta local **images**. Cada imagem é convertida para três formatos diferentes: small (320x240),
medium (384x288) e large (640x480), pela função **resize_imagejpg**. Suas URL's são armazenadas em uma array, que posteriormente é anexada ao objeto JSON. Após a geração de todas as imagens, o objeto JSON atualizado é retornado como um webservice endpoint.

# Testes
Os testes automatizados são feitos através do framework PHPUnit (https://phpunit.de/index.html). Para isto foi criado um script adicional nomeado **testes.php**

Para execução dos testes é necessário ter os pacotes do PHPUnit instalados. Siga as instruções do link: https://phpunit.de/manual/current/pt_br/installation.html

Após instalado basta executar o seguinte comando no console:

```phpunit --bootstrap src/skyhub.php tests/testes```

Os testes realizados são os seguintes:
- **testEnderecoValido**: Testa o script para uma entrada com endereço válido.
- **testEnderecoNaoPodeSerNULL**: Testa para uma entrada onde o endereço não é informado.
- **testEnderecoJSONInvalido**: Testa para uma entrada onde o endereço informado não possui um objeto JSON.
