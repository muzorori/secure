<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Paynow\Payments\Paynow;

class PaynowController extends Controller
{
    protected const PRODUCT_NAME = 'SMS-Pop Credit Top-Up';

    protected const PRODUCT_PRICE = 2.00;

    protected function client(): Paynow
    {
        return new Paynow(
            config('services.paynow.integration_id'),
            config('services.paynow.integration_key'),
            config('services.paynow.return_url'),
            config('services.paynow.result_url'),
        );
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $paynow = $this->client();

        $reference = 'SMSPOP-'.strtoupper(uniqid());

        $payment = $paynow->createPayment($reference, $validated['email']);
        $payment->add(self::PRODUCT_NAME, self::PRODUCT_PRICE);

        $response = $paynow->send($payment);

        if (! $response->success()) {
            Log::error('Paynow checkout failed', ['errors' => $response->errors(false)]);

            return response()->json([
                'message' => 'Unable to initiate payment. Please try again.',
            ], 422);
        }

        session([
            "paynow.order.{$reference}" => [
                'email' => $validated['email'],
                'poll_url' => $response->pollUrl(),
                'description' => self::PRODUCT_NAME,
                'amount' => self::PRODUCT_PRICE,
                'created_at' => now()->toIso8601String(),
            ],
            'paynow.last_reference' => $reference,
        ]);

        return response()->json([
            'redirect_url' => $response->redirectUrl(),
            'reference' => $reference,
        ]);
    }

    public function return(Request $request)
    {
        $reference = session('paynow.last_reference');
        $order = $reference ? session("paynow.order.{$reference}") : null;

        if (! $order) {
            return view('paynow.return', ['paid' => false]);
        }

        $status = $this->client()->pollTransaction($order['poll_url']);

        if (! $status->paid()) {
            return view('paynow.return', ['paid' => false]);
        }

        $order['paynow_reference'] = $status->paynowReference();
        $order['paid_at'] = now()->toIso8601String();

        session(["paynow.paid_order.{$reference}" => $order]);

        $downloadUrl = URL::temporarySignedRoute(
            'paynow.download',
            now()->addHours(24),
            ['reference' => $reference],
        );

        return view('paynow.return', [
            'paid' => true,
            'downloadUrl' => $downloadUrl,
            'productName' => self::PRODUCT_NAME,
        ]);
    }

    public function download(Request $request, string $reference)
    {
        $order = session("paynow.paid_order.{$reference}");

        abort_unless($order, 403);

        $logoPath = public_path('images/smspop-logo.png');
        $logoData = file_exists($logoPath)
            ? 'data:image/png;base64,'.base64_encode(file_get_contents($logoPath))
            : null;

        $html = view('pdf.receipt', [
            'reference' => $reference,
            'order' => $order,
            'logoData' => $logoData,
        ])->render();

        $options = new Options();
        $options->set('isRemoteEnabled', false);
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="SMSPop-Receipt-'.$reference.'.pdf"',
        ]);
    }

    public function result(Request $request)
    {
        Log::info('Paynow result callback received', $request->all());

        return response('OK', 200);
    }
}
