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
                ->setDisplay(true);

        $menu['Utilisateurs']->addChild('Éditer', array('route' => '_utilisateur_editer'))
                ->setDisplay(true);


        $menu->addChild('Articles', array('route' => '_article_list'));

        $menu['Articles']->addChild('A venir', array('route' => '_article_a_venir_list'));
        
        $menu['Articles']->addChild('Commentaires', array('route' => '_article_commentaires'))
                ->setDisplay(true);

        $menu['Articles']->addChild('Ajouter', array('route' => '_article_ajouter'))
                ->setDisplay(true);

        $menu['Articles']->addChild('Éditer', array('route' => '_article_editer'))
                ->setDisplay(true);

        /*
            $menu->addChild('Autorité Nationale', array('route' => '_utilisateur_gestion_autorite_nationale'))
            ->addChild('Liste', array('route' => '_utilisateur_gestion_autorite_nationale')); // liste des autorités nationales
    // Liste des utilisateurs lié aux autorité nationale
    $menu['Autorité Nationale']->addChild('Agents', array('route' => '_utilisateur_gestion', 'routeParameters' => array('typeCompte' => 'autorite-nationale')));
    $this->setMenuUserAddEdit($menu['Autorité Nationale']['Agents'], TypeOrganisme::URL_AUTORITE_NATIONALE);

    $menu['Autorité Nationale']->addChild('Ajout autorité Nationale', array('route' => '_utilisateur_add_organisme', 'routeParameters' => array('typeOrganismeRole' => 'autorite-nationale')))
            ->setDisplay(false);
    $menu['Autorité Nationale']->addChild('Édition autorité Nationale', array('route' => '_utilisateur_edit_organisme', 'routeParameters' => array('typeOrganismeRole' => 'autorite-nationale')))
            ->setDisplay(false);


            $menu->addChild('Dispensateur de formation', array('route' => '_utilisateur_gestion_dispensateur_formation'));
            $menu['Dispensateur de formation']->addChild('Liste', array('route' => '_utilisateur_gestion_dispensateur_formation'));

            $menu['Dispensateur de formation']->addChild('Formateurs', array('route' => '_utilisateur_gestion', 'routeParameters' => array('typeCompte' => 'dispensateur-de-formation')));
            $this->setMenuUserAddEdit($menu["Dispensateur de formation"]['Formateurs'], TypeOrganisme::URL_DISPENSATEUR_DE_FORMATION);


            $menu['Dispensateur de formation']->addChild('Ajout dispensateur de formation', array('route' => '_utilisateur_add_organisme', 'routeParameters' => array('typeOrganismeRole' => 'dispensateur-de-formation')))
                    ->setDisplay(false);
            $menu['Dispensateur de formation']->addChild('Édition dispensateur de formation', array('route' => '_utilisateur_edit_organisme', 'routeParameters' => array('typeOrganismeRole' => 'dispensateur-de-formation')))
                    ->setDisplay(false);
            $menu['Dispensateur de formation']->addChild('Association dispensateur de formation et habilitation', array('route' => '_utilisateur_association_organisme_habilitation'))
                    ->setDisplay(false);
            
            $menu->addChild('Autorité Régionale', array('route' => '_utilisateur_gestion_autorite_regionale'))
                    ->addChild('Liste', array('route' => '_utilisateur_gestion_autorite_regionale')); // liste des autorités régionale
            // Liste des utilisateurs lié aux autorité régionale
            $menu['Autorité Régionale']->addChild('Agents', array('route' => '_utilisateur_gestion', 'routeParameters' => array('typeCompte' => 'autorite-regionale')));
            $this->setMenuUserAddEdit($menu['Autorité Régionale']['Agents'], TypeOrganisme::URL_AUTORITE_REGIONALE);

            $menu['Autorité Régionale']->addChild('Ajout autorité Régionale', array('route' => '_utilisateur_add_organisme', 'routeParameters' => array('typeOrganismeRole' => 'autorite-regionale')))
                    ->setDisplay(false);
            $menu['Autorité Régionale']->addChild('Édition autorité Régionale', array('route' => '_utilisateur_edit_organisme', 'routeParameters' => array('typeOrganismeRole' => 'autorite-regionale')))
                    ->setDisplay(false);

            $menu->addChild("Habilitation", array('route' => '_habilitation_en_cours'));
            $menu['Habilitation']->addChild("En cours", array('route' => '_habilitation_en_cours'));
            $menu['Habilitation']->addChild("Fermées", array('route' => '_habilitation_fermees'));

        }


        if ($securityContext->isGranted(Utilisateur::ROLE_DISPENSATEUR_DE_FORMATION)) {
//            $menu->addChild('Sessions', array('route' => '_df_session_evaluation', 'routeParameters' => array('etatSession' => null)));
            $menu->addChild('Sessions d\'évaluation', array('route' => '_df_session_evaluation'));
            $menu['Sessions d\'évaluation']->addChild('En cours', array('route' => '_df_session_evaluation', 'routeParameters' => array('etatSession' => 'en-cours')));
            $menu['Sessions d\'évaluation']->addChild('Closes', array('route' => '_df_session_evaluation', 'routeParameters' => array('etatSession' => 'closes')));

            $menu['Sessions d\'évaluation']->addChild('Ajouter session', array('route' => '_df_session_evaluation_ajout'))
                    ->setDisplay(false);
            $menu['Sessions d\'évaluation']->addChild('Éditer session', array('route' => '_df_session_evaluation_edit', 'routeParameters' => array('sessionFormation' => null)))
                    ->setDisplay(false);
            $menu['Sessions d\'évaluation']->addChild("Association des candidats à une session", array('route' => '_df_session_evaluation_association_candidat'))
                    ->setDisplay(false);

            $menu->addChild('Gestion des candidats', array('route' => '_candidat_gestion'));
            $menu['Gestion des candidats']->addChild('Ajouter un candidat', array('route' => '_candidat_ajout'))
                    ->setDisplay(false);
            $menu['Gestion des candidats']->addChild('Éditer un candidat', array('route' => '_candidat_edit'))
                    ->setDisplay(false);
        }


        if ($securityContext->isGranted(Utilisateur::ROLE_ADMINISTRATEUR)) {
            $menu->addChild('Super Admin', array('route' => '_utilisateur_gestion', 'routeParameters' => array('typeCompte' => 'administrateur')));
            $menu['Super Admin']->addChild("Utilisateurs Super Admin", array('route' => '_utilisateur_gestion', 'routeParameters' => array('typeCompte' => 'administrateur')));
            $this->setMenuUserAddEdit($menu['Super Admin']['Utilisateurs Super Admin'], TypeOrganisme::URL_ADMINISTRATEUR);
        }
*/
        return $menu;
    }

  


    /**
     * Ajoute les lien édition et ajous pour le bredcrump
     *
     * @param type $menu
     * @param type $typeCompte
     */
    private function setMenuUserAddEdit(&$menu, $typeCompte)
    {
        $menu->addChild('Ajout utilisateur', array('route' => '_utilisateur_add_account', 'routeParameters' => array('typeCompte' => $typeCompte)))
                ->setDisplay(false);
        $menu->addChild('Édition utilisateur', array('route' => '_utilisateur_edit_account', 'routeParameters' => array('typeCompte' => $typeCompte)))
                ->setDisplay(false);
    }

}