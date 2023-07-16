<?php

namespace App\Console\Commands;

use App\Domain\Payments\Enums\PaymentStatus;
use App\Domain\Payments\Enums\Provider;
use App\Domain\Payments\Factories\PlaceToPay\PlaceToPayPaymentActionsFactory;
use App\Domain\Payments\Models\Payment;
use App\Domain\Payments\Services\PlaceToPay\PlaceToPayService;
use Illuminate\Console\Command;
use Exception;

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
        try {
            $placeToPayService = resolve(name: PlaceToPayService::class);

            $payments = Payment::query()
                ->with(relations: ['order'])
                ->whereProvider(provider: Provider::PLACE_TO_PAY)
                ->whereStatus(status: PaymentStatus::PENDING)
                ->select(columns: ['id', 'order_id', 'provider', 'data_provider', 'status'])
                ->get();

            $checkPaymentActions = (new PlaceToPayPaymentActionsFactory($placeToPayService))->make();

            foreach ($payments as $payment) {
                $status = $placeToPayService->getSession($payment->data_provider['requestId'])['status']['status'];

                foreach ($checkPaymentActions as $checkPaymentAction) {
                    $checkPaymentAction($status, $payment->order);
                }
            }

            $this->info(string: 'Scheduled task to check PlaceToPay payments statuses executed successfully');
        } catch (Exception $exception) {
            logger()->error(message: 'error during review pending payments of place to pay', context: [
                'module' => 'CheckPlaceToPayPayments.handle',
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTrace()
            ]);

            $this->error(string: 'Scheduled task to check PlaceToPay payments failed');
        }
    }
}
