<?php

namespace Waldo\DemoMenuBundle\Tests\BreadcrumpTest;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BreadcrumpTest extends WebTestCase
{
    private $client;
    
    public function setUp()
    {
        $this->client = static::createClient();
    }
    
    public function tearDown()
    {
        $this->client = null;
    }
    
    /**
     * @dataProvider menuProvider
     */
    public function testShouldFoundTheRightThing($menuName, $parameters, $needle)
    {
        
        $crawler = $this->client->request('GET', $this->getRoute($menuName, $parameters));
               
        $this->assertContains($needle, $this->client->getResponse()->getContent()); 
    }
    
    public function menuProvider()
    {
        return array(
            array('_welcome', array(), '<nav class="breadcrumb"><span>Accueil</span></nav>'),
            array('_utilisateur_list', array(), '<nav class="breadcrumb"><span>Utilisateurs</span></nav>'),
            array('_utilisateur_actif_list', array(), <<<EOF
<nav class="breadcrumb"><a href="/utilisateurs">Utilisateurs</a>
                                                                                                         &gt;                         <span>Actif</span></nav>
EOF
),
            array('_utilisateur_ajouter', array(), <<<EOF
<nav class="breadcrumb"><a href="/utilisateurs">Utilisateurs</a>
                                                                                                         &gt;                         <span>Ajouter</span></nav>
EOF
            ),
            array('_utilisateur_editer', array("id" => "waldo"), <<<EOF
<nav class="breadcrumb"><a href="/utilisateurs">Utilisateurs</a>
                                                                                                         &gt;                         <span>Éditer</span></nav>
EOF
            ),
            array('_article_commentaires', array('idArticle' => 'loutre-plage'), <<<EOF
<nav class="breadcrumb"><a href="/articles">Articles</a>
                                                                                                         &gt;                         <span>Commentaires</span></nav>
EOF
            ),
            array('_article_blog', array('idArticle' => 'loutre-plage'), <<<EOF
<nav class="breadcrumb"><a href="/articles">Articles</a>
                                                                                                         &gt;                         <span>Les loutres à la plage</span></nav>
EOF
            ),
            array('_article_blog', array('idArticle' => 'loutre-montagne'), <<<EOF
<nav class="breadcrumb"><a href="/articles">Articles</a>
                                                                                                         &gt;                         <span>Les loutres à la montagne</span></nav>
EOF
            ),
            array('_article_blog', array('idArticle' => 'loutre-a-bierre'), <<<EOF
<nav class="breadcrumb"><a href="/articles">Articles</a>
                                                                                                         &gt;                         <span>Les loutres en tournée dans les bars</span></nav>
EOF
            ),
            array('_article_blog', array('idArticle' => 'niveau-0'), <<<EOF
<nav class="breadcrumb"><span>Niveau 0</span></nav>
EOF
            ),
            array('_article_blog', array('idArticle' => 'niveau-1'), <<<EOF
<nav class="breadcrumb"><a href="/article/niveau-0">Niveau 0</a>
                                                                                                         &gt;                         <span>Niveau 1</span></nav>
EOF
            ),
            array('_article_blog', array('idArticle' => 'niveau-2'), <<<EOF
<nav class="breadcrumb"><a href="/article/niveau-0">Niveau 0</a>
                                                                                                         &gt;                         <a href="/article/niveau-1">Niveau 1</a>
                                                                                                         &gt;                         <span>Niveau 2</span></nav>
EOF
            ),
            array('_article_blog', array('idArticle' => 'niveau-3'), <<<EOF
<nav class="breadcrumb"><a href="/article/niveau-0">Niveau 0</a>
                                                                                                         &gt;                         <a href="/article/niveau-1">Niveau 1</a>
                                                                                                         &gt;                         <a href="/article/niveau-2">Niveau 2</a>
                                                                                                         &gt;                         <span>Niveau 3</span></nav>
EOF
            ),
            array('_article_blog', array('idArticle' => 'niveau-4'), <<<EOF
<nav class="breadcrumb"><a href="/article/niveau-0">Niveau 0</a>
                                                                                                         &gt;                         <a href="/article/niveau-1">Niveau 1</a>
                                                                                                         &gt;                         <a href="/article/niveau-2">Niveau 2</a>
                                                                                                         &gt;                         <a href="/article/niveau-3">Niveau 3</a>
                                                                                                         &gt;                         <span>Niveau 4</span></nav>
EOF
            ),
            array('_article_blog', array('idArticle' => 'niveau-5'), <<<EOF
<nav class="breadcrumb"><a href="/article/niveau-0">Niveau 0</a>
                                                                                                         &gt;                         <a href="/article/niveau-1">Niveau 1</a>
                                                                                                         &gt;                         <a href="/article/niveau-2">Niveau 2</a>
                                                                                                         &gt;                         <a href="/article/niveau-3">Niveau 3</a>
                                                                                                         &gt;                         <a href="/article/niveau-4">Niveau 4</a>
                                                                                                         &gt;                         <span>Niveau 5</span></nav>
EOF
            ),
            array('_article_blog', array('idArticle' => 'niveau-3-1'), <<<EOF
<nav class="breadcrumb"><a href="/article/niveau-0">Niveau 0</a>
                                                                                                         &gt;                         <a href="/article/niveau-1">Niveau 1</a>
                                                                                                         &gt;                         <a href="/article/niveau-2">Niveau 2</a>
                                                                                                         &gt;                         <span>Niveau 3.1</span></nav>
EOF
            ),
            array('_article_blog', array('idArticle' => 'niveau-3-2'), <<<EOF
<nav class="breadcrumb"><a href="/article/niveau-0">Niveau 0</a>
                                                                                                         &gt;                         <a href="/article/niveau-1">Niveau 1</a>
                                                                                                         &gt;                         <a href="/article/niveau-2">Niveau 2</a>
                                                                                                         &gt;                         <span>Niveau 3.2</span></nav>
EOF
            ),
        );
    }
    
    private function getRoute($route, $parameters = array())
    {
        return $this->client->getContainer()->get('router')->generate($route, $parameters, UrlGeneratorInterface::ABSOLUTE_PATH);
    }
}