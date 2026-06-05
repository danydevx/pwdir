<footer class="bg-light py-4 mt-5">
    <div class="container text-center">
        <p class="text-muted mb-0">&copy; <?php echo date('Y'); ?> Directorio Inteligente</p>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/glightbox@3.2.0/dist/js/glightbox.min.js"></script>
<script src="<?php echo $config->urls->templates; ?>assets/js/main.js"></script>
<script>
    const lightbox = GLightbox({ selector: '.glightbox' });
</script>