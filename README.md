
# CRUD Biblioteca

Projeto de crud basico com Laravel 

## Postman Collection

Para testar as APIs do projeto, você pode importar a coleção do Postman disponível [aqui](docs/CRUD_lib.postman_collection.json). A coleção contém exemplos de requisições para facilitar o teste das APIs.
## Instalação

Clone o projeto 


```bash
  git clone https://github.com/DevRafaelCena/crud_biblioteca
```
Acesse a pasta do projeto
```bash
    cd crud_biblioteca
```
Instale as dependências

```bash
   composer install
```

Copie o arquivo de exemplo do `.env` e edite conforme seu ambiente

```bash
   cp .env.example .env
```

Execute as migrations

```bash
   php artisan migrate
```

Execute os seeds

```bash
   php artisan db:seed
```

Execute o projeto

```bash
   php artisan serve
```

O projeto Laravel estará acessível em http://localhost:8000 no seu navegador.





