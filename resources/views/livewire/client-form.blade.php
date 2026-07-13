<div class="grid gap-6">
    <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold">{{ $selectedClient ? 'Editar cliente' : 'Crear cliente' }}</h2>
                <p class="mt-2 text-sm text-slate-300">Gestiona los datos básicos de la ficha del cliente.</p>
            </div>


          <x-botones.icono-buton
                  color="indigo"
                  icon="salir"
                  especial="size-5"
                  label="Volver al listado"
                  texto="Volver al listado"
                  onclick="history.back()" />
        </div>

        <form class="mt-6 grid grid-cols-3 gap-4" wire:submit="save">
            <flux:field>
                <flux:label>Nombre</flux:label>
                <x-formularios.input wire:model="nombre" />
                <flux:error name="nombre" />
            </flux:field>

            <flux:field>
                <flux:label>Apellidos</flux:label>
                <x-formularios.input wire:model="apellidos" />
                <flux:error name="apellidos" />
            </flux:field>

            <flux:field>
                <flux:label>Teléfono</flux:label>
                <x-formularios.input wire:model="telefono" placeholder="600123123" />
                <flux:error name="telefono" />
            </flux:field>

            <div class="flex flex-wrap gap-2 mt-4">
              <x-botones.icono-buton
                      icon="{{ $selectedClient ? 'disquete' : 'check' }}"
                      type="submit"
                      especial="size-5"
                      label="{{ $selectedClient ? 'Guardar cambios' : 'Crear cliente' }}"
                      texto="{{ $selectedClient ? 'Guardar cambios' : 'Crear cliente' }}"
              />

              <x-botones.icono-buton
                      color="indigo"
                      icon="salir"
                      especial="size-5"
                      label="Volver"
                      texto="Volver"
                      onclick="history.back()" />
            </div>
        </form>
    </div>

    @if ($selectedClient)
        <section class="overflow-hidden rounded-3xl border border-white/10 bg-white/5 backdrop-blur" aria-labelledby="appointment-history-heading">
            <header class="flex flex-wrap items-start justify-between gap-4 border-b border-white/10 px-6 py-5">
                <div>
                    <h2 id="appointment-history-heading" class="text-xl font-semibold text-white">Historial de citas</h2>
                    <p class="mt-1 text-sm text-slate-300">Visitas anteriores de este cliente, de la más reciente a la más antigua.</p>
                </div>
                <span class="inline-flex items-center rounded-full bg-emerald-400/10 px-3 py-1 text-sm font-semibold text-emerald-300 ring-1 ring-inset ring-emerald-400/20">
                    {{ $appointmentHistory->count() }} {{ $appointmentHistory->count() === 1 ? 'cita' : 'citas' }}
                </span>
            </header>

            <ol class="divide-y divide-white/10">
                @forelse ($appointmentHistory as $appointment)
                    @php
                        $isInactive = ! $appointment->activo || ! $appointment->cita_activa;
                        $clinicalStatus = match (true) {
                            $isInactive => ['Cancelada', 'bg-red-500/10 text-red-300 ring-red-400/20'],
                            $appointment->pendiente_reprogramacion => ['Reprogramación pendiente', 'bg-amber-500/10 text-amber-300 ring-amber-400/20'],
                            $appointment->confirmada => ['Cita Confirmada', 'bg-emerald-400/10 text-emerald-300 ring-emerald-400/20'],
                            default => ['Finalizada', 'bg-slate-400/10 text-slate-300 ring-white/10'],
                        };
                    @endphp
                    <li wire:key="appointment-history-{{ $appointment->id }}" class="grid gap-3 px-6 py-4 sm:grid-cols-[minmax(0,1fr)_auto] sm:items-center">
                        <div class="min-w-0">
                            <p class="font-medium text-slate-100">{{ ucfirst($appointment->fecha->translatedFormat('l, d \d\e F \d\e Y')) }}</p>
                            <p class="mt-1 text-sm text-slate-400">A las {{ Str::substr($appointment->hora, 0, 5) }}</p>
                        </div>
                        <div class="flex flex-wrap gap-2 sm:justify-end">
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset {{ $clinicalStatus[1] }}">{{ $clinicalStatus[0] }}</span>
                            @if ($appointment->entregado)
                                <span class="inline-flex items-center rounded-full bg-emerald-400/10 px-2.5 py-1 text-xs font-medium text-emerald-300 ring-1 ring-inset ring-emerald-400/20">WhatsApp entregado</span>
                            @elseif ($appointment->enviado)
                                <span class="inline-flex items-center rounded-full bg-sky-400/10 px-2.5 py-1 text-xs font-medium text-sky-300 ring-1 ring-inset ring-sky-400/20">WhatsApp enviado</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-slate-400/10 px-2.5 py-1 text-xs font-medium text-slate-400 ring-1 ring-inset ring-white/10">WhatsApp no enviado</span>
                            @endif
                        </div>
                    </li>
                @empty
                    <li class="px-6 py-10 text-center text-sm text-slate-300">Este cliente no tiene citas anteriores.</li>
                @endforelse
            </ol>
        </section>
    @endif
</div>
