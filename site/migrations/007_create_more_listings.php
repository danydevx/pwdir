<?php
/**
 * Migration: 007_create_more_listings
 * Description: Creates 7 more sample listings
 * Depends: 003_create_default_pages
 */

$templates = $GLOBALS['__mig_templates'];
$pages = $GLOBALS['__mig_pages'];

$listingsData = [
    [
        'title' => 'Terraza Las Lomas',
        'name' => 'terraza-las-lomas',
        'category' => 'terraces',
        'location' => 'guadalajara',
        'excerpt' => 'Terraza espaciosa con vista a la ciudad, ideal para bodas y eventos grandes.',
        'description' => 'Terraza Las Lomas ofrece un espacio único con vista panorámica a Guadalajara. Con capacidad para hasta 500 personas, es perfecta para bodas, quinceañeras y eventos corporativos. Contamos con cocina industrial, estacionamiento para 200 autos y jardín lateral.',
        'address' => 'Av. Adolfo de la Madrid 1234',
        'city' => 'Guadalajara',
        'state' => 'Jalisco',
        'latitude' => 20.6723,
        'longitude' => -103.3245,
        'capacity_min' => 100,
        'capacity_max' => 500,
        'price_min' => 15000,
        'price_max' => 45000,
        'whatsapp' => '523312345678',
        'email' => 'info@terrazalaslomas.com',
        'featured' => true,
        'verified' => true,
        'features' => ['parking', 'kitchen', 'garden', 'security']
    ],
    [
        'title' => 'Salón Emperador',
        'name' => 'salon-emperador',
        'category' => 'salons',
        'location' => 'zapopan',
        'excerpt' => 'Salón elegante con arquitectura colonial, ideal para bodas y eventos formales.',
        'description' => 'Salón Emperador combina elegancia colonial con amenidades modernas. Perfecto para bodas íntimas hasta eventos de 300 personas. Incluye palco para música en vivo, cocina completa y jardín externo.',
        'address' => 'Camino Real a Colima 890',
        'city' => 'Zapopan',
        'state' => 'Jalisco',
        'latitude' => 20.7156,
        'longitude' => -103.4023,
        'capacity_min' => 50,
        'capacity_max' => 300,
        'price_min' => 20000,
        'price_max' => 60000,
        'whatsapp' => '523312345679',
        'email' => 'contacto@salonemperador.com',
        'featured' => true,
        'verified' => true,
        'features' => ['parking', 'kitchen', 'garden', 'security']
    ],
    [
        'title' => 'Jardín Los Arcos',
        'name' => 'jardin-los-arcos',
        'category' => 'gardens',
        'location' => 'tlaquepaque',
        'excerpt' => 'Jardín hermoso con fuente central y arcos florales, perfecto para ceremonias.',
        'description' => 'Jardín Los Arcos es un oasis urbano con más de 30 años de experiencia en eventos. Contamos con fuente monumental, área de ceremonia con arcos florales naturales, restoran cocina tradicional mexicana.',
        'address' => 'Av. Juárez 456',
        'city' => 'Tlaquepaque',
        'state' => 'Jalisco',
        'latitude' => 20.6423,
        'longitude' => -103.2834,
        'capacity_min' => 80,
        'capacity_max' => 400,
        'price_min' => 12000,
        'price_max' => 35000,
        'whatsapp' => '523312345680',
        'email' => 'reservaciones@jardinlosarcos.com',
        'featured' => false,
        'verified' => true,
        'features' => ['garden', 'kitchen', 'parking']
    ],
    [
        'title' => 'Terraza Nocturna',
        'name' => 'terraza-nocturna',
        'category' => 'terraces',
        'location' => 'tonala',
        'excerpt' => 'Terraza con ambiente festivo, iluminación LED y música en vivo los fines de semana.',
        'description' => 'Terraza Nocturna es el lugar perfecto para fiestas juveniles, cumpleaños y eventos nocturnos. Contamos con iluminación ambiente, pista de baile, sonido profesional y bar.',
        'address' => 'Calle Manuel Payno 234',
        'city' => 'Tonalá',
        'state' => 'Jalisco',
        'latitude' => 20.6956,
        'longitude' => -103.2432,
        'capacity_min' => 50,
        'capacity_max' => 250,
        'price_min' => 8000,
        'price_max' => 25000,
        'whatsapp' => '523312345681',
        'email' => 'fiestas@terrazanocturna.com',
        'featured' => false,
        'verified' => false,
        'features' => ['parking', 'bathrooms']
    ],
    [
        'title' => 'Salón Premier',
        'name' => 'salon-premier',
        'category' => 'salons',
        'location' => 'guadalajara',
        'excerpt' => 'Salón ejecutivo con amenidades premium para eventos corporativos y sociales.',
        'description' => 'Salón Premier ofrece espacios versátiles para conferencias, seminarios, bodas y eventos sociales. Amenidades incluyen proyector, pantalla gigante, sonido surround, y servicio de catering opcional.',
        'address' => 'Av. Vallarta 2345',
        'city' => 'Guadalajara',
        'state' => 'Jalisco',
        'latitude' => 20.6789,
        'longitude' => -103.3567,
        'capacity_min' => 30,
        'capacity_max' => 200,
        'price_min' => 10000,
        'price_max' => 40000,
        'whatsapp' => '523312345682',
        'email' => 'events@salonpremier.com',
        'featured' => true,
        'verified' => true,
        'features' => ['parking', 'kitchen', 'security']
    ],
    [
        'title' => 'Rancho El Encino',
        'name' => 'rancho-el-encino',
        'category' => 'gardens',
        'location' => 'zapopan',
        'excerpt' => 'Espacio rural con ambiente campirano, caballos y zona de fogata.',
        'description' => 'Rancho El Encino ofrece una experiencia única con ambiente campirano. Perfecto para despedidas, bodas al aire libre y eventos temáticos. Contamos con zona de fogata, área de juegos y cocina rural.',
        'address' => 'Carretera a San Cristóbal 567',
        'city' => 'Zapopan',
        'state' => 'Jalisco',
        'latitude' => 20.7589,
        'longitude' => -103.4567,
        'capacity_min' => 100,
        'capacity_max' => 600,
        'price_min' => 18000,
        'price_max' => 55000,
        'whatsapp' => '523312345683',
        'email' => 'contacto@ranchoelencino.com',
        'featured' => false,
        'verified' => false,
        'features' => ['parking', 'garden', 'kitchen']
    ],
    [
        'title' => 'Terraza Bella Vista',
        'name' => 'terraza-bella-vista',
        'category' => 'terraces',
        'location' => 'guadalajara',
        'excerpt' => 'Terraza moderna en el centro con vista a los edificios históricos.',
        'description' => 'Terraza Bella Vista combina lo moderno con lo clásico. Ubicada en el centro histórico, ofrece una vista única. Ideal para eventos corporativos y celebraciones íntimas.',
        'address' => 'Av. 16 de Septiembre 152',
        'city' => 'Guadalajara',
        'state' => 'Jalisco',
        'latitude' => 20.6734,
        'longitude' => -103.3432,
        'capacity_min' => 30,
        'capacity_max' => 120,
        'price_min' => 6000,
        'price_max' => 18000,
        'whatsapp' => '523312345684',
        'email' => 'hola@terrazabellavista.com',
        'featured' => false,
        'verified' => true,
        'features' => ['parking', 'bathrooms']
    ],
    [
        'title' => 'Salón Diamante Elite',
        'name' => 'salon-diamante-elite',
        'category' => 'salons',
        'location' => 'zapopan',
        'excerpt' => 'Salón de lujo con cristalería Swarovski y acabados en oro.',
        'description' => 'Salón Diamante Elite es la expresión máxima del lujo. Con acabados en mármol italiano, cristalería Swarovski y servicio de mayordomía, cada evento es inolvidable.',
        'address' => 'Av. Patria 1234',
        'city' => 'Zapopan',
        'state' => 'Jalisco',
        'latitude' => 20.7234,
        'longitude' => -103.3876,
        'capacity_min' => 100,
        'capacity_max' => 400,
        'price_min' => 50000,
        'price_max' => 150000,
        'whatsapp' => '523312345685',
        'email' => 'reservaciones@salondiamante.com',
        'featured' => true,
        'verified' => true,
        'features' => ['parking', 'kitchen', 'security', 'garden']
    ],
];

