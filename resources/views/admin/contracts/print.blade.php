<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale()==='ar'?'rtl':'ltr' }}">
<head>
<meta charset="UTF-8">
<title>{{ __('admin.receipt') }} — {{ $receipt->receipt_number }}</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box }
  body { font-family: 'Segoe UI', Arial, sans-serif; background: #fff; color: #111; font-size: 14px; padding: 40px }
  .receipt-wrapper { max-width: 680px; margin: 0 auto; border: 2px solid #111; border-radius: 12px; overflow: hidden }

  /* Header */
  .receipt-header { background: #111; color: #fff; padding: 28px 32px; display: flex; justify-content: space-between; align-items: flex-start }
  .company-name { font-size: 22px; font-weight: 800; letter-spacing: .5px }
  .company-sub { font-size: 12px; opacity: .65; margin-top: 4px }
  .receipt-title { text-align: end }
  .receipt-title h2 { font-size: 28px; font-weight: 900; letter-spacing: 2px; text-transform: uppercase }
  .receipt-title .rcp-num { font-size: 14px; opacity: .75; margin-top: 4px }

  /* Body */
  .receipt-body { padding: 28px 32px }

  /* Meta row */
  .meta-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px }
  .meta-block label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .8px; color: #666; display: block; margin-bottom: 4px }
  .meta-block .val { font-size: 14px; font-weight: 600 }

  /* Amount box */
  .amount-box { border: 2px dashed #111; border-radius: 10px; padding: 20px 28px; text-align: center; margin-bottom: 24px }
  .amount-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #666; margin-bottom: 6px }
  .amount-value { font-size: 40px; font-weight: 900; letter-spacing: -1px }
  .amount-words { font-size: 12px; color: #555; margin-top: 6px; font-style: italic }

  /* Details table */
  .detail-table { width: 100%; border-collapse: collapse; margin-bottom: 24px }
  .detail-table td { padding: 9px 12px; border-bottom: 1px solid #eee; font-size: 13px }
  .detail-table td:first-child { color: #666; font-weight: 500; width: 44% }
  .detail-table td:last-child { font-weight: 700 }
  .detail-table tr:last-child td { border-bottom: none }

  /* Signatures */
  .sig-row { display: flex; justify-content: space-between; margin-top: 32px; padding-top: 20px; border-top: 1px solid #ddd }
  .sig-box { text-align: center; width: 44% }
  .sig-line { border-top: 1px solid #333; margin-top: 40px; padding-top: 6px; font-size: 12px; color: #666 }

  /* Footer */
  .receipt-footer { background: #f5f5f5; padding: 14px 32px; display: flex; justify-content: space-between; align-items: center; font-size: 11px; color: #888 }
  .stamp-area { width: 90px; height: 90px; border: 2px dashed #ccc; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #ccc; text-align: center; margin-inline-start: auto }

  @media print {
    body { padding: 0 }
    .no-print { display: none }
    .receipt-wrapper { border-radius: 0; border: 1px solid #333 }
  }
</style>
</head>
<body>

{{-- Print button --}}
<div class="no-print" style="max-width:680px;margin:0 auto 20px;display:flex;gap:10px">
    <button onclick="window.print()"
            style="background:#111;color:#fff;border:none;border-radius:8px;padding:10px 22px;font-size:13px;font-weight:700;cursor:pointer">
        🖨️ {{ __('admin.print') }}
    </button>
    <a href="{{ route('admin.contracts.show', $receipt->contractPayment->contract) }}"
       style="background:#f0f0f0;color:#111;border:none;border-radius:8px;padding:10px 18px;font-size:13px;font-weight:700;cursor:pointer;text-decoration:none">
        ← {{ __('admin.back') }}
    </a>
</div>

<div class="receipt-wrapper">

    {{-- Header --}}
    <div class="receipt-header">
        <div>
            <div class="company-name">{{ config('app.name') }}</div>
            <div class="company-sub">{{ __('admin.receipt_header_sub') }}</div>
        </div>
        <div class="receipt-title">
            <h2>{{ __('admin.receipt') }}</h2>
            <div class="rcp-num">{{ $receipt->receipt_number }}</div>
        </div>
    </div>

    <div class="receipt-body">

        {{-- Meta --}}
        <div class="meta-grid">
            <div class="meta-block">
                <label>{{ __('admin.client') }}</label>
                <div class="val">{{ $receipt->contractPayment->contract->client->name }}</div>
                @if($receipt->contractPayment->contract->client->contact_person)
                <div style="font-size:12px;color:#666;margin-top:2px">
                    {{ $receipt->contractPayment->contract->client->contact_person }}
                </div>
                @endif
            </div>
            <div class="meta-block" style="text-align:end">
                <label>{{ __('admin.receipt_date') }}</label>
                <div class="val">{{ $receipt->receipt_date->format('d / m / Y') }}</div>
                <div style="font-size:12px;color:#666;margin-top:4px">
                    {{ __('admin.contract') }}: {{ $receipt->contractPayment->contract->contract_number }}
                </div>
            </div>
        </div>

        {{-- Amount --}}
        <div class="amount-box">
            <div class="amount-label">{{ __('admin.received_amount') }}</div>
            <div class="amount-value">${{ number_format($receipt->amount, 2) }}</div>
            {{-- بمكن تضيف مكتبة تحويل الأرقام لحروف لاحقاً --}}
        </div>

        {{-- Details --}}
        <table class="detail-table">
            <tr>
                <td>{{ __('admin.installment_label') }}</td>
                <td>{{ $receipt->contractPayment->label ?: __('admin.installment').' '.$receipt->contractPayment->payment_number }}</td>
            </tr>
            <tr>
                <td>{{ __('admin.payment_method') }}</td>
                <td>{{ $receipt->payment_method ? __('admin.'.$receipt->payment_method) : '—' }}</td>
            </tr>
            @if($receipt->reference)
            <tr>
                <td>{{ __('admin.reference') }}</td>
                <td>{{ $receipt->reference }}</td>
            </tr>
            @endif
            <tr>
                <td>{{ __('admin.received_by') }}</td>
                <td>{{ $receipt->issuedBy->name }}</td>
            </tr>
            @if($receipt->notes)
            <tr>
                <td>{{ __('admin.notes') }}</td>
                <td>{{ $receipt->notes }}</td>
            </tr>
            @endif
        </table>

        {{-- Signatures + Stamp --}}
        <div class="sig-row">
            <div class="sig-box">
                <div class="sig-line">{{ __('admin.client_signature') }}</div>
            </div>
            <div class="stamp-area">{{ __('admin.stamp') }}</div>
            <div class="sig-box">
                <div class="sig-line">{{ __('admin.received_by') }}: {{ $receipt->issuedBy->name }}</div>
            </div>
        </div>

    </div>{{-- /.receipt-body --}}

    <div class="receipt-footer">
        <span>{{ __('admin.receipt_footer_note') }}</span>
        <span>{{ now()->format('d/m/Y H:i') }}</span>
    </div>
</div>

</body>
</html>