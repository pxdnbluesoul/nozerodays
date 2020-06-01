<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Goal;

class User extends Authenticatable
{
    use Notifiable;

    public function addGoal(array $goal)
    {
        // Guards
        $this->guardAgainstInvalidGoalDates($goal["start_date"]);
        if(isset($goal["due_date"])) { // Nullable field.
            $this->guardAgainstInvalidGoalDates($goal["due_date"]);
        }

        $new_goal = new Goal([
            'user_id' => $this->id,
            'title' => $goal["title"],
            'start_date' => $goal["start_date"],
            'due_date' => $goal["due_date"] ?? null,
            'target' => $goal["target"]
        ]);
        $new_goal->save();
        return $new_goal;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relations

    public function goals()
    {
        return $this->hasMany(Goal::class);
    }

    // Guards
    public function guardAgainstInvalidGoalDates($date)
    {
        $test = date_parse($date);
        if($test["warning_count"] + $test["error_count"] > 0) {
            throw new \RangeException("Invalid Date");
        }
        return;
    }
}
