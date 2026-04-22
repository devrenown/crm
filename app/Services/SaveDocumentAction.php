<?php

namespace App\Services;
use App\Models\DocumentAction;

class SaveDocumentAction 
{
	public static function save ($model, $type, $status, $remark = null, $userId = null)
	{
		return DocumentAction::updateOrCreate(
			[
	            'documentable_id' => $model->id,
	            'documentable_type' => get_class($model),
	            'document_type' => $type,
	        ],
	        [
	            'user_id' => $userId ?? auth()->id(),
	            'action_by' => auth()->id(),
	            'status' => $status,
	            'remark' => $remark,
	            'action_at' => now(),
	        ]
    	);
	}
}