<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    public function addProgress(float $progress)
    {
        $this->progress += $progress;
        if($this->progress >= $this->target && $this->completed_date == null) {
            $this->completed_date = Carbon::now()->toDateString();
        }
        $this->save();

    }

    public function setProgress(float $progress)
    {
        $this->progress = $progress;
        if($this->progress >= $this->target && $this->completed_date == null) {
            $this->completed_date = Carbon::now()->toDateString();
        }
        $this->save();
    }

    public function setTarget(float $target)
    {
        $this->target = $target;
        if($this->target >= $this->target && $this->completed_date == null) {
            $this->completed_date = Carbon::now()->toDateString();
        }
        $this->save();
    }

    public function showProgress()
    {
        return number_format($this->progress, strlen(substr(strrchr($this->progress, "."), 1)));
    }

    public function showTarget()
    {
        return number_format($this->target, strlen(substr(strrchr($this->target, "."), 1)));
    }

    public function showProgressPct()
    {
        $pct = $this->progress / $this->target * 100;
        $decimals = strlen(substr(strrchr($pct, "."), 1));
        $decimals = $decimals > 3 ? 3 : $decimals;
        return number_format($pct, $decimals) . "%";
    }

    public function complete()
    {
        $this->addProgress($this->target - $this->progress);
    }

    public function isComplete()
    {
        return $this->completed_date != null;
    }

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'start_date',
        'due_date',
        'target'
    ];

    protected $casts = [
      'start_date' => 'date:Y-m-d',
      'due_date' => 'date:Y-m-d',
      'completed_date' => 'date'
    ];

}
