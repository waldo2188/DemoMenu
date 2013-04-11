<?php

namespace Waldo\DemoMenuBundle\Menu;

use Symfony\Component\DependencyInjection\ContainerAware;
use Knp\Menu\FactoryInterface;
use Knp\Menu\Iterator\CurrentItemFilterIterator;
use Knp\Menu\Iterator\RecursiveItemIterator;
use Knp\Menu\MenuItem;
use \RecursiveIteratorIterator;
use \ArrayIterator;

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

        $menu['Articles']->addChild('Les loutres à la plage', array('route' => '_article_blog', 'routeParameters' => array('idArticle' => 'loutre-plage')));
        $menu['Articles']->addChild('Les loutres à la montagne', array('route' => '_article_blog', 'routeParameters' => array('idArticle' => 'loutre-montagne')));
        $menu['Articles']->addChild('Les loutres en tournée dans les bars', array('route' => '_article_blog', 'routeParameters' => array('idArticle' => 'loutre-a-bierre')));

        $menu['Articles']->addChild('Commentaires', array('route' => '_article_commentaires'))
                ->setDisplay(false); // N'affiche pas l'entrée dans l'arborescence du menu
     
        return $menu;
    }

    /**
     * Permet de générer le BreadCrumb
     * @param \Knp\Menu\FactoryInterface $factory
     * @param array $options
     * 
     * @return \Knp\Menu\Iterator\CurrentItemFilterIterator
     */
    public function breadCrumb(FactoryInterface $factory, array $options)
    {
        $menu = $this->menuPrincipal($factory, $options);

        /* @var $matcher \Knp\Menu\Matcher\Matcher */
        $matcher = $this->container->get('knp_menu.matcher');

        $treeIterator = new RecursiveIteratorIterator(
                new RecursiveItemIterator(
                new ArrayIterator(array($menu))
                ), RecursiveIteratorIterator::SELF_FIRST
        );

        $iterator = new CurrentItemFilterIterator($treeIterator, $matcher);

        // Set Current as an empty Item in order to avoid exceptions on knp_menu_get
        $current = new MenuItem('', $factory);

        foreach ($iterator as $item) {
            $item->setCurrent(true);
            $current = $item;
            break;
        }

        return $current;
    }


}