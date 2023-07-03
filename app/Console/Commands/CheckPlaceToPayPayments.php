<?php

namespace App\Console\Commands;

use App\Domain\Payments\Enums\PaymentStatus;
use App\Domain\Payments\Enums\Provider;
use App\Domain\Payments\Factories\PlaceToPay\PlaceToPayPaymentActionsFactory;
use App\Domain\Payments\Models\Payment;
use App\Services\Payments\PlaceToPay\PlaceToPayService;
use Illuminate\Console\Command;
use Throwable;

class CheckPlaceToPayPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-place-to-pay-payments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to check place to pay payment statuses';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $placeToPayService = resolve(name: PlaceToPayService::class);

        $payments = Payment::query()
            ->select(columns: ['id', 'order_id', 'provider', 'data_provider', 'status'])
            ->with(relations: ['order'])
            ->where(column: 'provider', operator: '=', value: Provider::PLACE_TO_PAY)
            ->where(column: 'status', operator: '=', value: PaymentStatus::PENDING)
            ->get();

        try {
            $checkPaymentActions = (new PlaceToPayPaymentActionsFactory($placeToPayService))->make();

            foreach ($payments as $payment) {
                $status = $placeToPayService->getSession($payment->data_provider['requestId'])['status']['status'];

                foreach ($checkPaymentActions as $checkPaymentAction) {
                    $checkPaymentAction($status, $payment->order);
                }
            }

            $this->info(string: 'Scheduled task to check PlaceToPay payments statuses executed successfully');
        } catch (Throwable $throwable) {
            report($throwable);
        }
    }
}
