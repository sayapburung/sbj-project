<!DOCTYPE html>
<html>
<head>
    <title>SPK - {{ $order->po_number }}</title>

    <style>
        @page {
            size: A4 landscape;
            margin: 8mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
        }

        table.layout {
            width: 100%;
            border-collapse: collapse;
        }

        td.left {
            width: 65%;
            vertical-align: top;
            text-align: center;
        }

        td.right {
            width: 35%;
            vertical-align: top;
            border-left: 2px solid black;
            padding-left: 10px;
        }

        /* HEADER */
        .header {
            border-bottom: 3px solid black;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        .logo {
            font-size: 20px;
            font-weight: bold;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
        }

        /* PREVIEW IMAGE BOX */
        .preview-box {
            width: 120px;
            height: 260px;
            border: 1px solid black;
            margin: 5px;
            background: #f2f2f2;
            text-align: center;
        }

        .preview-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* SECTION DIVISI */
        .section-title {
            background: #f1c40f;
            font-weight: bold;
            padding: 4px;
            margin-top: 8px;
        }

        .row {
            width: 100%;
            border-bottom: 1px dotted #aaa;
            padding: 3px 0;
        }

        .label {
            display: inline-block;
            width: 110px;
            font-weight: bold;
        }

        /* SIGNATURE */
        .signature-table {
            width: 100%;
            margin-top: 15px;
            border-top: 2px solid black;
        }

        .signature-table td {
            text-align: center;
            font-size: 9px;
            padding-top: 10px;
        }

        .sign-line {
            margin-top: 30px;
            border-top: 1px solid black;
            width: 70px;
            display: inline-block;
        }
    </style>
</head>

<body>

@php
    use App\Models\PoImage;

    // Ambil max 3 gambar berdasarkan po_id
    $images = PoImage::where('po_id', $order->id)
        ->orderBy('id', 'asc')
        ->take(3)
        ->get();
@endphp

<?php
    use App\Models\StageInput;

    $desain = StageInput::where('po_id', $order->id)
        ->where('stage_name', 'desain')
        ->first();

    $press = StageInput::where('po_id', $order->id)
        ->where('stage_name', 'press')
        ->first();

    $printing = StageInput::where('po_id', $order->id)
        ->where('stage_name', 'printing')
        ->first();
?>

<table class="layout">
    <tr>

        <!-- ================= LEFT ================= -->
        <td class="left">

            <!-- HEADER -->
            <div class="header">
                <div class="logo">SBJ PRINTEX</div>
                <div class="title">WORK ASSIGNMENT LETTER</div>
            </div>

            <!-- CUSTOMER -->
            <h2>{{ $order->nama_konsumen }}</h2>

            <!-- MATERIAL -->
            <p>
                BAHAN
                <span style="color:red;font-weight:bold;">
                    {{ $order->jenis_bahan }}
                </span>
            </p>

            <p style="color:red;font-size:18px;font-weight:bold;">
                {{ ($desain->meteran_desain ?? 0) * 100 }} CM
            </p>

            <!-- ================= PREVIEW 3 GAMBAR ================= -->
            <table style="margin:auto; margin-top:15px;">
                <tr>

                    @for($i = 0; $i < 3; $i++)

                        <td>
                            <div class="preview-box">

                                @if(isset($images[$i]))
                                    <img src="{{ public_path('storage/'.$images[$i]->image_path) }}">
                                @else
                                    <p style="padding-top:120px;font-size:11px;">
                                        NO IMAGE
                                    </p>
                                @endif

                            </div>
                        </td>

                    @endfor

                </tr>
            </table>

            <!-- NOTES -->
            <p style="margin-top:10px;">
                <b>KETERANGAN:</b>
                {{ $order->keterangan ?? '-' }}
            </p>

            <!-- SIGNATURE -->
            <table class="signature-table">
                <tr>
                    <td>ADMIN<br><br><br><br><br><span class="sign-line"></span></td>
                    <td>HEAD<br><br><br><br><br><span class="sign-line"></span></td>
                    <td>DESIGN<br><br><br><br><br><span class="sign-line"></span></td>
                    <td>PRINT<br><br><br><br><br><span class="sign-line"></span></td>
                    <td>PRESS<br><br><br><br><br><span class="sign-line"></span></td>
                    <td>CUTTING<br><br><br><br><br><span class="sign-line"></span></td>
                    <td>QC<br><br><br><br><br><span class="sign-line"></span></td>
                </tr>
            </table>

        </td>

        <!-- ================= RIGHT ================= -->
        <td class="right">

            <!-- HEADER INFO -->
            <div style="background:#90ee90;padding:6px;font-weight:bold;">
                PRINT PRESS PRODUCTION<br>

                Deadline:
                <span style="color:red;">
                    {{ $order->deadline ? $order->deadline->format('d F Y') : '-' }}
                </span><br><br>

                Kode PO:
                <span style="background:red;color:white;padding:3px 8px;">
                    {{ $order->po_number }}
                </span>
            </div>

            <!-- ================= DIVISI ADMIN ================= -->
            <div class="section-title">1. DIVISI ADMIN</div>
            <div class="row"><span class="label">Customer</span>: {{ $order->nama_konsumen }}</div>
            <div class="row"><span class="label">Order Date</span>: {{ $order->created_at->format('d F Y') }}</div>
            <div class="row"><span class="label">Deadline</span>: <span style="color:red;">{{ $order->deadline->format('d F Y') }}</span></div>
            <div class="row">
                <span class="label">Jenis Order</span>:
                <span style="background:black;color:white;padding:3px 8px;">
                    {{ $order->jenis_po }}
                </span>
            </div>

            <!-- ================= DIVISI DESIGN ================= -->
            <div class="section-title">2. DIVISI DESIGN</div>
            <div class="row"><span class="label">Tanggal</span>: </div>
            <div class="row"><span class="label">Model</span>: </div>
            <div class="row"><span class="label">Ukuran</span>:
            {{ $desain->meteran_desain ?? '-' }} Meter
            </div>

            <!-- ================= DIVISI PRINT ================= -->
            <div class="section-title">3. DIVISI PRINT</div>
            <div class="row"><span class="label">Tanggal</span>: </div>
            <div class="row"><span class="label">Nama File</span>: {{ $order->nama_file ?? '-' }}</div>
            <div class="row"><span class="label">Ukuran</span>:
            {{ $printing->meteran_printing ?? '-' }} Meter
            </div>

            <!-- ================= DIVISI PRESS ================= -->
            <div class="section-title">4. DIVISI PRESS</div>
            <div class="row"><span class="label">Tanggal</span>: </div>
            <div class="row">
                <span class="label">Jenis Bahan</span>:
                <span style="background:black;color:white;padding:3px 8px;">
                    {{ $order->jenis_bahan }}
                </span>
            </div>
            <div class="row"><span class="label">Ukuran</span>:
            {{ $press->meteran_press ?? '-' }} Meter
            </div>
            <div class="row"><span class="label">Berat</span>: </div>
            <div class="row"><span class="label">Suhu dan Kecepatan</span>: </div>

            <!-- ================= DIVISI CUTTING ================= -->
            <div class="section-title">5. DIVISI CUTTING</div>
            <div class="row"><span class="label">Tanggal</span>: </div>
            <div class="row"><span class="label">QTY</span>: {{ $order->qty ?? '-' }}</div>
            <div class="row"><span class="label">Meter</span>: </div>

            <!-- ================= DIVISI QC ================= -->
            <div class="section-title">6. DIVISI QC</div>
            <div class="row"><span class="label">Lolos</span>: </div>
            <div class="row"><span class="label">Reject</span>: </div>

        <div class="section-title" style="background:#2c3e50;color:white;">
            PERSIAPAN BAHAN BAKU
        </div>

        <div class="row">
            <span class="label">Kain (KG)</span>:
        </div>
        <div class="row">
            <span class="label">Kertas (Meter)</span>:
        </div>

        </td>

    </tr>
</table>

</body>
</html>
