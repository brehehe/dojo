<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kredensial Akun Kontingen</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      background-color: #f4f1ea;
      font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
      color: #2b2723;
      -webkit-font-smoothing: antialiased;
    }
    .wrapper {
      width: 100%;
      table-layout: fixed;
      background-color: #f4f1ea;
      padding: 30px 0 50px 0;
    }
    .container {
      max-width: 600px;
      margin: 0 auto;
      background-color: #ffffff;
      border-radius: 16px;
      overflow: hidden;
      border: 1px solid #e6decb;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    }
    .header {
      background: linear-gradient(135deg, #a92518 0%, #7d1c12 100%);
      padding: 36px 30px;
      text-align: center;
      border-bottom: 3px solid #d4a843;
    }
    .badge {
      display: inline-block;
      width: 48px;
      height: 48px;
      line-height: 48px;
      border-radius: 12px;
      background-color: rgba(255, 255, 255, 0.15);
      border: 1px solid rgba(212, 168, 67, 0.4);
      color: #ffd875;
      font-size: 20px;
      font-weight: bold;
      margin-bottom: 12px;
    }
    .header h1 {
      margin: 0;
      color: #ffffff;
      font-size: 20px;
      letter-spacing: 2px;
      text-transform: uppercase;
      font-weight: 700;
    }
    .header p {
      margin: 6px 0 0 0;
      color: #ffd875;
      font-size: 11px;
      letter-spacing: 1.5px;
      text-transform: uppercase;
    }
    .content {
      padding: 36px 30px 24px 30px;
    }
    .greeting {
      font-size: 16px;
      font-weight: 600;
      color: #1a1714;
      margin-bottom: 12px;
    }
    .intro {
      font-size: 14px;
      line-height: 1.6;
      color: #554f47;
      margin-bottom: 24px;
    }
    .credentials-box {
      background-color: #fbf9f4;
      border: 1px solid #e2dac9;
      border-left: 4px solid #c0392b;
      border-radius: 12px;
      padding: 22px;
      margin-bottom: 28px;
    }
    .credentials-title {
      font-size: 12px;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: #943024;
      font-weight: 700;
      margin-bottom: 16px;
    }
    .cred-row {
      margin-bottom: 12px;
    }
    .cred-row:last-child {
      margin-bottom: 0;
    }
    .cred-label {
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: #7a7369;
      margin-bottom: 3px;
    }
    .cred-value {
      font-size: 16px;
      font-weight: 700;
      color: #1a1714;
      font-family: 'Courier New', Courier, monospace;
      background: #ffffff;
      display: inline-block;
      padding: 4px 10px;
      border-radius: 6px;
      border: 1px solid #e6decb;
    }
    .contingent-summary {
      background-color: #f6f7f9;
      border: 1px solid #e1e4e8;
      border-radius: 12px;
      padding: 18px 20px;
      margin-bottom: 28px;
      font-size: 13px;
    }
    .summary-title {
      font-weight: 700;
      color: #2b2723;
      margin-bottom: 10px;
      font-size: 13px;
    }
    .summary-item {
      display: flex;
      justify-content: space-between;
      padding: 4px 0;
      color: #554f47;
      border-bottom: 1px dashed #e8eaed;
    }
    .summary-item:last-child {
      border-bottom: none;
    }
    .summary-label {
      color: #7a7369;
    }
    .summary-val {
      font-weight: 600;
      color: #1a1714;
      text-align: right;
    }
    .btn-container {
      text-align: center;
      margin: 32px 0;
    }
    .btn {
      display: inline-block;
      background: linear-gradient(135deg, #c0392b 0%, #96281b 100%);
      color: #ffffff !important;
      text-decoration: none;
      font-weight: 700;
      font-size: 14px;
      padding: 14px 34px;
      border-radius: 10px;
      box-shadow: 0 4px 14px rgba(192, 57, 43, 0.35);
      letter-spacing: 0.5px;
    }
    .security-notice {
      font-size: 12px;
      color: #7a7369;
      line-height: 1.5;
      padding: 14px 16px;
      background-color: #fff9ed;
      border: 1px solid #f2e2be;
      border-radius: 8px;
      margin-bottom: 24px;
    }
    .footer {
      background-color: #ede9e1;
      padding: 24px;
      text-align: center;
      font-size: 11px;
      color: #8c8478;
      border-top: 1px solid #e2dac9;
    }
    .footer p {
      margin: 4px 0;
    }
  </style>
</head>
<body>
  <div class="wrapper">
    <div class="container">

      <!-- Header -->
      <div class="header">
        <div class="badge">SK</div>
        <h1>Shorinji Kempo Indonesia</h1>
        <p>Portal Resmi Kejuaraan Nasional</p>
      </div>

      <!-- Main Content -->
      <div class="content">
        <div class="greeting">
          Halo, {{ $contingent->leader_name }}!
        </div>

        <p class="intro">
          Selamat! Pendaftaran kontingen Anda telah berhasil diproses. Akun resmi kontingen Anda telah dibuat di portal sistem kejuaraan Shorinji Kempo.
        </p>

        <!-- Credentials Box -->
        <div class="credentials-box">
          <div class="credentials-title">
            Informasi Akses & Kata Sandi Anda
          </div>

          <div class="cred-row">
            <div class="cred-label">Email Login (Username):</div>
            <div class="cred-value">{{ $user->email }}</div>
          </div>

          <div class="cred-row" style="margin-top: 14px;">
            <div class="cred-label">Kata Sandi (Password):</div>
            <div class="cred-value" style="color: #c0392b; letter-spacing: 1px;">{{ $plainPassword }}</div>
          </div>
        </div>

        <!-- Contingent Profile Summary -->
        <div class="contingent-summary">
          <div class="summary-title">Data Kontingen Terdaftar:</div>
          <table width="100%" style="border-collapse: collapse; font-size: 13px;">
            <tr>
              <td style="padding: 4px 0; color: #7a7369;">Nama Kontingen:</td>
              <td style="padding: 4px 0; font-weight: 600; color: #1a1714; text-align: right;">{{ $contingent->name }}</td>
            </tr>
            <tr>
              <td style="padding: 4px 0; color: #7a7369;">Kabupaten / Kota:</td>
              <td style="padding: 4px 0; font-weight: 600; color: #1a1714; text-align: right;">{{ $contingent->kab_kota }}</td>
            </tr>
            <tr>
              <td style="padding: 4px 0; color: #7a7369;">Manager / Ketua:</td>
              <td style="padding: 4px 0; font-weight: 600; color: #1a1714; text-align: right;">{{ $contingent->leader_name }}</td>
            </tr>
            @if($contingent->leader_phone)
            <tr>
              <td style="padding: 4px 0; color: #7a7369;">No. WhatsApp:</td>
              <td style="padding: 4px 0; font-weight: 600; color: #1a1714; text-align: right;">{{ $contingent->leader_phone }}</td>
            </tr>
            @endif
          </table>
        </div>

        <!-- Action Button -->
        <div class="btn-container">
          <a href="{{ route('login') }}" class="btn">Masuk ke Portal Kontingen</a>
        </div>

        <!-- Security Notice -->
        <div class="security-notice">
          <strong>Perhatian Keamanan:</strong> Demi menjaga keamanan akun dan data kontingen Anda, harap segera mengganti kata sandi setelah Anda berhasil masuk untuk pertama kali. Jangan bagikan informasi akun ini kepada pihak yang tidak berwenang.
        </div>
      </div>

      <!-- Footer -->
      <div class="footer">
        <p><strong>Pengurus Besar Shorinji Kempo Indonesia</strong></p>
        <p>Email ini dikirimkan secara otomatis oleh sistem. Harap tidak membalas langsung email ini.</p>
        <p>© {{ date('Y') }} PB Perkemi. Seluruh hak cipta dilindungi.</p>
      </div>

    </div>
  </div>
</body>
</html>
