class SearchWizard {
    constructor(container) {
        this.container = container;
        this.step = 1;
        this.totalSteps = 4;
        this.formData = {
            eventType: '',
            people: '',
            location: '',
            hasPool: false,
            hasParking: false,
            hasKitchen: false,
            hasGarden: false
        };
        this.listings = [];
        this.showResults = false;
        this.loading = false;

        this.eventTypes = [
            { value: 'birthday', label: 'Cumpleaños' },
            { value: 'wedding', label: 'Boda' },
            { value: 'corporate', label: 'Evento corporativo' },
            { value: 'quinceanera', label: 'Quinceañera' },
            { value: 'baptism', label: 'Bautizo' },
            { value: 'graduation', label: 'Graduación' },
            { value: 'other', label: 'Otro' }
        ];

        this.locationOptions = [
            { value: 'guadalajara', label: 'Guadalajara' },
            { value: 'zapopan', label: 'Zapopan' },
            { value: 'tlaquepaque', label: 'Tlaquepaque' },
            { value: 'tonala', label: 'Tonalá' },
            { value: 'all', label: 'Todas las zonas' }
        ];

        this.stepTitles = ['Tipo de evento', '¿Cuántas personas?', '¿Dónde lo buscas?', 'Servicios extra'];
        this.render();
    }

    render() {
        if (this.showResults) {
            this.renderResults();
        } else {
            this.renderWizard();
        }
    }

    renderWizard() {
        const progressPercent = (this.step / this.totalSteps * 100);

        let stepContent = '';
        if (this.step === 1) {
            stepContent = this.renderStep1();
        } else if (this.step === 2) {
            stepContent = this.renderStep2();
        } else if (this.step === 3) {
            stepContent = this.renderStep3();
        } else if (this.step === 4) {
            stepContent = this.renderStep4();
        }

        this.container.innerHTML = `
            <div class="search-wizard">
                <div class="search-wizard__steps">
                    <div class="search-wizard__progress mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="step-title">${this.stepTitles[this.step - 1]}</span>
                            <span class="text-muted">Paso ${this.step} de ${this.totalSteps}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar" style="width: ${progressPercent}%"></div>
                        </div>
                    </div>
                    <div class="search-wizard__step-content bg-white p-4 rounded shadow-sm">
                        ${stepContent}
                    </div>
                    <div class="search-wizard__actions mt-4 d-flex justify-content-between">
                        ${this.step > 1 ? '<button type="button" class="btn btn-outline-secondary" id="sw-prev">← Atrás</button>' : '<div></div>'}
                        ${this.step < this.totalSteps ? '<button type="button" class="btn btn-primary" id="sw-next">Siguiente →</button>' : '<button type="button" class="btn btn-success" id="sw-search">🔍 Buscar lugares</button>'}
                    </div>
                </div>
            </div>
        `;

        this.bindEvents();
    }

    renderStep1() {
        let options = this.eventTypes.map(type => `
            <div class="col-6 col-md-4">
                <label class="search-wizard__option-card card h-100">
                    <div class="card-body text-center p-2">
                        <input type="radio" name="eventType" value="${type.value}" class="form-check-input mb-2" ${this.formData.eventType === type.value ? 'checked' : ''}>
                        <div class="form-check-label">${type.label}</div>
                    </div>
                </label>
            </div>
        `).join('');

        return `
            <h3 class="h5 mb-3">¿Qué tipo de evento planeas?</h3>
            <div class="row g-2">
                ${options}
            </div>
        `;
    }

    renderStep2() {
        const buttons = [50, 100, 150, 200, 300].map(n =>
            `<button type="button" class="btn btn-outline-primary btn-sm sw-people-btn" data-people="${n}">${n}</button>`
        ).join('');

        return `
            <h3 class="h5 mb-3">¿Cuántas personas?</h3>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <label class="form-label">Número de personas</label>
                    <input type="number" id="sw-people" class="form-control form-control-lg text-center" placeholder="Ej: 100" min="1" value="${this.formData.people}">
                    <div class="d-flex flex-wrap gap-2 mt-3 justify-content-center">
                        ${buttons}
                    </div>
                </div>
            </div>
        `;
    }

