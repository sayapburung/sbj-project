<!DOCTYPE html>
<html>
<head>
    <title>SPK - {{ $order->po_number }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 10mm;
        }
        
        body { 
            font-family: Arial, sans-serif; 
            margin: 0;
            padding: 10px;
            font-size: 9px;
        }
        
        .container {
            display: flex;
            gap: 15px;
            height: 100%;
        }
        
        .left-section {
            flex: 0 0 58%;
        }
        
        .right-section {
            flex: 0 0 40%;
            border-left: 2px solid #000;
            padding-left: 10px;
        }
        
        .header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 3px solid #000;
        }
        
        .logo {
            width: 100px;
            height: 50px;
            background-color: #000;
            margin-right: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FFD700;
            font-weight: bold;
            font-size: 12px;
            flex-direction: column;
            padding: 5px;
        }
        
        .logo-text {
            font-size: 14px;
            line-height: 1.2;
        }
        
        .logo-sub {
            font-size: 8px;
        }
        
        .header-title {
            flex: 1;
            text-align: center;
        }
        
        .header-title h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        
        .header-title h2 {
            margin: 3px 0 0 0;
            font-size: 20px;
            font-weight: bold;
        }
        
        .production-info {
            background-color: #90EE90;
            padding: 6px 8px;
            margin-bottom: 10px;
            font-weight: bold;
            font-size: 10px;
        }
        
        .production-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .badge {
            background-color: #D4A574;
            padding: 2px 8px;
            border-radius: 3px;
        }
        
        .main-content {
            margin-top: 15px;
        }
        
        .material-title {
            text-align: center;
            margin: 20px 0 15px 0;
        }
        
        .material-title h2 {
            color: #FF0000;
            font-size: 28px;
            margin: 0;
            font-weight: bold;
        }
        
        .material-title h3 {
            color: #0000FF;
            font-size: 24px;
            margin: 3px 0;
            font-weight: bold;
        }
        
        .images-section {
            display: flex;
            gap: 15px;
            margin-top: 15px;
            justify-content: center;
        }
        
        .image-placeholder {
            width: 120px;
            height: 280px;
            border: 2px solid #ccc;
            background-color: #f0f0f0;
        }
        
        .divisi-section {
            margin-bottom: 8px;
        }
        
        .divisi-header {
            background-color: #D4A574;
            padding: 4px 8px;
            font-weight: bold;
            margin-bottom: 3px;
            font-size: 10px;
        }
        
        .divisi-content {
            padding: 3px 8px;
        }
        
        .info-row {
            display: flex;
            padding: 2px 0;
            font-size: 9px;
        }
        
        .info-label {
            width: 90px;
            font-weight: normal;
        }
        
        .info-separator {
            width: 15px;
            text-align: center;
        }
        
        .info-value {
            flex: 1;
            font-weight: bold;
        }
        
        .press-button {
            background-color: #333;
            color: white;
            padding: 4px 8px;
            display: inline-block;
            font-size: 9px;
        }
        
        .material-prep {
            background-color: #2C3E50;
            color: white;
            padding: 5px 8px;
            margin-top: 10px;
            font-weight: bold;
            font-size: 10px;
        }
        
        .material-prep-content {
            padding: 5px 8px;
        }
        
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            padding-top: 8px;
            border-top: 2px solid #000;
            gap: 5px;
        }
        
        .signature-box {
            text-align: center;
            flex: 1;
            font-size: 8px;
        }
        
        .signature-name {
            margin-top: 40px;
            padding-top: 5px;
            border-top: 1px solid #000;
            display: inline-block;
            min-width: 60px;
            font-size: 8px;
        }
        
        .notes-section {
            margin-top: 15px;
            padding: 8px;
            border: 1px solid #000;
            min-height: 30px;
        }
        
        .notes-label {
            font-weight: bold;
            margin-bottom: 3px;
            font-size: 9px;
        }
    </style>
