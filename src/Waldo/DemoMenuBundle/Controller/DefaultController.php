<?php

namespace Waldo\DemoMenuBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{

    /**
     * @Route("/", name="_welcome")
     * @Template()
     */
    public function welcomeAction()
    {
        return array();
    }

    /**
     * @Route("/utilisateurs", name="_utilisateur_list")
     * @Template()
     */
    public function utilisateursAction()
    {
        return array();
    }

    /**
     * @Route("/utilisateurs/actif", name="_utilisateur_actif_list")
     * @Template()
     */
    public function utilisateursActifAction()
    {
        return array();
    }

    /**
     *
     * @Route("/utilisateur/ajouter", name="_utilisateur_ajouter", defaults={"id"=null})
     * @Route("/utilisateur/editer/{id}", name="_utilisateur_editer", defaults={"id"=null})
     * 
     *  l'option 'defaults={"id"=null}' permet d'éviter une erreur de rendue de template de ce type :
     *      An exception has been thrown during the rendering of a template ("Some mandatory parameters
     *      are missing ("id") to generate a URL for route "_utilisateur_editer".") in [...] 
     *
     * @Template()
     */
    public function utilisateurEditionAction($id)
    {
        return array();
    }

    /**
     * @Route("/articles", name="_article_list")
     * @Template()
     */
    public function articlesAction()
    {
        return array();
    }

    /**
     * @Route("/article/{idArticle}", name="_article_blog", defaults={"idArticle"=null})
     * @Template()
     */
    public function articleBlogAction($idArticle)
    {
        return array("article" => $idArticle);
    }

    /**
     * @Route("/article/commentaires/{idArticle}", name="_article_commentaires", defaults={"idArticle"=null})
     * @Template()
     */
    public function articleCommentairesAction($idArticle)
    {
        return array();
    }

    

}
