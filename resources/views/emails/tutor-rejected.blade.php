<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Informasi Pendaftaran Tutor</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Inter', Arial, sans-serif; background-color: #f1f5f9; color: #334155;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #f1f5f9; padding: 20px 0;">
        <tr>
            <td align="center">
                <table width="600" border="0" cellspacing="0" cellpadding="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                    <!-- Header Banner -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #b91c1c 0%, #7f1d1d 100%); padding: 40px 30px; text-align: center; color: white;">
                            <h1 style="margin: 0; font-size: 24px; font-weight: 700; letter-spacing: -0.5px;">UPT PJJ UIN Siber Cirebon</h1>
                            <p style="margin: 5px 0 0; font-size: 14px; opacity: 0.9;">Hasil Seleksi Calon Tutor Online</p>
                        </td>
                    </tr>
                    
                    <!-- Content Body -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <h2 style="margin-top: 0; color: #b91c1c; font-size: 20px;">Mohon Maaf, Pendaftaran Belum Diterima</h2>
                            <p style="font-size: 15px; line-height: 1.6; color: #475569;">
                                Yth. <strong>{{ $tutor->full_name_with_titles }}</strong>,<br><br>
                                Terima kasih atas partisipasi dan ketertarikan Anda untuk bergabung sebagai tutor di UPT PJJ UIN Siber Syekh Nurjati Cirebon.
                            </p>
                            
                            <p style="font-size: 15px; line-height: 1.6; color: #475569;">
                                Setelah melakukan peninjauan dan verifikasi terhadap berkas pendaftaran Anda dengan Nomor Registrasi <strong>{{ $tutor->registration_number }}</strong>, kami mohon maaf karena Anda dinyatakan **belum lolos** seleksi administrasi pada periode ini karena alasan berikut:
                            </p>
                            
                            <!-- Rejection Reason Card -->
                            <div style="background-color: #fef2f2; border: 1px solid #fee2e2; border-radius: 6px; padding: 20px; margin: 25px 0; font-size: 14px;">
                                <strong style="color: #991b1b; display: block; margin-bottom: 8px;">Alasan Penolakan:</strong>
                                <span style="color: #374151; font-style: italic; line-height: 1.5;">
                                    "{{ $tutor->rejection_reason }}"
                                </span>
                            </div>
                            
                            <p style="font-size: 15px; line-height: 1.6; color: #475569; margin-bottom: 30px;">
                                Anda dapat memperbarui data biodata, instansi, riwayat pendidikan, berkas dokumen, atau mata kuliah yang Anda pilih, dan mengirimkan kembali berkas pendaftaran Anda jika periode rekrutmen masih dibuka. Silakan masuk ke dashboard Anda untuk melakukan perbaikan.
                            </p>
                            
                            <!-- CTA Button -->
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center">
                                        <a href="{{ route('login') }}" style="background-color: #b91c1c; color: #ffffff; text-decoration: none; padding: 12px 30px; font-weight: 600; border-radius: 6px; display: inline-block; font-size: 15px; box-shadow: 0 4px 6px -1px rgba(185, 28, 28, 0.2);">
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
