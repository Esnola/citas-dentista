@php use App\Models\WhatsAppCredential;use App\Services\WhatsApp\WhatsAppSender; @endphp
@php use Illuminate\Support\Str; @endphp
@extends('layouts.app')

@section('content')
  @php
    $credential = WhatsAppCredential::get();
    $driver = $credential->resolveDriver();
    $twilioAccountSid = (string) $credential->resolveAccountSid();
    $twilioAuthToken = (string) $credential->resolveAuthToken();
    $twilioApiKeySid = (string) $credential->resolveApiKeySid();
    $twilioApiKeySecret = (string) $credential->resolveApiKeySecret();
    $twilioFrom = (string) $credential->resolveFrom();
    $twilioUsesApiKey = filled($twilioApiKeySid) && filled($twilioApiKeySecret);
    $twilioHasCredentials = filled($twilioAccountSid) && ($twilioUsesApiKey || filled($twilioAuthToken));
    $selectedSenderNumber = $credential->selectedSenderNumber();
  @endphp

  <div x-data="settingsBoard()"
       x-init="init()"
       class="grid gap-4">

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

    <div x-ref="board" class="grid gap-4" aria-label="Secciones de ajustes">
      <x-settings.section id="credentials"
                          title="Credenciales Twilio"
                          description="Modo de envío, remitente y API key."
                          drag-label="Credenciales en movimiento">
        <livewire:twilio-credential-settings/>
      </x-settings.section>

      <x-settings.section id="reminders"
                          title="Tiempos de envío"
                          description="Selecciona WhatsApp y email 1, 2, 3 días o una semana antes."
                          drag-label="Recordatorios en movimiento">
        <livewire:appointment-reminder-settings/>
      </x-settings.section>

      <x-settings.section id="maintenance"
                          title="Mantenimiento / Opciones"
                          description="Define cuándo se eliminan automáticamente las citas pasadas."
                          drag-label="Mantenimiento en movimiento">
        <livewire:appointment-cleanup-settings/>
      </x-settings.section>

      <x-settings.section id="twilio-templates"
                          title="Plantillas de Twilio"
                          description="Guarda Content SID y elige cuál usa WhatsApp."
                          drag-label="Plantillas en movimiento">
        <livewire:twilio-content-template-settings/>
      </x-settings.section>

      <x-settings.section id="connection"
                          title="Prueba de conexión"
                          description="Panel de envío real y vista previa del payload."
                          drag-label="Sección lista para soltar">
        <livewire:whatsapp-connection-test/>
      </x-settings.section>
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
