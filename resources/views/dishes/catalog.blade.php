<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú del Restaurante</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">

<div class="container py-5">
    <!-- Encabezado -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold text-dark mb-1"><i class="bi bi-shop text-danger"></i> Nuestro Menú</h1>
            <p class="text-muted mb-0">Selecciona los platos y cantidades que deseas ordenar</p>
        </div>
        <a href="{{ route('dishes.index') }}" class="btn btn-outline-dark mt-3 mt-md-0">
            <i class="bi bi-gear"></i> Administrar Platos (CRUD)
        </a>
    </div>

    <!-- Barra de Búsqueda y Filtros (JS) -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-7">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" id="searchInput" class="form-control" placeholder="Buscar plato por nombre o descripción...">
                    </div>
                </div>
                <div class="col-md-5">
                    <select id="priceFilter" class="form-select">
                        <option value="all">Todos los precios</option>
                        <option value="low">Económicos (Menos de $10.00)</option>
                        <option value="mid">Intermedios ($10.00 - $20.00)</option>
                        <option value="high">Especiales (Más de $20.00)</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid del Catálogo de Platos -->
    <div class="row g-4" id="dishesContainer">
        @forelse($dishes as $dish)
            <div class="col-12 col-md-6 col-lg-4 dish-card" 
                 data-name="{{ strtolower($dish->name) }}" 
                 data-desc="{{ strtolower($dish->description ?? '') }}" 
                 data-price="{{ $dish->price }}">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title fw-bold mb-0">{{ $dish->name }}</h5>
                            <span class="badge bg-success fs-6">${{ number_format($dish->price, 2) }}</span>
                        </div>
                        <p class="card-text text-muted small flex-grow-1">
                            {{ $dish->description ?: 'Deliciosa preparación de la casa.' }}
                        </p>

                        <!-- Selector de Cantidad -->
                        <div class="border-top pt-3 mt-2">
                            <label class="form-label small fw-semibold text-secondary">Cantidad a pedir:</label>
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="input-group input-group-sm" style="width: 120px;">
                                    <button class="btn btn-outline-secondary btn-minus" type="button" data-id="{{ $dish->id }}">-</button>
                                    <input type="number" class="form-control text-center qty-input" 
                                           id="qty-{{ $dish->id }}" 
                                           data-id="{{ $dish->id }}" 
                                           data-name="{{ $dish->name }}"
                                           data-price="{{ $dish->price }}" 
                                           value="0" min="0" max="50" readonly>
                                    <button class="btn btn-outline-secondary btn-plus" type="button" data-id="{{ $dish->id }}">+</button>
                                </div>
                                <span class="fw-bold text-primary subtotal-text" id="sub-{{ $dish->id }}">$0.00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted fs-5">Aún no hay platos registrados en el menú.</p>
            </div>
        @endforelse
    </div>

    <!-- Barra flotante de resumen de selección (Lista para conectar con Pedidos) -->
    <div class="card shadow border-0 mt-5 bg-dark text-white">
        <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-center">
            <div>
                <h5 class="mb-1">Resumen de selección</h5>
                <small class="text-light" id="selectedSummary">Ningún plato seleccionado todavía.</small>
            </div>
            <div class="d-flex align-items-center gap-3 mt-3 mt-md-0">
                <span class="fs-4 fw-bold text-warning" id="grandTotal">$0.00</span>
                <button type="button" class="btn btn-warning fw-bold" id="btnConfirmSelection" disabled>
                    <i class="bi bi-cart-check"></i> Confirmar Selección
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const priceFilter = document.getElementById('priceFilter');
    const cards = document.querySelectorAll('.dish-card');

    // 1. Búsqueda y Filtros en tiempo real
    function filterDishes() {
        const query = searchInput.value.toLowerCase().trim();
        const priceRange = priceFilter.value;

        cards.forEach(card => {
            const name = card.dataset.name;
            const desc = card.dataset.desc;
            const price = parseFloat(card.dataset.price);

            const matchesText = name.includes(query) || desc.includes(query);
            let matchesPrice = true;

            if (priceRange === 'low') matchesPrice = price < 10;
            else if (priceRange === 'mid') matchesPrice = price >= 10 && price <= 20;
            else if (priceRange === 'high') matchesPrice = price > 20;

            card.style.display = (matchesText && matchesPrice) ? 'block' : 'none';
        });
    }

    searchInput.addEventListener('input', filterDishes);
    priceFilter.addEventListener('change', filterDishes);

    // 2. Control de cantidades y cálculo de totales
    function updateTotals() {
        let total = 0;
        let itemsCount = 0;
        let selectedItems = [];

        document.querySelectorAll('.qty-input').forEach(input => {
            const id = input.dataset.id;
            const qty = parseInt(input.value);
            const price = parseFloat(input.dataset.price);
            const sub = qty * price;

            document.getElementById(`sub-${id}`).textContent = `$${sub.toFixed(2)}`;

            if (qty > 0) {
                total += sub;
                itemsCount += qty;
                selectedItems.push({ dish_id: id, name: input.dataset.name, quantity: qty, price: price });
            }
        });

        document.getElementById('grandTotal').textContent = `$${total.toFixed(2)}`;
        const summaryEl = document.getElementById('selectedSummary');
        const btnConfirm = document.getElementById('btnConfirmSelection');

        if (itemsCount > 0) {
            summaryEl.textContent = `${itemsCount} plato(s) seleccionado(s) listo(s) para ordenar.`;
            btnConfirm.disabled = false;
            // Guardamos en sessionStorage por si el compañero de pedidos lo quiere leer directo
            sessionStorage.setItem('selected_dishes', JSON.stringify(selectedItems));
        } else {
            summaryEl.textContent = 'Ningún plato seleccionado todavía.';
            btnConfirm.disabled = true;
            sessionStorage.removeItem('selected_dishes');
        }
    }

    document.querySelectorAll('.btn-plus').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = document.getElementById(`qty-${btn.dataset.id}`);
            if (parseInt(input.value) < 50) {
                input.value = parseInt(input.value) + 1;
                updateTotals();
            }
        });
    });

    document.querySelectorAll('.btn-minus').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = document.getElementById(`qty-${btn.dataset.id}`);
            if (parseInt(input.value) > 0) {
                input.value = parseInt(input.value) - 1;
                updateTotals();
            }
        });
    });

    document.getElementById('btnConfirmSelection').addEventListener('click', () => {
        alert('Selección guardada en sesión. Lista para enviarse al módulo de Pedidos.');
    });
});
</script>
</body>
</html>