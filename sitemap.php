<?php
date_default_timezone_set('America/New_York');
define("DS", DIRECTORY_SEPARATOR);
define("ROOT", dirname(__FILE__));
$_SERVER['REQUEST_URI'] = '';

require_once(ROOT . DS . 'src' . DS . 'HungryHuntington.php');
require_once(ROOT . DS . 'src' . DS . 'Router.php');
require_once(ROOT . DS . 'src' . DS . 'Controller.php');
require_once(ROOT . DS . 'vendor' . DS . 'autoload.php');
$hungry     = new Wappr\HungryHuntington;
$router     = new Wappr\Router;
$controller = new Wappr\Controller($_POST);
require_once(ROOT . DS . 'routes.php');

$xmlHead = '<?xml version="1.0" encoding="UTF-8"?>
<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
$xmlFoot = '</urlset>';

$r = 'https://hungryhuntington.com/';

# Set homepage
$pages[] = $r;

# Add days of week
$pages[] = $r . 'weekly/';
$pages[] = $r . 'weekly/monday/';
$pages[] = $r . 'weekly/tuesday/';
$pages[] = $r . 'weekly/wednesday/';
$pages[] = $r . 'weekly/thursday/';
$pages[] = $r . 'weekly/friday/';
$pages[] = $r . 'weekly/saturday/';
$pages[] = $r . 'weekly/sunday/';

# Add bar deals
$pages[] = $r . 'bar/';

# Add restaurants
$pages[] = $r . 'restaurants/';
$restaurants = $hungry->getCompanies();
foreach($restaurants as $restaurant) {
    $pages[] = $r . 'restaurants/' . $restaurant['url_title'] . '/';
}

# Random
$pages[] = $r . 'random/';
$pages[] = $r . 'random/food-deals/';
$pages[] = $r . 'random/drink-deals/';

# Submit a deal
$pages[] = $r . 'submit/';

# Get all the deals unique urls
$deals = $hungry->getDeals();
foreach($deals as $deal) {
    $pages[] = $r . $deal['url_title'] . '/' . $deal['deal_url'] . '/';
}

$sitemap = $xmlHead . "\r\n";
foreach($pages as $page) {
    $sitemap .= "\t" . '<url>
      <loc>' . $page . '</loc>
      <changefreq>daily</changefreq>
    </url>' . "\r\n";
}
$sitemap .= $xmlFoot;

# Create XML object
$xmlSitemap = new SimpleXMLElement($sitemap);
# Write the sitemap to a file
$xmlSitemap->asXML(ROOT . DS . 'public' . DS . 'sitemap.xml');
# Submit the sitemap

$sitemapUrl = 'https://hungryhuntington.com/sitemap.xml';

$url = "http://www.google.com/webmasters/sitemaps/ping?sitemap=".$sitemapUrl;
  SubmitSiteMap($url);

//Bing / MSN
$url = "http://www.bing.com/webmaster/ping.aspx?siteMap=".$sitemapUrl;
SubmitSiteMap($url);

function Submit($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $httpCode;
}
function SubmitSiteMap($url) {
    $returnCode = Submit($url);
    if ($returnCode != 200) {
        echo "Error $returnCode: $url <BR/>";
    } else {
        echo "Submitted $returnCode: $url <BR/>";
    }
}
