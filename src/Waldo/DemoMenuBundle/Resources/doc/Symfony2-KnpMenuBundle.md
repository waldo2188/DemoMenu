Les menus avec KnpMenuBundle
============================

Le KnpMenuBundle est un bundle offert par [KnpLab](http://knplabs.com/) disponible sir github [https://github.com/KnpLabs/KnpMenuBundle](https://github.com/KnpLabs/KnpMenuBundle)

Ce Bundle permet de créer facilement des menus. La [documentation](https://github.com/KnpLabs/KnpMenuBundle/blob/master/Resources/doc/index.md) est d'ailleurs assez bien faite pour les choses simples. Je vous laisse vous y reporter pour l'installation du bundle dans votre architecture Symfony2.

Ce que je vais vous présenté ici, c'est une gestion un peu plus avancé des menus avec en prime (dans un prochain article), la possibilité de créer un breadcrumb/fil d'Ariane en deux coups de cuillère à pot.

Dans un premier temps voyons un peux l'arborescence de notre menu final.
Notre menu va comporter des entrées cachées, elles serviront à la construction du Breadcrumb. 

- Accueil
- Utilisateurs (Affiche une liste d'utilisateurs)
	- Actif (Affiche une liste des utilisateurs actif)
	- Ajouter (cette entré sera caché)
	- Éditer (cette entré sera caché)
- Articles (Affiche une liste d'articles)
	- A venir (Affiche une liste d'articles non encore parut) 
	- Commentaires (cette entré sera caché)
	- Ajouter (cette entré sera caché)
	- Éditer (cette entré sera caché)

Ce menu sera suffisant pour illustrer cet article.
Maintenant nous allons attribuer une route (lien + nom) à chaque entré du menu. Une route sera représenté sous cette forme [lien, nom].

- Accueil ["/", _welcome]
- Utilisateurs ["/utilisateurs", _utilisateur_list]
- Actif ["/utilisateurs/actif", _utilisateur_actif_list]
- Ajouter ["/utilisateur/ajouter", _utilisateur_ajouter]
- Éditer ["/utilisateur/editer/{id}", _utilisateur_editer]
- Articles ["/articles", _article_list]
- A venir ["/articles/a-venir", _article_a_venir_list]
- Commentaires ["/article/commentaires/{idArticle}", _article_commentaires]
- Ajouter ["/article/ajouter", _article_ajouter]
- Éditer ["/article/editer/{id}", _article_editer]

On va créer un controller ``DefaultController`` sdf sdf

