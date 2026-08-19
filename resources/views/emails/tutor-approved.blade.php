<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pendaftaran Tutor Diterima</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Inter', Arial, sans-serif; background-color: #f1f5f9; color: #334155;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #f1f5f9; padding: 20px 0;">
        <tr>
            <td align="center">
                <table width="600" border="0" cellspacing="0" cellpadding="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                    <!-- Header Banner -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #0d6b46 0%, #064e3b 100%); padding: 40px 30px; text-align: center; color: white;">
                            <h1 style="margin: 0; font-size: 24px; font-weight: 700; letter-spacing: -0.5px;">UPT PJJ UIN Siber Cirebon</h1>
                            <p style="margin: 5px 0 0; font-size: 14px; opacity: 0.9;">Hasil Seleksi Calon Tutor Online</p>
                        </td>
                    </tr>
                    
                    <!-- Content Body -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <h2 style="margin-top: 0; color: #0d6b46; font-size: 20px;">Selamat, Anda Diterima!</h2>
                            <p style="font-size: 15px; line-height: 1.6; color: #475569;">
                                Yth. <strong>{{ $tutor->full_name_with_titles }}</strong>,<br><br>
                                Berdasarkan hasil verifikasi berkas dan seleksi administrasi, dengan ini kami sampaikan bahwa Anda dinyatakan **DITERIMA** sebagai tutor di UPT PJJ UIN Siber Syekh Nurjati Cirebon.
                            </p>
                            
                            <p style="font-size: 15px; line-height: 1.6; color: #475569;">
                                Anda ditugaskan pada detail program studi dan mata kuliah berikut:
                            </p>
                            
                            @php
                                $course = $tutor->courses->first();
                                $studyProgram = $course ? $course->studyProgram : null;
                                $faculty = $studyProgram ? $studyProgram->faculty : null;
                            @endphp

                            <!-- Details Card -->
                            <div style="background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 6px; padding: 20px; margin: 25px 0; font-size: 14px;">
                                <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                    <tr>
                                        <td width="120" style="color: #166534; font-weight: 600;">Nomor Registrasi:</td>
                                        <td style="color: #0f172a; font-weight: 700;">{{ $tutor->registration_number }}</td>
                                    </tr>
                                    <tr>
                                        <td style="color: #166534; font-weight: 600;">Fakultas:</td>
                                        <td style="color: #0f172a; font-weight: 700;">{{ $faculty ? $faculty->name : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="color: #166534; font-weight: 600;">Program Studi:</td>
                                        <td style="color: #0f172a; font-weight: 700;">{{ $studyProgram ? $studyProgram->name : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="color: #166534; font-weight: 600;">Mata Kuliah:</td>
                                        <td style="color: #0f172a; font-weight: 700;">
                                            {{ $course ? $course->code : '-' }} - {{ $course ? $course->name : '-' }} ({{ $course ? $course->credits : '-' }} SKS)
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            
                            <p style="font-size: 15px; line-height: 1.6; color: #475569; margin-bottom: 30px;">
                                Langkah selanjutnya terkait penandatanganan kontrak mengajar, pembekalan tutor, serta koordinasi perkuliahan akan diinformasikan kemudian. Silakan masuk ke dashboard Anda untuk melihat status pendaftaran terbaru Anda.
                            </p>
                            
                            <!-- CTA Button -->
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center">
                                        <a href="{{ route('login') }}" style="background-color: #0d6b46; color: #ffffff; text-decoration: none; padding: 12px 30px; font-weight: 600; border-radius: 6px; display: inline-block; font-size: 15px; box-shadow: 0 4px 6px -1px rgba(13, 107, 70, 0.2);">
                                            Masuk ke Dashboard Tutor &rarr;
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; border-top: 1px solid #f1f5f9; padding: 20px 30px; text-align: center; font-size: 12px; color: #94a3b8;">
                            <p style="margin: 0 0 5px;">Ini adalah email otomatis dari sistem pendaftaran UPT PJJ UIN Siber Cirebon.</p>
                            <p style="margin: 0;">&copy; {{ date('Y') }} UPT PJJ UIN Siber Syekh Nurjati Cirebon. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
