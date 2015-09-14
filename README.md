DemoMenu
========

Démonstration de l'utilisation du KnpMenuBundle et le CnertaBreadcrumbBundle avec Symfony2

Les menus avec KnpMenuBundle
============================

Le KnpMenuBundle est un bundle offert par [KnpLab](http://knplabs.com/) disponible sur github [https://github.com/KnpLabs/KnpMenuBundle](https://github.com/KnpLabs/KnpMenuBundle)

Ce Bundle permet de créer des menus facilement. La [documentation](https://github.com/KnpLabs/KnpMenuBundle/blob/master/Resources/doc/index.md) est d'ailleurs assez bien faite pour des choses simples. Je vous laisse vous y reporter pour l'installation du bundle dans votre architecture Symfony2.

Je vais vous présenter ici une gestion un peu plus avancée des menus avec en prime, la possibilité de créer un breadcrumb/fil d'Ariane en deux coups de cuillère à pot.

Tout le code est disponible sur (mon github)[https://github.com/waldo2188/DemoMenu]. Si vous voulez tester en live ça sera plus simple !

Le menu !
=========

- [Menu version Vanille](#menu-version-vanille)
- [Menu version Épicé](#menu-version-epice)
- [Menu version Über Épicé](#menu-version-uber-epice)
- [Génération du BreadCrumb](#generation-du-breadcrumb)


<a name="menu-version-vanille"></a>Menu version Vanille
--------------------

Le but de cette démonstration est de vous présenter la création d'un menu multi-niveaux, avec des entrées cachées. Dans certaines applications nous ne voulons pas afficher dans notre menu des entrées comme « Ajouter un article » ou « Modifier un article ». Ces entrées seront présentes dans l'application sous forme de bouton dans l'interface. Cependant nous souhaitons que ces entrées fassent partie du breadcrumb.

Dans un premier temps, voyons un peu l'arborescence de notre menu final.

- Accueil
- Utilisateurs (Affiche une liste d'utilisateurs)
	- Actif (Affiche une liste des utilisateurs actifs)
	- Ajouter (cette entrée sera cachée)
	- Éditer (cette entrée sera cachée)
- Articles (Affiche une liste d'articles)
	- Les loutres à la plage
	- Les loutres à la montagne
	- Les loutres en tournée dans les bars
	- Commentaires (cette entrée sera cachée)
- Niveau 0 (Cette partie est juste là pour démontrer la puissance du KnpMenuBundle)
	- Niveau 1
		- Niveau 2
			- Niveau 3
				- Niveau 4
					- Niveau 5
			- Niveau 3.1
			- Niveau 3.2

Ce menu sera suffisant pour illustrer cet article.
Maintenant nous allons attribuer une route (lien + nom) à chaque entrée du menu. Une route sera représentée sous cette forme [lien, nom].

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
(Pour la partie Niveau X du menu, ça n'a pas grand intérêt)


Nous allons créer un controller [*DefaultController*](https://github.com/waldo2188/DemoMenu/blob/master/src/Waldo/DemoMenuBundle/Controller/DefaultController.php).
Le point important de ce Controller c'est l'option `defaults` de la *Route*. Cette option permet de donner une valeur par défaut à une variable qui est passée dans l'URL.

La documentation du KnpMenuBundle nous invite à créer un fichier [*Builder*](https://github.com/waldo2188/DemoMenu/blob/master/src/Waldo/DemoMenuBundle/Menu/Builder.php) dans le dossier *Menu*.

C'est dans ce fichier que nous allons construire l'arborescence du menu.
Comme nous pouvons le voir, nous créons un arbre avec une entrée racine
```php
$menu = $factory->createItem('root'); // Donnez lui le nom que vous voulez.
```

Puis nous y ajoutons trois branches principales :
```php
$menu->addChild('Accueil', array('route' => '_welcome'));
[...]
$menu->addChild('Utilisateurs', array('route' => '_utilisateur_list'));
[...]
$menu->addChild('Articles', array('route' => '_article_list'));
```

Auxquelles nous ajoutons des sous-branches :
```php
$menu['Utilisateurs']->addChild('Actif', array('route' => '_utilisateur_actif_list'));

$menu['Utilisateurs']->addChild('Ajouter', array('route' => '_utilisateur_ajouter'))
        ->setDisplay(false);

$menu['Utilisateurs']->addChild('Éditer', array('route' => '_utilisateur_editer'))
        ->setDisplay(false);
```
Deux sous-branches appellent la méthode `setDisplay`. Celle-ci permet de définir si l'entrée est à intégrer ou non dans le menu qui sera généré.
Dans notre cas nous définissons `setDisplay` à `false` pour cacher ces entrées. Bien qu'elles n'apparaitrons pas dans le menu final, nous pourrons les exploiter pour la génération du Breadcrumbs.

L'ajout de l'option ``defaults={"id"=null}`` dans la route *_utilisateur_editer* du controller évite d'avoir à passer des arguments en plus lors de la création de l'enrtrée *Éditer*.

Il ne reste plus qu'à afficher le menu dans un [template twig de référence](https://github.com/waldo2188/DemoMenu/blob/master/src/Waldo/DemoMenuBundle/Resources/views/base.html.twig) :
```twig
{{ knp_menu_render('WaldoDemoMenuBundle:Builder:menuPrincipal') }}
```
Et voilà, le tour est joué pour la partie menu.

<a name="menu-version-epice"></a>Menu version Épicé
------------------
Pour l'instant nous avons créé de simples entrées de menu. Mais que faire dans le cas où une même route affiche différente choses, comme dans le cadre d'un blog. J'ai une route *_article_blog* qui prend en paramètre *idArticle*.

Pour cela nous allons utiliser l'option *routeParameters* du KnpMenuBunle, comme ci-dessous.

```php
$menu['Articles']->addChild('Les loutres à la plage', array('route' => '_article_blog', 'routeParameters' => array('idArticle' => 'loutre-plage')));
$menu['Articles']->addChild('Les loutres à la montagne', array('route' => '_article_blog', 'routeParameters' => array('idArticle' => 'loutre-montagne')));
$menu['Articles']->addChild('Les loutres en tournée dans les bars', array('route' => '_article_blog', 'routeParameters' => array('idArticle' => 'loutre-a-bierre')));
```

Lors de l'affichage du menu, la class CSS *current* (qui représente l'entrée de menu sélectionnée) sera uniquement appliquée à la bonne entrée de menu.
Le KnpMenuBundle, par le biais de classes ``Voter``, fait la correspondance entre les paramètres passés dans l'url et ceux déclarés avec l'option ``routeParameters``.



<a name="menu-version-uber-epice"></a>Menu version Über Épicé
-----------------------
Ce que nous avons vue précédemment n'est qu'un prémice. Corsons les choses en créant les parties du *Niveau 0* à *Niveau 5*.

Avec le KnpMenuBundle il est possible de chaîner la création d'enfants,
la méthode ``addChild`` retournant le nœud (``Knp\Menu\MenuItem``) nouvellement créé.

```php
$menu->addChild('Niveau 0', array('route' => '_article_blog', 'routeParameters' => array('idArticle' => 'niveau-0')))
        ->addChild('Niveau 1', array('route' => '_article_blog', 'routeParameters' => array('idArticle' => 'niveau-1')))
            ->addChild('Niveau 2', array('route' => '_article_blog', 'routeParameters' => array('idArticle' => 'niveau-2')))
                ->addChild('Niveau 3', array('route' => '_article_blog', 'routeParameters' => array('idArticle' => 'niveau-3')))
                    ->addChild('Niveau 4', array('route' => '_article_blog', 'routeParameters' => array('idArticle' => 'niveau-4')))
                        ->addChild('Niveau 5', array('route' => '_article_blog', 'routeParameters' => array('idArticle' => 'niveau-5')));
```

Nous nous retrouvons donc avec une arborescence de ce type :
```
Niveau 0
|_  Niveau1
    |_  Niveau 2
        |_  Niveau 3
...
```

Mais comment faire pour ajouter plus d'un enfant à un nœud ?

De ce que j'ai pu tester, l'objet ``Knp\Menu\MenuItem`` peut se comporter comme un tableau multidimensionnel (un tableau de tableau de tableau...).

Donc pour accéder au menu ``Niveau 3``, il suffit simplement d'utiliser ce paradigme.

```php
$menu['Niveau 0']['Niveau 1']['Niveau 2']->addChild('Niveau 3.1', array('route' => '_article_blog', 'routeParameters' => array('idArticle' => 'niveau-3-1')));

$menu['Niveau 0']['Niveau 1']['Niveau 2']->addChild('Niveau 3.2', array('route' => '_article_blog', 'routeParameters' => array('idArticle' => 'niveau-3-2')));
```


<a name="generation-du-breadcrumb"></a>Génération du BreadCrumb
-------------------------
Pour pouvoir générer un BreadCrumb de qualité nous allons simplement utiliser
le [CnertaBreadcrumbBundle](https://github.com/AgrosupDijon-Eduter/BreadcrumbBundle)

