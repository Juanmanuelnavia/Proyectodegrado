@extends('layouts.app')
@section('title', 'Nueva Factura')

@section('content')

<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm p-8">
        <form action="{{ route('invoices.store') }}" method="POST" class="space-y-6" id="invoiceForm">
            @csrf

            {{-- Datos básicos --}}
            <div>
                <h3 class="text-base font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">
                    <i class="fa-solid fa-user text-purple-600 mr-2"></i>Datos del cliente
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Propietario *</label>
                        <select name="owner_id" id="owner_id"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">Seleccionar propietario</option>
                            @foreach($owners as $owner)
                            <option value="{{ $owner->id }}" {{ old('owner_id') == $owner->id ? 'selected' : '' }}>
                                {{ $owner->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('owner_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cita relacionada</label>
                        <select name="appointment_id"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">Sin cita asociada</option>
                            @foreach($appointments as $apt)
<option value="{{ $apt->id }}" data-owner="{{ $apt->owner_id }}">
    {{ $apt->pet->name }} — {{ \Carbon\Carbon::parse($apt->scheduled_at)->format('d/m/Y') }}
</option>
@endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- Items --}}
            <div>
                <h3 class="text-base font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">
                    <i class="fa-solid fa-list text-purple-600 mr-2"></i>Servicios y productos
                </h3>

                <div id="items-container" class="space-y-3 mb-4">
                    <div class="item-row grid grid-cols-12 gap-2 items-end">
                        <div class="col-span-5">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Descripción</label>
                            <input type="text" name="items[0][description]"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"
                                placeholder="Ej: Consulta general">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Tipo</label>
                            <select name="items[0][type]"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                                <option value="servicio">Servicio</option>
                                <option value="medicamento">Medicamento</option>
                                <option value="vacuna">Vacuna</option>
                            </select>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Cantidad</label>
                            <input type="number" name="items[0][quantity]" value="1" min="1"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 item-qty">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Precio unit.</label>
                            <input type="number" name="items[0][unit_price]" value="0" min="0"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 item-price">
                        </div>
                        <div class="col-span-1 flex justify-center">
                            <button type="button" onclick="removeItem(this)"
                                class="w-8 h-8 bg-red-50 text-red-500 rounded-lg flex items-center justify-center hover:bg-red-100 transition mt-1">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <button type="button" onclick="addItem()"
                    class="w-full border-2 border-dashed border-gray-200 hover:border-purple-400 text-gray-400 hover:text-purple-600 py-2.5 rounded-xl text-sm transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-plus"></i> Agregar ítem
                </button>
            </div>

            {{-- Total --}}
            <div class="bg-gray-50 rounded-xl p-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 text-sm">Subtotal:</span>
                    <span class="font-semibold text-gray-800" id="subtotal">$0</span>
                </div>
                <div class="flex justify-between items-center mt-2 pt-2 border-t border-gray-200">
                    <span class="text-gray-800 font-bold">Total:</span>
                    <span class="text-xl font-bold text-purple-600" id="total">$0</span>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notas</label>
                <textarea name="notes" rows="2"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"
                    placeholder="Observaciones adicionales...">{{ old('notes') }}</textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2.5 rounded-xl text-sm font-medium transition">
                    <i class="fa-solid fa-floppy-disk mr-1"></i> Crear factura
                </button>
                <a href="{{ route('invoices.index') }}"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-xl text-sm font-medium transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
let itemIndex = 1;

function addItem() {
    const container = document.getElementById('items-container');
    const div = document.createElement('div');
    div.className = 'item-row grid grid-cols-12 gap-2 items-end';
    div.innerHTML = `
        <div class="col-span-5">
            <input type="text" name="items[${itemIndex}][description]"
                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"
                placeholder="Descripción">
        </div>
        <div class="col-span-2">
            <select name="items[${itemIndex}][type]"
                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                <option value="servicio">Servicio</option>
                <option value="medicamento">Medicamento</option>
                <option value="vacuna">Vacuna</option>
            </select>
        </div>
        <div class="col-span-2">
            <input type="number" name="items[${itemIndex}][quantity]" value="1" min="1"
                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 item-qty">
        </div>
        <div class="col-span-2">
            <input type="number" name="items[${itemIndex}][unit_price]" value="0" min="0"
                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 item-price">
        </div>
        <div class="col-span-1 flex justify-center">
            <button type="button" onclick="removeItem(this)"
                class="w-8 h-8 bg-red-50 text-red-500 rounded-lg flex items-center justify-center hover:bg-red-100 transition">
                <i class="fa-solid fa-trash text-xs"></i>
            </button>
        </div>
    `;
    container.appendChild(div);
    itemIndex++;
    updateTotal();
    div.querySelectorAll('.item-qty, .item-price').forEach(el => el.addEventListener('input', updateTotal));
}

function removeItem(btn) {
    const rows = document.querySelectorAll('.item-row');
    if (rows.length > 1) {
        btn.closest('.item-row').remove();
        updateTotal();
    }
}

function updateTotal() {
    let subtotal = 0;
    document.querySelectorAll('.item-row').forEach(row => {
        const qty = parseFloat(row.querySelector('.item-qty')?.value || 0);
        const price = parseFloat(row.querySelector('.item-price')?.value || 0);
        subtotal += qty * price;
    });
    document.getElementById('subtotal').textContent = '$' + subtotal.toLocaleString('es-CO');
    document.getElementById('total').textContent = '$' + subtotal.toLocaleString('es-CO');
}

document.querySelectorAll('.item-qty, .item-price').forEach(el => el.addEventListener('input', updateTotal));
// Filtrar citas por propietario
document.getElementById('owner_id').addEventListener('change', function() {
    const ownerId = this.value;
    const aptSelect = document.querySelector('select[name="appointment_id"]');
    const options = aptSelect.querySelectorAll('option');

    options.forEach(option => {
        if (option.value === '') return; // mantener opción vacía
        const dataOwner = option.getAttribute('data-owner');
        option.style.display = dataOwner === ownerId ? '' : 'none';
    });

    // resetear selección
    aptSelect.value = '';
});
</script>

@endsection