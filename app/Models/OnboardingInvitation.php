<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class OnboardingInvitation extends Model
{
    use HasFactory, BelongsToTenant;

    protected $tableName = 'onboarding_invitations';

    protected $primaryKey = 'id';

    protected $foreignKey = 'user_id';

    protected $fillable = ['user_id', 'tenant_id', 'verification_code', 'accepted_at', 'expired_at', 'is_sent', 'progress', 'status'];
}
