<?php

namespace App\Mail;

use App\Models\Calculation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEvaluationReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $file;

    public Calculation $calculation;

    public function __construct(string $file, Calculation $calculation)
    {
        $this->file = $file;
        $this->calculation = $calculation;
    }

    public function build()
    {
        $licensePlate = $this->calculation->license_plate ?? 'N/A';
        $object = "Rapport d'évaluation du véhicule immatriculé {$licensePlate} - VALORIX";

        return $this->view('email.evaluation_report.index')
            ->subject($object)
            ->attach($this->file);
    }
}
