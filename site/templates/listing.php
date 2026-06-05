<?php namespace ProcessWire;

use ProcessWire\InputfieldForm;
use ProcessWire\InputfieldText;
use ProcessWire\InputfieldEmail;
use ProcessWire\InputfieldTextarea;
use ProcessWire\InputfieldInteger;

$listing = $page;
$category = $listing->fld_category;
$features = $listing->fld_features;

$listingImages = $listing->fld_cover_image;
$coverImage = count($listingImages) > 0 ? $listingImages->first() : null;

$whatsapp = $listing->fld_whatsapp ?: '523300000000';
$waMessage = rawurlencode("Hola, vi tu lugar '" . $listing->title . "' en el directorio y quiero información para un evento.");
$waLink = "https://wa.me/{$whatsapp}?text={$waMessage}";

$related = $pages->find("template=listing, fld_category={$category}, fld_status=active, id!={$listing->id}, limit=3");

$lat = $listing->fld_latitude;
$lng = $listing->fld_longitude;
$hasLocation = $lat && $lng;

// Contact form
$form = new InputfieldForm();
$form->action = './';
$form->method = 'post';
$form->attr('id', 'contact-form');

$firstname = new InputfieldText();
$firstname->name = 'input_firstname';
$firstname->label = 'Nombre';
$firstname->required = true;
$firstname->attr('class', 'form-control');
$form->add($firstname);

$email = new InputfieldEmail();
$email->name = 'input_email';
$email->label = 'Email';
$email->required = true;
$email->attr('class', 'form-control');
$form->add($email);

$whatsappField = new InputfieldText();
$whatsappField->name = 'input_whatsapp';
$whatsappField->label = 'Whatsapp';
$whatsappField->required = true;
$whatsappField->attr('class', 'form-control');
$form->add($whatsappField);

$eventDate = new InputfieldText();
$eventDate->name = 'input_event_date';
$eventDate->label = 'Fecha del Evento';
$eventDate->required = true;
$eventDate->attr('class', 'form-control');
$eventDate->attr('type', 'date');
$form->add($eventDate);

$eventPeople = new InputfieldInteger();
$eventPeople->name = 'input_event_people';
$eventPeople->label = 'Personas';
$eventPeople->required = true;
$eventPeople->min = 1;
$eventPeople->attr('class', 'form-control');
$form->add($eventPeople);

$eventHourStart = new InputfieldText();
$eventHourStart->name = 'input_event_hour_start';
$eventHourStart->label = 'Comienza';
$eventHourStart->required = true;
$eventHourStart->attr('class', 'form-control');
$eventHourStart->attr('type', 'time');
$form->add($eventHourStart);

$eventHourEnd = new InputfieldText();
$eventHourEnd->name = 'input_event_hour_end';
$eventHourEnd->label = 'Termina';
$eventHourEnd->required = true;
$eventHourEnd->attr('class', 'form-control');
$eventHourEnd->attr('type', 'time');
$form->add($eventHourEnd);

$comments = new InputfieldTextarea();
$comments->name = 'input_event_comments';
$comments->label = 'Comentarios';
$comments->attr('class', 'form-control');
$comments->rows = 4;
$form->add($comments);

$submit = new InputfieldSubmit();
$submit->attr('value', 'Enviar mensaje');
$submit->attr('class', 'btn btn-primary btn-lg w-100');
$form->add($submit);

