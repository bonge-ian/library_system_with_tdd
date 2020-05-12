<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $guarded = [];

    public function path()
    {
        return sprintf('/book/%s', $this->id);
    }

    public function setAuthorIdAttribute($author)
    {
        $this->attributes['author_id'] = Author::firstOrCreate([
            'name' => $author
        ])->id;
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
    
    public function checkout($user)
    {
        $this->reservations()->create([
            'user_id' => $user->id,
            'checked_out_at' => now()
        ]);
    }

    public function checkin(Book $book)
    {
        $reservation = $this->reservations()->where('book_id', $book->id)
            ->whereNotNull('checked_out_at')
            ->whereNull('checked_in_at')
            ->first();

        if (!$reservation) {
            throw new Exception('No reservation was found', 1);
        }

        $reservation->update(['checked_in_at' => now()]);
    }
}
