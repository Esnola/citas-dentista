<div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">
  <div class="flex flex-wrap items-center justify-between ">
    <div class="flex items-center gap-6">
      <h1 class="text-xl font-semibold d uppercase tracking-[0.08em] text-emerald-300/80">Listado de
        clientes</h1>
      <div class="rounded-2xl border border-white/10 bg-slate-900/60 px-4 py-3 text-sm text-slate-300">
        {{ $clients->total() }} cliente{{ $clients->total() === 1 ? '' : 's' }}
      </div>
    </div>
    <x-botones.accion variant="add" icono="plus" href="{{ route('clients.create') }}">Nuevo cliente
    </x-botones.accion>
  </div>

  <div class="mt-4 grid gap-4 sm:grid-cols-3">
    <flux:field>
      <flux:label>Nombre</flux:label>
      <x-formularios.input wire:model.live.debounce.300ms="filter_nombre" placeholder="Buscar por nombre"/>
    </flux:field>

    <flux:field>
      <flux:label>Apellidos</flux:label>
      <x-formularios.input wire:model.live.debounce.300ms="filter_apellidos" placeholder="Buscar por apellidos"/>
    </flux:field>

    <flux:field>
      <flux:label>Teléfono</flux:label>
      <x-formularios.input wire:model.live.debounce.300ms="filter_telefono" placeholder="Buscar por teléfono"/>
    </flux:field>
  </div>

  <div class="mt-4 overflow-hidden rounded-2xl border border-white/10">
    <table class="min-w-full divide-y divide-white/10 text-left text-sm">
      <thead class="bg-slate-900/70 text-slate-300">
      <tr>
        <th class="px-4 py-3">
          <button type="button"
                  class="inline-flex cursor-pointer items-center gap-1 font-semibold text-slate-200 hover:text-white"
                  wire:click="sortByName">
            Nombre completo
            <span class="text-xs text-slate-400">{{ $sort_direction === 'asc' ? '↑' : '↓' }}</span>
          </button>
        </th>
        <th class="px-4 py-3">Teléfono</th>
        <th class="px-4 py-3 text-center">Acciones</th>
      </tr>
      </thead>
      <tbody class="divide-y divide-white/10 bg-slate-950/40">
      @forelse ($clients as $client)
        <tr wire:key="client-{{ $client->id }}">
          <td class="px-4 py-3">{{ $client->nombre }} {{ $client->apellidos }}</td>
          <td class="px-4 py-3">{{ $client->telefono }}</td>
          <td class="px-4 py-3 text-right">
            <div class="flex justify-center  gap-4">
              <x-botones.accion
                      variant="warning"
                      size="sm"
                      href="{{ route('appointments.index', ['client' => $client->id]) }}"
              >
                <x-iconos.ojo clase="size-5"/>
              </x-botones.accion>
              <x-botones.accion
                      variant="add"
                      size="sm"
                      icono="plus"
                      href="{{ route('appointments.create', ['client' => $client->id]) }}"
              >
              </x-botones.accion>

              <x-botones.accion
                      variant="edit"
                      size="sm"
                      icono="edit"
                      href="{{ route('clients.edit', $client) }}"
                      tooltip="Editar cliente"
              />
              <x-botones.accion
                      variant="delete"
                      size="sm"
                      icono="delete"
                      type="button"
                      onclick="if (! confirm('¿Eliminar este cliente?')) return; $wire.delete({{ $client->id }})"
                      tooltip="Eliminar cliente"
              />
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td class="px-4 py-6 text-slate-400" colspan="4">
            @if ($showAllClients || $hasClientSearch)
              No hay clientes para esa búsqueda.
            @else
              Las coincidencias aparecerán aquí cuando escribas al menos un carácter.
            @endif
          </td>
        </tr>
      @endforelse
      </tbody>
    </table>
  </div>

  @if ($showAllClients || $hasClientSearch)
    <div class="mt-4">
      {{ $clients->links('vendor.pagination.tailwind') }}
    </div>
  @endif
</div>
