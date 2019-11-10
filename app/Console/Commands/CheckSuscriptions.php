<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Suscription;
use Carbon\Carbon;
use App\Payment;

class CheckSuscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:suscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $suscriptions = Suscription::where('status', 1)->get();
        foreach ($suscriptions as $key => $value) {
            if( !$value->payments->count() ) {

                $dateTo = $value->created_at->copy();

                if ( $dateTo->lt( Carbon::now() ) ) {
                    $dateFrom = $value->created_at;
                } else {
                    $dateFrom = Carbon::now();
                    $dateTo = $dateFrom->copy();
                }

                switch ($value->membership->frequency) {
                    case 'anual':
                        $dateTo->addYear(1);
                        break;
                    
                    default:
                        $dateTo->addMonth(1);
                        # code...
                        break;
                }

                echo "Pago creado";

                $payment = Payment::create([
                    'suscription_id' => $value->id,
                    'transaction_id' => '',
                    'sub_total' => $value->membership->price,
                    'tax' => $value->membership->iva,
                    'tax_rate' => $value->membership->tax_rate,
                    'date_from' => $dateFrom,
                    'date_to' => $dateTo,
                ]);
            } else {
                if ( !$value->organization->currentPaymentIsUpToDate() ) {
                    $currentPayment = $value->organization->currentPayment();

                    $dateTo = Carbon::create($currentPayment->date_to);
                    $dateFrom = Carbon::create($currentPayment->date_from);

                    switch ($value->membership->frequency) {
                        case 'anual':
                            $dateTo->addYear(1);

                            if ( $dateTo->lte( Carbon::now() ) ) {
                                $dateFrom = Carbon::now();
                                $dateTo = $dateFrom->copy()->addYear(1);
                            } else {
                                $dateFrom->addYear(1);
                            }

                            break;
                        
                        default:
                            $dateTo->addMonth(1);

                            if ( $dateTo->lte( Carbon::now() ) ) {
                                $dateFrom = Carbon::now();
                                $dateTo = $dateFrom->copy()->addMonth(1);
                            } else {
                                $dateFrom->addMonth(1);
                            }

                            break;
                    }

                    echo "Pago renovado";

                    $payment = Payment::create([
                        'suscription_id' => $value->id,
                        'transaction_id' => '',
                        'sub_total' => $value->membership->price,
                        'tax' => $value->membership->iva,
                        'tax_rate' => $value->membership->tax_rate,
                        'date_from' => $dateFrom,
                        'date_to' => $dateTo,
                    ]);
                }
            }
        }
    }
}
