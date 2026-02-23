<?php

namespace App\Enums;

enum TaskStatus: int
{
	case COMPLETED 	= 1;
	case INPROGRESS = 2;
	case PENDING   	= 3;
	case CANCELLED 	= 4;

	public function label(): string
	{
		return match($this) {
			self::COMPLETED 	=> 	'Completed',
			self::INPROGRESS 	=> 	'Inprogress',
			self::PENDING 		=> 	'Pending',
			self::CANCELLED 	=> 	'Cancelled',
		};
	}

	public function badgeClass(): string
	{
		return match($this) {
			self::COMPLETED 	=> 'success',
			self::INPROGRESS 	=> 'info',
			self::PENDING   	=> 'warning',
			self::CANCELLED 	=> 'danger',
		};
	}
}
