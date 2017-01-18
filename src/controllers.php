<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

Request::setTrustedProxies(array('127.0.0.1'));

$app->get('/', function () use ($app) {


    $hiddenSites = $app['app_vars']['hiddensites'];
    $tld = $app['app_vars']['tld'];
    $finder = new Finder();
    $finder->files()->in($app['app_vars']['dir']);
    $dirs = $finder->depth('== 0')->directories();

    $apps = [];

    foreach ($dirs as $dir) {

        if ( in_array( $dir->getBasename(), $hiddenSites ) ) continue;
        $apps[$dir->getBasename()]['name'] = $dir->getBasename();

        $dirSplit = explode('/', $dir->getPathname());
        $dirName = $dirSplit[count($dirSplit)-2];
        $apps[$dir->getBasename()]['dirname'] = $dirName;

        $url = sprintf( 'http://%1$s.%2$s.%3$s', $dir->getBasename(), $dirName, $tld );

        $apps[$dir->getBasename()]['url'] = $url;

        $files = $finder->files()->name('composer.json')->in($dir->getPathname());
        if(count($files) > 0) {
            $apps[$dir->getBasename()]['type'] = 'php';
            $apps[$dir->getBasename()]['img'] = $app['app_vars']['appimage']['php'][array_rand($app['app_vars']['appimage']['php'])];
        } else {
            $apps[$dir->getBasename()]['type'] = 'html';
            $apps[$dir->getBasename()]['img'] = $app['app_vars']['appimage']['html'][array_rand($app['app_vars']['appimage']['html'])];
        }
    }

    return $app['twig']->render('index.html.twig',
                                ['page_title'=> $app['app_vars']['page_title'],
                                 'app_name'  => $app['app_vars']['app_name'],
                                 'header_title' => $app['app_vars']['header_title'],
                                 'devtools'  => $app['app_vars']['devtools'],
                                 'apps'      => $apps]);
})->bind('homepage');

$app->get('/info', function () {
    return phpinfo();
})->bind('info');

$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/'.$code.'.html.twig',
        'errors/'.substr($code, 0, 2).'x.html.twig',
        'errors/'.substr($code, 0, 1).'xx.html.twig',
        'errors/default.html.twig',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});
