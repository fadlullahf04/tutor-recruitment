<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password Akun Tutor</title>
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
                            <p style="margin: 5px 0 0; font-size: 14px; opacity: 0.9;">Reset Password Akun Tutor</p>
                        </td>
                    </tr>
                    
                    <!-- Content Body -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <h2 style="margin-top: 0; color: #0f172a; font-size: 20px;">Permintaan Reset Password Anda Berhasil</h2>
                            <p style="font-size: 15px; line-height: 1.6; color: #475569;">
                                Yth. <strong>{{ $user->name }}</strong>,<br><br>
                                Kami telah menerima permintaan untuk mereset kata sandi akun pendaftaran tutor online Anda. Password akun Anda telah diatur ulang menjadi:
                            </p>
                            
                            <!-- Credentials Card -->
                            <div style="background-color: #fffbeb; border: 1px solid #fef3c7; border-radius: 6px; padding: 20px; margin: 25px 0; font-size: 14px; text-align: center;">
                                <div style="color: #d97706; font-size: 13px; font-weight: 600; text-transform: uppercase; margin-bottom: 5px;">Password Baru Sementara</div>
                                <div style="color: #b45309; font-weight: 700; font-family: monospace; font-size: 20px; letter-spacing: 1px;">{{ $rawPassword }}</div>
                            </div>
                            
                            <p style="font-size: 15px; line-height: 1.6; color: #475569;">
                                Password di atas adalah password baru sementara yang dibuat oleh sistem. Silakan login kembali menggunakan password baru ini, kemudian segera ubah password Anda demi alasan privasi dan keamanan melalui menu **Ganti Password** pada dashboard tutor Anda.
                            </p>
                            
                            <p style="font-size: 15px; line-height: 1.6; color: #475569; margin-bottom: 30px;">
                                Jika Anda tidak merasa meminta reset password ini, abaikan email ini atau hubungi admin UPT PJJ UIN Siber Cirebon.
                            </p>
                            
                            <!-- CTA Button -->
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center">
                                        <a href="{{ route('login') }}" style="background-color: #0d6b46; color: #ffffff; text-decoration: none; padding: 12px 30px; font-weight: 600; border-radius: 6px; display: inline-block; font-size: 15px; box-shadow: 0 4px 6px -1px rgba(13, 107, 70, 0.2);">
                                            Masuk ke Halaman Login &rarr;
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