</head>
<body>
    <?php
        // Ambil data dari order_histories untuk tanggal desain selesai
        $designCompleteDate = \App\Models\OrderHistory::where('po_id', $order->id)
            ->where('to_stage', 'desain')
            ->where('to_status', 'selesai')
            ->first();
        
        // Ambil data dari stage_inputs untuk ukuran desain
        $designMeasurement = \App\Models\StageInput::where('po_id', $order->id)
            ->where('stage_name', 'meteran_desain')
            ->first();
    ?>
    
    <div class="container">
        <!-- LEFT SECTION -->
        <div class="left-section">
            <div class="header">
                <div class="logo">
                    <div class="logo-text">SUMBER JAYA</div>
                    <div class="logo-sub">TEXTILE PRINTING</div>
                </div>
                <div class="header-title">
                    <h1>WORK ASSIGNMENT</h1>
                    <h2>LATTER</h2>
                </div>
            </div>
            
            <div class="main-content">
                <div class="material-title">
                    <h2>BAHAN PIQUE</h2>
                    <h3>1785 CM</h3>
                </div>
                
                <div class="images-section">
                    <div class="image-placeholder"></div>
                    <div class="image-placeholder"></div>
                    <div class="image-placeholder"></div>
                </div>
            </div>
            
            <div class="notes-section">
                <div class="notes-label">KETERANGAN:</div>
            </div>
            
            <div class="signature-section">
                <div class="signature-box">
                    <div>ADMIN</div>
                    <div class="signature-name">( SELLA )</div>
                </div>
                <div class="signature-box">
                    <div>HEAD OF PRODUCTION</div>
                    <div class="signature-name">( )</div>
                </div>
                <div class="signature-box">
                    <div>DESIGN/SETTING</div>
                    <div class="signature-name">( RICKY )</div>
                </div>
                <div class="signature-box">
                    <div>PRINT</div>
                    <div class="signature-name">( )</div>
                </div>
                <div class="signature-box">
                    <div>PRESS</div>
                    <div class="signature-name">( )</div>
                </div>
                <div class="signature-box">
                    <div>CUTTING</div>
                    <div class="signature-name">( )</div>
                </div>
                <div class="signature-box">
                    <div>QUALITY CONTROL</div>
                    <div class="signature-name">( )</div>
                </div>
            </div>
        </div>
        
        <!-- RIGHT SECTION -->
        <div class="right-section">
            <div class="production-info">
                <div class="production-header">
                    <span>PRINT PRESS PRODUCTION</span>
                    <span class="badge">1</span>
                    <span>DIVISI ADMIN</span>
                </div>
                <div style="margin-top: 5px;">
                    <strong>DEADLINE : {{ $order->deadline ? $order->deadline->format('d F Y') : '-' }}</strong>
                </div>
                <div style="margin-top: 3px; font-size: 9px;">
                    <strong>KODE PO : {{ $order->po_number }}</strong>
                </div>
            </div>
            
            <div class="divisi-content">
                <div class="info-row">
                    <span class="info-label">Nama Customer</span>
                    <span class="info-separator">:</span>
                    <span class="info-value">{{ $order->nama_konsumen }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Order Date</span>
                    <span class="info-separator">:</span>
                    <span class="info-value">{{ $order->created_at->format('d F Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Deadline</span>
                    <span class="info-separator">:</span>
                    <span class="info-value" style="color: red;">{{ $order->deadline ? $order->deadline->format('d F Y') : '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Jenis Order</span>
                    <span class="info-separator">:</span>
                    <span class="info-value">{{ $order->jenis_po }}</span>
                </div>
            </div>
            
            <div class="divisi-section">
                <div class="divisi-header">
                    <span class="badge">2</span> DIVISI DESIGN
                </div>
                <div class="divisi-content">
                    <div class="info-row">
                        <span class="info-label">Tanggal</span>
                        <span class="info-separator">:</span>
                        <span class="info-value">{{ $designCompleteDate ? $designCompleteDate->created_at->format('d F Y') : '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Model</span>
                        <span class="info-separator">:</span>
                        <span class="info-value"></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Ukuran</span>
                        <span class="info-separator">:</span>
                        <span class="info-value">{{ $designMeasurement ? $designMeasurement->value : '-' }} Meter</span>
                    </div>
                </div>
            </div>
            
            <div class="divisi-section">
                <div class="divisi-header">
                    <span class="badge">3</span> DIVISI PRINT
                </div>
                <div class="divisi-content">
                    <div class="info-row">
                        <span class="info-label">Tanggal</span>
                        <span class="info-separator">:</span>
                        <span class="info-value"></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Nama File</span>
                        <span class="info-separator">:</span>
                        <span class="info-value">JERSEY BRANJANGAN BIRU</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Ukuran</span>
                        <span class="info-separator">:</span>
                        <span class="info-value"></span>
                    </div>
                </div>
            </div>
            
            <div class="divisi-section">
                <div class="divisi-header">
                    <span class="badge">4</span> DIVISI PRESS
                </div>
                <div class="divisi-content">
                    <div class="info-row">
                        <span class="info-label">Tanggal</span>
                        <span class="info-separator">:</span>
                        <span class="info-value"></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Jenis Bahan</span>
                        <span class="info-separator">:</span>
                        <span class="info-value"><span class="press-button">PIQUE SBJ</span></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Ukuran Meter</span>
                        <span class="info-separator">:</span>
                        <span class="info-value"></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Ukuran Kg</span>
                        <span class="info-separator">:</span>
                        <span class="info-value"></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Suhu</span>
                        <span class="info-separator">:</span>
                        <span class="info-value"></span>
                    </div>
                </div>
            </div>
            
            <div class="divisi-section">
                <div class="divisi-header">
                    <span class="badge">5</span> DIVISI CUTTING
                </div>
                <div class="divisi-content">
                    <div class="info-row">
                        <span class="info-label">Tanggal</span>
                        <span class="info-separator">:</span>
                        <span class="info-value"></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">QTY</span>
                        <span class="info-separator">:</span>
                        <span class="info-value"></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Meter</span>
                        <span class="info-separator">:</span>
                        <span class="info-value"></span>
                    </div>
                </div>
            </div>
            
            <div class="divisi-section">
                <div class="divisi-header">
                    <span class="badge">6</span> DIVISI QC
                </div>
                <div class="divisi-content">
                    <div class="info-row">
                        <span class="info-label">Lolos</span>
                        <span class="info-separator">:</span>
                        <span class="info-value"></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Reject</span>
                        <span class="info-separator">:</span>
                        <span class="info-value"></span>
                    </div>
                </div>
            </div>
            
            <div class="material-prep">
                PERSIAPAN BAHAN BAKU
            </div>
            <div class="material-prep-content">
                <div class="info-row">
                    <span class="info-label">Kain ( KG)</span>
                    <span class="info-separator">:</span>
                    <span class="info-value">6,16</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Kertas (Meter)</span>
                    <span class="info-separator">:</span>
                    <span class="info-value">17,85</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tinta (CC)</span>
                    <span class="info-separator">:</span>
                    <span class="info-value">160,65</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>