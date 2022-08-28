<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $guarded = [];

    public function setAuthorIdAttribute($author)
    {
        $author = Author::firstOrCreate([ "name" => $author ]);
        $this->attributes["author_id"] = $author->id;
    }

    public function checkout(User $user)
    {
        $this->reservations()
            ->create([
                "user_id" => $user->id,
                "checked_out_at" => now()
            ]);
    }

    public function checkin(User $user)
    {
        $reservation = $this->reservations()->where("user_id", $user->id)
            ->whereNotNull("checked_out_at")
            ->whereNull("checked_in_at")
            ->first();

        if (is_null($reservation)) throw new \Exception();

        $reservation->update([
            "checked_in_at" => now()
        ]);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
