<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Platos - CRUD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-2 mb-md-0">Gestión del Menú (Platos)</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('dishes.catalog') }}" class="btn btn-outline-secondary">
                <i class="bi bi-eye"></i> Ver Catálogo Cliente
            </a>
            <a href="{{ route('dishes.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nuevo Plato
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre del Plato</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dishes as $dish)
                        <tr>
                            <td>#{{ $dish->id }}</td>
                            <td class="fw-semibold">{{ $dish->name }}</td>
                            <td class="text-muted">{{ $dish->description ?? 'Sin descripción' }}</td>
                            <td><span class="badge bg-success fs-6">${{ number_format($dish->price, 2) }}</span></td>
                            <td class="text-end">
                                <a href="{{ route('dishes.edit', $dish) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil-square"></i> Editar
                                </a>
                                <form action="{{ route('dishes.destroy', $dish) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar este plato?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No hay platos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>