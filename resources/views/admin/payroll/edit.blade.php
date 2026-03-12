@extends('layouts.admin')
@section('title', __('admin.edit_payroll'))
@push('styles')
    <style>
        .ph {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            gap: 16px;
            flex-wrap: wrap
        }

        .ph h1 {
            font-family: 'Syne', sans-serif;
            font-size: 22px;
            font-weight: 800
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            border-radius: 10px;
            padding: 9px 18px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all .2s;
            font-family: inherit;
            border: none
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent), #8b7eff);
            color: #fff
        }

        .btn-primary:hover {
            opacity: .9
        }

        .btn-ghost {
            background: var(--surface2);
            color: var(--text);
            border: 1.5px solid var(--border)
        }

        .btn-ghost:hover {
            border-color: var(--accent);
            color: var(--accent)
        }

        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 16px
        }

        .card-title {
            font-family: 'Syne', sans-serif;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 9px
        }

        .card-title i {
            color: var(--accent)
        }

        .fg {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px
        }

        @media(max-width:600px) {
            .fg {
                grid-template-columns: 1fr
            }
        }

        .fl {
            font-size: 11px;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .6px;
            display: block;
            margin-bottom: 6px
        }

        .fin {
            background: var(--surface2);
            border: 1.5px solid var(--border);
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 13px;
            color: var(--text);
            font-family: inherit;
            outline: none;
            transition: border-color .2s;
            width: 100%;
            box-sizing: border-box
        }

        .fin:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(108, 99, 255, .12)
        }

        textarea.fin {
            min-height: 80px;
            resize: vertical
        }

        .read-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 9px 14px;
            background: var(--surface2);
            border-radius: 10px;
            margin-bottom: 8px;
            font-size: 13px
        }

        .read-lbl {
            color: var(--muted)
        }

        .read-val {
            font-weight: 700
        }

        /* Live preview */
        .preview-net {
            font-family: 'Syne', sans-serif;
            font-size: 36px;
            font-weight: 800;
            color: var(--accent);
            text-align: center;
            margin: 16px 0 4px
        }

        .preview-lbl {
            text-align: center;
            font-size: 12px;
            color: var(--muted)
        }
    </style>
@endpush
@section('content')

    <div class="ph">
        <div>
            <h1>{{ __('admin.edit_payroll') }}</h1>
            <p style="font-size:13px;color:var(--muted);margin-top:3px">{{ $payroll->employee->name }} —
                {{ $payroll->month_name }}</p>
        </div>
        <a href="{{ route('admin.payroll.show', $payroll) }}" class="btn btn-ghost">
            <i class="fas fa-arrow-right"></i> {{ __('admin.back') }}
        </a>
    </div>

    <form method="POST" action="{{ route('admin.payroll.update', $payroll) }}">
        @csrf @method('PUT')

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;align-items:start">
            {{-- Left: read-only info --}}
            <div>
                <div class="card">
                    <div class="card-title"><i class="fas fa-info-circle"></i> {{ __('admin.auto_calculated') }}</div>
                    <div class="read-row">
                        <span class="read-lbl">{{ __('admin.basic_salary') }}</span>
                        <span class="read-val">{{ number_format($payroll->basic_salary, 2) }} JD</span>
                    </div>
                    <div class="read-row">
                        <span class="read-lbl">{{ __('admin.commissions') }}</span>
                        <span class="read-val" style="color:#43e97b">+{{ number_format($payroll->commissions_amount, 2) }}
                            JD</span>
                    </div>
                    <div class="read-row">
                        <span class="read-lbl">{{ __('admin.overtime') }} ({{ $payroll->overtime_hours }}h)</span>
                        <span class="read-val" style="color:#43e97b">+{{ number_format($payroll->overtime_amount, 2) }}
                            JD</span>
                    </div>
                    <div class="read-row">
                        <span class="read-lbl">{{ __('admin.absence_deduction') }} ({{ $payroll->absent_days }}d)</span>
                        <span class="read-val"
                            style="color:var(--accent2)">-{{ number_format($payroll->deduction_absence, 2) }} JD</span>
                    </div>
                    <div class="read-row">
                        <span class="read-lbl">{{ __('admin.late_deduction') }} ({{ $payroll->late_count }}x)</span>
                        <span class="read-val"
                            style="color:var(--accent2)">-{{ number_format($payroll->deduction_late, 2) }} JD</span>
                    </div>
                </div>
            </div>

            {{-- Right: editable + preview --}}
            <div>
                <div class="card">
                    <div class="card-title"><i class="fas fa-edit"></i> {{ __('admin.adjustments') }}</div>
                    <div class="fg" style="margin-bottom:16px">
                        <div>
                            <label class="fl">{{ __('admin.bonuses') }} (JD)</label>
                            <input type="number" name="bonuses" id="bonuses" class="fin" step="0.01" min="0"
                                value="{{ old('bonuses', $payroll->bonuses) }}" oninput="calcNet()">
                        </div>
                        <div>
                            <label class="fl">{{ __('admin.manual_deduction') }} (JD)</label>
                            <input type="number" name="deduction_manual" id="ded_manual" class="fin" step="0.01"
                                min="0" value="{{ old('deduction_manual', $payroll->deduction_manual) }}"
                                oninput="calcNet()">
                        </div>
                    </div>
                    <div style="margin-bottom:16px">
                        <label class="fl">{{ __('admin.deduction_reason') }}</label>
                        <input type="text" name="deduction_manual_note" class="fin"
                            value="{{ old('deduction_manual_note', $payroll->deduction_manual_note) }}"
                            placeholder="{{ __('admin.deduction_reason_hint') }}">
                    </div>
                    <div>
                        <label class="fl">{{ __('admin.notes') }}</label>
                        <textarea name="notes" class="fin">{{ old('notes', $payroll->notes) }}</textarea>
                    </div>
                </div>

                {{-- Live net preview --}}
                <div class="card"
                    style="background:linear-gradient(135deg,rgba(108,99,255,.08),rgba(139,126,255,.04));border-color:rgba(108,99,255,.2)">
                    <div class="preview-net" id="net-preview">{{ number_format($payroll->net_salary, 2) }}</div>
                    <div class="preview-lbl">JD — {{ __('admin.net_salary_preview') }}</div>
                </div>

                <div style="display:flex;justify-content:flex-end;gap:10px">
                    <a href="{{ route('admin.payroll.show', $payroll) }}"
                        class="btn btn-ghost">{{ __('admin.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ __('admin.save_changes') }}
                    </button>
                </div>
            </div>
        </div>
    </form>

@endsection
@push('scripts')
    <script>
        const BASE = {{ $payroll->basic_salary }};
        const COMM = {{ $payroll->commissions_amount }};
        const DED_ABS = {{ $payroll->deduction_absence }};
        const DED_LATE = {{ $payroll->deduction_late }};

        function calcNet() {
            const bonuses = parseFloat(document.getElementById('bonuses').value) || 0;
            const manual = parseFloat(document.getElementById('ded_manual').value) || 0;
            const net = BASE + COMM + bonuses - DED_ABS - DED_LATE - manual;
            document.getElementById('net-preview').textContent = net.toFixed(2);
            document.getElementById('net-preview').style.color = net < 0 ? 'var(--accent2)' : 'var(--accent)';
        }
    </script>
@endpush
