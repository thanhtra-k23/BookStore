<?php

namespace App\Mail;

use App\Models\DonHang;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusChanged extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public DonHang $donHang;
    public string $oldStatus;
    public string $newStatus;

    /**
     * Create a new message instance.
     */
    public function __construct(DonHang $donHang, string $oldStatus, string $newStatus)
    {
        $this->donHang = $donHang;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Cập nhật trạng thái đơn hàng #' . $this->donHang->ma_don . ' - BookStore',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order-status-changed',
            with: [
                'donHang' => $this->donHang,
                'oldStatus' => $this->oldStatus,
                'newStatus' => $this->newStatus,
                'statusText' => $this->getStatusText($this->newStatus),
            ],
        );
    }

    /**
     * Get status text in Vietnamese
     */
    private function getStatusText(string $status): string
    {
        return match($status) {
            'cho_xac_nhan' => 'Chờ xác nhận',
            'da_xac_nhan' => 'Đã xác nhận',
            'dang_giao' => 'Đang giao hàng',
            'da_giao' => 'Đã giao hàng',
            'da_huy' => 'Đã hủy',
            default => 'Không xác định',
        };
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