// Process form
$formSuccess = false;
if ($input->post('input_firstname')) {
    $form->processInput($input->post);
    $errors = $form->getErrors();

    if (empty($errors)) {
        $senderName = $sanitizer->text($input->post('input_firstname'));
        $senderEmail = $sanitizer->email($input->post('input_email'));
        $senderWhatsapp = $sanitizer->text($input->post('input_whatsapp'));
        $eventDate = $sanitizer->text($input->post('input_event_date'));
        $eventPeople = $sanitizer->text($input->post('input_event_people'));
        $eventHourStart = $sanitizer->text($input->post('input_event_hour_start'));
        $eventHourEnd = $sanitizer->text($input->post('input_event_hour_end'));
        $comments = $sanitizer->text($input->post('input_event_comments'));

        $toEmail = $listing->fld_email ?: $config->adminEmail;

        $subject = "Contacto desde Directorio: {$listing->title}";

        $body = "
Nombre: {$senderName}
Email: {$senderEmail}
WhatsApp: {$senderWhatsapp}

Fecha del evento: {$eventDate}
Personas: {$eventPeople}
Hora de inicio: {$eventHourStart}
Hora de término: {$eventHourEnd}

Comentarios:
{$comments}
";

        $m = new WireMail();
        $m->to($toEmail);
        $m->from($senderEmail);
        $m->subject($subject);
        $m->body($body);
        $m->send();

        $formSuccess = true;
    }
}
?>
<!doctype html>
<html lang="es">
<head>
    <?php include __DIR__ . '/partials/head.php'; ?>
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main class="site-main">
        <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <div class="container">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="/listings/">Lugares</a></li>
                    <?php if ($category && $category->id): ?>
                    <li class="breadcrumb-item"><a href="/listings/?category=<?php echo $category->name; ?>"><?php echo $category->title; ?></a></li>
                    <?php endif; ?>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $listing->title; ?></li>
                </ol>
            </div>
        </nav>

        <article class="listing-detail py-4">
            <div class="container">
                <?php if ($listing->fld_featured): ?>
                <span class="badge bg-warning text-dark mb-2">Destacado</span>
                <?php endif; ?>
                <?php if ($listing->fld_verified): ?>
                <span class="badge bg-success mb-2">Verificado</span>
                <?php endif; ?>

                <h1 class="mb-3"><?php echo $listing->title; ?></h1>

                <?php if ($listing->fld_excerpt): ?>
                <p class="lead text-muted"><?php echo $listing->fld_excerpt; ?></p>
                <?php endif; ?>

                <div class="row mt-4">
                    <div class="col-lg-8">
                        <?php if ($coverImage): ?>
                        <div class="listing-detail__image mb-4">
                            <img src="<?php echo $coverImage->size(800, 500)->url; ?>" alt="<?php echo $listing->title; ?>" class="img-fluid rounded">
                        </div>
                        <?php else: ?>
                        <div class="listing-detail__image listing-detail__image--placeholder mb-4">
                            <div class="bg-light rounded p-5 text-center">
                                <span class="text-muted">Sin imagen</span>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php
                        $gallery = $listing->fld_gallery;
                        if ($gallery && $gallery->count()):
                        ?>
                        <div class="listing-detail__gallery mb-4">
                            <h2>Galería</h2>
                            <div class="row g-2">
                                <?php foreach ($gallery as $img): ?>
                                <div class="col-6 col-md-4">
                                    <a href="<?php echo $img->size(1200, 800)->url; ?>" data-gallery="listing-gallery" class="glightbox">
                                        <img src="<?php echo $img->size(400, 300)->url; ?>" alt="<?php echo $listing->title; ?>" class="img-fluid rounded">
                                    </a>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if ($listing->fld_description): ?>
                        <div class="listing-detail__description mb-4">
                            <h2>Descripción</h2>
                            <p><?php echo nl2br($listing->fld_description); ?></p>
                        </div>
                        <?php endif; ?>

                        <?php if ($features && $features->count()): ?>
                        <div class="listing-detail__features mb-4">
                            <h2>Servicios</h2>
                            <div class="row">
                                <?php foreach ($features as $feature): ?>
                                <div class="col-md-4">
                                    <span class="badge bg-secondary me-1 mb-1"><?php echo $feature->title; ?></span>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if ($hasLocation): ?>
                        <div class="listing-detail__map mb-4">
                            <h2>Ubicación</h2>
                            <div id="listing-map" style="height: 300px; border-radius: 8px; overflow: hidden; border: 1px solid #dee2e6;"></div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="col-lg-4">
                        <div class="listing-detail__sidebar">
                            <?php if ($formSuccess): ?>
                            <div class="alert alert-success mb-3">
                                <strong>¡Mensaje enviado!</strong><br>
                                El propietario se pondrá en contacto contigo pronto.
                            </div>
                            <?php else: ?>
                            <div class="card mb-3">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Contactar</h5>
                                </div>
                                <div class="card-body">
                                    <form data-form action="./" method="post" id="contact-form">
                                        <div data-field-holder class="mb-3">
                                            <label for="Inputfield_input_firstname" class="form-label">Nombre *</label>
                                            <input type="text" name="input_firstname" id="Inputfield_input_firstname" class="form-control" required minlength="2">
                                            <span data-field-error class="text-danger small"></span>
                                        </div>
                                        <div data-field-holder class="mb-3">
                                            <label for="Inputfield_input_email" class="form-label">Email *</label>
                                            <input type="email" name="input_email" id="Inputfield_input_email" class="form-control" required>
                                            <span data-field-error class="text-danger small"></span>
                                        </div>
                                        <div data-field-holder class="mb-3">
                                            <label for="Inputfield_input_whatsapp" class="form-label">Whatsapp *</label>
                                            <input type="text" name="input_whatsapp" id="Inputfield_input_whatsapp" class="form-control" required>
                                            <span data-field-error class="text-danger small"></span>
                                        </div>
                                        <div data-field-holder class="mb-3">
                                            <label for="Inputfield_input_event_date" class="form-label">Fecha del Evento *</label>
                                            <input type="date" name="input_event_date" id="Inputfield_input_event_date" class="form-control" required>
                                            <span data-field-error class="text-danger small"></span>
                                        </div>
                                        <div data-field-holder class="mb-3">
                                            <label for="Inputfield_input_event_people" class="form-label">Personas *</label>
                                            <input type="number" name="input_event_people" id="Inputfield_input_event_people" class="form-control" required min="1">
                                            <span data-field-error class="text-danger small"></span>
                                        </div>
                                        <div data-field-holder class="mb-3">
                                            <label for="Inputfield_input_event_hour_start" class="form-label">Comienza *</label>
                                            <input type="time" name="input_event_hour_start" id="Inputfield_input_event_hour_start" class="form-control" required>
                                            <span data-field-error class="text-danger small"></span>
                                        </div>
                                        <div data-field-holder class="mb-3">
                                            <label for="Inputfield_input_event_hour_end" class="form-label">Termina *</label>
                                            <input type="time" name="input_event_hour_end" id="Inputfield_input_event_hour_end" class="form-control" required>
                                            <span data-field-error class="text-danger small"></span>
                                        </div>
                                        <div data-field-holder class="mb-3">
                                            <label for="Inputfield_input_event_comments" class="form-label">Comentarios</label>
                                            <textarea name="input_event_comments" id="Inputfield_input_event_comments" class="form-control" rows="3"></textarea>
                                            <span data-field-error class="text-danger small"></span>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-lg w-100">Enviar mensaje</button>
                                    </form>
                                </div>
                            </div>
                            <?php endif; ?>

                            <div class="d-grid gap-2">
                                <a href="<?php echo $waLink; ?>" target="_blank" class="btn btn-success btn-lg">
                                    Contactar por WhatsApp
                                </a>
                            </div>

                            <?php if ($listing->fld_verified): ?>
                            <div class="alert alert-success mt-3">
                                <strong>✓ Verificado</strong><br>
                                <small>WhatsApp y ubicación confirmados</small>
                            </div>
                            <?php else: ?>
                            <div class="alert alert-secondary mt-3">
                                <strong>Sin verificar</strong><br>
                                <small>Este lugar aún no ha sido verificado</small>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php if ($related->count()): ?>
                <section class="listing-related mt-5">
                    <h2 class="mb-4">Lugares relacionados</h2>
                    <div class="row row-cols-1 row-cols-md-3 g-4">
                        <?php foreach ($related as $rel): ?>
                        <div class="col">
                            <?php
                            $savePage = $page;
                            $page = $rel;
                            include __DIR__ . '/partials/listing-card.php';
                            $page = $savePage;
                            ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </section>
                <?php endif; ?>
            </div>
        </article>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>

    <script src="/site/templates/assets/js/vanillajs-validation.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var form = document.querySelector('[data-form]');
        if (form) {
            var validator = new vanillaValidation(form, {
                onfocusout: true,
                rules: {
                    input_firstname: { required: true, minlength: 2 },
                    input_email: { required: true, email: true },
                    input_whatsapp: { required: true },
                    input_event_date: { required: true },
                    input_event_people: { required: true, digits: true, minlength: 1 },
                    input_event_hour_start: { required: true },
                    input_event_hour_end: { required: true }
                },
                messages: {
                    input_firstname: { required: 'El nombre es requerido', minlength: 'Mínimo 2 caracteres' },
                    input_email: { required: 'El email es requerido', email: 'Email inválido' },
                    input_whatsapp: { required: 'El whatsapp es requerido' },
                    input_event_date: { required: 'La fecha es requerida' },
                    input_event_people: { required: 'El número de personas es requerido', digits: 'Solo números' },
                    input_event_hour_start: { required: 'La hora de inicio es requerida' },
                    input_event_hour_end: { required: 'La hora de término es requerida' }
                },
                errorPlacement: function(error, input) {
                    var holder = input.closest('[data-field-holder]');
                    if (holder) {
                        var errorEl = holder.querySelector('[data-field-error]');
                        if (errorEl) errorEl.textContent = error.message;
                        input.classList.add('is-invalid');
                    }
                },
                beforeErrorPlacement: function(validator) {
                    validator.form.querySelectorAll('[data-field-error]').forEach(function(el) {
                        el.textContent = '';
                    });
                    validator.form.querySelectorAll('.is-invalid').forEach(function(el) {
                        el.classList.remove('is-invalid');
                    });
                }
            });
        }

        <?php if ($hasLocation): ?>
        var lat = <?php echo (float) $lat; ?>;
        var lng = <?php echo (float) $lng; ?>;
        var title = <?php echo json_encode($listing->title); ?>;

        var map = L.map('listing-map').setView([lat, lng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        L.marker([lat, lng]).addTo(map)
            .bindPopup(title)
            .openPopup();
        <?php endif; ?>
    });
    </script>
</body>
</html>