<?php
require __DIR__ . '/bootstrap.php';

use Novosga\Twig\SecFormat;
use Slim\Views\TwigExtension;

$root = __DIR__ . '/public';
$tplDir = array("$root", "$root/templates");
$tmpDir = NOVOSGA_CACHE;
$loader = new Twig_Loader_Filesystem($tplDir);

// force auto-reload to always have the latest version of the template
$twig = new Twig_Environment($loader, array(
    'cache' => $tmpDir,
    'auto_reload' => true
));

$twig->addExtension(new Twig_Extensions_Extension_I18n());
$twig->addExtension(new TwigExtension());
$twig->addFilter(new SecFormat());
// configure Twig the way you want

// iterate over all your templates
foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator("$root"), RecursiveIteratorIterator::LEAVES_ONLY) as $file)
{
    // force compilation
    if ($file->isFile() && in_array('twig', explode('.', $file->getBasename()))) {
        $filename = str_replace($root, '', $file->getPathname());
        $filename = str_replace('/templates', '', $filename);
        $twig->loadTemplate($filename);
        echo "LOADED: {$file->getPathname()} \n";
    }
}