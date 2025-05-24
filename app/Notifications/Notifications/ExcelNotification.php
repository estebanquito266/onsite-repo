<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ExcelNotification extends Notification
{
    use Queueable;

    protected $filePath;
    protected $fileName;
    protected $formId;

    public function __construct($filePath,$fileName="Descargar",$formId = "")
    {
        $this->filePath = $filePath;
        $this->fileName = $fileName;
        $this->formId = $formId;
    }

    public function via($notifiable)
    {
        return ['database']; 
    }

    public function toArray($notifiable)
    {
        $filexls = url($this->filePath);
        $labelnoty = "";
        if(!empty($filexls)){
            $class_to_hidde = "downloadnoti{$this->formId}";
            $labelnoty = "<a onclick=\"downloadXLSByURL('{$filexls}','{$class_to_hidde}');\" href=\"#\" class=\"clickoverlay\">".$this->fileName."</a>";
        }
        return [
            'message' => 'Tu archivo Excel está listo para descargar.',
            'file' => url($this->filePath),
            'file_name' => $this->fileName,
            'form_id' => $this->formId,
            'link'=>$labelnoty
        ];
    }
}
