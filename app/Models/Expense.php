<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['reason', 'category', 'description', 'amount', 'expense_date'];

    /** Sent to every screen so they all date an expense the same way. */
    protected $appends = ['effective_date'];

    /**
     * The day the money was actually spent.
     *
     * expense_date was added to the form later, so rows entered before that have
     * it null; those fall back to the day they were recorded rather than
     * disappearing from a month's figures.
     */
    public function getEffectiveDateAttribute(): ?string
    {
        if (filled($this->expense_date)) {
            return substr((string) $this->expense_date, 0, 10);
        }

        return optional($this->created_at)->toDateString();
    }
}
