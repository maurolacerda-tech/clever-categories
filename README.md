# clever-categories
Módulo de criação de categorias do CMS da cleverweb.com.br

## Instalação
```
composer require maurolacerda-tech/clever-categories:dev-master
```
```
php artisan migrate
```

## Opcionais
Você poderá públicar os arquivos de visualização padrão em seu diretório views/vendor/Category

```
php artisan vendor:publish --provider="Modules\Categories\Providers\CategoryServiceProvider" --tag=views
```


Para públicar os arquivos de configurações.

```
php artisan vendor:publish --provider="Modules\Categories\Providers\CategoryServiceProvider" --tag=config
```

