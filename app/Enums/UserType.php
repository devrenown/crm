<?php

namespace App\Enums;

enum UserType: string
{
    case SUPERADMIN = 'Super Admin';
    case ADMIN      = 'Admin';
    case EMPLOYEE   = 'Employee';
    case CLIENT     = 'Client';
    case MANAGER    = 'Manager';
    case HR         = 'Hr';
    case ACCOUNTANT = 'Accountant';
    case TL         = 'Tl';
}
