<?php
/**
 * Migration: 005_create_sample_listings
 * Description: Creates 7 sample listings for testing
 * Depends: 003_create_default_pages
 */

$listingsData = [
    [
        'name' => 'terraza-mirasol',
        'title' => 'Terraza Mirasol',
        'excerpt' => 'Terraza amplia con vista a las montañas, perfecta para eventos familiares y cumpleaños.',
        'description' => 'Terraza Mirasol ofrece un espacio único con capacidad para hasta 200 personas. Contamos con alberca, jardín exuberante, cocina industrial totalmente equipada y área de juegos para niños. Nuestro equipo de seguridad está disponible las 24 horas.',
        'address' => 'Av. Vallarta 4521, Col. La Estancia',
        'city' => 'Zapopan',
        'state' => 'Jalisco',
        'latitude' => 20.6581,
        'longitude' => -103.4369,
        'phone' => '33 1234 5678',
        'whatsapp' => '523312345678',
        'email' => 'contacto@terrazamirasol.com',
        'capacity_min' => 50,
        'capacity_max' => 200,
        'price_min' => 5000,
        'price_max' => 15000,
        'featured' => true,
        'verified' => true,
        'verification_status' => 'basic',
        'category' => 'terraces',
        'location' => 'zapopan',
        'features' => ['pool', 'parking', 'kitchen', 'garden', 'bathrooms', 'security']
    ],
    [
        'name' => 'terraza-roca',
        'title' => 'Terraza La Roca',
        'excerpt' => 'Terraza moderna con vista panorámica, ideal para eventos corporativos.',
        'description' => 'Terraza La Roca ofrece un espacio contemporáneo con acabados de piedra natural y cristal. Con capacidad para 150 personas, es perfecto para lanzamientos de productos, conferencias y eventos corporativos.',
        'address' => 'Av. Angeles 1500, Col. Angeles',
        'city' => 'Tonalá',
        'state' => 'Jalisco',
        'latitude' => 20.7061,
        'longitude' => -103.2312,
        'phone' => '33 4567 8901',
        'whatsapp' => '523345678901',
        'email' => 'corporativo@terrazaroca.com',
        'capacity_min' => 50,
        'capacity_max' => 150,
        'price_min' => 8000,
        'price_max' => 20000,
        'featured' => false,
        'verified' => false,
        'verification_status' => 'unverified',
        'category' => 'terraces',
        'location' => 'tonala',
        'features' => ['parking', 'kitchen', 'bathrooms', 'security']
    ],
    [
        'name' => 'terraza-estrella',
        'title' => 'Terraza Estrella',
        'excerpt' => 'Terraza con ambiente rústico y fuego prendido, perfecta para reuniones nocturnas.',
        'description' => 'Terraza Estrella ofrece una experiencia única con chimeneas al aire libre y luces cálidas. El espacio tiene capacidad para 80 personas y es perfecto para posadas, reuniones y eventos nocturnos.',
        'address' => 'Av. López Mateos 3200, Col. Jardines del Bosque',
        'city' => 'Guadalajara',
        'state' => 'Jalisco',
        'latitude' => 20.6812,
        'longitude' => -103.3987,
        'phone' => '33 6789 0123',
        'whatsapp' => '523367890123',
        'email' => 'eventos@terrazaestrella.com',
        'capacity_min' => 30,
        'capacity_max' => 80,
        'price_min' => 3000,
        'price_max' => 8000,
        'featured' => false,
        'verified' => true,
        'verification_status' => 'basic',
        'category' => 'terraces',
        'location' => 'guadalajara',
        'features' => ['parking', 'garden', 'bathrooms']
    ],
    [
        'name' => 'salon-el-castillo',
        'title' => 'Salón El Castillo',
        'excerpt' => 'Elegante salón con arquitectura colonial, ideal para bodas y eventos formales.',
        'description' => 'Salón El Castillo combina elegancia colonial con comodidades modernas. Con capacidad para 300 invitados, ofrecemos servicio de catering incluido, pista de baile profesional y estacionamiento vigilado.',
        'address' => 'Calle Morelos 234, Centro',
        'city' => 'Guadalajara',
        'state' => 'Jalisco',
        'latitude' => 20.6761,
        'longitude' => -103.3478,
        'phone' => '33 2345 6789',
        'whatsapp' => '523323456789',
        'email' => 'info@salonelcastillo.com',
        'capacity_min' => 100,
        'capacity_max' => 300,
        'price_min' => 15000,
        'price_max' => 35000,
        'featured' => true,
        'verified' => true,
        'verification_status' => 'documents',
        'category' => 'salons',
        'location' => 'guadalajara',
        'features' => ['parking', 'kitchen', 'bathrooms', 'security']
    ],
    [
        'name' => 'salon-diamante',
        'title' => 'Salón Diamante',
        'excerpt' => 'Salón ejecutivo con amenidades premium para eventos de alto nivel.',
        'description' => 'Salón Diamante es la elección perfecta para eventos exclusivos. Con tecnología de punta, servicio de conserjería y cocina de autor, garantizamos una experiencia inolvidable.',
        'address' => 'Av. Mariano Otero 2801, Col. Verde Valle',
        'city' => 'Guadalajara',
        'state' => 'Jalisco',
        'latitude' => 20.6987,
        'longitude' => -103.4123,
        'phone' => '33 5678 9012',
        'whatsapp' => '523356789012',
        'email' => 'vip@salondiamante.com',
        'capacity_min' => 200,
        'capacity_max' => 500,
        'price_min' => 25000,
        'price_max' => 50000,
        'featured' => true,
        'verified' => true,
        'verification_status' => 'onsite',
        'category' => 'salons',
        'location' => 'guadalajara',
        'features' => ['parking', 'kitchen', 'garden', 'bathrooms', 'security']
    ],
    [
        'name' => 'jardin-botanico',
        'title' => 'Jardín Botánico',
        'excerpt' => 'Espacio al aire libre rodeado de naturaleza, perfecto para eventos íntimos.',
        'description' => 'Jardín Botánico es un oasis urbano con más de 500 especies de plantas. El espacio es ideal para bodas íntimas, comuniones y reuniones familiares. Contamos con pérgolas naturales, fuente central y iluminación ambiental.',
        'address' => 'Carretera a Nogales Km 5',
        'city' => 'Tlaquepaque',
        'state' => 'Jalisco',
        'latitude' => 20.6403,
        'longitude' => -103.3078,
        'phone' => '33 3456 7890',
        'whatsapp' => '523334567890',
        'email' => 'reservaciones@jardinbotanico.mx',
        'capacity_min' => 30,
        'capacity_max' => 150,
        'price_min' => 4000,
        'price_max' => 12000,
        'featured' => false,
        'verified' => true,
        'verification_status' => 'basic',
        'category' => 'gardens',
        'location' => 'tlaquepaque',
        'features' => ['garden', 'parking', 'bathrooms']
    ]
];