    renderStep3() {
        let options = this.locationOptions.map(loc => `
            <div class="col-6 col-md-4">
                <label class="search-wizard__option-card card h-100">
                    <div class="card-body text-center p-2">
                        <input type="radio" name="location" value="${loc.value}" class="form-check-input mb-2" ${this.formData.location === loc.value ? 'checked' : ''}>
                        <div class="form-check-label">${loc.label}</div>
                    </div>
                </label>
            </div>
        `).join('');

        return `
            <h3 class="h5 mb-3">¿En qué zona de Guadalajara?</h3>
            <div class="row g-2">
                ${options}
            </div>
        `;
    }

    renderStep4() {
        return `
            <h3 class="h5 mb-3">¿Qué servicios necesitas?</h3>
            <p class="text-muted mb-3">Opcional - Selecciona los servicios que necesitas</p>
            <div class="row g-2">
                <div class="col-6 col-md-3">
                    <label class="search-wizard__feature-card card h-100">
                        <div class="card-body text-center">
                            <input type="checkbox" id="sw-pool" class="form-check-input mb-2" ${this.formData.hasPool ? 'checked' : ''}>
                            <div>🏊 Alberca</div>
                        </div>
                    </label>
                </div>
                <div class="col-6 col-md-3">
                    <label class="search-wizard__feature-card card h-100">
                        <div class="card-body text-center">
                            <input type="checkbox" id="sw-parking" class="form-check-input mb-2" ${this.formData.hasParking ? 'checked' : ''}>
                            <div>🅿️ Estacionamiento</div>
                        </div>
                    </label>
                </div>
                <div class="col-6 col-md-3">
                    <label class="search-wizard__feature-card card h-100">
                        <div class="card-body text-center">
                            <input type="checkbox" id="sw-kitchen" class="form-check-input mb-2" ${this.formData.hasKitchen ? 'checked' : ''}>
                            <div>🍳 Cocina</div>
                        </div>
                    </label>
                </div>
                <div class="col-6 col-md-3">
                    <label class="search-wizard__feature-card card h-100">
                        <div class="card-body text-center">
                            <input type="checkbox" id="sw-garden" class="form-check-input mb-2" ${this.formData.hasGarden ? 'checked' : ''}>
                            <div>🌳 Jardín</div>
                        </div>
                    </label>
                </div>
            </div>
        `;
    }

    renderResults() {
        let content = '';

        if (this.loading) {
            content = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 text-muted">Buscando lugares...</p>
                </div>
            `;
        } else if (this.listings.length === 0) {
            content = `
                <div class="alert alert-info">
                    No encontramos lugares con esos filtros. Intenta con otros criterios.
                </div>
            `;
        } else {
            const cards = this.listings.map(l => `
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <h4 class="h6">${l.title}</h4>
                            <p class="text-muted small mb-2">${l.excerpt || ''}</p>
                            <div class="mb-2">
                                <strong class="text-primary">$${(l.price_min || 0).toLocaleString()}</strong>
                                <span class="text-muted"> desde</span>
                            </div>
                            <div class="mb-2 small">
                                ${l.category ? `<span class="badge bg-secondary me-1">${l.category}</span>` : ''}
                            </div>
                            <div class="small text-muted mb-2">
                                📍 ${l.address || l.city || 'Ubicación no disponible'}
                            </div>
                        </div>
                        <div class="card-footer bg-white border-top-0">
                            <a href="${l.url}" class="btn btn-primary btn-sm w-100">Ver detalles</a>
                            ${l.whatsapp_url ? `<a href="${l.whatsapp_url}" class="btn btn-success btn-sm w-100 mt-2" target="_blank">💬 Contactar</a>` : ''}
                        </div>
                    </div>
                </div>
            `).join('');

            content = `<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">${cards}</div>`;
        }

        this.container.innerHTML = `
            <div class="search-wizard">
                <div class="search-wizard__results">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="h5 mb-0">${this.listings.length} lugares encontrados</h3>
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="sw-reset">← Nueva búsqueda</button>
                    </div>
                    ${content}
                </div>
            </div>
        `;

        this.bindResultEvents();
    }

