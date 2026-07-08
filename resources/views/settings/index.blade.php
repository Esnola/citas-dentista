@php use App\Services\WhatsApp\WhatsAppSender; @endphp
@php use Illuminate\Support\Str; @endphp
@extends('layouts.app')

@section('content')
  @php
    $driver = config('whatsapp.driver');
    $twilio = config('whatsapp.twilio', []);
    $twilioAccountSid = (string) ($twilio['account_sid'] ?? '');
    $twilioAuthToken = (string) ($twilio['auth_token'] ?? '');
    $twilioApiKeySid = (string) ($twilio['api_key_sid'] ?? '');
    $twilioApiKeySecret = (string) ($twilio['api_key_secret'] ?? '');
    $twilioFrom = (string) ($twilio['from'] ?? '');
    $twilioServiceSid = (string) ($twilio['messaging_service_sid'] ?? '');
    $twilioContentSid = (string) app(WhatsAppSender::class)->twilioContentSid();
    $twilioMode = (string) ($twilio['mode'] ?? 'auto');
    $twilioResolvedMode = app(WhatsAppSender::class)->resolveTwilioMode();
    $twilioUsesApiKey = filled($twilioApiKeySid) && filled($twilioApiKeySecret);
    $twilioHasCredentials = filled($twilioAccountSid) && ($twilioUsesApiKey || filled($twilioAuthToken));
    $twilioHasSender = $twilioResolvedMode === 'service' ? filled($twilioServiceSid) : filled($twilioFrom);
    $twilioUsesTemplate = config('whatsapp.message_mode') === 'template';
    $selectedSenderNumber = \App\Models\WhatsAppCredential::get()->selectedSenderNumber();
  @endphp

  <div x-data="settingsBoard()"
          x-init="init()"
          class="grid gap-4" >

    <div class="rounded-3xl border border-white/10 bg-white/5 p-8 backdrop-blur">
      <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
          <h2 class="text-2xl font-semibold">Ajustes</h2>
          <p class="mt-3 text-sm text-slate-300">
            Reordena las secciones arrastrando su cabecera y contrae o expande cada bloque cuando quieras.
          </p>
        </div>
        <div class="flex flex-wrap gap-2">
          <x-botones.accion-ajustes icono="abrir" variant="emerald" x-on:click="expandAll">
            Expandir todos
          </x-botones.accion-ajustes>
          <x-botones.accion-ajustes icono="cerrar" variant="yellow" x-on:click="collapseAll">
            Contraer todos
          </x-botones.accion-ajustes>
          <x-botones.accion-ajustes icono="restablecer" variant="rose" x-on:click="resetLayout">
            Restablecer todo
          </x-botones.accion-ajustes>
        </div>
      </div>
    </div>

    <div x-ref="board"  class="grid gap-4" aria-label="Secciones de ajustes" >
      <section data-settings-section="overview"
              data-default-open="true"
              class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur"
              x-bind:class="sectionStateClasses('overview')"
              x-on:dragenter.prevent="setDropTarget('overview', $event)"
              x-on:dragover.prevent
              x-on:drop.prevent="drop('overview', $event)"
              x-show="isVisible('overview')"
      >
        <div x-show="showDropHint('overview', 'before')" x-cloak
             class="mb-4 h-1 rounded-full bg-emerald-400/80 shadow-[0_0_24px_rgba(52,211,153,0.45)]"></div>
        <div class="flex items-center justify-between gap-4">
          <div class="flex items-center gap-3">
            <x-botones.arrastrar-seccion seccion="overview"/>
            <div>
              <h3 class="text-lg font-semibold">Resumen</h3>
              <p class="text-sm text-slate-300">Estado general de WhatsApp, plantillas y sandbox.</p>
            </div>
          </div>
          <x-botones.expandir-contraer abierto="isOpen('overview')" seccion="overview"/>
        </div>

        <div x-show="dragging === 'overview'" x-cloak
             class="mt-4 rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-xs uppercase tracking-[0.28em] text-emerald-100">
          Soltando esta tarjeta aquí
        </div>

        <div x-show="isOpen('overview')" x-cloak class="mt-6">
          <livewire:settings-overview/>
        </div>
        <div x-show="showDropHint('overview', 'after')" x-cloak
             class="mt-4 h-1 rounded-full bg-emerald-400/80 shadow-[0_0_24px_rgba(52,211,153,0.45)]"></div>
      </section>

      <section data-settings-section="status"
              data-default-open="false"
              class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur"
              x-bind:class="sectionStateClasses('status')"
              x-on:dragenter.prevent="setDropTarget('status', $event)"
              x-on:dragover.prevent
              x-on:drop.prevent="drop('status', $event)" >

        <div x-show="showDropHint('status', 'before')" x-cloak
             class="mb-4 h-1 rounded-full bg-emerald-400/80 shadow-[0_0_24px_rgba(52,211,153,0.45)]"></div>
        <div class="flex items-center justify-between gap-4">
          <div class="flex items-center gap-3">
            <x-botones.arrastrar-seccion seccion="status"/>
            <div>
              <h3 class="text-lg font-semibold">Estado actual</h3>
              <p class="text-sm text-slate-300">Credenciales, sender y estado de conexión.</p>
            </div>
          </div>

          <x-botones.expandir-contraer abierto="isOpen('status')" seccion="status"/>
        </div>

        <div x-show="dragging === 'status'" x-cloak
             class="mt-4 rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-xs uppercase tracking-[0.28em] text-emerald-100">
          Arrastre activo
        </div>

        <div x-show="isOpen('status')" x-cloak class="mt-6 grid gap-4">
          <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4">
            <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Account SID</p>
            <p class="mt-2 font-medium">
              {{ $twilioAccountSid ? Str::mask($twilioAccountSid, '*', 4) : 'No configurado' }}
            </p>
          </div>
          <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4">
            <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Sender</p>
            <p class="mt-2 font-medium">{{ $selectedSenderNumber?->full_number ?: ($twilioServiceSid ?: 'No configurado') }}</p>
            <p class="mt-1 text-sm text-slate-300">
              {{ $selectedSenderNumber ? ($selectedSenderNumber->name ?: 'Sender activo') : ($twilioServiceSid ? 'Messaging Service' : 'Añade un sender o servicio') }}
            </p>
          </div>
          <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4">
            <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Auth Token</p>
            <p class="mt-2 font-medium">{{ $twilioAuthToken ? 'Configurado' : 'No configurado' }}</p>
            <p class="mt-1 text-sm text-slate-300">Necesario para validar las firmas de los webhooks.</p>
          </div>
          <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4">
            <p class="text-xs uppercase tracking-[0.25em] text-slate-400">API Key</p>
            <p class="mt-2 font-medium">{{ $twilioUsesApiKey ? Str::mask($twilioApiKeySid, '*', 4) : 'No configurada' }}</p>
            <p class="mt-1 text-sm text-slate-300">{{ $twilioUsesApiKey ? 'Usada para conectar con la API REST' : 'Se usará Account SID + Auth Token' }}</p>
          </div>
          <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4">
            <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Content SID</p>
            <p class="mt-2 font-medium">{{ $twilioContentSid ? Str::mask($twilioContentSid, '*', 4) : 'No configurado' }}</p>
            <p class="mt-1 text-sm text-slate-300">Necesario cuando `WHATSAPP_MESSAGE_MODE=template`.</p>
          </div>
        </div>
        <div x-show="showDropHint('status', 'after')" x-cloak
             class="mt-4 h-1 rounded-full bg-emerald-400/80 shadow-[0_0_24px_rgba(52,211,153,0.45)]"></div>
      </section>

      <section data-settings-section="connection"
               data-default-open="true"
               class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur"
               x-bind:class="sectionStateClasses('connection')"
               x-on:dragenter.prevent="setDropTarget('connection', $event)"
               x-on:dragover.prevent
               x-on:drop.prevent="drop('connection', $event)"
      >
        <div x-show="showDropHint('connection', 'before')" x-cloak
             class="mb-4 h-1 rounded-full bg-emerald-400/80 shadow-[0_0_24px_rgba(52,211,153,0.45)]"></div>
        <div class="flex items-center justify-between gap-4">
          <div class="flex items-center gap-3">
            <x-botones.arrastrar-seccion seccion="connection"/>
            <div>
              <h3 class="text-lg font-semibold">Prueba de conexión</h3>
              <p class="text-sm text-slate-300">Panel de envío real y vista previa del payload.</p>
            </div>
          </div>
          <x-botones.expandir-contraer abierto="isOpen('connection')" seccion="connection"/>
        </div>

        <div x-show="dragging === 'connection'" x-cloak
             class="mt-4 rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-xs uppercase tracking-[0.28em] text-emerald-100">
          Sección lista para soltar
        </div>

        <div x-show="isOpen('connection')" x-cloak class="mt-6">
          <livewire:whatsapp-connection-test/>
        </div>
        <div x-show="showDropHint('connection', 'after')" x-cloak
             class="mt-4 h-1 rounded-full bg-emerald-400/80 shadow-[0_0_24px_rgba(52,211,153,0.45)]"></div>
      </section>

      <section data-settings-section="twilio-templates"
               data-default-open="true"
               class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur"
               x-bind:class="sectionStateClasses('twilio-templates')"
               x-on:dragenter.prevent="setDropTarget('twilio-templates', $event)"
               x-on:dragover.prevent
               x-on:drop.prevent="drop('twilio-templates', $event)">
        <div x-show="showDropHint('twilio-templates', 'before')" x-cloak
             class="mb-4 h-1 rounded-full bg-emerald-400/80 shadow-[0_0_24px_rgba(52,211,153,0.45)]"></div>
        <div class="flex items-center justify-between gap-4">
          <div class="flex items-center gap-3">
            <x-botones.arrastrar-seccion seccion="twilio-templates"/>
            <div>
              <h3 class="text-lg font-semibold">Plantillas de Twilio</h3>
              <p class="text-sm text-slate-300">Guarda Content SID y elige cuál usa WhatsApp.</p>
            </div>
          </div>
          <x-botones.expandir-contraer abierto="isOpen('twilio-templates')" seccion="twilio-templates"/>
        </div>

        <div x-show="dragging === 'twilio-templates'" x-cloak
             class="mt-4 rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-xs uppercase tracking-[0.28em] text-emerald-100">
          Plantillas en movimiento
        </div>

        <div x-show="isOpen('twilio-templates')" x-cloak class="mt-6">
          <livewire:twilio-content-template-settings/>
        </div>
        <div x-show="showDropHint('twilio-templates', 'after')" x-cloak
             class="mt-4 h-1 rounded-full bg-emerald-400/80 shadow-[0_0_24px_rgba(52,211,153,0.45)]"></div>
      </section>

      <section data-settings-section="credentials"
               data-default-open="true"
               class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur"
               x-bind:class="sectionStateClasses('credentials')"
               x-on:dragenter.prevent="setDropTarget('credentials', $event)"
               x-on:dragover.prevent
               x-on:drop.prevent="drop('credentials', $event)">
        <div x-show="showDropHint('credentials', 'before')" x-cloak
             class="mb-4 h-1 rounded-full bg-emerald-400/80 shadow-[0_0_24px_rgba(52,211,153,0.45)]"></div>
        <div class="flex items-center justify-between gap-4">
          <div class="flex items-center gap-3">
            <x-botones.arrastrar-seccion seccion="credentials"/>
            <div>
              <h3 class="text-lg font-semibold">Credenciales Twilio</h3>
              <p class="text-sm text-slate-300">Modo de envío, remitente y API key.</p>
            </div>
          </div>
          <x-botones.expandir-contraer abierto="isOpen('credentials')" seccion="credentials"/>
        </div>

        <div x-show="dragging === 'credentials'" x-cloak
             class="mt-4 rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-xs uppercase tracking-[0.28em] text-emerald-100">
          Credenciales en movimiento
        </div>

        <div x-show="isOpen('credentials')" x-cloak class="mt-6">
          <livewire:twilio-credential-settings/>
        </div>
        <div x-show="showDropHint('credentials', 'after')" x-cloak
             class="mt-4 h-1 rounded-full bg-emerald-400/80 shadow-[0_0_24px_rgba(52,211,153,0.45)]"></div>
      </section>

      <section data-settings-section="reminders"
               data-default-open="true"
               class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur"
               x-bind:class="sectionStateClasses('reminders')"
               x-on:dragenter.prevent="setDropTarget('reminders', $event)"
               x-on:dragover.prevent
               x-on:drop.prevent="drop('reminders', $event)"
      >
        <div x-show="showDropHint('reminders', 'before')" x-cloak
             class="mb-4 h-1 rounded-full bg-emerald-400/80 shadow-[0_0_24px_rgba(52,211,153,0.45)]"></div>
        <div class="flex items-center justify-between gap-4">
          <div class="flex items-center gap-3">
            <x-botones.arrastrar-seccion seccion="reminders"/>
            <div>
              <h3 class="text-lg font-semibold">Tiempos de envío</h3>
              <p class="text-sm text-slate-300">Selecciona WhatsApp y email 1, 2, 3 días o una semana antes.</p>
            </div>
          </div>
          <x-botones.expandir-contraer abierto="isOpen('reminders')" seccion="reminders"/>
        </div>

        <div x-show="dragging === 'reminders'" x-cloak
             class="mt-4 rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-xs uppercase tracking-[0.28em] text-emerald-100">
          Recordatorios en movimiento
        </div>

        <div x-show="isOpen('reminders')" x-cloak class="mt-6">
          <livewire:appointment-reminder-settings/>
        </div>
        <div x-show="showDropHint('reminders', 'after')" x-cloak
             class="mt-4 h-1 rounded-full bg-emerald-400/80 shadow-[0_0_24px_rgba(52,211,153,0.45)]"></div>
      </section>
    </div>
  </div>

  <script>
      document.addEventListener('alpine:init', () => {
          Alpine.data('settingsBoard', () => ({
              orderKey: 'eugenia.settings.sections.order',
              openKey: 'eugenia.settings.sections.open',
              dragging: null,
              dropTarget: null,
              dropPosition: null,
              placeholder: null,
              openState: {},

              init() {
                  this.openState = this.loadOpenState();
                  this.applySavedOrder();
              },

              isVisible(sectionId) {
                  return this.sectionElement(sectionId) !== null;
              },

              isOpen(sectionId) {
                  if (Object.prototype.hasOwnProperty.call(this.openState, sectionId)) {
                      return this.openState[sectionId];
                  }

                  const section = this.sectionElement(sectionId);
                  return section ? section.dataset.defaultOpen === 'true' : false;
              },

              toggle(sectionId) {
                  this.openState[sectionId] = !this.isOpen(sectionId);
                  this.saveOpenState();
              },

              expandAll() {
                  this.sectionIds().forEach((sectionId) => {
                      this.openState[sectionId] = true;
                  });
                  this.saveOpenState();
              },

              collapseAll() {
                  this.sectionIds().forEach((sectionId) => {
                      this.openState[sectionId] = false;
                  });
                  this.saveOpenState();
              },

              startDrag(sectionId, event) {
                  const dragged = this.sectionElement(sectionId);

                  if (!dragged) {
                      return;
                  }

                  this.dragging = sectionId;
                  this.dropTarget = sectionId;
                  this.dropPosition = 'before';
                  event.dataTransfer.effectAllowed = 'move';
                  event.dataTransfer.setData('text/plain', sectionId);
                  event.dataTransfer.setDragImage(this.buildDragImage(dragged), 20, 20);
                  dragged.classList.add('settings-dragging');
              },

              stopDrag() {
                  const dragged = this.dragging ? this.sectionElement(this.dragging) : null;

                  if (dragged) {
                      dragged.classList.remove('settings-dragging');
                  }

                  this.removePlaceholder();
                  this.dragging = null;
                  this.dropTarget = null;
                  this.dropPosition = null;
              },

              setDropTarget(sectionId, event) {
                  if (!this.dragging || this.dragging === sectionId) {
                      return;
                  }

                  this.dropTarget = sectionId;

                  const section = this.sectionElement(sectionId);
                  if (!section) {
                      return;
                  }

                  const rect = section.getBoundingClientRect();
                  this.dropPosition = event.clientY > rect.top + (rect.height / 2) ? 'after' : 'before';
                  this.repositionDragged(section, this.dropPosition);
              },

              drop(sectionId) {
                  if (!this.dragging || this.dragging === sectionId) {
                      this.stopDrag();
                      return;
                  }

                  const dragged = this.sectionElement(this.dragging);

                  if (!dragged) {
                      this.persistOrder();
                      this.stopDrag();
                      return;
                  }

                  this.persistOrder();
                  this.stopDrag();
              },

              repositionDragged(target, position) {
                  if (!this.dragging || !target) {
                      return;
                  }

                  const dragged = this.sectionElement(this.dragging);

                  if (!dragged || dragged === target) {
                      return;
                  }

                  const previousRects = this.captureSectionRects();
                  const parent = target.parentNode;
                  const referenceNode = position === 'after' ? target.nextSibling : target;

                  if (referenceNode) {
                      parent.insertBefore(dragged, referenceNode);
                  } else {
                      parent.appendChild(dragged);
                  }

                  this.animateReflow(previousRects);
              },

              createPlaceholder(section) {
                  this.removePlaceholder();

                  const placeholder = document.createElement('div');
                  placeholder.dataset.settingsPlaceholder = 'true';
                  placeholder.className = 'settings-drop-placeholder';
                  placeholder.style.minHeight = `${Math.max(section.getBoundingClientRect().height, 96)}px`;
                  placeholder.style.height = `${Math.max(section.getBoundingClientRect().height, 96)}px`;
                  placeholder.innerHTML = '<span>Soltar aquí</span>';

                  this.placeholder = placeholder;
                  this.$refs.board.insertBefore(placeholder, section);

                  return placeholder;
              },

              movePlaceholder(target, position) {
                  if (!this.placeholder || !this.placeholder.parentNode || !target) {
                      return;
                  }

                  const parent = target.parentNode;
                  const referenceNode = position === 'after' ? target.nextSibling : target;

                  if (referenceNode) {
                      parent.insertBefore(this.placeholder, referenceNode);
                  } else {
                      parent.appendChild(this.placeholder);
                  }
              },

              removePlaceholder() {
                  if (this.placeholder && this.placeholder.parentNode) {
                      this.placeholder.parentNode.removeChild(this.placeholder);
                  }

                  this.placeholder = null;
              },

              buildDragImage(section) {
                  const clone = section.cloneNode(true);
                  clone.classList.add('pointer-events-none', 'scale-95', 'rotate-[-1deg]', 'shadow-2xl', 'shadow-emerald-500/25');
                  clone.style.position = 'absolute';
                  clone.style.top = '-9999px';
                  clone.style.left = '-9999px';
                  clone.style.width = `${section.offsetWidth}px`;
                  document.body.appendChild(clone);

                  setTimeout(() => clone.remove(), 0);

                  return clone;
              },

              captureSectionRects() {
                  return new Map(
                      this.sectionNodes().map((section) => [
                          section.dataset.settingsSection,
                          section.getBoundingClientRect(),
                      ])
                  );
              },

              animateReflow(previousRects) {
                  requestAnimationFrame(() => {
                      this.sectionNodes().forEach((section) => {
                          const sectionId = section.dataset.settingsSection;
                          const previousRect = previousRects.get(sectionId);

                          if (!previousRect) {
                              return;
                          }

                          const currentRect = section.getBoundingClientRect();
                          const deltaX = previousRect.left - currentRect.left;
                          const deltaY = previousRect.top - currentRect.top;

                          if (deltaX === 0 && deltaY === 0) {
                              return;
                          }

                          section.animate(
                              [
                                  {transform: `translate(${deltaX}px, ${deltaY}px)`},
                                  {transform: 'translate(0, 0)'},
                              ],
                              {
                                  duration: 240,
                                  easing: 'cubic-bezier(0.2, 0, 0, 1)',
                              }
                          );
                      });
                  });
              },

              applySavedOrder() {
                  const savedOrder = this.loadOrder();
                  if (!savedOrder.length) {
                      this.persistOrder();
                      return;
                  }

                  const board = this.$refs.board;
                  const elements = new Map(this.sectionIds().map((sectionId) => [sectionId, this.sectionElement(sectionId)]));

                  savedOrder
                      .filter((sectionId) => elements.has(sectionId))
                      .forEach((sectionId) => {
                          board.appendChild(elements.get(sectionId));
                      });

                  this.sectionIds()
                      .filter((sectionId) => !savedOrder.includes(sectionId))
                      .forEach((sectionId) => {
                          board.appendChild(this.sectionElement(sectionId));
                      });
              },

              persistOrder() {
                  localStorage.setItem(this.orderKey, JSON.stringify(this.sectionIds()));
              },

              loadOrder() {
                  try {
                      const raw = localStorage.getItem(this.orderKey);
                      return raw ? JSON.parse(raw) : [];
                  } catch (error) {
                      return [];
                  }
              },

              loadOpenState() {
                  try {
                      const raw = localStorage.getItem(this.openKey);
                      if (raw) {
                          return JSON.parse(raw);
                      }
                  } catch (error) {
                      // Fallback to defaults below.
                  }

                  return this.sectionIds().reduce((state, sectionId) => {
                      const section = this.sectionElement(sectionId);
                      state[sectionId] = section ? section.dataset.defaultOpen === 'true' : false;
                      return state;
                  }, {});
              },

              saveOpenState() {
                  localStorage.setItem(this.openKey, JSON.stringify(this.openState));
              },

              resetLayout() {
                  localStorage.removeItem(this.orderKey);
                  this.openState = this.sectionIds().reduce((state, sectionId) => {
                      state[sectionId] = false;
                      return state;
                  }, {});
                  this.saveOpenState();
                  window.location.reload();
              },

              sectionIds() {
                  return Array.from(this.$refs.board.querySelectorAll('[data-settings-section]'))
                      .map((section) => section.dataset.settingsSection);
              },

              sectionElement(sectionId) {
                  return this.$refs.board.querySelector(`[data-settings-section="${sectionId}"]`);
              },

              sectionNodes() {
                  return Array.from(this.$refs.board.querySelectorAll('[data-settings-section]'));
              },

              showDropHint(sectionId, position) {
                  return this.dragging && this.dropTarget === sectionId && this.dropPosition === position && this.dragging !== sectionId;
              },

              sectionStateClasses(sectionId) {
                  return {
                      'ring-4 ring-emerald-300/80 shadow-[0_24px_80px_rgba(16,185,129,0.24)] scale-[1.015]': this.dropTarget === sectionId && this.dragging && this.dragging !== sectionId,
                      'opacity-80 scale-[0.97] shadow-[0_16px_40px_rgba(15,23,42,0.32)]': this.dragging === sectionId,
                      'border-emerald-400/30 bg-emerald-500/5': this.dragging === sectionId || this.dropTarget === sectionId,
                      'transition-all duration-200 ease-out': true,
                  };
              },
          }));
      });
  </script>
@endsection