$pages = $GLOBALS['__mig_pages'];
$templates = $GLOBALS['__mig_templates'];

$templateListing = $templates->get('listing');
if (!$templateListing) {
    echo "Listing template not found, skipping\n";
    exit;
}

foreach ($listingsData as $data) {
    $category = $pages->get("/listings/{$data['category']}/");
    if (!$category->id) {
        echo "Category {$data['category']} not found, creating parent\n";
        continue;
    }

    $existing = $pages->find("parent=$category, name={$data['name']}")->first();
    if ($existing->id) {
        echo "Listing {$data['name']} already exists, skipping\n";
        continue;
    }

    $page = new \ProcessWire\Page();
    $page->template = $templateListing;
    $page->parent = $category;
    $page->title = $data['title'];
    $page->name = $data['name'];

    $page->fld_name = $data['title'];
    $page->fld_excerpt = $data['excerpt'];
    $page->fld_description = $data['description'];
    $page->fld_address = $data['address'];
    $page->fld_city = $data['city'];
    $page->fld_state = $data['state'];
    $page->fld_latitude = $data['latitude'];
    $page->fld_longitude = $data['longitude'];
    $page->fld_phone = $data['phone'];
    $page->fld_whatsapp = $data['whatsapp'];
    $page->fld_email = $data['email'];
    $page->fld_capacity_min = $data['capacity_min'];
    $page->fld_capacity_max = $data['capacity_max'];
    $page->fld_price_min = $data['price_min'];
    $page->fld_price_max = $data['price_max'];
    $page->fld_featured = $data['featured'];
    $page->fld_verified = $data['verified'];
    $page->fld_verification_status = $data['verification_status'];
    $page->fld_status = 'active';
    $page->fld_plan = $data['featured'] ? 'premium' : 'basic';

    $page->fld_category = $category;

    $location = $pages->get("/locations/{$data['location']}/");
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

echo "\nMigration 005 completed: sample listings created\n";