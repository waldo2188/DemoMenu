<?php

namespace Waldo\DemoMenuBundle\Menu;

use Symfony\Component\DependencyInjection\ContainerAware;
use Knp\Menu\FactoryInterface;

class Builder extends ContainerAware
{

    public function menuPrincipal(FactoryInterface $factory, array $options)
    {
       
        $menu = $factory->createItem('root');

        $menu->addChild('Accueil', array('route' => '_welcome'));

        $menu->addChild('Utilisateurs', array('route' => '_utilisateur_list'));
        $menu['Utilisateurs']->addChild('Actif', array('route' => '_utilisateur_actif_list'));
        
        $menu['Utilisateurs']->addChild('Ajouter', array('route' => '_utilisateur_ajouter'))
                ->setDisplay(false); // N'affiche pas l'entrée dans l'arborescence du menu

        $menu['Utilisateurs']->addChild('Éditer', array('route' => '_utilisateur_editer'))
                ->setDisplay(false); // N'affiche pas l'entrée dans l'arborescence du menu


        $menu->addChild('Articles', array('route' => '_article_list'));

        $menu['Articles']->addChild('Les loutres à la plage', array('route' => '_article_blog', 'routeParameters' => array('idArticle' => 'loutre-plage')));
        $menu['Articles']->addChild('Les loutres à la montagne', array('route' => '_article_blog', 'routeParameters' => array('idArticle' => 'loutre-montagne')));
        $menu['Articles']->addChild('Les loutres en tournée dans les bars', array('route' => '_article_blog', 'routeParameters' => array('idArticle' => 'loutre-a-bierre')));

        $menu['Articles']->addChild('Commentaires', array('route' => '_article_commentaires'))
                ->setDisplay(false); // N'affiche pas l'entrée dans l'arborescence du menu
        
        // Création d'une arborescence de menu        
        $menu->addChild('Niveau 0', array('route' => '_article_blog', 'routeParameters' => array('idArticle' => 'niveau-0')))
                ->addChild('Niveau 1', array('route' => '_article_blog', 'routeParameters' => array('idArticle' => 'niveau-1')))
                    ->addChild('Niveau 2', array('route' => '_article_blog', 'routeParameters' => array('idArticle' => 'niveau-2')))
                        ->addChild('Niveau 3', array('route' => '_article_blog', 'routeParameters' => array('idArticle' => 'niveau-3')))
                            ->addChild('Niveau 4', array('route' => '_article_blog', 'routeParameters' => array('idArticle' => 'niveau-4')))
                                ->addChild('Niveau 5', array('route' => '_article_blog', 'routeParameters' => array('idArticle' => 'niveau-5')));
        
        // Ajout de deux sous menu à un sous menu
        $menu['Niveau 0']['Niveau 1']['Niveau 2']->addChild('Niveau 3.1', array('route' => '_article_blog', 'routeParameters' => array('idArticle' => 'niveau-3-1')));
        $menu['Niveau 0']['Niveau 1']['Niveau 2']->addChild('Niveau 3.2', array('route' => '_article_blog', 'routeParameters' => array('idArticle' => 'niveau-3-2')));

       
        return $menu;
    }
}