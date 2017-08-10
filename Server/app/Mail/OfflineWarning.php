<?php

namespace App\Mail;
use App\MonitorData;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OfflineWarning extends Mailable
{
    use Queueable, SerializesModels;

    public $miner;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(MonitorData $miner)
    {
        $this->miner = $miner;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Miner离线通知')->view('emails.offlinewarning');
    }
}
