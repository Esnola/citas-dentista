<div class="grid gap-6 xl:grid-cols-[360px_minmax(0,1fr)]">
    @if (session('status'))
        <div class="xl:col-span-2 rounded-2xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
            {{ session('status') }}
        </div>
    @endif

    <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-semibold">{{ $editingTemplateId ? 'Editar plantilla' : 'Nueva plantilla' }}</h2>
                <p class="mt-1 text-sm text-slate-300">Las plantillas disponibles alimentan el editor y el importador.</p>
            </div>
            <flux:button class="action-add" type="button" wire:click="create">Nuevo</flux:button>
        </div>

        <form class="mt-6 grid gap-4" wire:submit="save">
            <flux:field>
                <flux:label>Clave</flux:label>
                @if ($editingTemplateId)
                    <div class="rounded-2xl border border-white/10 bg-slate-900/60 px-4 py-3 text-sm text-slate-200">{{ $key }}</div>
                @else
                    <flux:input wire:model="key" placeholder="recordatorio-clinica" />
                    <p class="mt-2 text-xs text-slate-400">Si lo dejas vacío, se generará automáticamente a partir del nombre.</p>
                @endif
                <flux:error name="key" />
            </flux:field>

            <flux:field>
                <flux:label>Nombre</flux:label>
                <flux:input wire:model="label" placeholder="Recordatorio clínica" />
                <flux:error name="label" />
            </flux:field>

            <flux:field>
                <flux:label>Mensaje</flux:label>
                <flux:textarea wire:model="message" rows="7" placeholder="Hola [NOMBRE]..." />
                <flux:error name="message" />
            </flux:field>

            <div class="grid gap-4 sm:grid-cols-2">
                <flux:field>
                    <flux:label>Orden</flux:label>
                    <flux:input type="number" wire:model="sort_order" min="0" />
                    <flux:error name="sort_order" />
                </flux:field>

                <div class="space-y-4 pt-6">
                    <label class="flex items-center gap-3 text-sm text-slate-200">
                        <input type="checkbox" wire:model="is_default" class="rounded border-white/20 bg-slate-900 text-emerald-400">
                        <span>Predeterminada</span>
                    </label>
                    <label class="flex items-center gap-3 text-sm text-slate-200">
                        <input type="checkbox" wire:model="is_active" class="rounded border-white/20 bg-slate-900 text-emerald-400">
                        <span>Activa</span>
                    </label>
                </div>
            </div>

            <div class="flex flex-wrap gap-3">
                <flux:button class="action-add" type="submit">{{ $editingTemplateId ? 'Guardar cambios' : 'Crear plantilla' }}</flux:button>
                @if ($editingTemplateId)
                    <flux:button type="button" wire:click="create">Cancelar</flux:button>
                @endif
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
                                <div class="mt-1 text-xs text-slate-400">{{ \Illuminate\Support\Str::limit($template->message, 90) }}</div>
                            </td>
                            <td class="px-4 py-3">{{ $template->label }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col gap-1 text-xs">
                                    <span class="inline-flex w-fit rounded-full bg-white/10 px-2 py-1 text-slate-200">{{ $template->is_active ? 'Activa' : 'Inactiva' }}</span>
                                    @if ($template->is_default)
                                        <span class="inline-flex w-fit rounded-full bg-emerald-400/20 px-2 py-1 text-emerald-200">Predeterminada</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3">{{ $template->sort_order }}</td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <flux:button class="action-edit" type="button" size="sm" wire:click="edit({{ $template->id }})">Editar</flux:button>
                                    <flux:button type="button" size="sm" wire:click="setDefault({{ $template->id }})">Default</flux:button>
                                    <flux:button class="action-delete" type="button" size="sm" wire:click="delete({{ $template->id }})" onclick="return confirm('¿Eliminar esta plantilla?')">Eliminar</flux:button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
