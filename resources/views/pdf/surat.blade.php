<!DOCTYPE html>
<html>
<head>
    <title>{{ $surat->jenis_surat }}</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 11pt; line-height: 1.15; margin: 0; padding: 0; }
        .kop-surat { width: 100%; border-bottom: 3px double #000; margin-bottom: 15px; padding-bottom: 8px; }
        .kop-table { width: 100%; border-collapse: collapse; }
        .kop-table td { vertical-align: middle; padding: 0; }
        .logo-cell { width: 15%; text-align: center; }
        .logo-img { max-height: 70px; width: auto; }
        .text-cell { width: 70%; text-align: center; }
        .kop-header1 { font-size: 12pt; font-weight: bold; text-transform: uppercase; margin: 0; }
        .kop-header2 { font-size: 14pt; font-weight: bold; text-transform: uppercase; margin: 0; }
        .kop-header3 { font-size: 12pt; font-weight: bold; margin: 0; }
        .kop-text { font-size: 10pt; margin: 0; }
        .content { /* text-align follows Quill classes */ }
        
        /* Quill text-align classes - Essential */
        .ql-align-center { text-align: center; }
        .ql-align-right { text-align: right; }
        .ql-align-justify { text-align: justify; }
        
        /* Quill Indentation Classes */
        .ql-indent-1 { padding-left: 3em; }
        .ql-indent-2 { padding-left: 6em; }
        .ql-indent-3 { padding-left: 9em; }
        .ql-indent-4 { padding-left: 12em; }
        .ql-indent-5 { padding-left: 15em; }
        .ql-indent-6 { padding-left: 18em; }
        .ql-indent-7 { padding-left: 21em; }
        .ql-indent-8 { padding-left: 24em; }

        /* Paragraph styling - Proper spacing for letters */
        .content p { margin-top: 0; margin-bottom: 10px; } 

        .content h1, .content h2, .content h3, .content h4, .content h5, .content h6 {
            margin-top: 0.5em;
            margin-bottom: 0.5em;
            font-weight: bold;
        }
    </style>
</head>
<body>
    @if($surat->use_system_header)
    <div class="kop-surat">
        <table class="kop-table">
            <tr>
                @if($lembaga->logo_kiri)
                <td class="logo-cell">
                    <img src="{{ public_path('storage/' . $lembaga->logo_kiri) }}" class="logo-img" alt="Logo Kiri">
                </td>
                @else
                <td class="logo-cell"></td>
                @endif

                <td class="text-cell">
                    @if($lembaga->kop_baris1) <h3 class="kop-header1">{{ $lembaga->kop_baris1 }}</h3> @endif
                    @if($lembaga->kop_baris2) <h2 class="kop-header2">{{ $lembaga->kop_baris2 }}</h2> @endif
                    @if($lembaga->kop_baris3) <h3 class="kop-header3">{{ $lembaga->kop_baris3 }}</h3> @endif
                    @if($lembaga->kop_baris4) <p class="kop-text">{{ $lembaga->kop_baris4 }}</p> @endif
                    @if($lembaga->kop_baris5) <p class="kop-text">{{ $lembaga->kop_baris5 }}</p> @endif
                </td>

                @if($lembaga->logo_kanan)
                <td class="logo-cell">
                    <img src="{{ public_path('storage/' . $lembaga->logo_kanan) }}" class="logo-img" alt="Logo Kanan">
                </td>
                @else
                <td class="logo-cell"></td>
                @endif
            </tr>
        </table>
    </div>
    @endif

    <div class="content">
        {!! $surat->isi_surat !!}
    </div>
</body>
</html>
