<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    // Tipe notifikasi
    public const CLAIM_RECEIVED = 'claim_received';
    public const CLAIM_APPROVED = 'claim_approved';
    public const CLAIM_REJECTED = 'claim_rejected';
    public const CLAIM_COMPLETED = 'claim_completed';
    public const REVIEW_RECEIVED = 'review_received';
    public const EVENT_STARTED = 'event_started';
    public const NEW_MESSAGE = 'new_message';
    public const WISHLIST_ITEM_CLAIMED = 'wishlist_item_claimed';
    public const WISHLIST_REQUEST_RESPONSE = 'wishlist_request_response';
    public const WISHLIST_REQUEST_EXPIRED = 'wishlist_request_expired';
    public const NEW_REPORT = 'new_report';
    public const REPORT_RESOLVED = 'report_resolved';
    public const MODERATION_WARNING = 'moderation_warning';

    /**
     * Kirim notifikasi in-app
     *
     * @param int $userId ID User tujuan
     * @param string $type Tipe notifikasi dari konstanta
     * @param string $title Judul notifikasi
     * @param string $message Isi pesan notifikasi
     * @param array $data Data tambahan (misal: action_url)
     * @return Notification
     */
    public static function send($userId, $type, $title, $message, $data = [])
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data, // Disimpan sebagai JSON
        ]);
    }
}
