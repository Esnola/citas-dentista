<?php
  
  namespace App\Livewire;
  
  use App\Models\Appointment;
  use App\Models\WhatsAppMessage;
  use Carbon\Carbon;
  use Illuminate\View\View;
  use Livewire\Component;
  
  class DashboardOverview extends Component
  {
    public int $selectedDateOffset = 0;
    
    public function selectDate(string $offset): void
    {
      $this->selectedDateOffset = (int)$offset;
    }
    
    public function render(): View
    {
      Carbon::setLocale('es');
      $targetDate = $this->targetDate();
      
      return view('livewire.dashboard-overview', [
        'pendingCount' => WhatsAppMessage::pending()->count(),
        'sentCount' => WhatsAppMessage::where('status', WhatsAppMessage::STATUS_SENT)->count(),
        'failedCount' => WhatsAppMessage::where('status', WhatsAppMessage::STATUS_FAILED)->count(),
        'nextAppointments' => Appointment::query()
          ->with('client')
          ->where('activo', true)
          ->whereDate('fecha', $targetDate->toDateString())
          ->orderBy('hora')
          ->limit(5)
          ->get(),
        'targetDates' => $this->targetDates(),
        'selectedDate' => $targetDate,
        'sundayWarning' => $this->sundayWarning(),
        'resolvedDates' => $this->resolvedDates(),
        'futureDayOptions' => $this->futureDayOptions(),
      ]);
    }
    
    private function targetDate(): Carbon
    {
      return $this->resolvedDates()[$this->selectedDateOffset];
    }
    
    private function resolvedDates(): array
    {
      $now = now(config('app.timezone'));
      $dates = [0 => $now->copy()->startOfDay()];
      
      for ($offset = 1; $offset <= 10; $offset++) {
        $date = $now->copy()->addDays($offset);
        
        if ($date->isSunday()) {
          $date->addDay();
        }
        
        if (isset($dates[$offset - 1]) && $date->toDateString() === $dates[$offset - 1]->toDateString()) {
          $date->addDay();
          if ($date->isSunday()) {
            $date->addDay();
          }
        }
        
        $dates[$offset] = $date;
      }
      
      return $dates;
    }
    
    private function targetDates(): array
    {
      $resolved = $this->resolvedDates();
      
      return collect([0, 1])
        ->mapWithKeys(fn(int $offset) => [
          $offset => [
            'offset' => $offset,
            'label' => match ($offset) {
              0 => 'Hoy',
              1 => 'Mañana',
              default => '',
            },
            'date' => $resolved[$offset],
          ],
        ])
        ->all();
    }
    
    private function sundayWarning(): ?string
    {
      if ($this->selectedDateOffset === 0) {
        return null;
      }
      
      $now = now(config('app.timezone'));
      $rawDate = $now->copy()->addDays($this->selectedDateOffset);
      
      if (!$rawDate->isSunday()) {
        return null;
      }
      
      $resolvedDate = $this->targetDate();
      
      return 'La fecha seleccionada es domingo, mostrando las citas del ' . ucfirst($resolvedDate->translatedFormat('l d'));
    }
    
    public function futureDayOptions(): array
    {
      return range(2, 10);
    }
  }
