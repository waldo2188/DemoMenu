<?php

namespace Waldo\DemoMenuBundle\Menu;

use Symfony\Component\DependencyInjection\ContainerAware;
use Knp\Menu\FactoryInterface;
use Knp\Menu\Iterator\CurrentItemFilterIterator;
use Knp\Menu\Iterator\RecursiveItemIterator;
use Knp\Menu\MenuItem;

class Builder extends ContainerAware
{

    public function menuPrincipal(FactoryInterface $factory, array $options)
    {
       
        $menu = $factory->createItem('menu');

        $menu->addChild('Accueil', array('route' => '_welcome'));

        $menu->addChild('Utilisateurs', array('route' => '_utilisateur_list'));
        $menu['Utilisateurs']->addChild('Actif', array('route' => '_utilisateur_actif_list'));
        
        $menu['Utilisateurs']->addChild('Ajouter', array('route' => '_utilisateur_ajouter'))
                ->setDisplay(false); // N'affiche pas l'entrée dans l'arborescence du menu

        $menu['Utilisateurs']->addChild('Éditer', array('route' => '_utilisateur_editer'))
                ->setDisplay(false); // N'affiche pas l'entrée dans l'arborescence du menu


        $menu->addChild('Articles', array('route' => '_article_list'));

        $menu['Articles']->addChild('A venir', array('route' => '_article_a_venir_list'));
        
        $menu['Articles']->addChild('Commentaires', array('route' => '_article_commentaires'))
                ->setDisplay(false); // N'affiche pas l'entrée dans l'arborescence du menu

        $menu['Articles']->addChild('Ajouter', array('route' => '_article_ajouter'))
                ->setDisplay(false); // N'affiche pas l'entrée dans l'arborescence du menu

        $menu['Articles']->addChild('Éditer', array('route' => '_article_editer'))
                ->setDisplay(false); // N'affiche pas l'entrée dans l'arborescence du menu

      
        return $menu;
    }


}