    bindEvents() {
        const prevBtn = document.getElementById('sw-prev');
        const nextBtn = document.getElementById('sw-next');
        const searchBtn = document.getElementById('sw-search');

        if (prevBtn) prevBtn.addEventListener('click', () => this.prevStep());
        if (nextBtn) nextBtn.addEventListener('click', () => this.nextStep());
        if (searchBtn) searchBtn.addEventListener('click', () => this.search());

        const eventTypeRadios = document.querySelectorAll('input[name="eventType"]');
        eventTypeRadios.forEach(radio => {
            radio.addEventListener('change', (e) => {
                this.formData.eventType = e.target.value;
            });
        });

        const locationRadios = document.querySelectorAll('input[name="location"]');
        locationRadios.forEach(radio => {
            radio.addEventListener('change', (e) => {
                this.formData.location = e.target.value;
            });
        });

        const peopleInput = document.getElementById('sw-people');
        if (peopleInput) {
            peopleInput.addEventListener('input', (e) => {
                this.formData.people = e.target.value;
            });
        }

        const peopleBtns = document.querySelectorAll('.sw-people-btn');
        peopleBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const val = e.target.dataset.people;
                this.formData.people = val;
                const peopleInput = document.getElementById('sw-people');
                if (peopleInput) peopleInput.value = val;
            });
        });

        const poolCheck = document.getElementById('sw-pool');
        const parkingCheck = document.getElementById('sw-parking');
        const kitchenCheck = document.getElementById('sw-kitchen');
        const gardenCheck = document.getElementById('sw-garden');

        if (poolCheck) poolCheck.addEventListener('change', (e) => this.formData.hasPool = e.target.checked);
        if (parkingCheck) parkingCheck.addEventListener('change', (e) => this.formData.hasParking = e.target.checked);
        if (kitchenCheck) kitchenCheck.addEventListener('change', (e) => this.formData.hasKitchen = e.target.checked);
        if (gardenCheck) gardenCheck.addEventListener('change', (e) => this.formData.hasGarden = e.target.checked);
    }

    bindResultEvents() {
        const resetBtn = document.getElementById('sw-reset');
        if (resetBtn) resetBtn.addEventListener('click', () => this.resetWizard());
    }

    nextStep() {
        if (this.step < this.totalSteps) this.step++;
        this.render();
    }

    prevStep() {
        if (this.step > 1) this.step--;
        this.render();
    }

    async search() {
        this.loading = true;
        this.showResults = true;
        this.render();

        try {
            const params = new URLSearchParams();
            if (this.formData.people) params.set('capacity', this.formData.people);
            if (this.formData.location && this.formData.location !== 'all') params.set('location', this.formData.location);
            if (this.formData.eventType) params.set('category', this.formData.eventType);

            const resp = await fetch(`/api/listings?${params.toString()}`);
            const result = await resp.json();
            this.listings = result.listings || [];
        } catch (e) {
            console.error('Error searching:', e);
            this.listings = [];
        }

        this.loading = false;
        this.render();
    }

    resetWizard() {
        this.step = 1;
        this.formData = {
            eventType: '',
            people: '',
            location: '',
            hasPool: false,
            hasParking: false,
            hasKitchen: false,
            hasGarden: false
        };
        this.listings = [];
        this.showResults = false;
        this.render();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('sw-container');
    if (container) {
        new SearchWizard(container);
    }
});