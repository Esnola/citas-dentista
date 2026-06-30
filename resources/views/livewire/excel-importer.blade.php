<div class="grid gap-6">
    <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">
        <h2 class="text-xl font-semibold">Importar Excel</h2>
        <p class="mt-2 text-sm text-slate-300">
            Carga el archivo, elige una plantilla y previsualiza los registros antes de importarlos.
        </p>

        <form class="mt-6 grid gap-4 lg:grid-cols-5 lg:items-end" wire:submit="import">
            <div class="lg:col-span-2">
                <flux:field>
                    <flux:label>Plantilla</flux:label>
                    <x-formularios.select wire:model="template_key">
                        @foreach ($templateOptions as $template)
                            <option value="{{ $template['key'] }}">{{ $template['label'] }}</option>
                        @endforeach
                    </x-formularios.select>
                    <flux:error name="template_key" />
                </flux:field>
            </div>
            <div class="lg:col-span-3">
                <flux:field>
                    <flux:label>Archivo</flux:label>
                    <x-formularios.input wire:model="file" type="file" accept=".xlsx,.xls,.csv" />
                    <flux:error name="file" />
                </flux:field>
            </div>
            <div class="lg:col-span-5 flex flex-wrap gap-3">
          {{--      <x-botones.accion icono="eye" type="button" wire:click="preview">Previsualizar</x-botones.accion>
                <x-botones.accion variant="add" icono="check" type="submit">Importar</x-botones.accion>--}}

              <x-botones.icono-buton
                color="amber"
                icono="ojo"
                type="button"
                wire:click="preview"
                label="Previsualizar"
                texto="Previsualizar"
                />
              <x-botones.icono-buton
                type="submit"
                icono="check"
                label="Importar"
                texto="Importar"
              />
            </div>
        </form>
    </div>

    <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">
        <h2 class="text-xl font-semibold">Filtros de previsualización</h2>
        <p class="mt-2 text-sm text-slate-300">Sirven para localizar registros dentro del archivo antes de importarlos.</p>

        <div class="mt-4 grid gap-4 md:grid-cols-3">
            <flux:field>
                <flux:label>Nombre</flux:label>
                <x-formularios.input wire:model.live.debounce.300ms="filter_nombre" placeholder="Filtrar por nombre" />
            </flux:field>
            <flux:field>
                <flux:label>Apellidos</flux:label>
                <x-formularios.input wire:model.live.debounce.300ms="filter_apellidos" placeholder="Filtrar por apellidos" />
            </flux:field>
            <flux:field>
                <flux:label>Teléfono</flux:label>
                <x-formularios.input wire:model.live.debounce.300ms="filter_telefono" placeholder="Filtrar por teléfono" />
            </flux:field>
        </div>
    </div>

    <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold">Vista previa del archivo</h2>
                <p class="mt-2 text-sm text-slate-300">
                    {{ $previewLoaded ? 'Los datos mostrados ya han sido interpretados y la plantilla se resuelve con los nombres del archivo.' : 'Pulsa previsualizar para cargar las filas del Excel.' }}
                </p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-slate-900/60 px-4 py-3 text-sm text-slate-300">
                {{ count($filteredPreviewRows) }} filas visibles
            </div>
        </div>

        <div class="mt-4 overflow-hidden rounded-2xl border border-white/10">
            <table class="min-w-full divide-y divide-white/10 text-left text-sm">
                <thead class="bg-slate-900/70 text-slate-300">
                    <tr>
                        <th class="px-4 py-3">Nombre</th>
                        <th class="px-4 py-3">Apellidos</th>
                        <th class="px-4 py-3">Teléfono</th>
                        <th class="px-4 py-3">Fecha</th>
                        <th class="px-4 py-3">Hora</th>
                        <th class="px-4 py-3">Mensaje</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10 bg-slate-950/40">
                    @forelse ($filteredPreviewRows as $row)
                        <tr>
                            <td class="px-4 py-3">{{ $row['nombre'] ?? '' }}</td>
                            <td class="px-4 py-3">{{ $row['apellidos'] ?? '' }}</td>
                            <td class="px-4 py-3">{{ $row['telefono'] ?? '' }}</td>
                            <td class="px-4 py-3">{{ $row['scheduled_date'] ?? '' }}</td>
                            <td class="px-4 py-3">{{ $row['scheduled_time'] ?? '' }}</td>
                            <td class="px-4 py-3 text-slate-300">{{ $row['message'] ?? '' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-6 text-slate-400" colspan="6">No hay filas para mostrar todavía.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
