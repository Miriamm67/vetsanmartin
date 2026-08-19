<?php
namespace App\Observers;
use App\Models\Bitacora;
use Illuminate\Database\Eloquent\Model;

class BitacoraObserver
{
    public function created(Model $m) { $this->log($m,'create'); }
    public function updated(Model $m) { $this->log($m,'update'); }
    public function deleted(Model $m) { $this->log($m,'delete'); }

    private function log(Model $m, string $accion): void
    {
        if (!auth()->check() || $m instanceof Bitacora) return;
        Bitacora::create(['id_usuario'=>auth()->id(),'tabla'=>$m->getTable(),'registro_id'=>$m->getKey() ?? 0,'accion'=>$accion]);
    }
}