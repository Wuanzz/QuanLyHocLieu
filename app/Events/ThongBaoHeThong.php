<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ThongBaoHeThong implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    // Biến chứa nội dung thông báo 
    public $message;

    // Hàm khởi tạo sự kiện, nhận tham số $message để truyền vào biến $message của sự kiện
    public function __construct($message)
    {
        $this->message = $message;
    }

    // Xác định kênh phát sóng sự kiện, ở đây là kênh 'thong-bao-chung' để tất cả người dùng đang lắng nghe kênh này đều nhận được thông báo
    public function broadcastOn()
    {
        // Trả về một instance của Channel với tên kênh là 'thong-bao-chung'
        return new Channel('thong-bao-chung');
    }
}