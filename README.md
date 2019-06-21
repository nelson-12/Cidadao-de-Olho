# Cidadao-de-Olho

1° Criar um banco de dados nomeado localmente utf8_bin

2° Renomeie o arquivo .env.example para .env sua raiz do projeto e preencha as informações do banco de dados.

3° Abra o console e grave o diretório raiz do seu projeto.

4° Utilize o comando composer install e logo em seguida php artisan key:generate.

5° Utilize o comando php artisan module:migrate para gerar as tabelas do banco de dados.

6° Utilize o comando php artisan serve para testar a aplicação.

A Aplicação utiliza a bibliteca Laravel-Modules para criar modularização do projeto, por conta deste a Url devem conter o nome do modulo. 

Para a os deputados serem inseridos no Banco [URL_Padrão]/deputados/{ano_mantado}, EX: [URL_Padrão]/deputados/17

Para a os deputados custo mensais  [URL_Padrão]/deputados/gastosDeputados/{ano_mantado}/{mes}, EX: [URL_Padrão]/deputados/gastosDeputados/17/1

Para as Redes Sociais mais Utilizada [URL_Padrão]/deputados/midia

O Projeto foi dividido de forma modular para que fique bem melhor organizado o codígo, para consumir a Api foi utilizado curl, para isso foi criado uma class Client de acesso global, caso outros modulos precisem consumir API's externas.
Todas a validações são realizadas nos controller's que realizão a chamada para a class Client, que recebe o retorno da API, guarda as informações no banco de dados, caso essa informação já exista no banco o controller retorna o json.