$templateListing = $templates->get('listing');
$parent = $pages->get('/listings/');

foreach ($listingsData as $data) {
    $existing = $pages->find("template=listing, name={$data['name']}")->count();
    if ($existing > 0) {
        echo "Listing {$data['title']} already exists, skipping\n";
        continue;
    }

    $category = $pages->get("/listings/{$data['category']}/");
    $location = $pages->get("/locations/{$data['location']}/");

    $page = new \ProcessWire\Page();
    $page->template = $templateListing;
    $page->parent = $parent;
    $page->name = $data['name'];
    $page->title = $data['title'];

    $page->fld_name = $data['title'];
    $page->fld_excerpt = $data['excerpt'];
    $page->fld_description = $data['description'];
    $page->fld_address = $data['address'];
    $page->fld_city = $data['city'];
    $page->fld_state = $data['state'];
    $page->fld_latitude = $data['latitude'];
    $page->fld_longitude = $data['longitude'];
    $page->fld_whatsapp = $data['whatsapp'];
    $page->fld_email = $data['email'];
    $page->fld_capacity_min = $data['capacity_min'];
    $page->fld_capacity_max = $data['capacity_max'];
    $page->fld_price_min = $data['price_min'];
    $page->fld_price_max = $data['price_max'];
    $page->fld_featured = $data['featured'];
    $page->fld_verified = $data['verified'];
    $page->fld_verification_status = $data['verified'] ? 'basic' : 'unverified';
    $page->fld_status = 'active';
    $page->fld_plan = $data['featured'] ? 'premium' : 'basic';

    if ($category->id) {
        $page->fld_category = $category;
    }
    if ($location->id) {
        $page->fld_location = $location;
    }

    $featuresArray = [];
    foreach ($data['features'] as $featName) {
        $feature = $pages->get("/listing-features/$featName/");
        if ($feature->id) {
            $featuresArray[] = $feature;
        }
    }
    if (count($featuresArray) > 0) {
        $page->set('fld_features', $featuresArray);
    }

    $page->save();
    echo "Created listing: {$data['title']} (ID: {$page->id})\n";
}

echo "\nMigration 007 completed: more listings created\n";