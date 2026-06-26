<div class="grid gap-6 xl:grid-cols-[360px_minmax(0,1fr)]">
    <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-semibold">{{ $editingTemplateId ? 'Editar plantilla' : 'Nueva plantilla' }}</h2>
            </div>
            <x-botones.accion
                variant="add"
                type="button"
                wire:click="create">
                <x-iconos.nuevo/>
                Nuevo
            </x-botones.accion>
        </div>

        <form class="mt-6 grid gap-4" wire:submit="save">
            <flux:field>
                <flux:label>Clave</flux:label>
                @if ($editingTemplateId)
                    <div
                        class="rounded-2xl border border-white/10 bg-slate-900/60 px-4 py-3 text-sm text-slate-200">{{ $key }}</div>
                @else
                    <x-formularios.input wire:model="key" placeholder="recordatorio-clinica"/>
                    <p class="mt-2 text-xs text-slate-400">Si lo dejas vacío, se generará automáticamente a partir del
                        nombre.</p>
                @endif
                <flux:error name="key"/>
            </flux:field>

            <flux:field>
                <flux:label>Nombre</flux:label>
                <x-formularios.input wire:model="label" placeholder="Recordatorio clínica"/>
                <flux:error name="label"/>
            </flux:field>

            <flux:field>
                <flux:label>Mensaje</flux:label>
                <flux:textarea wire:model="message" rows="7" placeholder="Hola [NOMBRE]..."/>
                <flux:error name="message"/>
            </flux:field>

            <div class="grid gap-4 sm:grid-cols-2">
                <flux:field>
                    <flux:label>Orden</flux:label>
                    <x-formularios.input type="number" wire:model="sort_order" min="0"/>
                    <flux:error name="sort_order"/>
                </flux:field>

                <div class="space-y-4 pt-6">
                    <x-formularios.toggle wire:model="is_default" texto="Predeterminada"/>
                    <x-formularios.toggle wire:model="is_active" texto="Activa"/>
                </div>
            </div>

            <div class="flex flex-wrap gap-3">
                <x-botones.accion
                    variant="add"
                    type="button"
                    wire:click="create">
                    @if ($editingTemplateId)
                        <x-iconos.guardar />
                        Guardar Cambios
                    @else
                        <x-iconos.nuevo/>
                        Crear plantilla
                    @endif

                </x-botones.accion>
            </div>
        </form>
    </div>

    <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">
        <h2 class="text-xl font-semibold">Plantillas guardadas</h2>
        <div class="mt-4 overflow-hidden rounded-2xl border border-white/10">
            <table class="min-w-full divide-y divide-white/10 text-left text-sm">
                <thead class="bg-slate-900/70 text-slate-300">
                <tr>
                    <th class="px-4 py-3">Clave</th>
                    <th class="px-4 py-3">Nombre</th>
                    <th class="px-4 py-3">Estado</th>
                    <th class="px-4 py-3">Orden</th>
                    <th class="px-4 py-3"></th>
                </tr>
                </thead>
                <tbody class="divide-y divide-white/10 bg-slate-950/40">
                @foreach ($templates as $template)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="font-medium">{{ $template->key }}</div>
                            <div
                                class="mt-1 text-xs text-slate-400">{{ \Illuminate\Support\Str::limit($template->message, 90) }}</div>
                        </td>
                        <td class="px-4 py-3">{{ $template->label }}</td>
                        <td class="px-4 py-3">
                            <div class="flex flex-col gap-1 text-xs">
                                <span
                                    class="inline-flex w-fit rounded-full bg-white/10 px-2 py-1 text-slate-200">{{ $template->is_active ? 'Activa' : 'Inactiva' }}</span>
                                @if ($template->is_default)
                                    <span
                                        class="inline-flex w-fit rounded-full bg-emerald-400/20 px-2 py-1 text-emerald-200">Predeterminada</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3">{{ $template->sort_order }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex justify-end gap-2">
                                <x-botones.accion variant="edit" size="sm" icono="edit" type="button"
                                                  wire:click="edit({{ $template->id }})">Editar
                                </x-botones.accion>
                                <x-botones.accion variant="warning" size="sm" type="button"
                                                  wire:click="setDefault({{ $template->id }})">Default
                                </x-botones.accion>
                                <x-botones.accion variant="delete" size="sm" icono="delete" type="button"
                                                  wire:click="delete({{ $template->id }})"
                                                  onclick="return confirm('¿Eliminar esta plantilla?')">Eliminar
                                </x-botones.accion>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
