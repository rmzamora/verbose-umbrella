<?php

use Silex\Application;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;

$app = new Application();
$app->register(new ServiceControllerServiceProvider());
$app->register(new AssetServiceProvider());
$app->register(new TwigServiceProvider());
$app->register(new HttpFragmentServiceProvider());

$app['app_vars'] = ['page_title' => 'Local Development',
                    'app_name'   => 'verbose-umbrella',
                    'header_title' => 'mell zamora\'s MAC',
                    'projroot'   => './',
                    // change to your local directory
                    'dir'        => ["/Users/rommel.zamora/Documents/DevFiles/default/htdocs/sites"],
                    'tld'        => 'dev',
                    'icons'      => ['apple-touch-icon.png', 'favicon.ico'],
                    'appimage'   => ['php'=>['img/portfolio/web/php-logo_01_preview.jpg',
                                             'img/portfolio/web/php-logo_02_preview.jpg',
                                             'img/portfolio/web/symfony2-logo_01_preview.jpg',
                                             'img/portfolio/web/symfony2-logo_02_preview.jpg'],
                                     'html'=>['img/portfolio/web/web-logo__01_preview.jpg',
                                              'img/portfolio/web/web-logo__02_preview.jpg']],
                    'devtools'   => [['name' => 'Github', 'url' => 'https://github.com/', 'logo'=>'img/team/tools/tools-logo_01.jpg', 'desc'=>'Code Repository'],
                                     ['name' => 'Bitbucket', 'url' => 'https://bitbucket.org/', 'logo'=>'img/team/tools/tools-logo_02.jpg', 'desc'=>'Code Repository'],
                                     ['name' => 'Packagist', 'url' => 'https://packagist.org/', 'logo'=>'img/team/tools/tools-logo_03.jpg', 'desc'=>'Package Manager']],
                    'siteoptions' => [
                        //  'dirname' => 'Display Name',
                        //	'dirname' => ['displayname' => 'DisplayName', 'adminurl' => 'http://something/admin' ],
                        ],
                    'hiddensites' => ['home']];


$app['twig'] = $app->extend('twig', function ($twig, $app) {
    // add custom globals, filters, tags, ...
    return $twig;
});

return $app;
