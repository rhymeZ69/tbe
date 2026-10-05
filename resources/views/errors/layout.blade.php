<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="theme-color" content="#050F26">
<title>@yield('title', 'Error') — Three Brothers Enterprises</title>

<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/logo.png') }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/logo.png') }}">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">

<style>
:root{
  --navy-900:#050F26;
  --navy-800:#0A1F44;
  --navy-700:#0F2C5C;
  --navy-600:#14498F;
  --gold-600:#B8901C;
  --gold-500:#C9A227;
  --gold-400:#DFBE55;
  --gold-300:#F0DA9A;
  --white:#FFFFFF;
  --off:#F5F8FD;
  --muted:#6B7A90;
  --line:rgba(10,31,68,.10);
}

*,*::before,*::after{box-sizing:border-box}

html,body{
  margin:0;padding:0;
  height:100%;
  font-family:'Manrope',system-ui,-apple-system,"Segoe UI",Roboto,sans-serif;
  -webkit-font-smoothing:antialiased;
  overflow-x:hidden;
}

body.error-page{
  min-height:100vh;
  display:flex;
  flex-direction:column;
  background:var(--navy-900);
  color:#fff;
  position:relative;
  overflow:hidden;
  isolation:isolate;
}

/* Background layers */
body.error-page::before{
  content:"";
  position:absolute;
  inset:0;
  z-index:-2;
  background:
    radial-gradient(900px 620px at 82% 12%, rgba(201,162,39,.22), transparent 62%),
    radial-gradient(780px 620px at 8% 92%, rgba(27,95,184,.34), transparent 62%),
    linear-gradient(155deg,#050F26 0%,#0A1F44 46%,#0F2C5C 100%);
}

body.error-page::after{
  content:"";
  position:absolute;
  inset:0;
  z-index:-1;
  opacity:.3;
  background-image:
    repeating-linear-gradient(115deg,rgba(255,255,255,.05) 0 1px,transparent 1px 62px),
    repeating-linear-gradient(25deg,rgba(255,255,255,.035) 0 1px,transparent 1px 62px);
  mask-image:radial-gradient(circle at 60% 40%,#000 20%,transparent 78%);
  -webkit-mask-image:radial-gradient(circle at 60% 40%,#000 20%,transparent 78%);
}

/* Header */
.err-header{
  padding:28px 0;
  position:relative;
  z-index:2;
}

.err-header .container{
  max-width:1220px;
  margin:0 auto;
  padding:0 24px;
}

.err-logo{
  display:inline-flex;
  align-items:center;
  gap:13px;
  text-decoration:none;
  color:inherit;
}

.err-logo img{
  width:46px;
  height:46px;
  border-radius:12px;
  object-fit:contain;
  background:rgba(255,255,255,.04);
  padding:4px;
  box-shadow:0 8px 22px rgba(201,162,39,.3);
}

.err-logo-text{
  display:flex;
  flex-direction:column;
  line-height:1.12;
}

.err-logo-text strong{
  font-family:'Playfair Display',serif;
  font-size:1.08rem;
  color:#fff;
  letter-spacing:.2px;
}

.err-logo-text span{
  font-size:.62rem;
  letter-spacing:.26em;
  text-transform:uppercase;
  color:var(--gold-400);
  font-weight:800;
}

/* Main */
.err-main{
  flex:1;
  display:flex;
  align-items:center;
  justify-content:center;
  padding:40px 24px 80px;
  position:relative;
  z-index:1;
}

.err-inner{
  max-width:640px;
  text-align:center;
}

/* Big code number */
.err-code{
  display:inline-block;
  font-family:'Playfair Display',serif;
  font-size:clamp(5rem,14vw,9rem);
  font-weight:800;
  line-height:.9;
  letter-spacing:-.04em;
  background:linear-gradient(100deg,#F4E3A6 0%,#C9A227 45%,#F0DA9A 70%,#B8901C 100%);
  -webkit-background-clip:text;
  background-clip:text;
  color:transparent;
  margin-bottom:12px;
  position:relative;
}

/* Icon bubble */
.err-icon{
  width:76px;
  height:76px;
  border-radius:50%;
  margin:0 auto 24px;
  display:grid;
  place-items:center;
  background:rgba(201,162,39,.12);
  border:1px solid rgba(201,162,39,.32);
  color:var(--gold-400);
  box-shadow:0 0 0 12px rgba(201,162,39,.06), 0 0 0 24px rgba(201,162,39,.03);
}

.err-icon svg{
  width:34px;
  height:34px;
}

/* Title */
.err-title{
  font-family:'Playfair Display',serif;
  font-size:clamp(1.6rem,3.2vw,2.2rem);
  font-weight:700;
  letter-spacing:-.01em;
  color:#fff;
  margin:0 0 14px;
  line-height:1.2;
}

/* Message */
.err-message{
  font-size:1.02rem;
  color:rgba(255,255,255,.72);
  line-height:1.75;
  margin:0 0 32px;
  max-width:520px;
  margin-left:auto;
  margin-right:auto;
}

/* Actions */
.err-actions{
  display:flex;
  gap:12px;
  justify-content:center;
  flex-wrap:wrap;
  margin-bottom:36px;
}

.err-btn{
  display:inline-flex;
  align-items:center;
  justify-content:center;
  gap:9px;
  padding:14px 28px;
  border-radius:999px;
  font-weight:700;
  font-size:.92rem;
  letter-spacing:.02em;
  text-decoration:none;
  border:1.5px solid transparent;
  cursor:pointer;
  transition:transform .25s cubic-bezier(.2,.7,.3,1), box-shadow .25s, background .25s, color .25s, border-color .25s;
  min-height:48px;
  white-space:nowrap;
}

.err-btn--gold{
  background:linear-gradient(135deg,#EFD98F 0%,#C9A227 55%,#B8901C 100%);
  color:#06152F;
  box-shadow:0 12px 32px rgba(201,162,39,.34);
}
.err-btn--gold:hover{
  transform:translateY(-3px);
  box-shadow:0 20px 44px rgba(201,162,39,.46);
}

.err-btn--ghost{
  background:rgba(255,255,255,.04);
  color:#fff;
  border-color:rgba(255,255,255,.24);
}
.err-btn--ghost:hover{
  background:rgba(255,255,255,.10);
  transform:translateY(-3px);
}

.err-btn svg{
  width:17px;
  height:17px;
  flex-shrink:0;
}

/* Help note */
.err-help{
  font-size:.84rem;
  color:rgba(255,255,255,.5);
  line-height:1.65;
}

.err-help a{
  color:var(--gold-400);
  font-weight:700;
  text-decoration:none;
  transition:color .2s;
}
.err-help a:hover{
  color:var(--gold-300);
  text-decoration:underline;
}

/* Footer */
.err-footer{
  padding:22px 0;
  position:relative;
  z-index:2;
  border-top:1px solid rgba(255,255,255,.08);
  font-size:.78rem;
  color:rgba(255,255,255,.42);
}

.err-footer .container{
  max-width:1220px;
  margin:0 auto;
  padding:0 24px;
  display:flex;
  justify-content:space-between;
  align-items:center;
  gap:16px;
  flex-wrap:wrap;
}

.err-footer b{
  color:var(--gold-400);
  font-weight:700;
}

@media (max-width:520px){
  .err-header{padding:20px 0;}
  .err-logo img{width:40px;height:40px;}
  .err-logo-text strong{font-size:.98rem;}
  .err-logo-text span{font-size:.56rem;letter-spacing:.2em;}

  .err-main{padding:20px 18px 60px;}

  .err-icon{width:64px;height:64px;margin-bottom:20px;}
  .err-icon svg{width:28px;height:28px;}

  .err-actions{flex-direction:column;}
  .err-btn{width:100%;}

  .err-footer .container{
    flex-direction:column;
    text-align:center;
    gap:8px;
  }
}
</style>
</head>
<body class="error-page">

{{-- ================= HEADER ================= --}}
<header class="err-header">
  <div class="container">
    <a href="{{ url('/') }}" class="err-logo">
      <img src="{{ asset('img/logo.png') }}" alt="Three Brothers Enterprises" width="46" height="46">
      <div class="err-logo-text">
        <strong>Three Brothers</strong>
        <span>Enterprises</span>
      </div>
    </a>
  </div>
</header>

{{-- ================= MAIN ================= --}}
<main class="err-main">
  <div class="err-inner">

    @hasSection('icon')
      <div class="err-icon">
        @yield('icon')
      </div>
    @else
      <div class="err-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
          <circle cx="12" cy="12" r="10"/>
          <path d="M12 8v5M12 16h.01"/>
        </svg>
      </div>
    @endif

    <div class="err-code">@yield('code', 'Error')</div>

    <h1 class="err-title">@yield('heading', 'Something went wrong')</h1>

    <p class="err-message">@yield('message', 'An unexpected error occurred. Please try again or head back to the homepage.')</p>

    <div class="err-actions">
      <a href="{{ url('/') }}" class="err-btn err-btn--gold">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4">
          <path d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V10"/>
        </svg>
        Back to Homepage
      </a>

      @hasSection('secondary_action')
        @yield('secondary_action')
      @else
        <a href="{{ url('/') }}#contact" class="err-btn err-btn--ghost">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="2" y="4" width="20" height="16" rx="2"/>
            <path d="M2 7l10 6 10-6"/>
          </svg>
          Contact Us
        </a>
      @endif
    </div>

    <p class="err-help">
      Need help? Email us at
      <a href="mailto:{{ setting('contact_email', 'exports@threebrothers.com') }}">
        {{ setting('contact_email', 'exports@threebrothers.com') }}
      </a>
      or WhatsApp
      <a href="https://wa.me/{{ setting('contact_whatsapp', '923000000000') }}" target="_blank" rel="noopener">
        {{ setting('contact_phone', '+92 300 000 0000') }}
      </a>
    </p>

  </div>
</main>

{{-- ================= FOOTER ================= --}}
<footer class="err-footer">
  <div class="container">
    <span>© {{ date('Y') }} Three Brothers Enterprises. All rights reserved.</span>
    <span>Multi-Product Exporter · <b>Pakistan → GCC &amp; Worldwide</b></span>
  </div>
</footer>

</body>
</html>