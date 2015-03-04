<?php
require __DIR__ . '/bootstrap.php';

$root = __DIR__;
$tplDir = array("$root", "$root/templates");
$tmpDir = NOVOSGA_CACHE;
$loader = new Twig_Loader_Filesystem($tplDir);

// force auto-reload to always have the latest version of the template
$twig = new Twig_Environment($loader, array(
    'cache' => $tmpDir,
    'auto_reload' => true
));

$twig->addExtension(new \Twig_Extensions_Extension_I18n());
$twig->addExtension(new \Slim\Views\TwigExtension());
$twig->addExtension(new \Novosga\Twig\Extensions());

// configure Twig the way you want
// iterate over all your templates
foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator("$root"), RecursiveIteratorIterator::LEAVES_ONLY) as $file)
{
    // force compilation
    if ($file->isFile()) {
        $ext = end(explode('.', $file->getBasename()));
        if ($ext === 'twig') {
            $filename = str_replace($root, '', $file->getPathname());
            if (substr($filename, 0, 8) !== '/vendor/') {
                $filename = str_replace('/templates', '', $filename);
                echo "LOADING: {$file->getPathname()} ... ";
                $twig->loadTemplate($filename);
                echo "[OK]\n";
            }
        }
    }
}
