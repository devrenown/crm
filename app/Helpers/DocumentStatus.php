<?php

namespace App\Helpers;

class DocumentStatus {

	public static function statusBadge($status) 
	{

		switch ($status) {
			case '1':
				return '<i class="fa-solid fa-check-circle text-success status-mark me-1"></i>';

			case '2':
				return '<i class="fa-solid fa-circle-xmark text-danger status-mark me-1"></i>';
			
			default:
				return '<i class="fa-solid fa-clock text-warning status-mark me-1"></i>';
		}
	}

	public static function remarks($remarks)
	{
	    $remarksText = $remarks ?? 'No remarks';

	    return '
	        <div class="px-3 py-2 shadow-sm rounded d-flex align-items-center gap-2 remarks-message">
	            <p class="m-0">
	                <i class="fa-solid fa-triangle-exclamation text-danger"></i>
	            </p>
	            <p class="m-0 text-sm">' . e($remarksText) . '</p>
	        </div>
	    ';
	}
}
