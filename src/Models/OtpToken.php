<?php



namespace Upcodersir\OtpAuth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;


class OtpToken extends Model
{
  use HasFactory;

  protected $fillable = [
      'identifier',
      'token',
      'expires_at',
      'used',
  ];

  protected $casts = [
      'expires_at' => 'datetime',
      'used' => 'boolean',
  ];

  public function __construct(array $attributes = [])
  {
      parent::__construct($attributes);
      $this->table = config('otp.token_table', 'tokens');
  }

  public function user()
  {
      return $this->belongsTo(Config::get('otp.user_table', 'users'), 'identifier', 'mobile');
  }
  // Disable Laravel's mass assignment protection
  protected $guarded = [];
}