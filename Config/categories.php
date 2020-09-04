<?php

return [
    'name' => 'Categories',
    'controller' => 'CategoriesController',
    'actions' => 'get;index,get;create;create,post;store;store,get;edit;edit/{category},put;update;{category},delete;destroy;{category},post;status;{category}/status,post;featured;{category}/featured,post;order;{category}/order',
    'fields' => 'name,slug,image,summary,status,featured,order,body,seo_title,meta_description,meta_keywords,menu_id,parent_id',
    'menu' => true,
    'author' => 'Mauro Lacerda - contato@maurolacerda.com.br',
    'folder' => 'categories'
];