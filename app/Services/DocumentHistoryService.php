<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\DocumentHistoryModel;
use Illuminate\Database\Eloquent\Collection;

class DocumentHistoryService
{
	/**
	 * Hujjat tarixini olish
	 */
	public function getDocumentHistory($documentId): Collection
	{
		return DocumentHistoryModel::query()->where('document_id', $documentId)
			->orderBy('created_at', 'desc')
			->get();
	}
}