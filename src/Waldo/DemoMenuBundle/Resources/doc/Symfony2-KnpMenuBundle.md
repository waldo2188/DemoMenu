Les menus avec KnpMenuBundle
============================

Le KnpMenuBundle est un bundle offert par [KnpLab](http://knplabs.com/) disponible sir github [https://github.com/KnpLabs/KnpMenuBundle](https://github.com/KnpLabs/KnpMenuBundle)

Ce Bundle permet de créer facilement des menus. La [documentation](https://github.com/KnpLabs/KnpMenuBundle/blob/master/Resources/doc/index.md) est d'ailleurs assez bien faite pour les choses simples. Je vous laisse vous y reporter pour l'installation du bundle dans votre architecture Symfony2.

Ce que je vais vous présenté ici, c'est une gestion un peu plus avancé des menus avec en prime (dans un prochain article), la possibilité de créer un breadcrumb/fil d'Ariane en deux coups de cuillère à pot.

Tout le code est disponible sur (mon github)[https://github.com/waldo2188/DemoMenu]. Si vous voulez tester en live ça sera plus simple !

Le menu !
=========

Dans un premier temps voyons un peux l'arborescence de notre menu final.
Notre menu va comporter des entrées cachées, elles serviront à la construction du Breadcrumb. 

- Accueil
- Utilisateurs (Affiche une liste d'utilisateurs)
	- Actif (Affiche une liste des utilisateurs actif)
	- Ajouter (cette entré sera caché)
	- Éditer (cette entré sera caché)
- Articles (Affiche une liste d'articles)
	- Les loutres à la plage
	- Les loutres à la montagne
	- Les loutres en tournée dans les bars
	- Commentaires (cette entré sera caché)

Ce menu sera suffisant pour illustrer cet article.
Maintenant nous allons attribuer une route (lien + nom) à chaque entré du menu. Une route sera représenté sous cette forme [lien, nom].

- Accueil ["/", _welcome]
- Utilisateurs ["/utilisateurs", _utilisateur_list]
- Actif ["/utilisateurs/actif", _utilisateur_actif_list]
- Ajouter ["/utilisateur/ajouter", _utilisateur_ajouter]
- Éditer ["/utilisateur/editer/{id}", _utilisateur_editer]
- Articles ["/articles", _article_list]
- Les loutres à la plage ["/article/{idArticle}", _article_blog]
- Les loutres à la montagne ["/article/{idArticle}", _article_blog]
- Les loutres en tournée dans les bars ["/article/{idArticle}", _article_blog]
- Commentaires ["/article/commentaires/{idArticle}", _article_commentaires]

On va créer un controller ``DefaultController`` avec le code qui se trouve à cette adresse : [https://github.com/waldo2188/DemoMenu/blob/master/src/Waldo/DemoMenuBundle/Controller/DefaultController.php](https://github.com/waldo2188/DemoMenu/blob/master/src/Waldo/DemoMenuBundle/Controller/DefaultController.php)

Le point important de ce Controller c'est l'option `defaults` de la *Route*. Cette option permet de donner une valeur par défaut à une variable qui est passé dans l'URL.

Maintenant si vous allez voir dans le fichier (Menu/Builder.php)[https://github.com/waldo2188/DemoMenu/blob/master/src/Waldo/DemoMenuBundle/Menu/Builder.php]

C'est ici que l'on va construire l'arborescence du menu.
Comme nous pouvons le voir, nous créons un arbre avec une entrée racine
```php
$menu = $factory->createItem('menu');
```

Puis nous y ajoutons trois branches principales : 
```php
$menu->addChild('Accueil', array('route' => '_welcome'));
[...]
$menu->addChild('Utilisateurs', array('route' => '_utilisateur_list'));
[...]
$menu->addChild('Articles', array('route' => '_article_list'));
```

Auxquelles nous ajoutons des sous branches :
```php
$menu['Utilisateurs']->addChild('Actif', array('route' => '_utilisateur_actif_list'));
        
        $menu['Utilisateurs']->addChild('Ajouter', array('route' => '_utilisateur_ajouter'))
                ->setDisplay(false);

        $menu['Utilisateurs']->addChild('Éditer', array('route' => '_utilisateur_editer'))
                ->setDisplay(false);
```
Deux sous branches appellent la méthode `setDisplay`, cela permet de ne pas afficher les entrées dans le menu. Cependant, ces entrées font partie intégrante de l'arborescence, donc super utile pour une génération de Breadcrumbs !

Il ne reste plus qu'à afficher le menu dans un [template twig de référence](https://github.com/waldo2188/DemoMenu/blob/master/src/Waldo/DemoMenuBundle/Resources/views/base.html.twig) :
```twig
{{ knp_menu_render('WaldoDemoMenuBundle:Builder:menuPrincipal') }}
```


