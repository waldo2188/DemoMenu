<?php

namespace Waldo\DemoMenuBundle\Tests\BreadcrumpTest;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @group tata
 */
class CnertaBreadcrumpTest extends WebTestCase
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
    public function testShouldFoundTheRightThing($menuName, $parameters, $needles)
    {
        
        $crawler = $this->client->request('GET', $this->getRoute($menuName, $parameters));

        foreach($needles as $needle) {
            if(is_array($needle)) {
                $this->assertContains($needle[1], $this->rawFilterXPath($needle[0])->item(0)->nodeValue);
            } else {
                $this->assertEquals(1, $this->rawFilterXPath($needle)->length);
            }
        }

    }
    
    public function menuProvider()
    {
        return array(
            array('_welcome', array(), array('//nav[@class="cnerta-breadcrumb"]/span[text() = "Accueil"]') ),
            array('_utilisateur_list', array(), array('//nav[@class="cnerta-breadcrumb"]/span[text() = "Utilisateurs"]') ),
            array('_utilisateur_actif_list', array(), array(
                    '//nav[@class="cnerta-breadcrumb"]/a[text() = "Utilisateurs" and @href="/utilisateurs"]',
                    array('//nav[@class="cnerta-breadcrumb"]/a[text() = "Utilisateurs" and @href="/utilisateurs"]/following-sibling::text()', ">"),
                    '//nav[@class="cnerta-breadcrumb"]/span[text() = "Actif"]'
                )
),
            array('_utilisateur_ajouter', array(), array(
                    '//nav[@class="cnerta-breadcrumb"]/a[text() = "Utilisateurs" and @href="/utilisateurs"]',
                    array('//nav[@class="cnerta-breadcrumb"]/a[text() = "Utilisateurs" and @href="/utilisateurs"]/following-sibling::text()', ">"),
                    '//nav[@class="cnerta-breadcrumb"]/span[text() = "Ajouter"]'
                )
                ),
            array('_utilisateur_editer', array("id" => "waldo"), array(
                    '//nav[@class="cnerta-breadcrumb"]/a[text() = "Utilisateurs" and @href="/utilisateurs"]',
                    array('//nav[@class="cnerta-breadcrumb"]/a[text() = "Utilisateurs" and @href="/utilisateurs"]/following-sibling::text()', ">"),
                    '//nav[@class="cnerta-breadcrumb"]/span[text() = "Éditer"]'
                )
            ),
            array('_article_commentaires', array('idArticle' => 'loutre-plage'), array(
                    '//nav[@class="cnerta-breadcrumb"]/a[text() = "Articles" and @href="/articles"]',
                    array('//nav[@class="cnerta-breadcrumb"]/a[text() = "Articles" and @href="/articles"]/following-sibling::text()', ">"),
                    '//nav[@class="cnerta-breadcrumb"]/span[text() = "Commentaires"]'
                )
                ),
            array('_article_blog', array('idArticle' => 'loutre-plage'), array(
                    '//nav[@class="cnerta-breadcrumb"]/a[text() = "Articles" and @href="/articles"]',
                    array('//nav[@class="cnerta-breadcrumb"]/a[text() = "Articles" and @href="/articles"]/following-sibling::text()', ">"),
                    '//nav[@class="cnerta-breadcrumb"]/span[text() = "Les loutres à la plage"]'
                )
                ),
            array('_article_blog', array('idArticle' => 'loutre-montagne'), 
                array(
                    '//nav[@class="cnerta-breadcrumb"]/a[text() = "Articles" and @href="/articles"]',
                    array('//nav[@class="cnerta-breadcrumb"]/a[text() = "Articles" and @href="/articles"]/following-sibling::text()', ">"),
                    '//nav[@class="cnerta-breadcrumb"]/span[text() = "Les loutres à la montagne"]'
                )
            ),
            array('_article_blog', array('idArticle' => 'loutre-a-bierre'), array(
                    '//nav[@class="cnerta-breadcrumb"]/a[text() = "Articles" and @href="/articles"]',
                    array('//nav[@class="cnerta-breadcrumb"]/a[text() = "Articles" and @href="/articles"]/following-sibling::text()', ">"),
                    '//nav[@class="cnerta-breadcrumb"]/span[text() = "Les loutres en tournée dans les bars"]'
                )
                ),
            array('_article_blog', array('idArticle' => 'niveau-0'), array('//nav[@class="cnerta-breadcrumb"]/span[text() = "Niveau 0"]')),
            array('_article_blog', array('idArticle' => 'niveau-1'), array(
                    '//nav[@class="cnerta-breadcrumb"]/a[text() = "Niveau 0" and @href="/article/niveau-0"]',
                    array('//nav[@class="cnerta-breadcrumb"]/a[text() = "Niveau 0" and @href="/article/niveau-0"]/following-sibling::text()', ">"),
                    '//nav[@class="cnerta-breadcrumb"]/span[text() = "Niveau 1"]'
                )
            ),
            array('_article_blog', array('idArticle' => 'niveau-2'), 
                array(
                    '//nav[@class="cnerta-breadcrumb"]/a[text() = "Niveau 0" and @href="/article/niveau-0"]',
                    array('//nav[@class="cnerta-breadcrumb"]/a[text() = "Niveau 0" and @href="/article/niveau-0"]/following-sibling::text()', ">"),
                    '//nav[@class="cnerta-breadcrumb"]/a[text() = "Niveau 1" and @href="/article/niveau-1"]',
                    array('//nav[@class="cnerta-breadcrumb"]/a[text() = "Niveau 1" and @href="/article/niveau-1"]/following-sibling::text()', ">"),
                    '//nav[@class="cnerta-breadcrumb"]/span[text() = "Niveau 2"]'
                )   
            ),
            array('_article_blog', array('idArticle' => 'niveau-3'), array(
                    '//nav[@class="cnerta-breadcrumb"]/a[text() = "Niveau 0" and @href="/article/niveau-0"]',
                    array('//nav[@class="cnerta-breadcrumb"]/a[text() = "Niveau 0" and @href="/article/niveau-0"]/following-sibling::text()', ">"),
                    '//nav[@class="cnerta-breadcrumb"]/a[text() = "Niveau 1" and @href="/article/niveau-1"]',
                    array('//nav[@class="cnerta-breadcrumb"]/a[text() = "Niveau 1" and @href="/article/niveau-1"]/following-sibling::text()', ">"),
                    '//nav[@class="cnerta-breadcrumb"]/a[text() = "Niveau 2" and @href="/article/niveau-2"]',
                    array('//nav[@class="cnerta-breadcrumb"]/a[text() = "Niveau 2" and @href="/article/niveau-2"]/following-sibling::text()', ">"),
                    '//nav[@class="cnerta-breadcrumb"]/span[text() = "Niveau 3"]'
                )
            ),
            array('_article_blog', array('idArticle' => 'niveau-4'), array(
                    '//nav[@class="cnerta-breadcrumb"]/a[text() = "Niveau 0" and @href="/article/niveau-0"]',
                    array('//nav[@class="cnerta-breadcrumb"]/a[text() = "Niveau 0" and @href="/article/niveau-0"]/following-sibling::text()', ">"),
                    '//nav[@class="cnerta-breadcrumb"]/a[text() = "Niveau 1" and @href="/article/niveau-1"]',
                    array('//nav[@class="cnerta-breadcrumb"]/a[text() = "Niveau 1" and @href="/article/niveau-1"]/following-sibling::text()', ">"),
                    '//nav[@class="cnerta-breadcrumb"]/a[text() = "Niveau 2" and @href="/article/niveau-2"]',
                    array('//nav[@class="cnerta-breadcrumb"]/a[text() = "Niveau 2" and @href="/article/niveau-2"]/following-sibling::text()', ">"),
                    '//nav[@class="cnerta-breadcrumb"]/a[text() = "Niveau 3" and @href="/article/niveau-3"]',
                    array('//nav[@class="cnerta-breadcrumb"]/a[text() = "Niveau 3" and @href="/article/niveau-3"]/following-sibling::text()', ">"),
                    '//nav[@class="cnerta-breadcrumb"]/span[text() = "Niveau 4"]'
                )
            ),
            array('_article_blog', array('idArticle' => 'niveau-5'), array(
                    '//nav[@class="cnerta-breadcrumb"]/a[text() = "Niveau 0" and @href="/article/niveau-0"]',
                    array('//nav[@class="cnerta-breadcrumb"]/a[text() = "Niveau 0" and @href="/article/niveau-0"]/following-sibling::text()', ">"),
                    '//nav[@class="cnerta-breadcrumb"]/a[text() = "Niveau 1" and @href="/article/niveau-1"]',
                    array('//nav[@class="cnerta-breadcrumb"]/a[text() = "Niveau 1" and @href="/article/niveau-1"]/following-sibling::text()', ">"),
                    '//nav[@class="cnerta-breadcrumb"]/a[text() = "Niveau 2" and @href="/article/niveau-2"]',
                    array('//nav[@class="cnerta-breadcrumb"]/a[text() = "Niveau 2" and @href="/article/niveau-2"]/following-sibling::text()', ">"),
                    '//nav[@class="cnerta-breadcrumb"]/a[text() = "Niveau 3" and @href="/article/niveau-3"]',
                    array('//nav[@class="cnerta-breadcrumb"]/a[text() = "Niveau 3" and @href="/article/niveau-3"]/following-sibling::text()', ">"),
                    '//nav[@class="cnerta-breadcrumb"]/a[text() = "Niveau 4" and @href="/article/niveau-4"]',
                    array('//nav[@class="cnerta-breadcrumb"]/a[text() = "Niveau 4" and @href="/article/niveau-4"]/following-sibling::text()', ">"),
                    '//nav[@class="cnerta-breadcrumb"]/span[text() = "Niveau 5"]'
                )
            ),
            array('_article_blog', array('idArticle' => 'niveau-3-1'), array(
                    '//nav[@class="cnerta-breadcrumb"]/a[text() = "Niveau 0" and @href="/article/niveau-0"]',
                    array('//nav[@class="cnerta-breadcrumb"]/a[text() = "Niveau 0" and @href="/article/niveau-0"]/following-sibling::text()', ">"),
                    '//nav[@class="cnerta-breadcrumb"]/a[text() = "Niveau 1" and @href="/article/niveau-1"]',
                    array('//nav[@class="cnerta-breadcrumb"]/a[text() = "Niveau 1" and @href="/article/niveau-1"]/following-sibling::text()', ">"),
                    '//nav[@class="cnerta-breadcrumb"]/span[text() = "Niveau 3.1"]'
                )
            ),
            array('_article_blog', array('idArticle' => 'niveau-3-2'), array(
                    '//nav[@class="cnerta-breadcrumb"]/a[text() = "Niveau 0" and @href="/article/niveau-0"]',
                    array('//nav[@class="cnerta-breadcrumb"]/a[text() = "Niveau 0" and @href="/article/niveau-0"]/following-sibling::text()', ">"),
                    '//nav[@class="cnerta-breadcrumb"]/a[text() = "Niveau 1" and @href="/article/niveau-1"]',
                    array('//nav[@class="cnerta-breadcrumb"]/a[text() = "Niveau 1" and @href="/article/niveau-1"]/following-sibling::text()', ">"),
                    '//nav[@class="cnerta-breadcrumb"]/span[text() = "Niveau 3.2"]'
                )
            ),
        );
    }
    
    private function getRoute($route, $parameters = array())
    {
        return $this->client->getContainer()->get('router')->generate($route, $parameters, UrlGeneratorInterface::ABSOLUTE_PATH);
    }
    
    private function rawFilterXPath($xpathQuery)
    {
        $current = libxml_use_internal_errors(true);
        $disableEntities = libxml_disable_entity_loader(true);
        
        $doc = new \DOMDocument("1.0", "UTF-8"); 
        
        $doc->loadHTML($this->client->getResponse()->getContent());
        
        $xpath = new \DOMXpath($doc);
        
        libxml_use_internal_errors($current);
        libxml_disable_entity_loader($disableEntities);
        
        return $xpath->query($xpathQuery);
    }
}