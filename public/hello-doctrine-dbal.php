<?php

// déclaration des classes PHP qui seront utilisées
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;

// activation de la fonction autoloading de Composer
require __DIR__.'/../vendor/autoload.php';

$loader = new Twig_Loader_Filesystem(__DIR__.'/../templates');
// activer le mode debug et le mode de variables strictes
$twig = new Twig_Environment($loader, [
    'debug' => true,
    'strict_variables' => true,
]);

// charger l'extension Twig_Extension_Debug
$twig->addExtension(new Twig_Extension_Debug());

// création d'une variable avec une configuration par défaut
$config = new Configuration();

// création d'un tableau avec les paramètres de connection à la BDD
$connectionParams = [
    'driver'    => 'pdo_mysql',
    'host'      => '127.0.0.1',
    'port'      => '3306',
    'dbname'    => 'bus',
    'user'      => 'nahjo',
    'password'  => 'J0han/62410',
    'charset'   => 'utf8mb4',
];

// connection à la BDD
// la variable `$conn` permet de communiquer avec la BDD
$conn = DriverManager::getConnection($connectionParams, $config);

// envoi d'une requête SQL à la BDD et récupération du résultat sous forme de tableau PHP dans la variable `$items`
// $items = $conn->fetchAll('SELECT * FROM route');
$items = $conn->executeQuery('SELECT * FROM route');


// parcours de chacun des éléments du tableau `$items`

while($req = $items->fetch()){
    $routes[] = $req;
    $routes_id[] = $req['route_id'];
    $routes_long_name[] = $req['route_long_name'];
    $towns = explode(" - " , $req['route_long_name']);

    foreach ($towns as $towns) {
        // $stown_sp[] = $stowns;
    
        $town_split = $conn->executeQuery('SELECT * FROM Towns WHERE Towns_name = :town_name', [
            'town_name' => $towns,
        ]);
        
        $donner = $town_split->fetch();
    
        if ($donner) {
            
        }
        else{
    
            $count = $conn->insert('Towns', [
                'Towns_name' => $towns,
                
            ]);
            $error = 'ok';
    
        }
        
    }

}





echo $twig->render('home.html.twig', [
    'routes' => $routes,
    'routes_id' => $routes_id ,
    'routes_long_name' => $routes_long_name,   
    'error' => $error,
]);